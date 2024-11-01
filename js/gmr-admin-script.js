jQuery(document).ready(function($){

	/*
	Show and hide different options based on selection
  */

	jQuery(document).on( 'click', '.gmr-select-source input', function() {

  	 	var input_val = $(this).val();

  	 	if ( input_val == 'page' ) {
  	 		$( '#show_page' ).show();
  	 		$( '#show_url' ).hide();
  	 	}

      else if ( input_val == 'url' ) {
        $( '#show_page' ).hide();
        $( '#show_url' ).show();
  	 	}

  });


  jQuery(document).on( 'click', '.gmr-set-device input', function() {

      var input_val = $(this).val();

      if ( input_val == 'custom' ) {
        $( '#show-custom' ).show();
      }

      else if ( input_val == 'tablet' ) {
        $( '#show-custom' ).hide();
      }

      else if ( input_val == 'mobile' ) {
        $( '#show-custom' ).hide();
      }
  });

/*
Show selected input fields
*/

  if ($('input[id=page-input]:checked').length > 0) {

      $( '#show_page' ).show();
      $( '#show_url' ).hide();
  }

  if ($('input[id=url-input]:checked').length > 0) {

      $( '#show_page' ).hide();
      $( '#show_url' ).show();
  }

    if ($('input[id=custom-input]:checked').length > 0) {

      $( '#show-custom' ).show();

  }

});