!function(e){var t={};function r(n){if(t[n])return t[n].exports;var o=t[n]={i:n,l:!1,exports:{}};return e[n].call(o.exports,o,o.exports,r),o.l=!0,o.exports}r.m=e,r.c=t,r.d=function(e,t,n){r.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},r.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},r.t=function(e,t){if(1&t&&(e=r(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(r.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var o in e)r.d(n,o,function(t){return e[t]}.bind(null,o));return n},r.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return r.d(t,"a",t),t},r.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},r.p="",r(r.s=10)}({0:function(e,t){e.exports=window.wp.hooks},1:function(e,t){e.exports=window.wp.blocks},10:function(e,t,r){"use strict";r.r(t);var n={};r.r(n),r.d(n,"BLOCK_NAME",(function(){return d})),r.d(n,"BENTO_VARIATION_NAME",(function(){return N})),r.d(n,"BENTO_VARIATION_TITLE",(function(){return T})),r.d(n,"BENTO_VARIATION_ICON",(function(){return A})),r.d(n,"BENTO_VARIATION_SCOPE",(function(){return _}));var o={};r.r(o),r.d(o,"BLOCK_NAME",(function(){return I})),r.d(o,"BENTO_VARIATION_NAME",(function(){return p})),r.d(o,"BENTO_VARIATION_TITLE",(function(){return E})),r.d(o,"BENTO_VARIATION_SCOPE",(function(){return B}));var i={};r.r(i),r.d(i,"BLOCK_NAME",(function(){return v})),r.d(i,"BENTO_VARIATION_NAME",(function(){return y})),r.d(i,"BENTO_VARIATION_TITLE",(function(){return j})),r.d(i,"BENTO_VARIATION_ICON",(function(){return g})),r.d(i,"BENTO_VARIATION_SCOPE",(function(){return w}));var c={};r.r(c),r.d(c,"BLOCK_NAME",(function(){return m})),r.d(c,"BENTO_VARIATION_NAME",(function(){return V})),r.d(c,"BENTO_VARIATION_TITLE",(function(){return P})),r.d(c,"BENTO_VARIATION_ICON",(function(){return R})),r.d(c,"BENTO_VARIATION_SCOPE",(function(){return k}));var u=r(0);function O(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function a(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?O(Object(r),!0).forEach((function(t){s(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):O(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}function s(e,t,r){return t in e?Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}):e[t]=r,e}var f=["coblocks/accordion","jetpack/slideshow","web-stories/embed","atomic-blocks/ab-sharing"];Object(u.addFilter)("blocks.registerBlockType","blocks-bento-variations/extend-coblocks-accordion-attributes",(function(e,t){return f.includes(t)?a(a({},e),{},{attributes:a(a({},e.attributes),{},{isBento:{type:"boolean",default:!1}})}):e}));var b=r(1),l=function(e){if(e){var t=e.BLOCK_NAME,r=e.BENTO_VARIATION_NAME,n=e.BENTO_VARIATION_TITLE,o=e.BENTO_VARIATION_ICON,i=e.BENTO_VARIATION_SCOPE;Object(b.registerBlockVariation)(t,{name:r,title:n,icon:o,scop:i,attributes:{isBento:!0},isActive:["isBento"]})}},d="jetpack/slideshow",N="jetpack/slideshow-bento",T="Slideshow (Bento)",A="images-alt",_=["inserter"];l(n);var I="web-stories/embed",p="web-stories/embed-bento",E="Web Stories (Bento)",B=["inserter"];l(o);var v="coblocks/accordion",y="coblocks/accordion-bento",j="Accordion (Bento)",g="images-alt",w=["inserter"];l(i);var m="atomic-blocks/ab-sharing",V="atomic-blocks/ab-sharing-bento",P="Sharing (Bento)",R="admin-links",k=["inserter"];l(c)}});