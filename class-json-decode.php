<?php
/*
CLASS JsonDecode
Description: Class for decoding a JSON feed and converting it to a PHP object.
Version: 0.1
Author: Heath Loder
*/

class JsonDecode {
   private $jsonData = "";
   private $feedData = "";

   // Constructor
   public function __construct($feedData) {
      $this->feedData = $feedData;
      $this->decodedData = "";
	}

   // Convert JSON data into array
	public function decodeFeedData() {
      // Empty feed...
	   if(empty($this->feedData)) {
         echo "<p>Empty feed.</p>";
         return FALSE;
      } else {
	      $this->jsonData =  json_decode($this->feedData);
         // Check for encoding issues and give it one more shot
         if (is_null($this->jsonData)) {
            // utf8_encode JSON data, then try json_decode again
    	      $this->jsonData =  json_decode(utf8_encode($this->feedData));
            if (is_null($this->jsonData)) {
               echo "<p>Decoding failed.</p>";
               return FALSE;   // Check data structure and encoding
            }
            return TRUE;   // Got JSON data after utf8_encode
         }
         return TRUE;   // Got JSON data on first try
	   }
   }
   
   // ----------------------------------------------------------------
   // Accesor functions
   // ----------------------------------------------------------------
   public function getDecodedData() {
      return $this->decodedData;
   }
}
?>
