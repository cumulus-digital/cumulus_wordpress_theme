!function(){var t={705:function(t,n,e){var o=e(639).Symbol;t.exports=o},239:function(t,n,e){var o=e(705),r=e(607),i=e(333),u=o?o.toStringTag:void 0;t.exports=function(t){return null==t?void 0===t?"[object Undefined]":"[object Null]":u&&u in Object(t)?r(t):i(t)}},561:function(t,n,e){var o=e(990),r=/^\s+/;t.exports=function(t){return t?t.slice(0,o(t)+1).replace(r,""):t}},957:function(t,n,e){var o="object"==typeof e.g&&e.g&&e.g.Object===Object&&e.g;t.exports=o},607:function(t,n,e){var o=e(705),r=Object.prototype,i=r.hasOwnProperty,u=r.toString,a=o?o.toStringTag:void 0;t.exports=function(t){var n=i.call(t,a),e=t[a];try{t[a]=void 0;var o=!0}catch(t){}var r=u.call(t);return o&&(n?t[a]=e:delete t[a]),r}},333:function(t){var n=Object.prototype.toString;t.exports=function(t){return n.call(t)}},639:function(t,n,e){var o=e(957),r="object"==typeof self&&self&&self.Object===Object&&self,i=o||r||Function("return this")();t.exports=i},990:function(t){var n=/\s/;t.exports=function(t){for(var e=t.length;e--&&n.test(t.charAt(e)););return e}},279:function(t,n,e){var o=e(218),r=e(771),i=e(841),u=Math.max,a=Math.min;t.exports=function(t,n,e){var c,f,s,l,v,p,d=0,g=!1,h=!1,m=!0;if("function"!=typeof t)throw new TypeError("Expected a function");function b(n){var e=c,o=f;return c=f=void 0,d=n,l=t.apply(o,e)}function y(t){return d=t,v=setTimeout(j,n),g?b(t):l}function x(t){var e=t-p;return void 0===p||e>=n||e<0||h&&t-d>=s}function j(){var t=r();if(x(t))return w(t);v=setTimeout(j,function(t){var e=n-(t-p);return h?a(e,s-(t-d)):e}(t))}function w(t){return v=void 0,m&&c?b(t):(c=f=void 0,l)}function T(){var t=r(),e=x(t);if(c=arguments,f=this,p=t,e){if(void 0===v)return y(p);if(h)return clearTimeout(v),v=setTimeout(j,n),b(p)}return void 0===v&&(v=setTimeout(j,n)),l}return n=i(n)||0,o(e)&&(g=!!e.leading,s=(h="maxWait"in e)?u(i(e.maxWait)||0,n):s,m="trailing"in e?!!e.trailing:m),T.cancel=function(){void 0!==v&&clearTimeout(v),d=0,c=p=f=v=void 0},T.flush=function(){return void 0===v?l:w(r())},T}},218:function(t){t.exports=function(t){var n=typeof t;return null!=t&&("object"==n||"function"==n)}},5:function(t){t.exports=function(t){return null!=t&&"object"==typeof t}},448:function(t,n,e){var o=e(239),r=e(5);t.exports=function(t){return"symbol"==typeof t||r(t)&&"[object Symbol]"==o(t)}},771:function(t,n,e){var o=e(639);t.exports=function(){return o.Date.now()}},493:function(t,n,e){var o=e(279),r=e(218);t.exports=function(t,n,e){var i=!0,u=!0;if("function"!=typeof t)throw new TypeError("Expected a function");return r(e)&&(i="leading"in e?!!e.leading:i,u="trailing"in e?!!e.trailing:u),o(t,n,{leading:i,maxWait:n,trailing:u})}},841:function(t,n,e){var o=e(561),r=e(218),i=e(448),u=/^[-+]0x[0-9a-f]+$/i,a=/^0b[01]+$/i,c=/^0o[0-7]+$/i,f=parseInt;t.exports=function(t){if("number"==typeof t)return t;if(i(t))return NaN;if(r(t)){var n="function"==typeof t.valueOf?t.valueOf():t;t=r(n)?n+"":n}if("string"!=typeof t)return 0===t?t:+t;t=o(t);var e=a.test(t);return e||c.test(t)?f(t.slice(2),e?2:8):u.test(t)?NaN:+t}}},n={};function e(o){var r=n[o];if(void 0!==r)return r.exports;var i=n[o]={exports:{}};return t[o](i,i.exports,e),i.exports}e.n=function(t){var n=t&&t.__esModule?function(){return t.default}:function(){return t};return e.d(n,{a:n}),n},e.d=function(t,n){for(var o in n)e.o(n,o)&&!e.o(t,o)&&Object.defineProperty(t,o,{enumerable:!0,get:n[o]})},e.g=function(){if("object"==typeof globalThis)return globalThis;try{return this||new Function("return this")()}catch(t){if("object"==typeof window)return window}}(),e.o=function(t,n){return Object.prototype.hasOwnProperty.call(t,n)},function(){"use strict";var t=e(493),n=e.n(t);!function(t,e,o){var r,i,u,a,c,f,s=t("html"),l=t("body"),v=t("main"),p=t(".masthead"),d=t(".scroll-down-arrow"),g=t(".hero .video-container"),h=t(".hero video");function m(o,r,i,u){void 0===i&&(i=100),void 0===u&&(u=!0),t(o),t(e).on(r,n()(o,i,{leading:!0,trailing:!0})),u&&o()}function b(){var n=s.scrollTop();n>0&&n+r.top>=a?(p.addClass("switch"),x()):(y()||h.trigger("play"),p.removeClass("switch")),h.length&&f>.7*i&&t(e).scrollTop()<100?d.fadeIn(200):d.fadeOut(200)}function y(){if(h.length){var t=h.get(0);if(t.currentTime>0&&!t.paused&&!t.ended&&t.readyState>2)return!0}return!1}function x(){y()&&h.trigger("pause")}function j(t){t||!l.hasClass("menu-active")?(h.trigger("pause"),l.addClass("menu-active"),p.addClass("menu-active"),p.find(".menu").scrollTop(0)):(h.trigger("play"),l.removeClass("menu-active"),p.removeClass("menu-active"))}m((function(){i=t(e).height(),u=p.outerHeight(),a=Math.ceil(v.position().top),r={top:Math.abs(s.position().top)+u,bottom:t(e).height()},h.length&&(c=g.outerHeight(),f=g.position().top+c)}),"resize load"),m(b,"scroll resize load"),t(e).on("blur",(function(){x()})),t(e).on("focus",(function(){b()})),t('.hamburger-container, .masthead nav.menu a[href*="#"]').on("click",(function(){j()})),t(".masthead nav.menu a").on("focus",(function(){j(!0)})),t(".masthead nav.menu a").on("focusout",(function(){j(!1)})),d.on("click",(function(n){n.preventDefault(),t("html, body").animate({scrollTop:v.offset().top-u},500)}))}(jQuery,window.self)}()}();