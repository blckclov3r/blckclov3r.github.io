
var $ = jQuery;

function getAddress(ob) {
    
    var address = $(ob).parent().find("input").attr("value");
    
    if ( address !== "" ) {
        
        geocoder = new google.maps.Geocoder();
        
        geocoder.geocode({
            address: address
        }, function(results, status) {
            
            if (status == google.maps.GeocoderStatus.OK) {
                
                var output = $(ob).parent().find(".premium-address-result"),
                    latiude = results[0].geometry.location.lat(),
                    longitude = results[0].geometry.location.lng();
            
                $(output).html("Latitude: " + latiude + "<br>Longitude: " + longitude + "<br>(Copy and Paste your Latitude & Longitude value below)");
                
                $(ob).parents(".elementor-control-premium_map_notice").nextAll(".elementor-control-premium_maps_center_lat").find("input").val(latiude).trigger("input");
                
                $(ob).parents(".elementor-control-premium_map_notice").nextAll(".elementor-control-premium_maps_center_long").find("input").val(longitude).trigger("input");
                
            } else {
                
                alert("Geocode was not successful for the following reason: " + status);
                
            }
        });
    }
}

function getPinAddress(ob) {
    
    var address = $(ob).parent().find("input").attr("value");
    
    if (address !== "") {
        
        geocoder = new google.maps.Geocoder();
        
        geocoder.geocode({
            address: address
        }, function(results, status) {
            
            if (status == google.maps.GeocoderStatus.OK) {
                
                var output = $(ob).parent().find(".premium-address-result"),
                    latiude = results[0].geometry.location.lat(),
                    longitude = results[0].geometry.location.lng();
            
                $(output).html("Latitude: " + latiude + "<br>Longitude: " + longitude + "<br>(Copy and Paste your Latitude & Longitude value below)");
                
                $(ob).parents(".elementor-control-premium_map_pin_notice").nextAll(".elementor-control-map_latitude").find("input").val(latiude).trigger("input");
                
                $(ob).parents(".elementor-control-premium_map_pin_notice").nextAll(".elementor-control-map_longitude").find("input").val(longitude).trigger("input");
                
            } else {
                
                alert("Geocode was not successful for the following reason: " + status);
                
            }
        });
    }
}