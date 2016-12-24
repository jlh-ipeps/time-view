/* 
 * Come from layout.html.twig
 * do not work here like this
 */

$.fn.editable.defaults.mode = 'inline';
$.fn.editable.defaults.anim = 'true';
$(document).ready(function() {
  $('.editable').editable({
    type: 'text',
    pk: 1,
    ///url: 'post',
    url: $(this).data('url'),
    params: function(params) {  //params already contain `name`, `value` and `pk`
      var data = {};
      data['id'] = params.pk;
      data[params.name] = params.value;
      data['book'] = {{ book }};
      data.field = $(this).data('field');
      return data;
    },
    success:function(msg){
      console.log(msg);
      $('[data-book={{book}}]').html(msg.data.value);
    }
  });
});


