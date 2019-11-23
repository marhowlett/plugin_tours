(function($) {
	
	'use strict';

	$( document ).ready(function() {
            
                $( "div#sortable" ).sortable({
                        cursor: 'move',
                        start: function (e, ui) {
                            $(this).find('.cn-wp-editor').each(function () {
                                tinyMCE.execCommand('mceRemoveEditor', false, $(this).attr('id'));
                            });
                        },
                        stop: function (e, ui) {
                            $(this).find('.cn-wp-editor').each(function () {
                                tinyMCE.execCommand('mceAddEditor', true, $(this).attr('id'));
                            });
                        },
                });
                
                var number_of_clone = 1;
		toggle_remove_buttons();
              
                function add_cloned_fields_tour( $input ) {                        
			var $clone_last = $input.find( '.rwmb-clone:last' ),$clone = $clone_last.clone(),$input, name;
                        var tourplan =  $input.find( '.boldthemes_tourplan_wrapper' );
                        
                        if ( tourplan.length > 0 ){
                            $clone.find( '.wp-picker-container' ).remove();
                            $clone.find( '.boldthemes_value' ).remove();
                            $clone.find( 'img' ).remove();
                            $clone.find( 'span' ).remove();
                            $clone.find( 'textarea' ).remove();
                            $clone.find( '.boldthemes_key' ).remove();
                            $clone.find( '.mce-tinymce' ).remove();
                            $clone.find( '.boldthemes_tourplan_wrapper' ).remove();
                            
                            number_of_clone = $( '.boldthemes_tourplan_wrapper' ).data('number'); 
                            number_of_clone++;
                            $( '.boldthemes_tourplan_wrapper' ).data('number', number_of_clone );
                        
                            var html_clone = '';
                            html_clone   += '<span>Title:</span><input type="text" style="width:100%;" class="boldthemes_key" name="tour_plan_title[' + number_of_clone + ']" id="tour_plan_title_' + number_of_clone + '" value="" placeholder="Title">';			
                            html_clone   += '<span>Headline:</span><input type="text" style="width:100%;" class="boldthemes_key" name="tour_plan_headline[' + number_of_clone + ']" id="tour_plan_headline_' + number_of_clone + '" value="" placeholder="Headline">';
                            html_clone   += '<span>Description:</span><textarea rows="8" cols="40" name="tour_plan_description[' + number_of_clone + ']"  id="tour_plan_description_' + number_of_clone + '" class="boldthemes_key cn-wp-editor"></textarea>';
                            
                            $clone.append(html_clone);
                            $clone.insertAfter( $clone_last );

                            $input = $clone.find( ':input[class|="rwmb"]' );
                            var $input1 = $clone.find( ':input' ).val( '' );                        
                            if ( $input.attr( 'name' ) ) { 
                                name = $input.attr( 'name' ).replace( /\[(\d+)\]/, function( match, p1 ) {
                                        return '[' + ( parseInt( p1 ) + 1 ) + ']';
                                });
                                $input.attr( 'name', name );
                            }  
                            toggle_remove_buttons( $input );
                            $input.trigger( 'clone' );

                            var editor_id = 'tour_plan_description_' + number_of_clone;
                            
                            wp.editor.initialize(  editor_id, {
                                    tinymce: true
                            });

                            $( '#number_of_clones' ).val(number_of_clone);
                        }
		}
		
		$( '.add-clone-tour' ).on( 'click', function( e ) {
			e.preventDefault();
			e.stopPropagation();
			var $input = $( this ).closest( '.rwmb-input' ),
				$clone_group = $( this ).closest( '.rwmb-field' ).attr( 'clone-group' );

                        
                        add_cloned_fields_tour( $input );
			toggle_remove_buttons( $input );
			return false;		
		});	

		$( '.rwmb-input' ).on( 'click', '.remove-clone', function() {
			var $this = $( this ),
				$input = $this.closest( '.rwmb-input' ),
				$clone_group = $( this ).closest( '.rwmb-field' ).attr( 'clone-group' );

			if ( $input.find( '.rwmb-clone' ).length <= 1 ) {
				return false;
			}

			if ( $clone_group ) {
				var $metabox = $( this ).closest( '.inside' );
				var $clone_group_list = $metabox.find( 'div[clone-group="' + $clone_group + '"]' );
				var $index = $this.parent().index();

				$.each( $clone_group_list.find( '.rwmb-input' ),
					function( key, value ) {
						$( value ).children( '.rwmb-clone' ).eq( $index ).remove();
						toggle_remove_buttons( $( value ) );
					}
				);
			} else {	
                                $this.parent().remove();
				toggle_remove_buttons( $input );
			}

			return false;
		});

		function toggle_remove_buttons( $el ) {
			var $button;
			if ( ! $el )
				$el = $( '.rwmb-field' );
			$el.each(function() {
				$button = $( this ).find( '.remove-clone' );
				$button.length < 2 ? $button.hide() : $button.show();
			});
		}
	});
	
})(jQuery);