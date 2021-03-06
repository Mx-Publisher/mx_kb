<?php
/**
*
* @package MX-Publisher Module - mx_kb
* @version $Id: kb_mini.php,v 1.4 2008/06/03 20:08:23 jonohlsson Exp $
* @copyright (c) 2002-2006 [Jon Ohlsson, Mohd Basri, wGEric, PHP Arena, pafileDB, CRLin] MX-Publisher Project Team
* @license http://opensource.org/licenses/gpl-license.php GNU General Public License v2
*
*/

if( !defined('IN_PORTAL') || !is_object($mx_block))
{
	die("Hacking attempt");
}

//
// Read Block Settings
//
$title = $mx_block->block_info['block_title'];
$block_size = ( isset( $block_size ) && !empty( $block_size ) ? $block_size : '100%' );

$is_block = true;
global $images;

//
// Definitions
//
define( 'MXBB_MODULE', true );
define( 'MXBB_27x', file_exists( $mx_root_path . 'mx_login.php' ) );

list( $trash, $mx_script_name_temp ) = split ( trim( $board_config['server_name'] ), PORTAL_URL );
$mx_script_name = preg_replace( '#^\/?(.*?)\/?$#', '\1', trim( $mx_script_name_temp ) );

//
// Setup config parameters
//
$config_name = array( 'mini_default_cat_id', 'mini_display_options', 'mini_pagination' );

for( $i = 0; $i < count( $config_name ); $i++ )
{
	$config_value = $mx_block->get_parameters( $config_name[$i] );
	$mini_config[$config_name[$i]] = $config_value;
}

// ===================================================
// Include the common file
// ===================================================
include_once( $module_root_path . 'kb/kb_common.' . $phpEx );
$template->destroy();

// ===================================================
// Get action variable other wise set it to the main
// ===================================================
$action = ( isset( $_REQUEST['action_mini'] ) ) ? htmlspecialchars( $_REQUEST['action_mini'] ) : 'mini';

// ===================================================
// if the module is disabled give them a nice message
// ===================================================
if (!($kb_config['enable_module'] || $mx_user->is_admin))
{
	mx_message_die( GENERAL_MESSAGE, $lang['kb_disable'] );
}

// ===================================================
// an array of all expected actions
// ===================================================
$actions = array(
	'mini' => 'mini',
	'download' => 'download'
);

// ===================================================
// Lets Build the page
// ===================================================
$page_title = $lang['Download'];

if ( $action != 'download' )
{
	if ( !$is_block )
	{
		include( $mx_root_path . 'includes/page_header.' . $phpEx );
	}
}

$mx_kb->module( $actions[$action] );
$mx_kb->modules[$actions[$action]]->main($action = false);

$template->pparse( 'body' );

if ( $action != 'download' )
{
	if ( !$is_block )
	{
		include( $mx_root_path . 'includes/page_tail.' . $phpEx );
	}
}
?>