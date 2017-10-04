<?php
/*
CLASS DecodedJson2Html
Description: Class that returns an HTML table from a decoded JSON feed (expected from api.uri.edu).
Version: 0.1
Author: Heath Loder
*/

class DecodedJson2Html {
	private $decodedData;
   private $result;

	public function __construct($data) {
      $this->decodedData = $data;
      $this->result = "";
	}

   public function getResult() {
      return $this->result;
   }

   private function parseData($data) {
      $output = "";

      // Check for failed decode
      if(!empty($data)) {
         // Build HTML table for shortcode output
   	   $output .= '<table><th>Course</th><th>Title</th><th>Description</th>';
	      foreach($data as $course) {
		      $output .= '<tr>';
            $output .= '<td>' . $course->Subject . ' ' . $course->Catalog . '</td>';
            $output .= '<td>' . $course->Long_Title . '</td>';
			   $output .= '<td>' . $course->Descr . '</td>';
		      $output .= '</tr>';
	      }
	      $output .= '</table>';
      }
      return $output;
   }

   // Get JSON data, build html
	public function data2Html() {
      $structure = $this->decodedData;
            
      # Check that the data is an object or array
      if (!is_object($structure) && !is_array($structure)) {
         echo "Problems with data structure: not an array or object.<br/><br/>";
         //var_dump($structure);   // For debugging
         return FALSE;
      } else {
         # Parse the data
         $out = $this->parseData($structure);
         $this->result = trim($out);
         return TRUE;
      }
	}
}
?>
