<html>
<?php


class Filter {
 
	private $input;
 	
	function __construct( $input ) {
		$this->input = $input;

	}
 
	function filter() {
		$return_str = str_ireplace( 'img', '', $this->input );
		
		
		return $return_str;
		
	}
	 
}


?>