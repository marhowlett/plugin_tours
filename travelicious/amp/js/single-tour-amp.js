(function( $ ) {
    'use strict';      
    
    $( document ).ready(function() {
        
        $('.btDiscoverDestinationLink').on('click', function(event) {
            event.preventDefault();
            var link = $(event.target).data('link');
            if ( link == '' ) {               
            }else{
                location.href = link;
            }
        }); 
        
        //amp tour other start days
        $('.btTourSingleIncludeLinkViewOtherTime a').on('click', function(event) {
            event.preventDefault();
            $('#btTourSingleIncludeContentOtherTimes').toggle('fast');
        }); 
        
    });

})( jQuery );
