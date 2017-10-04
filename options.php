<?php
/*
Description: File to create admin settings menu for the URI REST API Client Plugin.
Version: 0.1
Author: Heath Loder
*/

// Create admin menu hook
add_action('admin_menu', 'uraclient_admin_menu_init');

// Create admin menu for plugin
function uraclient_admin_menu_init() {
	// Create new top-level admin menu (alongside "Dashboard)
	add_menu_page(
	   'URI REST API Client Settings',   // page title
      'URI API Client',   // menu text
	   'manage_options',   // capability req'd
	   'uraclient_basic_settings',   // menu slug ("page name")
	   'uraclient_settings_init',   // callback function
	   'dashicons-list-view',   // menu icon
	   63);   // menu location

	// Register plugin settings using the WP Settings API
	add_action( 'admin_init', 'register_uraclient_settings' );
}

// Register plugin settings with WordPress Settings API
function register_uraclient_settings() {
   // ----------------------------------------------------
   // Register setting(s)
   // ----------------------------------------------------
   // Setting for JSON feed URL
   register_setting( 'uraclient_options', 'uraclient_json_url' );
   // Setting for URI API ClientID
   register_setting( 'uraclient_options', 'uraclient_client_id' );

   // ----------------------------------------------------
   // Add section(s) and setting(s)
   // ----------------------------------------------------
   // Add the section to uraclient settings so we can add our fields to it
 	add_settings_section(
		'uraclient_basic_settings',   // $id
		'Basic Settings',   // $title
		'uraclient_settings_section_text',   // $callback - fill section here
		'uraclient_basic_settings'   // $page (menu slug)
	);
 	// Add the settings fields to our settings section
 	add_settings_field(
		'uraclient_json_url',   // $id
		'REST URL (https://)',   // $field title
		'uraclient_settings_json_url_field',   // $callback - fill field data here
		'uraclient_basic_settings',   // $page (menu slug)
		'uraclient_basic_settings'   // $section id
	);
 	add_settings_field(
		'uraclient_client_id',   // $id
		'URI API Client ID',   // $field title
		'uraclient_settings_client_id_field',   // $callback - fill field data here
		'uraclient_basic_settings',   // $page (menu slug)
		'uraclient_basic_settings'   // $section id
	);
}

// ------------------------------------------------------------------
// Settings section callback function
// ------------------------------------------------------------------
function uraclient_settings_section_text() {
   echo '<p>To display data from the URI catalog on your website, supply the relevant information below:</p>';
}
// ------------------------------------------------------------------
// Callback function for the JSON URL field
// ------------------------------------------------------------------
function uraclient_settings_json_url_field() {
   echo '<input type="text" name="uraclient_json_url" id="uraclient_json_url" value="' . get_option('uraclient_json_url') . '" />';
}
// ------------------------------------------------------------------
// Callback function for the JSON URL field
// ------------------------------------------------------------------
function uraclient_settings_client_id_field() {
   echo '<input type="text" name="uraclient_client_id" id="uraclient_client_id" value="' . get_option('uraclient_client_id') . '" />';	
}

// The actual settings page for the URI REST API Client Plugin
function uraclient_settings_init() {
   // Must check that the user has the required capability 
   if (!current_user_can('manage_options')) {
      wp_die( __('You do not have sufficient permissions to access this page.') );
   }
   ?>
   <div class="wrap">
      <div style="float:right; margin:12px; margin-top:0px; padding:12px;">
            <a href="https://api.uri.edu" target="_blank"><img src="<?php echo plugins_url( 'images/icon-256x80.png', __FILE__ ); ?>" style="border-radius:10px;" title="URI REST API"></a>
	   </div>
      <h1>URI REST API Client</h1>
      <form method="post" action="options.php">
         <?php 
            settings_fields( 'uraclient_options' );
            do_settings_sections( 'uraclient_basic_settings' );
            submit_button();
         ?>
      </form>
      <hr>
      <table>
         <tr>
           	<td>
               <strong>Example Shortcode Syntax for Wordpress Pages and Posts:</strong>
               <p>
               <strong>[uraclient]</strong>
            </td>
         </tr>
      </table>
   </div>
<?php
}
?>
