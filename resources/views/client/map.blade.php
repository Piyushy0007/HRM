<!DOCTYPE html>
<html>
  <head>
    <title>Places Search Box</title>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  </head>
  <body>
    <input
      id="pac-input"
      class="controls"
      type="text"
      placeholder="Search Box"
    />
    <div id="map"></div>

    <!-- Async script executes immediately and must be after any DOM elements used in callback. -->
  <script src="https://cdn.rawgit.com/bjornharrtell/jsts/gh-pages/1.4.0/jsts.min.js"></script>
<script src="https://maps.google.com/maps/api/js?key=AIzaSyDfX422PdA9Yy6GOf4HeRBRTtfoz-AGQdU&libraries=places,drawing&sensor=false" async> </script>
<script src="https://cdn.rawgit.com/bjornharrtell/jsts/gh-pages/1.4.0/jsts.min.js"></script>
  </body>
</html>

 <script>

var mapOptions = {
       zoom: 16,
       center: new google.maps.LatLng(62.1482, 6.0696)
     };

     var drawingManager = new google.maps.drawing.DrawingManager({
       drawingControl: false,
       polygonOptions: {
         editable: true
       }
     });

  var divmap = document.getElementById("map");
      // var divmap =   this.$refs.createprop;
      var map = new google.maps.Map( divmap , mapOptions);


      vm.input = document.getElementById("pac-input");
      console.log(vm.input)
      vm.searchBox = new google.maps.places.SearchBox(vm.input);
        // Create the search box and link it to the UI element.

  map.controls[google.maps.ControlPosition.TOP_LEFT].push(vm.input);
  // Bias the SearchBox results towards current map's viewport.
  map.addListener("bounds_changed", () => {
    vm.searchBox.setBounds(map.getBounds());
    console.log(vm.searchBox)
  });
  let markers = [];
  // Listen for the event fired when the user selects a prediction and retrieve
  // more details for that place.
  vm.searchBox.addListener("places_changed", () => {
    const places = vm.searchBox.getPlaces();

    if (places.length == 0) {
      return;
    }
    // Clear out the old markers.
    markers.forEach((marker) => {
      marker.setMap(null);
    });
    markers = [];
    // For each place, get the icon, name and location.
    const bounds = new google.maps.LatLngBounds();
    places.forEach((place) => {
      if (!place.geometry || !place.geometry.location) {
        console.log("Returned place contains no geometry");
        return;
      }
      const icon = {
        url: place.icon,
        size: new google.maps.Size(71, 71),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(17, 34),
        scaledSize: new google.maps.Size(25, 25),
      };
      // Create a marker for each place.
      markers.push(
        new google.maps.Marker({
          map,
          icon,
          title: place.name,
          position: place.geometry.location,
        })
      );

      if (place.geometry.viewport) {
        // Only geocodes have viewport.
        bounds.union(place.geometry.viewport);
           console.log( place.geometry.viewport, 'place.geometry.viewport' )
           vm.modal.geoloc.long = place.geometry.viewport.La.i;
           vm.modal.geoloc.lat = place.geometry.viewport.Ra.i;
      } else {
        bounds.extend(place.geometry.location);
      }
      if(place.geometry.location){
        console.log( 'place.geometry.location' ,  place.geometry.location )
          console.log( vm.modal.geoloc.lat ,  vm.modal.geoloc.long )
      }
    });
    map.fitBounds(bounds);
  });
  
</script>
    
  </body>
</html>


