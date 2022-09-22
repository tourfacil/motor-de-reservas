!function(e){var t={};function n(o){if(t[o])return t[o].exports;var a=t[o]={i:o,l:!1,exports:{}};return e[o].call(a.exports,a,a.exports,n),a.l=!0,a.exports}n.m=e,n.c=t,n.d=function(e,t,o){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:o})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var o=Object.create(null);if(n.r(o),Object.defineProperty(o,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var a in e)n.d(o,a,function(t){return e[t]}.bind(null,a));return o},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="/",n(n.s=1)}({1:function(e,t,n){e.exports=n("JO1w")},"8c1N":function(e,t){function n(){return new DOMException("The request is not allowed","NotAllowedError")}e.exports=async function(e){try{await async function(e){if(!navigator.clipboard)throw n();return navigator.clipboard.writeText(e)}(e)}catch(t){try{await async function(e){const t=document.createElement("span");t.textContent=e,t.style.whiteSpace="pre",t.style.webkitUserSelect="auto",t.style.userSelect="all",document.body.appendChild(t);const o=window.getSelection(),a=window.document.createRange();o.removeAllRanges(),a.selectNode(t),o.addRange(a);let i=!1;try{i=window.document.execCommand("copy")}finally{o.removeAllRanges(),window.document.body.removeChild(t)}if(!i)throw n()}(e)}catch(e){throw e||t||n()}}}},JO1w:function(e,t,n){window.App={init:function(){App.initControllers(),Helpers.normalizeString(),Helpers.bindOpenModal(),App.bindValidateInput(),App.autoValidateForm(),App.showListAcompanhantes(),App.cookieConsent(),App.onScrollNavShadow(),window.click_copy=n("8c1N")},initControllers:function(){for(var e=$("[data-controller]"),t=e.length,n=0;n<t;n++){var o=e[n],a=o.getAttribute("data-controller");$.isPlainObject(window[a])&&$.isFunction(window[a].init)?window[a].init(o):console.error("Nenhum controller definido como ".concat(a))}},bindValidateInput:function(){$(document).on("changeData","input.is-invalid",(function(e){$(e.currentTarget).removeClass("is-invalid valid")})),$(document).on("focus","input.is-invalid",(function(e){$(e.currentTarget).removeClass("is-invalid valid")})),$(document).on("change","select.is-invalid",(function(e){$(e.currentTarget).removeClass("is-invalid valid")}))},autoValidateForm:function(){$("form[data-validate-post]").on("submit",(function(e){var t=$(e.currentTarget);window.App.validateForm(t)?window.Helpers.loader.show():e.preventDefault()})),$("form[data-validate-ajax]").on("submit",(function(e){e.preventDefault();var t=$(e.currentTarget);if(window.App.validateForm(t)){window.Helpers.loader.show();var n=$.post(t.attr("action"),t.serialize());n.done((function(e){return App.captureSuccessAjax(e,t)})),n.fail(App.captureErrorAjax)}}))},captureSuccessAjax:function(e,t){window.Helpers.loadSweetAlert((function(){e.action.result?void 0!==e.action.url&&void 0!==t.attr("data-redirect")?window.location.href=e.action.url:(window.Helpers.loader.hide(),swal(e.action.title,e.action.message,"success").then((function(){window.Helpers.loader.show(),void 0!==e.action.url?window.location.href=e.action.url:location.reload()}))):(window.Helpers.loader.hide(),swal(e.action.title,e.action.message,"error"))}))},captureErrorAjax:function(e){window.Helpers.loadSweetAlert((function(){window.Helpers.loader.hide();var t=e.responseJSON;if(t)if(422===parseInt(e.status)){var n=t.errors;for(var o in n)if(n.hasOwnProperty(o)){var a=$("[name='"+o+"']"),i=a.siblings(".invalid-feedback");a&&(a.addClass("is-invalid"),i.html(n[o][0]))}}else{var r=t.message||"Erro na requisição!";swal("Op's erro "+e.status,r,"error")}else swal("Op's ocorreu um erro!","Tente novamente, caso o erro persista entre em contato conosco.","error")}))},validateForm:function(e){for(var t=$(e).find("[data-required]:not(:disabled)"),n=t.length,o=0,a=0;a<n;a++){var i=$(t[a]),r=i.attr("title"),l=i.val().trim(),c=i.siblings(".invalid-feedback");if(r=null===r?i.attr("placeholder").trim():r.trim(),void 0!==i.attr("type")){var s=parseInt(i.attr("data-min"));if(0===l.length){c.html("O campo "+r.toLowerCase()+" não pode ser vazio!"),i.addClass("is-invalid"),o++;continue}if(i.attr("data-nome-completo"))if(void 0===l.trim().split(" ")[1]){c.html("Por favor insira o nome completo!"),i.addClass("is-invalid"),o++;continue}if("email"===i.attr("type")&&!window.Helpers.isMail(l)){c.html("O e-mail deve ser um endereço de e-mail válido!"),i.addClass("is-invalid"),o++;continue}if(i.hasClass("is-invalid")){if("date"===i.attr("data-mask")){c.html("Formato da data de nascimento inválida. Por favor verifique! Formato aceito: DD/MM/AAAA"),i.addClass("is-invalid"),o++;continue}i.addClass("is-invalid"),o++;continue}s&&l.length<s&&(c.html("O campo "+r.toLowerCase()+", deve ter mais de "+s+" caracteres!"),i.addClass("is-invalid"),o++)}}return 0===o},showListAcompanhantes:function(){var e;$(document).on("focus","input[data-list]",(function(t){var n=$(t.currentTarget),o=$("ul.list-help");if(o.length){o.hide(),clearTimeout(e);var a=n[0].getBoundingClientRect(),i=a.top+a.height+2,r=$("#".concat(n.attr("data-list"))),l=$(window).height()-50,c=r.height();i+c>l&&(i=a.top-c-12),r.css({top:"".concat(i,"px"),left:"".concat(a.left,"px")}).attr("data-origin",n.attr("id")).show()}})),$(document).on("blur","input[data-list]",(function(t){var n=$(t.currentTarget);e=setTimeout((function(){$("ul.list-help#".concat(n.attr("data-list"))).hide()}),150)})),$(document).on("click",(function(e){var t=$(e.currentTarget);if(t[0].activeElement){var n=t[0].activeElement.getAttribute("data-list");n&&$("ul.list-help:not(#".concat(n,")")).hide()}})),$(document).on("click","ul.list-help li",(function(e){e.preventDefault();var t=$(e.currentTarget),n=window.DadosFactory.getDadosAcompanhantes(),o=t.parent("ul"),a=$("#".concat(o.attr("data-origin"))).parents(".list-acompanhantes"),i=n[t.attr("data-index")]||null;null!==i&&(a.find("[data-callback='nome']").val(i.nome).trigger("keyup").trigger("changeData").trigger("blur"),a.find("[data-callback='documento']").val(i.documento).trigger("keyup").trigger("changeData").trigger("blur"),a.find("[data-callback='nascimento']").val(i.nascimento).trigger("keyup").trigger("changeData").trigger("blur"))}))},cookie:{create:function(e,t,n){var o="";if(n){var a=new Date;a.setTime(a.getTime()+24*n*60*60*1e3),o="; expires="+a.toGMTString()}document.cookie=escape(e)+"="+escape(t)+o+"; path=/; samesite=lax"},read:function(e){for(var t=escape(e)+"=",n=document.cookie.split(";"),o=0;o<n.length;o++){for(var a=n[o];" "===a.charAt(0);)a=a.substring(1,a.length);if(0===a.indexOf(t))return unescape(a.substring(t.length,a.length))}return null},erase:function(e){App.cookie.create(e,"",-1)}},onScrollNavShadow:function(){for(var e=$(".nav-scroll-shadows"),t=e.length,n=function(t){var n=$(e[t]);n.find(".horizontal-scroll").on("scroll",(function(e){var t=$(e.currentTarget),o=t.scrollLeft(),a=t.width(),i=t.get(0).scrollWidth;o>=10?n.addClass("shadow-left"):n.removeClass("shadow-left"),i-o-a<=10?n.addClass("no-shadow-right"):n.removeClass("no-shadow-right")}))},o=0;o<t;o++)n(o)},cookieConsent:function(){"true"!==App.cookie.read("cookieConsent")&&$("#cookie-consent").addClass("show"),$("[data-action='closePopoutCookie']").on("click",(function(e){e.preventDefault(),App.cookie.create("cookieConsent","true",1e3),$("#cookie-consent").removeClass("show")}))},sliderConfig:{withButtons:{rewind:!0,lazyLoad:!0,margin:24,dots:!1,nav:!0,navText:['<i class="iconify" data-icon="jam:chevron-left"></i>','<i class="iconify" data-icon="jam:chevron-right"></i>'],responsive:{0:{items:1,stagePadding:15},375:{items:1,stagePadding:25},380:{items:1.3,stagePadding:20},600:{items:1.7},768:{items:2},992:{items:3},1280:{items:4},1590:{items:5}}}}},window,window.App.init()}});