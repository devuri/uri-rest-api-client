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
      $jsonGetObj = new JsonGet($this->feedUrl, $this->clientId);
      // Retreive JSON feed from URL
      if ($jsonGetObj->getFeed()) {
         // Store JSON feed data locally
         $this->feedData = $jsonGetObj->getFeedData();
      } else {
         return;   // Error will be thrown by getFeed() if there is one
      }
         
      // ---------------------------------------------------------------
      // Convert JSON feed to array/object
      // ---------------------------------------------------------------
      // Include json_decode file
      require_once plugin_dir_path( __FILE__ ) . '/class-json-decode.php';
		// Instantiate JSON feed conversion object
      $jsonDecodeObj = new JsonDecode($this->feedData);
      // Convert JSON feed to PHP String array
      if ($jsonDecodeObj->decodeFeedData()) {
         $this->decodedData = $jsonDecodeObj->getDecodedData();
      } else {
         return;   // Error will be thrown by decodeFeedData() if there is one
      }

      // ---------------------------------------------------------------
      // Parse and format array data for display
      // ---------------------------------------------------------------
      // Include JSON parsing file
      require_once plugin_dir_path( __FILE__ ) . '/class-decoded-json-2-html.php';
      // Instantiate JSON parsing object and convert array data to HTML
      $decodedJson2HtmlObj = new DecodedJson2Html($this->decodedData);
      // Return raw HTML for WordPress Page/Post import
      if ($decodedJson2HtmlObj->data2Html()) {
         $this->decodedData = $jsonDecodeObj->getResult();
         // Output data as an HTML Table
         return $this->decodedData;
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
