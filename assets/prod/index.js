!function(){var t={705:function(t,n,e){var r=e(639).Symbol;t.exports=r},239:function(t,n,e){var r=e(705),o=e(607),i=e(333),a=r?r.toStringTag:void 0;t.exports=function(t){return null==t?void 0===t?"[object Undefined]":"[object Null]":a&&a in Object(t)?o(t):i(t)}},561:function(t,n,e){var r=e(990),o=/^\s+/;t.exports=function(t){return t?t.slice(0,r(t)+1).replace(o,""):t}},957:function(t,n,e){var r="object"==typeof e.g&&e.g&&e.g.Object===Object&&e.g;t.exports=r},607:function(t,n,e){var r=e(705),o=Object.prototype,i=o.hasOwnProperty,a=o.toString,c=r?r.toStringTag:void 0;t.exports=function(t){var n=i.call(t,c),e=t[c];try{t[c]=void 0;var r=!0}catch(t){}var o=a.call(t);return r&&(n?t[c]=e:delete t[c]),o}},333:function(t){var n=Object.prototype.toString;t.exports=function(t){return n.call(t)}},639:function(t,n,e){var r=e(957),o="object"==typeof self&&self&&self.Object===Object&&self,i=r||o||Function("return this")();t.exports=i},990:function(t){var n=/\s/;t.exports=function(t){for(var e=t.length;e--&&n.test(t.charAt(e)););return e}},279:function(t,n,e){var r=e(218),o=e(771),i=e(841),a=Math.max,c=Math.min;t.exports=function(t,n,e){var u,f,l,s,d,p,v=0,m=!1,g=!1,h=!0;if("function"!=typeof t)throw new TypeError("Expected a function");function y(n){var e=u,r=f;return u=f=void 0,v=n,s=t.apply(r,e)}function b(t){var e=t-p;return void 0===p||e>=n||e<0||g&&t-v>=l}function w(){var t=o();if(b(t))return x(t);d=setTimeout(w,function(t){var e=n-(t-p);return g?c(e,l-(t-v)):e}(t))}function x(t){return d=void 0,h&&u?y(t):(u=f=void 0,s)}function j(){var t=o(),e=b(t);if(u=arguments,f=this,p=t,e){if(void 0===d)return function(t){return v=t,d=setTimeout(w,n),m?y(t):s}(p);if(g)return clearTimeout(d),d=setTimeout(w,n),y(p)}return void 0===d&&(d=setTimeout(w,n)),s}return n=i(n)||0,r(e)&&(m=!!e.leading,l=(g="maxWait"in e)?a(i(e.maxWait)||0,n):l,h="trailing"in e?!!e.trailing:h),j.cancel=function(){void 0!==d&&clearTimeout(d),v=0,u=p=f=d=void 0},j.flush=function(){return void 0===d?s:x(o())},j}},218:function(t){t.exports=function(t){var n=typeof t;return null!=t&&("object"==n||"function"==n)}},5:function(t){t.exports=function(t){return null!=t&&"object"==typeof t}},448:function(t,n,e){var r=e(239),o=e(5);t.exports=function(t){return"symbol"==typeof t||o(t)&&"[object Symbol]"==r(t)}},771:function(t,n,e){var r=e(639);t.exports=function(){return r.Date.now()}},493:function(t,n,e){var r=e(279),o=e(218);t.exports=function(t,n,e){var i=!0,a=!0;if("function"!=typeof t)throw new TypeError("Expected a function");return o(e)&&(i="leading"in e?!!e.leading:i,a="trailing"in e?!!e.trailing:a),r(t,n,{leading:i,maxWait:n,trailing:a})}},841:function(t,n,e){var r=e(561),o=e(218),i=e(448),a=/^[-+]0x[0-9a-f]+$/i,c=/^0b[01]+$/i,u=/^0o[0-7]+$/i,f=parseInt;t.exports=function(t){if("number"==typeof t)return t;if(i(t))return NaN;if(o(t)){var n="function"==typeof t.valueOf?t.valueOf():t;t=o(n)?n+"":n}if("string"!=typeof t)return 0===t?t:+t;t=r(t);var e=c.test(t);return e||u.test(t)?f(t.slice(2),e?2:8):a.test(t)?NaN:+t}}},n={};function e(r){var o=n[r];if(void 0!==o)return o.exports;var i=n[r]={exports:{}};return t[r](i,i.exports,e),i.exports}e.n=function(t){var n=t&&t.__esModule?function(){return t.default}:function(){return t};return e.d(n,{a:n}),n},e.d=function(t,n){for(var r in n)e.o(n,r)&&!e.o(t,r)&&Object.defineProperty(t,r,{enumerable:!0,get:n[r]})},e.g=function(){if("object"==typeof globalThis)return globalThis;try{return this||new Function("return this")()}catch(t){if("object"==typeof window)return window}}(),e.o=function(t,n){return Object.prototype.hasOwnProperty.call(t,n)},function(){"use strict";var t=e(493),n=e.n(t);function r(t,n){(null==n||n>t.length)&&(n=t.length);for(var e=0,r=new Array(n);e<n;e++)r[e]=t[e];return r}function o(t){return o="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},o(t)}var i=window.jQuery;e.n(i)()((function(){var t=document.querySelectorAll('iframe[src*="jotform.com"],iframe[data-src*="jotform.com"]');null!=t&&t.length&&(t.forEach((function(t){var n,e;t.addEventListener("load",(function t(n){n.target.removeEventListener("load",t),n.target.addEventListener("load",(function(){return window.parent.scrollTo(0,0)}))}));var r=t.getAttribute("src");if(t.getAttribute("data-src")&&(r=t.getAttribute("data-src")),(null===(n=window.location)||void 0===n||null===(e=n.search)||void 0===e?void 0:e.length)>1){var o=window.location.search.substring(1);r+=(r.indexOf("?")>-1?"&":"?")+o}r!==t.getAttribute("src")&&t.setAttribute("src",r)})),window.addEventListener("message",(function(t){if("object"!==o(t.data)&&"https://form.jotform.com"===(null==t?void 0:t.origin)){var n=t.data.split(":");if(n&&!(n.length<2)){var e=function(n){var e;(e=document.querySelectorAll('iframe[src*="jotform.com"],iframe[data-src*="jotform.com"]'),function(t){if(Array.isArray(t))return r(t)}(e)||function(t){if("undefined"!=typeof Symbol&&null!=t[Symbol.iterator]||null!=t["@@iterator"])return Array.from(t)}(e)||function(t,n){if(t){if("string"==typeof t)return r(t,n);var e=Object.prototype.toString.call(t).slice(8,-1);return"Object"===e&&t.constructor&&(e=t.constructor.name),"Map"===e||"Set"===e?Array.from(t):"Arguments"===e||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(e)?r(t,n):void 0}}(e)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()).some((function(e){if(e.contentWindow==t.source)return n(e),!1}))};switch(n[0]){case"scrollIntoView":e((function(t){t.scrollIntoView({block:"center"})}));break;case"setHeight":parseInt(n[1])>0&&e((function(t){t.setAttribute("height",n[1]+"px")}));break;case"reloadPage":window.self.location.reload();break;case"loadScript":break;case"exitFullScreen":["exitFullscreen","mozCancelFullscreen","webkitExitFullscreen","msExitFullscreen"].some((function(t){if(window.document[t])return window.document[t](),!1}))}e((function(t){var n;if(null!=t&&null!==(n=t.contentWindow)&&void 0!==n&&n.postMessage){var e={docurl:encodeURIComponent(document.URL),referrer:encodeURIComponent(document.referrer)};t.contentWindow.postMessage(JSON.stringify({type:"urls",value:e}))}}))}}else console.log(t.origin,"https://form.jotform.com"!==t.origin)}),!1))})),function(t,e,r){var o,i,a,c,u,f,l=t("html"),s=t("body"),d=t("main"),p=t(".masthead"),v=t(".scroll-down-arrow"),m=t(".hero .video-container"),g=t(".hero video");function h(r,o,i,a){void 0===i&&(i=100),void 0===a&&(a=!0),t(r),t(e).on(o,n()(r,i,{leading:!0,trailing:!0})),a&&r()}function y(){var n=l.scrollTop();n>0&&n+o.top>=c?(p.addClass("switch"),w()):(b()||g.trigger("play"),p.removeClass("switch")),g.length&&f>.7*i&&t(e).scrollTop()<100?v.fadeIn(200):v.fadeOut(200)}function b(){if(g.length){var t=g.get(0);if(t.currentTime>0&&!t.paused&&!t.ended&&t.readyState>2)return!0}return!1}function w(){b()&&g.trigger("pause")}function x(t){!0!==t&&s.hasClass("menu-active")?(!1===t||s.hasClass("menu-active"))&&(g.trigger("play"),s.removeClass("menu-active"),p.removeClass("menu-active")):(g.trigger("pause"),s.addClass("menu-active"),p.addClass("menu-active"),p.find(".menu").scrollTop(0))}function j(){if(e.location.hash.length>1){t('.masthead nav.menu a[href*="#"]').parent().removeClass("current_page_item");var n=t('.masthead nav.menu a[href*="'+e.location.hash+'"]');n.length&&n.parent().addClass("current_page_item")}}h((function(){i=t(e).height(),a=p.outerHeight(),c=Math.ceil(d.position().top),o={top:Math.abs(l.position().top)+a,bottom:t(e).height()},g.length&&(u=m.outerHeight(),f=m.position().top+u)}),"resize load"),h(y,"scroll resize load"),t(e).on("blur",(function(){w()})),t(e).on("focus",(function(){y()})),t(".hamburger-container").on("click",(function(){x()})),t('.masthead nav.menu a[href*="#"]').on("click",(function(){var t=new URL(this.href);e.location.pathname==t.pathname&&(setTimeout(j,500),x(!1))})),t((function(){j()})),v.on("click",(function(n){n.preventDefault(),t("html, body").animate({scrollTop:d.offset().top-a},500)}))}(jQuery,window.self)}()}();