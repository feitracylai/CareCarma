var map;
jQuery(document).ready(function(){

    map = new GMaps({
        div: '#map',
        lat: 38.253719,
        lng: -85.750060,
    });
    map.addMarker({
        lat: 38.253719,
        lng: -85.750060,
        title: 'Address',      
        infoWindow: {
            content: '<h5 class="title">College Green</h5><p><span class="region">Address line goes here</span><br><span class="postal-code">Postcode</span><br><span class="country-name">Country</span></p>'
        }
        
    });

});