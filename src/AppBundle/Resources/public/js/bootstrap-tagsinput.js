
$('#tagsinput').tagsinput({
    trimValue: true,
});

$('#tagsinput').on('itemAddedOnInit', function(event) {
    console.log('init');
});

$('#tagsinput').on('beforeItemAdd', function(event) {
    var tag = event.item;
    if (!event.options || !event.options.preventPost) {
        $.ajax({
            dataType : 'json',
            method: 'POST',
            data : {
                action : 'beforeItemAdd',
                tag : tag,
            },
            success: function(success, action, tag) {
                console.log(success);
            },
            error : function(err){
                console.error(err);
                console.log(err);
                $('#tags-input').tagsinput('remove', tag, {preventPost: true});
            }
        });
    }
});

$('#tagsinput').on('itemRemoved', function(event) {
    var tag = event.item;
    if (!event.options || !event.options.preventPost) {
        $.ajax({
            dataType : 'json',
            method: 'POST',
            data : {
                action : 'itemRemoved',
                tag : event.item,
            },
            success: function(success, action, tag) {
                console.log('success: ' + success);
                console.log(action, tag);
            },
            error : function(err){
                console.error(err);
            }
        });
    }
});

if (typeof alreadyTags !== 'undefined') {
    for (i=0; i < alreadyTags.length; i++) {
        $('#tagsinput').tagsinput('add', alreadyTags[i].tag_name, {preventPost: true});
    }
}

/*

$('#tagsinput').on('itemAddedOnInit', function(event) {
    console.log(event.item);
    console.log('beforeItemAdd : event.item: contains the item');
});

$('#tagsinput').on('beforeItemAdd', function(event) {
    console.log(event.item);
    console.log('beforeItemAdd : event.item: contains the item');
    console.log(event.cancel);
    console.log('beforeItemAdd : event.cancel: set to true to prevent the item getting added');
});

$('#tagsinput').on('itemAdded', function(event) {
    console.log(event.item);
    console.log('itemAdded : event.item: contains the item');
});

$('#tagsinput').on('beforeItemRemove', function(event) {
    console.log(event.item);
    console.log('beforeItemRemove : event.item: contains the item');
    console.log(event.cancel);
    console.log('beforeItemRemove : event.cancel: set to true to prevent the item getting removed');
});

$('#tagsinput').on('itemRemoved', function(event) {
    console.log(event.item);
    console.log('itemRemoved : event.item: contains the item');
});

*/