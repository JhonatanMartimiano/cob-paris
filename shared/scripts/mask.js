(function ($) {
  'use strict';
  // MAKS

  $(".mask-date").mask('00/00/0000');
  $(".mask-datetime").mask('00/00/0000 00:00');
  $(".mask-month").mask('00/0000', {reverse: true});
  $(".mask-doc").mask('000.000.000-00', {reverse: true});
  $(".mask-cnpj").mask('99.999.999/9999-99', {reverse: true});
  $(".mask-card").mask('0000  0000  0000  0000', {reverse: true});
  $(".mask-money").mask('000.000.000.000.000,00', {reverse: true, placeholder: "0,00"});
  $(".mask-ticket").mask('000000000000000000000000000000000000000000000000');

  var maskPhone = function (val) {
          return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
      },
      options = {onKeyPress: function(val, e, field, options) {
              field.mask(maskPhone.apply({}, arguments), options);
          }
      };

  $('.mask-phone').mask(maskPhone, options);
})(jQuery);