/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



$(document).ready(function() {
  
  $('form[name="choose_locale"]').change(function(){ 
    $('form[name="choose_locale"]').submit();
//    ajaxSubmit();
  });
   
//  $('#subtitle').editable({
//  });
});

function ajaxSubmit() {
  console.log(location.href);
//  console.log(location.hash.replace('#',''));
//  $.ajax({
//    url: location.hash.replace('^\/..',''),
//    type: 'get',
//    success: function(response){
//      $('#content').html(response);
//      $(document).trigger('ajaxContentLoaded');
//    }



}
