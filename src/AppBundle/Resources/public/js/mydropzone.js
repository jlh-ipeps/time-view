Dropzone.autoDiscover = false;

$(document).ready(function() {
  var myDropzone = new Dropzone("#dropzone_form", {
    paramName: "appbundle_image[file]", // The name that will be used to transfer the file
    maxFilesize: 5, // MB
    autoProcessQueue: true,
    forceFallback: false,
    accept: function(file, done) {
        console.log(file.name);
        done();
    },
    error: function(file){
        console.log('error');
    },
    init: function() {
        console.log('init');
    }
  });
});
