 (function () {
   'use strict';

   var WIDGET_SELECTOR = '.carekit-widget';
   var ajaxUrl = window.carekitAjaxUrl || '';

   function refreshWidget() {
     if (!ajaxUrl) return;

     fetch(ajaxUrl)
       .then(function (r) { return r.json(); })
       .then(function (data) {
         if (!data.html) return;

         // Odśwież wszystkie instancje widgetu (modal + strona produktu)
         document.querySelectorAll(WIDGET_SELECTOR).forEach(function (el) {
           var tmp = document.createElement('div');
           tmp.innerHTML = data.html;
           var fresh = tmp.querySelector(WIDGET_SELECTOR);
           if (fresh) {
             el.outerHTML = fresh.outerHTML;
           }
         });
       })
       .catch(function (e) { console.error('CareKit refresh error', e); });
   }

   // Koszyk (strona cart + modal koszyka)
   prestashop.on('updatedCart', refreshWidget);

   // Opcjonalnie: modal po dodaniu produktu ze strony produktu
   prestashop.on('updateCart', refreshWidget);

 })();

