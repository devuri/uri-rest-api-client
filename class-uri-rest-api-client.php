<?php
/*
CLASS UriRestApiClient
Description: Class for WordPress plugin "URI REST API Client"
Version: 0.1
Author: Heath Loder
*/

class UriRestApiClient {
   private $feedUrl;   // URL of JSON feed
   private $clientId;   // URI Client ID

   private $feedData;   // String with JSON feed data
   private $decodedData;   // String with data after json_decode
   private $finalResult;   // String with HTML formatted data

   // ---------------------------------------------------------------
   // Define shortcode syntax
   // ---------------------------------------------------------------
   public function __construct(){
      // [uraclient]
	   add_shortcode('uraclient', array(&$this , 'uraclientShortcode'));
   }
   
   // ---------------------------------------------------------------
   // Shortcode function - run upon shortcode invocation
   // ---------------------------------------------------------------
   public function uraclientShortcode($atts, $content, $tag){
      // Retreive settings from WordPress registered options for this plugin
      $this->feedUrl = $this->removeInvalidQuotes(get_option('uraclient_json_url'));
      $this->clientId = get_option('uraclient_client_id');

      // ---------------------------------------------------------------
      // Initiate HTTP connection and get JSON feed
      // ---------------------------------------------------------------
      // Include wp_safe_remote_get file
      require_once plugin_dir_path( __FILE__ ) . '/class-json-get.php';
      // Call function to create connection
      //echo 'URL: ' . $this->feedUrl . '<br/>Client ID: ' . $this->clientId . '<br/>';   // For debugging
      $jsonGetObj = new JsonGet($this->feedUrl, $this->clientId);
      // Retreive JSON feed from URL
      if ($jsonGetObj->getFeed()) {
         // Store JSON feed data locally
         $this->feedData = $jsonGetObj->getFeedData();
         //var_dump($this->feedData);   // For debugging
      } else {
         return;   // Error will be thrown by getFeed() if there is one
      }
         
      // ---------------------------------------------------------------
      // Convert JSON feed to object (or optionally, array)
      // ---------------------------------------------------------------
      // Include json_decode file
      require_once plugin_dir_path( __FILE__ ) . '/class-json-decode.php';
		// Instantiate JSON feed conversion object
      $jsonDecodeObj = new JsonDecode($this->feedData);
      // Convert JSON feed to PHP Object
      if ($jsonDecodeObj->decodeFeedData()) {
         $this->decodedData = $jsonDecodeObj->getDecodedData();
      } else {
         return;   // Error will be thrown by decodeFeedData() if there is one
      }
      //var_dump($this->decodedData);   // For debugging

      // ---------------------------------------------------------------
      // Parse and format data object for display
      // ---------------------------------------------------------------
      // Include JSON parsing file
      require_once plugin_dir_path( __FILE__ ) . '/class-decoded-json-2-html.php';
      // Instantiate JSON parsing object and convert array data to HTML
      $decodedJson2HtmlObj = new DecodedJson2Html($this->decodedData);
      // Return raw HTML for WordPress Page/Post import
      if ($decodedJson2HtmlObj->data2Html()) {
         $this->finalResult = $decodedJson2HtmlObj->getResult();
         // Output data as an HTML Table
         return $this->finalResult;
      } else {
         return;   // Error will be thrown by data2Html() if there is one
      }  
	}
   
   // ---------------------------------------------------------------
   // URL sanitization function
   // ---------------------------------------------------------------
   private function removeInvalidQuotes($txtin) {
      $invalid1 = urldecode("%E2%80%9D");
      $invalid2 = urldecode("%E2%80%B3");
      $txtin = preg_replace("/^[".$invalid1."|".$invalid2."]*/i", "", $txtin);
      $txtin = preg_replace("/[".$invalid1."|".$invalid2."]*$/i", "", $txtin);
      return $txtin;
   }
}
?>
