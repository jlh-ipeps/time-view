/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



$(document).ready(function() {
  $(window).on('hashchange',ajaxUrlLoad);
  $(document).on('ajaxContentLoaded',refreshUIElements)
//  $('#subtitle').editable({
//  });
});

function refreshUIElements(e){
  if ($('#map').length){
    initialize();
  }
  activeNavLinks();
  
}

function activeNavLinks(){
  console.log(window.location.hash);
  $('.sidebar-nav li a').closest('li').addClass('inactive').removeClass('active');
  $('.sidebar-nav li a[href="'+window.location.hash+'"]').closest('li').addClass('active').removeClass('inactive');
}


function ajaxUrlLoad(e){
  console.log('hashChange')
  console.log(location.hash.replace('#',''))
  $.ajax({
    url: location.hash.replace('#',''),
    type: 'get',
    success: function(response){
      $('#content').html(response);
      $(document).trigger('ajaxContentLoaded');
    }
  });
}


