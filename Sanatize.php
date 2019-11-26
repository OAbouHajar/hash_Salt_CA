<html>
<?php


class Sanatize {
 
	private $input;
 	
	function __construct( $input ) {
		$this->input = $input;

	}
 
	function sanatize() {
		$return_str = str_replace( array('<','>',"'",'"',')','('), array('&lt;','&gt;','&apos;','&#x22;','&#x29;','&#x28;'), $this->input );
		
		return $return_str;
		
	}
	 
}


?>