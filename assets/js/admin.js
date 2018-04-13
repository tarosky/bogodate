( function( $ ) {

	'use strict';

	$( function() {

		$( "input.dateformat, input.timeformat" ).change( function() {
			var format = $( this ),
				td = format.closest( 'td' ),
				example = td.find( '.example' ),
				spinner = td.find( '.spinner' );

			spinner.addClass( 'is-active' );

			$.post( ajaxurl, {
					action: 'dateformat' == format.attr( 'class' ) ? 'date_format' : 'time_format',
					date : format.val() ? format.val() : format.data( 'default' )
				}, function( d ) { spinner.removeClass( 'is-active' ); example.text( d ); } );
		});

    } );

} )( jQuery );
