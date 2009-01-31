<?php
/**
 * @package author-digest
 * @author Kyle Klaus
 * @version 1.0.0
 * @abstract Tools for multi-author WordPress installations.
 * @copyright Copyright 2009  Kyle Klaus  (email : kklaus@indemnity83.com)
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

/* Copyright 2009  Kyle Klaus  (email : kklaus@indemnity83.com)
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */   

/*
Plugin Name: Author Digest
Plugin URI: http://code.google.com/p/author-digest	
Description: Tools for multi-author WordPress installations.
Author: Kyle Klaus
Version: 1.0.0
Author URI: http://indemnity83.com
*/

/**
 * The current plugin version
 */
define('ADIGEST_VERSION','1.0.0');

add_action('wp_head', 'adigest_css');
add_action('admin_menu', 'adigest_menu');

add_shortcode('author','adigest_author_shortcode');

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
 * Handle the 'author' shortcode in content
 * @var array shortcode attributes
 * @var string shortcode content
 * @return string formatted shortcode replacement
 */
function adigest_author_shortcode($atts,$content = null) {
	extract( shortcode_atts( array(
    	'mode'	=> 'expanded',
		'id'	=> ''
	), $atts ) );
	
	if( $id == '' ) {
		$author = get_userdatabylogin($content);
	} else {
		$author = get_userdata(intval($id));
	}
	
	switch( $mode ) {
		case 'expanded':
      		return adigest_author($author);
      		break;
		case 'list':
			return adigest_list();
      		break;
		default:
			return 'Invalid Mode';
			break;
	}
}

/**
 * Output a single author definition
 */
function adigest_author($author) {
	$body  = '<div class="author">';
	$body .= '  <dl>';
	$body .= '	  <dt><a href="'.$wp_path.'?author='.$author->ID.'" />'.$author->nickname.'</a></dt>';
	$body .= '	  <dd class="img"><a href="'.$wp_path.'?author='.$author->ID.'" />'.get_avatar( $author->ID, $size = '80' ).'</a></dd>';
	$body .= '	  <dd>Welcome to WordPress. This is your first post. Edit or delete it, then start blogging!</dd>';
	$body .= '  </dl>';
	$body .= '</div>';
	return $body;
}

/**
 * Output the Author List
 */
function adigest_list() {
	global $wpdb;	
	$authors = $wpdb->get_results("SELECT * from " . $wpdb->users);	

	foreach( $authors as $author ) { 
		$author = get_userdata(intval($author->ID));
		$body .= adigest_author($author);
	}
	return $body;
}

/**
 * Print out the required CSS to format various elements of the Author Digests plugin
 */
function adigest_css() {
	echo "
	<style type='text/css'>
		.author {
			float: left;
			width: 450px;
			padding: 0;	
			margin: 0;
			}		
		.author dl {
			float: left;
			width: 450px;
			margin: 20px 0;
			padding: 0;
			display: inline; /* fixes IE/Win double margin bug */
			}
		.author dt {
			float: right;
			width: 352px;
			margin: 0;
			padding: 0;
			font-size: 130%;
			letter-spacing: 1px;
			}
		.author dd {
			margin: 0;
			padding: 0;
			font-size: 85%;
			line-height: 1.5em;	
			}
		.author dd.img img {
			float: left;
			margin: 0 8px 0 0;
			padding: 4px;
			border: 1px solid #D9E0E6;
			border-bottom-color: #C8CDD2;
			border-rright-color: #C8CDD2;
			background: #FFF;	
			}	
	</style>
	";
}

?>