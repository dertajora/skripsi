// google_dynamic_map.js file

// Map Initialize function
function initialize() 
{
	// Set static latitude, longitude value
	var latlng = new google.maps.LatLng(-7.771401, 110.378787);
	// Set map options
	var myOptions = {
		zoom: 16,
		center: latlng,
		panControl: true,
		zoomControl: true,
		scaleControl: true,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	}
	// Create map object with options
	map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
	// Create and set the marker
	marker = new google.maps.Marker({
		map: map,
		draggable:true,	
		position: latlng
	});
	
	// Register Custom "dragend" Event
	google.maps.event.addListener(marker, 'dragend', function() {
		
		// Get the Current position, where the pointer was dropped
		var point = marker.getPosition();
		// Center the map at given point
		map.panTo(point);
		// Update the textbox
		document.getElementById('txt_latlng').value=point.lat()+", "+point.lng();
	});
}
