
var placeSearch, autocomplete;

function initAutocomplete() {
    bt_initialize();
  autocomplete = new google.maps.places.Autocomplete(
      /** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
      {types: ['geocode']});

  // When the user selects an address from the dropdown, populate the address
  // fields in the form.
  autocomplete.addListener('place_changed', fillInAddress);
}

function fillInAddress() {
  var place = autocomplete.getPlace();

  for (var component in componentForm) {
    document.getElementById(component).value = '';
    document.getElementById(component).disabled = false;
  }
}
      
function geolocate() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      var geolocation = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
      };
      var circle = new google.maps.Circle({
        center: geolocation,
        radius: position.coords.accuracy
      });
      autocomplete.setBounds(circle.getBounds());
    });
  }
}





function bt_initialize() {
    var hlat = parseFloat(helper.lat);
    var hlng = parseFloat(helper.lng);

    var myLatLng = new google.maps.LatLng(hlat,hlng);
    var mapOptions = {
      center: myLatLng,
      zoom: parseFloat(helper.zoom)
    };
    var map = new google.maps.Map(document.getElementById('map-canvas'),
        mapOptions);
    var marker = new google.maps.Marker({position: myLatLng, map: map, draggable: true});
    marker.setMap(map);
    
    google.maps.event.addListener(marker, 'dragend', function(event) {
        placeMarker(event.latLng, map);
    });
}

function placeMarker(location,map) {
    map.setCenter(location);	
    document.getElementById("latitude").value = location.lat();
    document.getElementById("longitude").value = location.lng();
    
    document.getElementById("tour_destination_meta_lat").value = location.lat();
    document.getElementById("tour_destination_meta_lng").value = location.lng();
}

google.maps.event.addDomListener(window, 'load', bt_initialize);


