!function(){"use strict";var e,t={932:function(){function e(e,r){var n="undefined"!=typeof Symbol&&e[Symbol.iterator]||e["@@iterator"];if(!n){if(Array.isArray(e)||(n=function(e,r){if(e){if("string"==typeof e)return t(e,r);var n=Object.prototype.toString.call(e).slice(8,-1);return"Object"===n&&e.constructor&&(n=e.constructor.name),"Map"===n||"Set"===n?Array.from(e):"Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)?t(e,r):void 0}}(e))||r&&e&&"number"==typeof e.length){n&&(e=n);var o=0,i=function(){};return{s:i,n:function(){return o>=e.length?{done:!0}:{done:!1,value:e[o++]}},e:function(e){throw e},f:i}}throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}var a,s=!0,c=!1;return{s:function(){n=n.call(e)},n:function(){var e=n.next();return s=e.done,e},e:function(e){c=!0,a=e},f:function(){try{s||null==n.return||n.return()}finally{if(c)throw a}}}}function t(e,t){(null==t||t>e.length)&&(t=e.length);for(var r=0,n=new Array(t);r<t;r++)n[r]=e[r];return n}function r(e,t,r,n,o,i,a){try{var s=e[i](a),c=s.value}catch(e){return void r(e)}s.done?t(c):Promise.resolve(c).then(n,o)}function n(e){return function(){var t=this,n=arguments;return new Promise((function(o,i){var a=e.apply(t,n);function s(e){r(a,o,i,s,c,"next",e)}function c(e){r(a,o,i,s,c,"throw",e)}s(void 0)}))}}n(regeneratorRuntime.mark((function t(){var r,o,i,a,s,c;return regeneratorRuntime.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,window.customElements.whenDefined("bento-base-carousel");case 2:return t.next=4,window.customElements.whenDefined("bento-lightbox");case 4:r=document.querySelectorAll(".web-stories-list__carousel--bento"),o=function(){var t=n(regeneratorRuntime.mark((function t(){var r,n,o,i,a;return regeneratorRuntime.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:r=document.querySelectorAll(".web-stories-list .web-stories-list__inner-wrapper"),n=e(r),t.prev=2,n.s();case 4:if((o=n.n()).done){t.next=11;break}if(i=o.value,!(a=i.querySelector("bento-lightbox"))){t.next=9;break}return t.delegateYield(regeneratorRuntime.mark((function e(){var t,r,n;return regeneratorRuntime.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,a.getApi();case 2:t=e.sent,(r=i.querySelector("amp-story-player")).addEventListener("amp-story-player-close",(function(){r.rewind(),r.pause(),r.mute(),t.close(),document.body.classList.toggle("web-stories-lightbox-open")})),n=r.getStories().map((function(e){return e.href})),i.querySelectorAll("bento-base-carousel .web-stories-list__story").forEach((function(e,o){e.addEventListener("click",(function(){t.open(),r.show(n[o]),r.play(n[o]),document.body.classList.toggle("web-stories-lightbox-open")}))}));case 7:case"end":return e.stop()}}),e)}))(),"t0",9);case 9:t.next=4;break;case 11:t.next=16;break;case 13:t.prev=13,t.t1=t.catch(2),n.e(t.t1);case 16:return t.prev=16,n.f(),t.finish(16);case 19:case"end":return t.stop()}}),t,null,[[2,13,16,19]])})));return function(){return t.apply(this,arguments)}}(),i=function(e){if(e.closest(".web-stories-list").classList.contains("is-view-type-circles")){var t=e.querySelectorAll(".web-stories-list__story"),r=e.querySelector(".web-stories-list__story"),n=window.getComputedStyle(r),o=parseFloat(n.width)+(parseFloat(n.marginLeft)+parseFloat(n.marginRight));e.style.height=n.height;var i=o*t.length;e.style.width="".concat(i,"px")}},a=e(r),t.prev=8,c=regeneratorRuntime.mark((function e(){var t,r,n,a,c;return regeneratorRuntime.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return t=s.value,r=t.parentElement,e.next=4,t.getApi();case 4:n=e.sent,o(),i(t),r.querySelector(".bento-prev").addEventListener("click",(function(){n.prev()})),r.querySelector(".bento-next").addEventListener("click",(function(){n.next()})),a=t.closest(".web-stories-list").dataset.id,(c=document.querySelector(".web-stories-list__lightbox-wrapper.ws-lightbox-".concat(a)))&&c.remove();case 12:case"end":return e.stop()}}),e)})),a.s();case 11:if((s=a.n()).done){t.next=15;break}return t.delegateYield(c(),"t0",13);case 13:t.next=11;break;case 15:t.next=20;break;case 17:t.prev=17,t.t1=t.catch(8),a.e(t.t1);case 20:return t.prev=20,a.f(),t.finish(20);case 23:document.querySelectorAll("bento-base-carousel").forEach((function(e){var t=document.createElement("style");t.innerHTML='div[class*="slide-sizing-"] > ::slotted(*),\t\t\t\tdiv[class^="slide-sizing-"] > ::slotted(*) {\t\t\t\t\tmargin: 0 5px !important;\t\t\t}',e.shadowRoot.appendChild(t)}));case 24:case"end":return t.stop()}}),t,null,[[8,17,20,23]])})))()}},r={};function n(e){var o=r[e];if(void 0!==o)return o.exports;var i=r[e]={exports:{}};return t[e](i,i.exports,n),i.exports}n.m=t,e=[],n.O=function(t,r,o,i){if(!r){var a=1/0;for(l=0;l<e.length;l++){r=e[l][0],o=e[l][1],i=e[l][2];for(var s=!0,c=0;c<r.length;c++)(!1&i||a>=i)&&Object.keys(n.O).every((function(e){return n.O[e](r[c])}))?r.splice(c--,1):(s=!1,i<a&&(a=i));if(s){e.splice(l--,1);var u=o();void 0!==u&&(t=u)}}return t}i=i||0;for(var l=e.length;l>0&&e[l-1][2]>i;l--)e[l]=e[l-1];e[l]=[r,o,i]},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},function(){var e={157:0,626:0};n.O.j=function(t){return 0===e[t]};var t=function(t,r){var o,i,a=r[0],s=r[1],c=r[2],u=0;if(a.some((function(t){return 0!==e[t]}))){for(o in s)n.o(s,o)&&(n.m[o]=s[o]);if(c)var l=c(n)}for(t&&t(r);u<a.length;u++)i=a[u],n.o(e,i)&&e[i]&&e[i][0](),e[i]=0;return n.O(l)},r=self.webpackChunkblocks_bento_variations=self.webpackChunkblocks_bento_variations||[];r.forEach(t.bind(null,0)),r.push=t.bind(null,r.push.bind(r))}();var o=n.O(void 0,[626],(function(){return n(932)}));o=n.O(o)}();