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
      $result = "";

      // Check for failed decode
      if(!empty($data)) {
         // Build HTML table for shortcode output
   	   $result .= '<table><th>Course</th><th>Title></th><th>Description</th>';
	      foreach($data as $course) {
		      $result .= '<tr>';
            $result .= '<td>' . $course->dept . ' ' . $course->num . '</td>';
            $result .= '<td>' . $course->title . '</td>';
			   $result .= '<td>' . $course->descr . '</td>';
		      $result .= '</tr>';
	      }
	      $result .= '</table>';
      }
      return $result;
   }

   // Get JSON data, build html
	public function data2Html() {
      $structure = $this->decodedData;
            
      # Check that the data is an object or array
      if (!is_object($structure) && !is_array($structure)) {
        echo "Problems with data structure: not an array or object.";
        return FALSE;
      } else {
         # Parse the data
         $result = $this->parseData($structure);
         $this->result = trim($result);
         return TRUE;
      }
	}
}
?>
