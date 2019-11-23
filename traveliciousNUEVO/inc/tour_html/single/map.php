<?php
/*
embed -> file -> map*/
?>
<div class="btTourLocationMap">
    <div class="type_listing add_item_medium">
        <?php if ( BoldThemesFrameworkTemplate::$tour_rwmb_map_embed ) { ?>
        <div class="maparea" id="tour_map_embed_routes">
            <iframe src="<?php echo esc_attr(BoldThemesFrameworkTemplate::$tour_rwmb_map_embed);?>"></iframe>
        </div> 
        <?php } ?>
        <?php if ( !BoldThemesFrameworkTemplate::$tour_rwmb_map_embed ) { ?>
        <div class="maparea" id="tour_map_locations"></div>        
        <script> 
            var custom_style = '';
            var markerClusterer = null;
            var markers = [];
            var map;

            function bt_tour_location_init_map() {

                var hlat = parseFloat(<?php echo BoldThemesFrameworkTemplate::$tour_gmap_lat;?>);
                var hlng = parseFloat(<?php echo BoldThemesFrameworkTemplate::$tour_gmap_lng?>);

                var myLatLng = new google.maps.LatLng(hlat,hlng);
                var mapOptions = {
                  center: myLatLng,
                  zoom: parseFloat(<?php echo BoldThemesFrameworkTemplate::$tour_gmap_zoom;?>),
                  mapTypeId: google.maps.MapTypeId.ROADMAP
                };
                

                map = new google.maps.Map(document.getElementById('tour_map_locations'), mapOptions); 

               
                var marker, i;

                <?php if ( BoldThemesFrameworkTemplate::$custom_map_style != '') { ?>
                        var styledMapType = new google.maps.StyledMapType(                                                        
                            <?php echo BoldThemesFrameworkTemplate::$custom_map_style;?>,                                                       
                            {name: 'Styled Map'}
                        );
                        map.mapTypes.set('custom_style', styledMapType);
                        map.setMapTypeId('custom_style');
                <?php } ?>

                var icons = {
                        normal: {
                          icon: '<?php echo esc_html(BoldThemesFrameworkTemplate::$tour_pin_normal);?>'
                    },
                        selected: {
                          icon: '<?php echo esc_html(BoldThemesFrameworkTemplate::$tour_pin_selected);?>'
                    }
                };
                
                 var infowindow = new google.maps.InfoWindow();

                <?php foreach ( BoldThemesFrameworkTemplate::$tour_rwmb_destination as $destination){ ?>
                    <?php if ( $destination->map_lat != '' && $destination->map_lng != '' ) { ?>
                        var myLatLng = new google.maps.LatLng(parseFloat(<?php echo $destination->map_lat;?>),parseFloat(<?php echo $destination->map_lng;?>));
                        var marker = new google.maps.Marker({
                            id: <?php echo $destination->term_id;?>,
                            position: myLatLng,
                            title:  '<?php echo $destination->name;?>', 
                            icon:  '<?php echo BoldThemesFrameworkTemplate::$tour_pin_normal;?>',
                            map: map
                        });

                        markers.push(marker);                                        

                        google.maps.event.addListener(marker, 'click', (function(marker, i) {
                            return function() {
                                var header_height = jQuery( ".mainHeader" ).height(); 
                                var href    = jQuery( '#tour_destination_<?php echo $destination->term_id;?>' );            
                                jQuery('html, body').stop().animate({
                                    scrollTop: jQuery(href).offset().top - header_height - 50
                                }, 500);
                             }
                         })(marker, i));
                    <?php } ?>

                <?php } ?>

                // bounds
                var bounds = new google.maps.LatLngBounds();
               
                <?php foreach ( BoldThemesFrameworkTemplate::$tour_rwmb_destination as $destination){ ?>
                        var myLatLng = new google.maps.LatLng(parseFloat(<?php echo $destination->map_lat;?>),parseFloat(<?php echo $destination->map_lng;?>));
                        bounds.extend(myLatLng);
                <?php } ?>
                map.fitBounds(bounds);
                
                <?php if ( count(BoldThemesFrameworkTemplate::$tour_rwmb_destination) == 1 ) { ?>
                    zoomChangeBoundsListener = google.maps.event.addListenerOnce(map, 'bounds_changed', function(event) {
                            if ( this.getZoom() ){   
                                this.setZoom( parseFloat(<?php echo BoldThemesFrameworkTemplate::$tour_gmap_zoom;?>) ); 
                            }
                    });
                    setTimeout(function(){google.maps.event.removeListener(zoomChangeBoundsListener)}, 2000);
                 <?php } ?>

                var fontFamily	= 'Montserrat';
                var textColor	= '#ffffff';
                var url = '<?php echo get_template_directory_uri(); ?>/gfx/';

                var clusterStyles = [
                 {
                       url: url + 'm1.png',
                               fontFamily: fontFamily,
                               height: 50, 
                               width: 50, 
                               anchor: [-18, 0], 
                               textColor: textColor, 
                               textSize: 10,
                               iconAnchor: [15, 48]
                 },
                 {
                       url: url + 'm2.png',
                               fontFamily: fontFamily,
                               height: 56, 
                               width: 56, 
                               anchor: [-18, 0], 
                               textColor: textColor, 
                               textSize: 11, 
                               iconAnchor: [15, 48]
                 },
                 {
                       url: url + 'm3.png',
                               fontFamily: fontFamily,
                               height: 66, 
                               width: 66, 
                               anchor: [-18, 0], 
                               textColor: textColor, 
                               textSize: 11, 
                               iconAnchor: [15, 48]
                 },
                 {
                       url: url + 'm4.png',
                               fontFamily: fontFamily,
                               height: 78, 
                               width: 78, 
                               anchor: [-18, 0], 
                               textColor: textColor, 
                               textSize: 12, 
                               iconAnchor: [15, 48]
                 },
                 {
                       url: url + 'm5.png',
                               fontFamily: fontFamily,
                               height: 90, 
                               width: 90, 
                               anchor: [-18, 0], 
                               textColor: textColor, 
                               textSize: 12, 
                               iconAnchor: [15, 48]
                 }
               ];	
               var mcOptions = {gridSize: 50, maxZoom: 12, styles: clusterStyles, zoomOnClick: true, averageCenter: true};

               if ( typeof MarkerClusterer !== 'undefined' ){
                    var markerClusterer = new MarkerClusterer(map, markers, mcOptions);
               }else{
                    document.addEventListener('readystatechange', function() {
                        if ( typeof MarkerClusterer !== 'undefined' && (document.readyState === 'interactive' || document.readyState === 'complete') ) {
                                var markerClusterer = new MarkerClusterer(map, markers, mcOptions);
                        }
                    }, false);
               }
                
               <?php if ( BoldThemesFrameworkTemplate::$tour_rwmb_map_file ) { ?>
                    var kmlFile = '<?php echo BoldThemesFrameworkTemplate::$tour_rwmb_map_file;?>';
                    kmlFile = encodeURI(kmlFile);
                    var file_extension = kmlFile.substr((kmlFile.lastIndexOf('.')+1));
                    if(file_extension.toUpperCase() == 'KMZ' || file_extension.toUpperCase() == 'KML')
                    {
                            var kmlOptions = {
                                clickable: 0, // polygon ignores mouse clicks
                            };
                            var kmlLayer = new google.maps.KmlLayer(kmlFile, kmlOptions);
                            kmlLayer.setMap(map);
                    }
               <?php } ?>
            }
        </script>
        <?php } ?>
    </div>
</div> 

