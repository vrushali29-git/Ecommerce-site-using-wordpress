/**
 * Identify browser on site if they are logged in.
 *
 * Object possibly containing user/commenter email address:
 * @typedef {Object} klUser
 *   @property {string} currect_user_email - Email of logged in user
 *   @property {string} commenter_email - Email of logged in commenter
 *
 */


function klIdentifyBrowser(klUser) {
  var klaviyo = window.klaviyo || [];
  if (klUser.current_user_email) {
    klaviyo.push(["identify", {
      "$email": klUser.current_user_email
    }]);
  } else {
    // See if current user is a commenter
    if (klUser.commenter_email) {
      klaviyo.push(["identify", {
        "$email": klUser.commenter_email
      }]);
    }
  }
}


window.addEventListener("load", function() {
  !function(){if(!window.klaviyo){window._klOnsite=window._klOnsite||[];try{window.klaviyo=new Proxy({},{get:function(n,i){return"push"===i?function(){var n;(n=window._klOnsite).push.apply(n,arguments)}:function(){for(var n=arguments.length,o=new Array(n),w=0;w<n;w++)o[w]=arguments[w];var t="function"==typeof o[o.length-1]?o.pop():void 0,e=new Promise((function(n){window._klOnsite.push([i].concat(o,[function(i){t&&t(i),n(i)}]))}));return e}}})}catch(n){window.klaviyo=window.klaviyo||[],window.klaviyo.push=function(){var n;(n=window._klOnsite).push.apply(n,arguments)}}}}();
    klIdentifyBrowser(klUser);
});
