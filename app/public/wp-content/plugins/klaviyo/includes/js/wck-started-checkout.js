/**
 * WCK Started Checkout
 *
 * Incoming event object
 * @typedef {object} kl_checkout
 *   @property {string} email - Email of current logged in user
 *
 *   @property {object} event_data - Data for started checkout event
 *     @property {object} $extra - Event data
 *     @property {string} $service - Value will always be "woocommerce"
 *     @property {int} value - Total value of checkout event
 *     @property {array} Categories - Product categories (array of strings)
 *     @property {string} Currency - Currency type
 *     @property {string} CurrencySymbol - Currency type symbol
 *     @property {array} ItemNames - List of items in the cart
 *
 */


/**
 * Attach event listeners to save billing fields.
 */

// Constants for the klaviyo api url prefix and revision
const KLAVIYO_API_URL_PREFIX = 'https://a.klaviyo.com/';
const KLAVIYO_API_REVISION = '2025-04-15';

var identify_object = {
  'company_id': public_key.token,
  'properties': {}
};

var klaviyo_cookie_id = '__kla_id';

function buildProfileRequestPayload(event_attributes) {
  const topLevelAttributes = ['email', 'first_name', 'last_name'];

  // Destructure event_attributes:
  // - properties: gets the properties object from event_attributes, defaulting to empty object if not present
  // - restAttributes: gets all other fields from event_attributes using the rest operator (...)
  const { properties = {}, ...restAttributes } = event_attributes || {};

  // Create a new object for the filtered properties to avoid mutating the original input
  const filteredProperties = { ...properties };
  const dataAttributes = { ...restAttributes };

  // Move top level attributes from properties to data level
  topLevelAttributes.forEach(field => {
    if (filteredProperties[field] !== undefined) {
      dataAttributes[field] = filteredProperties[field];
      delete filteredProperties[field];
    }
  });

  // Add the filtered properties back to dataAttributes
  dataAttributes.properties = filteredProperties;

  return JSON.stringify({
    data: {
      type: "profile",
      attributes: dataAttributes
    }
  })
}

function buildEventRequestPayload(customer_properties, event_properties, metric_attributes) {
  return JSON.stringify({
    data: {
      type: 'event',
      attributes: {
        properties: {
          ...event_properties,
        },
        metric: {
        data: {
          type: 'metric',
          attributes: {
            ...metric_attributes,
          }
        }
      },
      profile: {
        data: {
          type: 'profile',
          attributes: {
            ...customer_properties,
          }
        }
      }
      }
    }
  })
}

function makePublicAPIcall(endpoint, event_data) {
  var company_id = public_key.token;
  jQuery.ajax(KLAVIYO_API_URL_PREFIX + endpoint + '?company_id=' + company_id, {
    type: "POST",
    contentType: "application/json",
    data: event_data,
    headers: {
      'revision': KLAVIYO_API_REVISION,
      'X-Klaviyo-User-Agent': plugin_meta_data.data,
    }
  });
}

function decodeKlaviyoCookieData(cookie) {
  var name = klaviyo_cookie_id + "=";
  var decodedCookie = decodeURIComponent(cookie);
  var ca = decodedCookie.split(';');
  for (var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return decodeURIComponent(atob(c.substring(name.length, c.length)));
    }
  }
  return "";
}

function getKlaviyoCookie() {
  return decodeKlaviyoCookieData(document.cookie);
}

function encodeKlaviyoCookieData(cookie_data) {
  return btoa(encodeURIComponent(JSON.stringify(cookie_data)));
}

function setKlaviyoCookie(cookie_data) {
  // Get existing cookie data
  const existingCookieData = getKlaviyoCookie();
  let mergedData = {};

  // If we have existing data, parse it and merge with new data
  if (existingCookieData) {
    try {
      const parsedExistingData = typeof existingCookieData === 'string' ?
        JSON.parse(existingCookieData) : existingCookieData;
      mergedData = { ...parsedExistingData, ...cookie_data };
    } catch (e) {
      // If parsing fails, just use the new data
      mergedData = cookie_data;
    }
  } else {
    mergedData = cookie_data;
  }

  const cvalue = encodeKlaviyoCookieData(mergedData);
  const date = new Date();
  date.setTime(date.getTime() + (63072e6)); // adding 2 years in milliseconds to current time
  const expires = "expires=" + date.toUTCString();
  document.cookie = klaviyo_cookie_id + "=" + cvalue + ";" + expires + "; path=/";
}

  /**
   * Queries the dom for first_name, last_name, and email inputs being displayed on the checkout page.
   * If both shipping and billing forms are present, both input nodes will be returned for the type (ie. first_name)
   * @return {object} an object of dom nodes (firstNameNode, lastNameNode, emailNode)
   */
function getTrackingNodes() {
  var emailNodes = jQuery('input[id*="email"]:visible, input[name*="email"]:visible');
  var firstNameNodes = jQuery('input[id*="first_name"]:visible, input[name*="first_name"]:visible');
  var lastNameNodes = jQuery('input[id*="last_name"]:visible, input[name*="last_name"]:visible');

  return { firstNameNodes, lastNameNodes, emailNodes};
}

/**
 * The event listener to be added to visible email, first_name, and last_name nodes.
 * It makes a call to client/profile with the values from the email field and either
 * the first_name or last_name value depending on the caller
 * @return {undefined}
 */
function identifyUser(nameType, self) {
  var { emailNodes } = getTrackingNodes();
  var email = emailNodes.val();
  var identify_properties = {
    [nameType]: jQuery.trim(jQuery(self).val())
  }
  if (email) {
    identify_properties["email"] = email;
    setKlaviyoCookie(identify_properties);
    identify_object.properties = identify_properties;
    makePublicAPIcall('client/profiles/', buildProfileRequestPayload(identify_object));
  }
}

/**
 * Adds the event listeners for tracking on the first name and last name inputs.
 * If both the shipping and billing forms are visible, listeners will be added to all first name and last name nodes
 * @return {undefined}
 */
function klIdentifyBillingField() {
  var { firstNameNodes, lastNameNodes } = getTrackingNodes();
  firstNameNodes.each(function(){
    var node = jQuery(this);
    node.change(() => identifyUser("first_name", node));
  });
  lastNameNodes.each(function(){
    var node = jQuery(this);
    node.change(() => identifyUser("last_name", node));
  });
}

window.addEventListener("load", function () {
  // Custom checkouts/payment platforms may still load this file but won't
  // fire woocommerce_after_checkout_form hook to load checkout data.
  if (typeof kl_checkout === 'undefined') {
    return;
  }

  var WCK = WCK || {};
  WCK.trackStartedCheckout = function () {
    var metric_attributes = {
      'name': 'Started Checkout',
      'service': 'woocommerce'
    }
    var customer_properties = {}
    if (kl_checkout.email) {
      customer_properties['email'] = kl_checkout.email;
      // Identify user once we have an email. This will trigger AVAB if enabled.
      klaviyo.identify(customer_properties);
    } else if (kl_checkout.exchange_id) {
      // Klaviyo use is already identified if we have an exchange ID.
      customer_properties['_kx'] = kl_checkout.exchange_id;
    } else {
      return;
    }

    makePublicAPIcall('client/events/', buildEventRequestPayload(customer_properties, kl_checkout.event_data, metric_attributes));
  };

  var klCookie = getKlaviyoCookie();

  // Priority of emails for syncing Started Checkout event: Logged-in user,
  // cookied exchange ID, cookied email, billing email address
  if (kl_checkout.email !== "") {
    identify_object.properties = {
      'email': kl_checkout.email
    };
    makePublicAPIcall('client/profiles/', buildProfileRequestPayload(identify_object));
    setKlaviyoCookie(identify_object.properties);
    WCK.trackStartedCheckout();
  } else if (klCookie && JSON.parse(klCookie).$exchange_id !== undefined) {
    kl_checkout.exchange_id = JSON.parse(klCookie).$exchange_id;
    WCK.trackStartedCheckout();
  } else if (klCookie && JSON.parse(klCookie).email !== undefined) {
    kl_checkout.email = JSON.parse(klCookie).email;
    WCK.trackStartedCheckout();
  } else {
    if (jQuery) {
      var { firstNameNodes, lastNameNodes, emailNodes } = getTrackingNodes();
      emailNodes.change(function () {
        var elem = jQuery(this),
          email = jQuery.trim(elem.val());

        if (email && /@/.test(email)) {
          var params = {
            "email": email
          };

          if (firstNameNodes.length > 0) {
            // Values come from first visible input node in the DOM
            params["first_name"] = firstNameNodes.val();
          }
          if (lastNameNodes.length > 0) {
            params["last_name"] = lastNameNodes.val();
          }

          setKlaviyoCookie(params);
          kl_checkout.email = params.email;
          identify_object.properties = params;
          makePublicAPIcall('client/profiles/', buildProfileRequestPayload((identify_object)));
          WCK.trackStartedCheckout();
        }
      });

      // Save billing fields
      klIdentifyBillingField();
    }
  }
});
