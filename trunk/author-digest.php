<?php
/**
 * @package author-digest
 * @author Kyle Klaus
 * @version 1.0.0
 * @abstract Tools for multi-author WordPress installations.
 */

/*
Plugin Name: Author Digest
Plugin URI: http://code.google.com/p/author-digest	
Description: Tools for multi-author WordPress installations.
Author: Kyle Klaus
Version: 1.0.0
Author URI: http://indemnity83.com
*/

/*  Copyright 2009  Kyle Klaus  (email : kklaus@indemnity83.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * The current plugin version
 */
define('ADIGEST_VERSION','1.0.0');

add_action('wp_head', 'adigest_css');
add_action('admin_menu', 'adigest_menu');

add_shortcode('author','adigest_shortcode');

register_activation_hook(__FILE__,'adigest_activate');
register_deactivation_hook( __FILE__, 'adigest_deactivate' );

/**
 * Perform some initial checks, and activate the plugin
 */
function adigest_activate() {
	add_option('adigest_version', ADIGEST_VERSION);
}

/**
 * Perform some cleanup, and deactivate the plugin
 */
function adigest_deactivate() {
	delete_option('adigest_version');
}

/**
 * Display the menu items for the plugin
 */
function adigest_menu() {
	add_submenu_page('users.php','Author Digest Options', 'Author Digest', 8, __FILE__, 'adigest_options');
}

/**
 * Display the options page for the plugin
 */
function adigest_options() {
	echo '<div class="wrap">';
	echo '<div id="icon-users" class="icon32"><br /></div>';
	echo '	<h2>Author Digests Options</h2>';
	echo '	<form method="post" action="options.php">';
	echo '		<table class="form-table">';
	
	echo '			<tr valign="top">';	
	echo '				<th scope="row"><label for="blogname">Blog Title</label></th>';
	echo '				<td><input name="blogname" type="text" id="blogname" value="" class="regular-text" /></td>';
	echo '			</tr>';
	
	echo '		</table>';
	echo '	</form>';
	echo '</div>';	
}

/**
 * Handle the shortcode in content
 * @var array shortcode attributes
 * @var string shortcode content
 * @return string formatted shortcode replacement
 */
function adigest_shortcode($atts,$content = null) {
	extract( shortcode_atts( array(
    	'expanded' => true,
	), $atts ) );
	
    if( $content != null ) {
		if( $expanded == true ) {
			$authid = $content;
      		return adigest_author(get_userdata($authid));
      	}
	}
	
	return 'Author Digest Error';
}



/**
 * Return the expanded author content
 * @var object author meta
 * @return string expanded author content
 */
function adigest_author($author) {
	$content  = '<dl>';
	$content .= '	<dt><a href="http://www.prudentialnorcal.com/?author='.$author->ID.'" />'.$author->first_name.' '.$author->last_name.'</a></dt>';
	$content .= '	<dd class="img"><a href="http://www.prudentialnorcal.com/?author='.$author->ID.'" /><img src="http://www.prudentialnorcal.com/wp-content/profile-pics/'.$author->ID.'.jpg" width="80" /></a></dd>';
	$content .= '	<dd></dd>';
	$content .= '</dl>';

	return $content;
}

/**
 * Output the Author List
 */
function adigest_list($options) {
	
}

/**
 * Print out the required CSS to format various elements of the Author Digests plugin
 */
function adigest_css() {
	echo "
	<style type='text/css'>

	</style>
	";
}

?>