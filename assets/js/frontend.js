jQuery(function(a){function b(){var a=c.ajax({action:"wooc_update_checkout"});a.done(function(a){d.html(a.data)})}console.log(wooc_ajax_object);var c={ajax:function(a){return jQuery.post(wooc_ajax_object.ajax_url,a)}};a(".woosc-floating-cart-footer > ul > li > a").on("click",function(c){c.preventDefault();var d=a(this).attr("ID");"woosc-floating-checkout-btn"==d?(a(".woosc-floating-cart-inner").hide(),b(),a(".woosc-floating-checkout-inner").show()):"woosc-floating-cart-btn"==d&&(a(".woosc-floating-cart-inner").show(),a(".woosc-floating-checkout-inner").hide())});var d=a("#woosc-floating-checkout-inner");d.on("click",".woocommerce-remove-coupon",function(d){var e=a(d.target);e.block({message:null,overlayCSS:{background:"#fff",opacity:.6}});var f={action:"wooc_remove_coupon"},g=this.search.split("remove_coupon=");if(g[1]!==void 0){f.coupon=g[1];var h=c.ajax(f);h.done(function(){e.unblock(),b()})}return!1})});