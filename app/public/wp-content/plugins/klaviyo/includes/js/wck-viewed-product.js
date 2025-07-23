/**
 * WCK Viewed Product
 *
 * Incoming product object
 * @typedef {Object} item
 *   @property {string} title - Product name
 *   @property {int} product_id - Parent product ID
 *   @property {int} variant_id - Product ID
 *   @property {string} url - Product permalink
 *   @property {string} image_url - Product image url
 *   @property {float} price - Product price
 *   @property {array} categories - Product categories (array of strings)
 *
 * Unfortunately wp_localize_script converts all variables to strings :( so we
 * will have to re-parse ints and floats.
 * See note in - https://codex.wordpress.org/Function_Reference/wp_localize_script
 *
 */

function trackViewedProduct() {
    var klaviyo = window.klaviyo || [];
    var track_viewed_item = {
        'Title': item.title,
        'ItemId': parseInt(item.product_id),
        'variantId': parseInt(item.variant_id),
        'Categories': item.categories,
        'ImageUrl': item.image_url,
        'Url': item.url,
        '$value': parseFloat(item.price),
        'Metadata': {
            'Price': parseFloat(item.price),
        }
    };
    var viewed_item = {
        ...track_viewed_item,
        'ProductID': parseInt(item.product_id),
    };

    klaviyo.push(['track', 'Viewed Product', viewed_item]);
    klaviyo.push(['trackViewedItem', track_viewed_item]);
};

window.addEventListener("load", function() {
  !function(){if(!window.klaviyo){window._klOnsite=window._klOnsite||[];try{window.klaviyo=new Proxy({},{get:function(n,i){return"push"===i?function(){var n;(n=window._klOnsite).push.apply(n,arguments)}:function(){for(var n=arguments.length,o=new Array(n),w=0;w<n;w++)o[w]=arguments[w];var t="function"==typeof o[o.length-1]?o.pop():void 0,e=new Promise((function(n){window._klOnsite.push([i].concat(o,[function(i){t&&t(i),n(i)}]))}));return e}}})}catch(n){window.klaviyo=window.klaviyo||[],window.klaviyo.push=function(){var n;(n=window._klOnsite).push.apply(n,arguments)}}}}();
  trackViewedProduct();
});
