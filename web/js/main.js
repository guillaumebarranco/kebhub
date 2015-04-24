$( document ).ready(function() {
    
    $( "#close" ).click(function() {
	  $( "#banner" ).addClass( "closed" );
	});

	$( "body" ).scroll(function() {
	  $( "#banner" ).addClass( "closed" );
	});

	$('.scrollTo').click( function() { 
		var page = $(this).attr('href'); 
		var speed = 750; 
		$('html, body').animate( { scrollTop: $(page).offset().top }, speed ); // Go
		return false;
	});

});