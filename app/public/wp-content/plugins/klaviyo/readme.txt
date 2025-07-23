=== Klaviyo ===
Contributors: klaviyo, bialecki, bawhalley
Tags: analytics, email, marketing, klaviyo, woocommerce
Requires at least: 5.2
Tested up to: 6.8
Stable tag: 3.7.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Klaviyo for WooCommerce

== Description ==

https://www.youtube.com/watch?v=sxdiLB58bSo

Grow faster and more efficiently with personalized email and SMS. Powered by your own customer data.

Over 13,000 WooCommerce brands use Klaviyo to power smarter digital relationships. Klaviyo unlocks data across 300+ integrations to drive increased revenue and lifetime value across channels –– email, SMS, mobile push, paid ads, and web –– to drive 46x ROI for our most successful brands.


####Use omnichannel templates for Welcome, Back in Stock, Abandon, Price Drop & more

Build email and SMS journeys triggered by specific customer behaviors with a library of high-converting templates built for WooCommerce. These templates include browse abandonment, price drop, canceled order, abandoned checkout, and more, that can be triggered by real-time event-level data.

Personalize your messages further with customization for triggers, branches / splits, personalized offers, and A/B testing. Send cart reminders by order value, upsell based on order frequency, or send post-purchase email and SMS based on new vs existing customers.

- Use single-use, dynamic coupons for targeted offers
- Add dynamic product recommendations based on browsing history, past purchases, and other behaviors
- Smart sending allows you to limit the number of emails, SMS, and push notifications customers receive
- Reusable sections that stay consistent across all templates, for consistency and easy mass-updates
- Custom HTML/CSS to upload existing templates or code new ones from scratch

####Sync your WooCommerce store data within minutes

As a WooCommerce strategic partner, our direct extension takes only minutes to integrate and is pre-built right out of the box.  This allows you to sync all your historical and real-time data, plus utilize metadata (like value, variant name, quantity, and discount codes), so you can stay on top of every single interaction buyers have with your brand.

Every event and metadata can be used to build segments and campaigns, send automated flows, and use in reporting, all with no coding required.

- Active on site
- Ordered product
- Fulfilled order
- Placed order
- Refunded orders
- Started checkout

####Use complete historical and real-time data for precise segmentation

Build precise groups of segmented audiences using any combination of event data (e.g. browsing behavior), behavior data (e.g. placed order and refunded orders) and predictive data (e.g. predicted next order date) to target paid ads, email, SMS, and web forms.

Klaviyo segments update in real-time any time a profile meets segment criteria––no manual tagging required.

- Build unlimited segment parameters using all-time data (e.g. your first 100 customers, last year’s BFCM shoppers, engaged subscribers in the last 90 days, etc)
- Use segments to target signup forms on your WooCommerce store (e.g. customers who have looked at a product page over 5 times but haven’t purchased)
- Connect Klaviyo with Facebook, Google Ads, and Criteo for retargeting and lookalike segmentation
- Use our segment growth report to spot any trends and track the performance of specific segments week by week
- Segment AI uses generative AI to create a segment based on your description

####Get more answers out of your data

With Klaviyo’s reporting tool for data analysis, you can track metrics and benchmarks through dashboards you can share and customize to spot new areas of opportunity and improvement.
- Use Klaviyo AI to write SMS and subject lines, design email sections, optimize forms display timing, build predictive analytics, and more
- Get real-time revenue data and analytics per automation, campaign, segment, and channel
- Benchmark your performance across key metrics with 100 brands in your category
- Get account-level email deliverability reporting within our deliverability hub
- Manage multiple global accounts with a single login

See why 13,000 WooCommerce brands choose to [grow with Klaviyo](https://www.klaviyo.com/platform-integrations/woocommerce).

== Installation ==

Integrating Klaviyo and your WooCommerce store is a simple process:

1. Install/activate Klaviyo's plugin.
2. Click on Klaviyo in your left-menu, then click Connect Account.
3. Click through two screens to approve access and finish setting up your integration.

For detailed instructions on integrating Klaviyo and WooCommerce please visit our [Help Center](https://help.klaviyo.com/hc/en-us/articles/115005255808-Integrate-with-WooCommerce).


== Screenshots ==
1. Use omnichannel templates for Welcome, Back in Stock, Abandon, Price Drop & more
2. Sync your WooCommerce store data within minutes
3. Use complete historical and real-time data for precise segmentation
4. Get more answers out of your data


== Changelog ==
= 3.7.0 2025-06-20 =
* Added - Support for anonymous visitor activity backfill (AVAB) when enabled in Klaviyo account.
* Changed - Updates API function names to avoid  potential conflicts.
* Changed - Merge __kla_id cookie values during checkout.

= 3.6.0 2025-06-02 =
* Added - Added admin notices to /plugins view on wordpress dashboard to prompt reviews after 60 days
* Fixed - JSON param access of billing address during checkout consent processing.
* Fixed - URI-encode checkout cookie.

= 3.5.0 2025-04-22 =
* Changed - Update Klaviyo backend API revision to 2025-04-15

= 3.4.3 2025-02-03 =
* Changed - Explicitly pass default value of  param to htmlspecialchars() for cross-version compatibility.
* Changed - Update WooCommerce <> Klaviyo logo asset.
* Fixed - Incorrect option accessor used in deprecated settings form.
* Fixed - Resolved deprecated warning triggered by calls to strtotime().

= 3.4.2 2024-11-11 =
* Fixed - Tweak order of checkout block hooks for better compatibility.

= 3.4.1 2024-10-07 =
* Changed - Add price info at top level for Viewed Product events.
* Fixed - Load Klaviyo core functions after WooCommerce plugin is initialized.

= 3.4.0 2024-09-17 =
* Added - Adds license information to readme.txt
* Changed - Updates klaviyo onsite javascript url to the new format
* Fixed - Update plugins TypeError when saved transient value is not an object.
* Fixed - Avoid conflict with Woo Pay.

= 3.3.5 2024-03-25 =
* Added - Notifications for common setup issues from settings page.

= 3.3.4 2024-02-02 =
* Fixed - Fix deprecation warning for dynamic properties.

= 3.3.3 2023-11-15 =
* Fixed - Fix newsletter checkboxes validation.
* Fixed - Move Added to Cart 'value' property up a level.

= 3.3.2 2023-11-09 =
* Fixed - Fixed tracking on checkout pages that show the shipping form by default

= 3.3.1 2023-10-02 =
* Fixed - Update events call payload to have brand parity with V1/V2 APIs

= 3.3.0 2023-09-27 =
* Added - New Klaviyo onsite object
* Added - New X-Klaviyo-User-Agent to headers to collect plugin usage meta data
* Added - Added support for V3 APIs /client/events and /client/profiles
* Added - Added user tracking support on both shortcode and block checkout page types
* Removed - Support for V2 APIs: /track and /identify
* Removed - Removed _learnq onsite object in favor of klaviyo object

= 3.2.2 2023-08-22 =
* Changed - Klaviyo settings link nested under Marketing tab if WooCommerce is installed.
* Deprecated - Deprecate getVersion method in favor of get_version to meet WP convention.
* Removed - Role creation and removal during install/uninstall.
* Fixed - Only include WooCommerce Checkout Block compatibility if StoreApi is supported.

= 3.2.1 2023-07-06 =
* Fixed - Add `paginate` arg to order query to access total order count.

= 3.2.0 2023-06-27 =
* Added - Checkout block integration to support consent at checkout for email and sms.

= 3.1.0 2023-06-20 =
* Changed - Update Klaviyo API orders routes to use HPOS compatible query methods.

= 3.0.13 2023-05-15 =
* Changed - Added utm parameters to Create Account url on plugin settings page.

= 3.0.12 2023-04-21 =
* Added - `kl_added_to_cart` hook which enables developers to edit the `$added_to_cart` payload
* Added - `kl_started_checkout` hook which enables developers to edit the `$started_checkout_data` payload
* Added - `kl_cart_rebuild` hook which enables developers to add/remove products from the cart before it is rebuild
* Added - `kl_cart_rebuild_complete` hook which allows developers to easily trigger a callback on successful cart rebuild
* Added - `kl_checkout_item` hook which allows you to add additional properties to the line items that make up Started Checkout Event

= 3.0.11 2023-03-27 =
* Changed - Bump WP tested up to 6.2
* Fixed - Rename function to avoid conflicts

= 3.0.10 2023-03-14 =
* Changed - Replaced deprecated FILTER_SANITIZE_STRING functionality

= 3.0.9 2023-03-09 =
* Added - Added `Stable tag` readme.txt management to changelogger.

= 3.0.8 2023-03-03 =
* Changed - Add filter `wck_should_add_started_checkout` for adding started checkout logic on pages.
* Security - Modified the `get_klaviyo_option` method to prevent script injection on the admin page.

= 3.0.7 2023-01-24 =
* Changed - Updated "Tested up to" from 6.0.1 => 6.1.1

= 3.0.6 2023-01-03 =
* Fixed - Undefined categories in cart rebuild.
* Fixed - Added ProductID to viewed product events for better integration with product recommenders.

= 3.0.5 2022-12-08 =
* Fixed - Prevent automatic integration removal.
* Removed - Sending webhook on plugin deactivation.

= 3.0.4 2022-10-20 =
* Update Removed product description from the kl_build_add_to_cart_data method to reduce the size of the payload.
* Update Started Checkout events not working with TT2 theme
* Update Use POST instead of GET when sending through Added to Cart Event.

= 3.0.3 2022-04-12 =
* Update - Query only for product post_type at klaviyo/v1/products resource.
* Update - Use get_home_url() for url query param in auth kickoff request.

= 3.0.2 2022-03-28 =
* Update - Assets for brand refresh.
* Fix - Undefined index warnings in cart build.

= 3.0.1 2022-02-07 =
* Fix - Remove redirect after update/install.

= 3.0.0 2022-02-07 =
* Add - Options endpoint supporting GET/POST requests.
* Add - Improved validation function for custom endpoints.
* Add - `is_most_recent_version` key to the response from the /klaviyo/v1/version endpoint detailing whether plugin update is available.
* Add - Webhook service for outgoing requests to Klaviyo's webhook endpoint.
* Add - Redirect to Klaviyo settings page after activation.
* Add - Deactivation logic removing options, webhooks and sending request to Klaviyo to keep integration state aligned.
* Add - WCK_Options class to handle deprecated options and adjusting via filter.
* Add - `disable` endpoint to handle plugin data cleanup and deactivation when removed in Klaviyo.
* Update - Updated plugin settings page allowing for management of settings in Klaviyo. Maintain original for non-WooCommerce sites.
* Update - Use __DIR__ to define KLAVIYO_PATH constant for test compatibility.
* Fix - PHP Notices on admin page when initial options are not set.
* Deprecate - Removed 'klaviyo_popup' and 'admin_settings_message' from `klaviyo_settings` option.

= 2.5.5 2021-12-09 =
* Update - Support for Synching Product Variations.

= 2.5.4 2021-11-10 =
* Update - Default SMS consent disclosure text

= 2.5.3 2021-10-27 =
* Fixed - Over representation of cart value in Added to Cart events.

= 2.5.2 2021-08-10 =
* Add - Support for Chained Products
* Deprecation - Displaying Email checkbox on checkout pages based on ListId set in Plugin settings.
This will be displayed using the Email checkbox setting on the Plugin settings page, as done for SMS checkout checkbox

= 2.5.1 2021-07-23 =
* Update - Adjusted priority of kl_added_to_cart_event hook to allow for line item calculations.

= 2.5.0 2021-07-12 =
* Add - Added to Cart event.

= 2.4.2 2021-06-16 =
* Add - Use exchange_id for "Started Checkout" if available
* Update - Lowered priority of consent checkboxes to address conflicts with some checkout plugins

= 2.4.1 2021-04-14 =
* Fix - Address console error faced while displaying deprecation notice on plugin settings page.

= 2.4.0 2021-03-17 =
* Add - Class to handle Plugins screen update messages.
* Add - Collecting SMS consent at checkout.
* Update - Refactor adding checkout checkbox to allow for re-ordering in form.
* Update - Plugin settings form redesigned to be more intuitive.
* Update - Enqueue Identify script before Viewed Product script.
* Update - Moving to webhooks to collect Email and SMS consent.
* Fix - Remove unnecessary wp_reset_query call in Klaviyo analytics.
* Fix - Move _learnq assignment outside of conditional in identify javascript.
* Fix - Assign commenter email value for localization.

= 2.3.6 2020-10-27 =
* Fix - Remove escaping backslashes from Started Checkout title property

= 2.3.5 2020-10-19 =
* Fix - Remove escaping backslashes from Viewed Product title property

= 2.3.4 2020-10-01 =
* Fix - Remove unused import.

= 2.3.3 2020-09-25 =
* Fix - Cart state issue with rebuild when composite products are present

= 2.3.2 2020-09-11 =
* Fix - Encode non-ascii started checkout event data
* Fix - Handle checkout without Klaviyo cookie

= 2.3.1 2020-09-08 =
* Fix - Update to fix fatal error for websites not using WooCommerce plugin

= 2.3.0 2020-09-07 =
* Update - Removing all external javascripts from the Checkout page

= 2.2.6 2020-09-04 =
* Fix - Update to add permission callback for all custom endpoints (Wordpress 5.5)

= 2.2.5 2020-08-20 =
* Fix - Rename undefined variable

= 2.2.4 2020-08-05 =
* Tweak - Update to be more defensive around global server variables

= 2.2.3 2020-06-23 =
* Fix - Identify call in checkout billing fields

= 2.2.2 2020-06-11 =
* Fix - Check for checkout variable
* Fix - Resolve register_rest_route_warning
* Dev - Increase max WP version to 5.4.2
* Dev - Increase max WC version to 4.2.0

= 2.2.1 2020-05-26 =
* Tweak - Small update to legacy signup form widget

= 2.2.0 2020-05-14 =
* Fix - Custom order and product count method

= 2.1.9 2020-05-12 =
* Fix - Security fix

= 2.1.8 2020-04-24 =
* Dev - Refactor API code for unit tests

= 2.1.7 2020-01-28 =
* Add new authentication for api

= 2.1.6 2020-01-27 =
* Fix - Revert authentication patch
* Fix - Making sure characters are encoded correctly on signup success

= 2.1.5 2020-01-22 =
* Fix - Improve authentication for custom api endpoints

= 2.1.4 2019-12-04 =
* Fix - Check index is set for subscribe checkbox during checkout
* Fix - Move klaviyo.js script to highest priority in footer and add missing single quotes around src

= 2.1.3 =
* Fix - Deactivate old Klaviyo plugins if active
* Fix - Check if Klaviyo Settings index exists
* Fix - Pluck product categories only if array

= 2.1.2 =
* Add support for latest api version (v3)

= 2.1.1 =
* Check for existing Klaviyo plugins avoiding incompatibility

= 2.1.0 =
* Move all javascript to external files
* Compatible with just WP

= 2.0.7 =
* Add widget for Klaviyo's built-in signup forms

= 2.0.6 =
* Be able to customize CSS for forms
* Fix issue with button text display

= 2.0.5 =
* Remove signupform js as it's included in klaviyo.js

= 2.0.4 =
* Add klaviyo.js

= 2.0.3 =
* Escape quotes in product titles

= 2.0.2 =
* Use new endpoint for checkout subscriptions

= 2.0.1 =
* Compatibility for PHP 7.2 and remove PHP warnings
* Add persistent cart URL for rebuilding abandoned carts
* Add support for composite product cart rebuild

= 2.0 =
* Bundles the Wordpress and Woocommerce plugin together as one.
* An option to Add a checkbox at the end of the billing form that can be configured to sync with a specified Klaviyo list. The text can be configured in the settings. Currently set to off by default.
* Install the Klaviyo pop-up code by clicking a checkbox in the admin UI
* Automatically adds the viewed product snippet to product pages.
* Adds product categories which can be segmented to the started checkout metric.
* Removes the old unused code and functions.
* Updates all deprecated WC and Wordpress functions/methods.
* Removes the description tag from the checkout started event.
* Captures first and last names to the started check out metric.

= 1.3.3 =
* Updating docs.

= 1.3.2 =
* Tested for support for Wordpress 4.8.

= 1.3 =
* Added HTTPS support for embedded form.
* Updated logo branding.
* Updated links.
* Updated previously deprecated functions.

= 1.2.0 =
* Updating to allow embedding an email sign up form.

= 1.1.2 =
* Updating docs.

= 1.1.1 =
* Fixing documentation a bit and one bug fix.

= 1.1 =
* Adding in automatic tracking of users if they log in or post a comment.

= 1.0 =
* Initial version
