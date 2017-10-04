<?php
/*
CLASS JsonGet
Description: Class for retrieving a JSON feed.
Version: 0.1
Author: Heath Loder
*/

// -------------------------------------------------------------
// Class for retrieving a json feed using WordPress functions
// -------------------------------------------------------------
class JsonGet {
   private $feedUrl;
   private $clientId;
   private $feedData;

   // Constructor
   public function __construct($feed_url, $client_id) {
      if ( empty($feed_url) || empty($client_id) )
         echo "No Client ID supplied.";
      else {
         $this->feedUrl = $feed_url;
         $this->clientId = $client_id ;
      }
   }

   // Accessor for feed data
   public function getFeedData() {
      return $this->feedData;
   }

	public function getFeed(){
      // -------------------------------------------------------------
      // Prepare wp_safe_remote_get (CURL) for transfer
      // -------------------------------------------------------------      
      // WordPress unicodes URL's and corrupts the parameters.
      // To fix this, we must un_unicode ampersands:
      $this->feedUrl = preg_replace("/&#038;/", "&", $this->feedUrl);
      $this->feedUrl = str_replace('&amp;', '&', $this->feedUrl);
      //$useragent = "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:35.0) Gecko/20100101 Firefox/35.0"; # in case of simulating a browser
      // So api.uri.edu can easily figure out who we are
      $useragent = 'URI REST API WordPress Plugin; ' . get_bloginfo('url');
      if (!empty($this->clientId)) {
         // Set ClientID in header here
         $args = [
            'user-agent'  => $useragent,
            'headers'     => [ "id" => $this->clientId ]
         ];
      }
      //echo "Headers sent: " . $args["headers"]["id"];   // For debugging
      // -------------------------------------------------------------
      // Get the JSON feed
      // -------------------------------------------------------------
      $response = wp_safe_remote_get($this->feedUrl, $args);
      if (is_wp_error($response)) {
         $error_message = $response->get_error_message();
         echo "There was an error with the URI REST API Client Plugin: $error_message";
         return FALSE;
      } else if (isset($response['body']) && !empty($response['body'])) {
         // Check for non-200 code responses here
         if ( wp_remote_retrieve_response_code($response) != '200' ) {
            //var_dump($this->feedData);   // For debugging
            echo "$response";
            return FALSE;
         }
         //$this->feedData = $response['body'];   // Deprecated
         $this->feedData = wp_remote_retrieve_body($response);
         return TRUE;
      } else {
         echo "Empty response from server?";
         return FALSE;
      }
   }
}
?>
