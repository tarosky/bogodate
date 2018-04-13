( function( $ ) {

	'use strict';

	$( function() {
        $( '#select-locale' ).change( function() {
            location = 'admin.php?page=bogodate&locale=' + $( this ).val();
        } );
    } );

} )( jQuery );
