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
   private $feedUrl = "";
   private $feedData = "";
   private $clientId;

   // Constructor
   public function __construct($feed_url, $client_id){
      if ( empty($feed_url) || empty($client_id) )
         echo "There was an error with the URI REST API Client Plugin: No Client ID supplied.";
      else
      $this->feedUrl = $feed_url;
      $this->clientID = $client_id ;
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
      $useragent = 'URI REST API WordPress Plugin; ' . get_bloginfo('url');
      if (!empty($this->clientId)) {
         $args = array(
            //'timeout'     => 5,
            //'httpversion' => '1.0',
            'user-agent'  => $useragent,   // So api.uri.edu can easily figure out who we are
            //'blocking'    => true,
            'headers'     => array('id' => $this->clientId),   // Set ClientID in header here
            //'cookies'     => array(),
            //'body'        => null,
            //'compress'    => false,
            //'decompress'  => true,
            //'sslverify'   => true,
            //'stream'      => false,
            //'filename'    => null
         );
      }
      
      // -------------------------------------------------------------
      // Get the JSON feed
      // -------------------------------------------------------------
      $response = wp_safe_remote_get($this->feedUrl, $args);
      if (is_wp_error($response)) {
         $error_message = $response->get_error_message();
         echo "There was an error with the URI REST API Client Plugin: $error_message";
         return FALSE;
      } else if (isset($response['body']) && !empty($response['body'])) {
         $this->feedData = $response['body'];
         return TRUE;
      } else {
         echo "There was an error with the URI REST API Client Plugin: empty response from server?";
         return FALSE;
      }
   }
}
?>
