(function($) {
  $.fn.respuestaRegistro = function(data) {
    jQuery('#modalRespuesta').modal();
  };
})(jQuery);


// (function ($, Drupal, drupalSettings) {
//   'use strict';
//   Drupal.behaviors.serempre = {
//     attach: function (context) {     
//       if (typeof Drupal.Ajax !== 'undefined') {
//         console.log('test');
//           Drupal.Ajax.prototype.beforeSubmit = function (form_values, element_settings, options) {
//             var validator = jQuery("#signupform").validate({
//       rules: {
//         firstname: "required",        
//       },
//       messages: {
//         firstname: "Enter your firstname",        
//       },
//       // the errorPlacement has to take the table layout into account
//       errorPlacement: function(error, element) {
//         if (element.is(":radio"))
//           error.appendTo(element.parent().next().next());
//         else if (element.is(":checkbox"))
//           error.appendTo(element.next());
//         else
//           error.appendTo(element.parent().next());
//       },
//       // specifying a submitHandler prevents the default submit, good for the demo
//       submitHandler: function() {
//         alert("submitted!");
//       },
//       // set this class to error-labels to indicate valid fields
//       success: function(label) {
//         // set &nbsp; as text for IE
//         alert('pasaste');
//       },
//       highlight: function(element, errorClass) {
//         jQuery(element).parent().next().find("." + errorClass).removeClass("checked");
//       }
//     });          
//             return false;
//           };       
//       }
//     }
//   };
// })(jQuery, Drupal, drupalSettings);