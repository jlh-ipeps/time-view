
$(document).ready(function() {
  
  $('#geolocation').click(function(event) {
    if (navigator.geolocation) {
      if(typeof(Storage) !== "undefined") {
        if (localStorage.loc) {
//          console.log(localStorage.loc);
//          console.log(localStorage.lat);
//          console.log(localStorage.lng);
          window.location = this.href;
        } else {
          event.preventDefault();
          
//            var ipgeolocation = navigator.geolocation.getCurrentPosition();
//            var user_defined_location = prompt("Please enter your location", ipgeolocation);
//            localStorage.loc = user_defined_location;

          navigator.geolocation.getCurrentPosition(success, error, options);
        }
      } else {
        alert("Geolocation is not supported by this browser.");
      }
    } else {
      alert("Geolocation is not supported by this browser.");
    }
  });
  

});

  
var options = {
  enableHighAccuracy: true,
  timeout: 5000,
  maximumAge: 0
};

function success(pos) {
  var crd = pos.coords;
  localStorage.loc = crd;
  
  localStorage.lat = crd.latitude;
  localStorage.lng = crd.longitude;

  console.log('Your current position is:');
  console.log('Latitude : ' + crd.latitude);
  console.log('Longitude: ' + crd.longitude);
  console.log('More or less ' + crd.accuracy + ' meters.');
  
  $('#geolocation').trigger("click")

};

function error(err) {
  // https://bugzilla.mozilla.org/show_bug.cgi?id=1283563
  console.warn('ERROR(' + err.code + '): ' + err.message);

};
