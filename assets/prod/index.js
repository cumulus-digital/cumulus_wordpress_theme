!function(){var t={705:function(t,e,n){var o=n(639).Symbol;t.exports=o},239:function(t,e,n){var o=n(705),r=n(607),i=n(333),a=o?o.toStringTag:void 0;t.exports=function(t){return null==t?void 0===t?"[object Undefined]":"[object Null]":a&&a in Object(t)?r(t):i(t)}},561:function(t,e,n){var o=n(990),r=/^\s+/;t.exports=function(t){return t?t.slice(0,o(t)+1).replace(r,""):t}},957:function(t,e,n){var o="object"==typeof n.g&&n.g&&n.g.Object===Object&&n.g;t.exports=o},607:function(t,e,n){var o=n(705),r=Object.prototype,i=r.hasOwnProperty,a=r.toString,c=o?o.toStringTag:void 0;t.exports=function(t){var e=i.call(t,c),n=t[c];try{t[c]=void 0;var o=!0}catch(t){}var r=a.call(t);return o&&(e?t[c]=n:delete t[c]),r}},333:function(t){var e=Object.prototype.toString;t.exports=function(t){return e.call(t)}},639:function(t,e,n){var o=n(957),r="object"==typeof self&&self&&self.Object===Object&&self,i=o||r||Function("return this")();t.exports=i},990:function(t){var e=/\s/;t.exports=function(t){for(var n=t.length;n--&&e.test(t.charAt(n)););return n}},279:function(t,e,n){var o=n(218),r=n(771),i=n(841),a=Math.max,c=Math.min;t.exports=function(t,e,n){var u,s,f,l,d,p,v=0,m=!1,g=!1,h=!0;if("function"!=typeof t)throw new TypeError("Expected a function");function b(e){var n=u,o=s;return u=s=void 0,v=e,l=t.apply(o,n)}function w(t){var n=t-p;return void 0===p||n>=e||n<0||g&&t-v>=f}function y(){var t=r();if(w(t))return x(t);d=setTimeout(y,function(t){var n=e-(t-p);return g?c(n,f-(t-v)):n}(t))}function x(t){return d=void 0,h&&u?b(t):(u=s=void 0,l)}function j(){var t=r(),n=w(t);if(u=arguments,s=this,p=t,n){if(void 0===d)return function(t){return v=t,d=setTimeout(y,e),m?b(t):l}(p);if(g)return clearTimeout(d),d=setTimeout(y,e),b(p)}return void 0===d&&(d=setTimeout(y,e)),l}return e=i(e)||0,o(n)&&(m=!!n.leading,f=(g="maxWait"in n)?a(i(n.maxWait)||0,e):f,h="trailing"in n?!!n.trailing:h),j.cancel=function(){void 0!==d&&clearTimeout(d),v=0,u=p=s=d=void 0},j.flush=function(){return void 0===d?l:x(r())},j}},218:function(t){t.exports=function(t){var e=typeof t;return null!=t&&("object"==e||"function"==e)}},5:function(t){t.exports=function(t){return null!=t&&"object"==typeof t}},448:function(t,e,n){var o=n(239),r=n(5);t.exports=function(t){return"symbol"==typeof t||r(t)&&"[object Symbol]"==o(t)}},771:function(t,e,n){var o=n(639);t.exports=function(){return o.Date.now()}},493:function(t,e,n){var o=n(279),r=n(218);t.exports=function(t,e,n){var i=!0,a=!0;if("function"!=typeof t)throw new TypeError("Expected a function");return r(n)&&(i="leading"in n?!!n.leading:i,a="trailing"in n?!!n.trailing:a),o(t,e,{leading:i,maxWait:e,trailing:a})}},841:function(t,e,n){var o=n(561),r=n(218),i=n(448),a=/^[-+]0x[0-9a-f]+$/i,c=/^0b[01]+$/i,u=/^0o[0-7]+$/i,s=parseInt;t.exports=function(t){if("number"==typeof t)return t;if(i(t))return NaN;if(r(t)){var e="function"==typeof t.valueOf?t.valueOf():t;t=r(e)?e+"":e}if("string"!=typeof t)return 0===t?t:+t;t=o(t);var n=c.test(t);return n||u.test(t)?s(t.slice(2),n?2:8):a.test(t)?NaN:+t}}},e={};function n(o){var r=e[o];if(void 0!==r)return r.exports;var i=e[o]={exports:{}};return t[o](i,i.exports,n),i.exports}n.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(e,{a:e}),e},n.d=function(t,e){for(var o in e)n.o(e,o)&&!n.o(t,o)&&Object.defineProperty(t,o,{enumerable:!0,get:e[o]})},n.g=function(){if("object"==typeof globalThis)return globalThis;try{return this||new Function("return this")()}catch(t){if("object"==typeof window)return window}}(),n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},function(){"use strict";var t=n(493),e=n.n(t),o=window.jQuery;n.n(o)()((()=>{const t=document.querySelectorAll('iframe[src*="jotform.com"],iframe[data-src*="jotform.com"]');t?.length&&(t.forEach((t=>{t.addEventListener("load",(function t(e){e.target.removeEventListener("load",t),e.target.addEventListener("load",(()=>window.parent.scrollTo(0,0)))}));let e=t.getAttribute("src");if(t.getAttribute("data-src")&&(e=t.getAttribute("data-src")),window.location?.search?.length>1){const t=window.location.search.substring(1);e+=(e.indexOf("?")>-1?"&":"?")+t}e!==t.getAttribute("src")&&t.setAttribute("src",e)})),window.addEventListener("message",(t=>{if("object"==typeof t.data||"https://form.jotform.com"!==t?.origin)return void console.log(t.origin,"https://form.jotform.com"!==t.origin);const e=t.data.split(":");if(!e||e.length<2)return;const n=e=>{[...document.querySelectorAll('iframe[src*="jotform.com"],iframe[data-src*="jotform.com"]')].some((n=>{if(n.contentWindow==t.source)return e(n),!1}))};switch(e[0]){case"scrollIntoView":n((t=>{t.scrollIntoView({block:"center"})}));break;case"setHeight":parseInt(e[1])>0&&n((t=>{t.setAttribute("height",e[1]+"px")}));break;case"reloadPage":window.self.location.reload();break;case"loadScript":break;case"exitFullScreen":["exitFullscreen","mozCancelFullscreen","webkitExitFullscreen","msExitFullscreen"].some((t=>{if(window.document[t])return window.document[t](),!1}))}n((t=>{if(t?.contentWindow?.postMessage){var e={docurl:encodeURIComponent(document.URL),referrer:encodeURIComponent(document.referrer)};t.contentWindow.postMessage(JSON.stringify({type:"urls",value:e}))}}))}),!1))})),function(t,n,o){var r,i,a,c,u,s,f=t("html"),l=t("body"),d=t("main"),p=t(".masthead"),v=t(".scroll-down-arrow"),m=t(".hero .video-container"),g=t(".hero video");function h(o,r,i,a){void 0===i&&(i=100),void 0===a&&(a=!0),t(o),t(n).on(r,e()(o,i,{leading:!0,trailing:!0})),a&&o()}function b(){var e=f.scrollTop();e>0&&e+r.top>=c?(p.addClass("switch"),y()):(w()||g.trigger("play"),p.removeClass("switch")),g.length&&s>.7*i&&t(n).scrollTop()<100?v.fadeIn(200):v.fadeOut(200)}function w(){if(g.length){var t=g.get(0);if(t.currentTime>0&&!t.paused&&!t.ended&&t.readyState>2)return!0}return!1}function y(){w()&&g.trigger("pause")}function x(t){!0!==t&&l.hasClass("menu-active")?(!1===t||l.hasClass("menu-active"))&&(g.trigger("play"),l.removeClass("menu-active"),p.removeClass("menu-active")):(g.trigger("pause"),l.addClass("menu-active"),p.addClass("menu-active"),p.find(".menu").scrollTop(0))}function j(){if(n.location.hash.length>1){t('.masthead nav.menu a[href*="#"]').parent().removeClass("current_page_item");var e=t('.masthead nav.menu a[href*="'+n.location.hash+'"]');e.length&&e.parent().addClass("current_page_item")}}h((function(){i=t(n).height(),a=p.outerHeight(),c=Math.ceil(d.position().top),r={top:Math.abs(f.position().top)+a,bottom:t(n).height()},g.length&&(u=m.outerHeight(),s=m.position().top+u)}),"resize load"),h(b,"scroll resize load"),t(n).on("blur",(function(){y()})),t(n).on("focus",(function(){b()})),t(".hamburger-container").on("click",(function(){x()})),t('.masthead nav.menu a[href*="#"]').on("click",(function(){var t=new URL(this.href);n.location.pathname==t.pathname&&(setTimeout(j,500),x(!1))})),t((function(){j()})),v.on("click",(function(e){e.preventDefault(),t("html, body").animate({scrollTop:d.offset().top-a},500)}))}(jQuery,window.self)}()}();