<?php
/**
*
* @package MX-Publisher Module - mx_kb
* @version $Id: kb_lists.php,v 1.6 2008/06/03 20:08:22 jonohlsson Exp $
* @copyright (c) 2002-2006 [Jon Ohlsson, Mohd Basri, wGEric, PHP Arena, pafileDB, CRLin] MX-Publisher Project Team
* @license http://opensource.org/licenses/gpl-license.php GNU General Public License v2
*
*/

if( !defined('IN_PORTAL') || !is_object($mx_block))
{
	die("Hacking attempt");
}
//error_reporting(E_ALL); //Default error reporting in PHP 5.2+
		
		//
		// Read Block Settings (default mode)
		//
$title = $mx_block->block_info['block_title'];
$desc = $mx_block->block_info['block_desc'];
$block_size = ( isset( $block_size ) && !empty( $block_size ) ? $block_size : '100%' );

$is_block = true;
global $images;

//
// Definitions
//
define( 'MXBB_MODULE', true );

define( 'MXBB_27x', @file_exists( $mx_root_path . 'mx_login.'.$phpEx ) );
define( 'MXBB_28x', @file_exists( $mx_root_path . 'includes/sessions/index.htm' ) );


// -------------------------------------------------------------------------------------------------------------------------
// -------------------------------------------------------------------------------------------------------------------------
// Start
// -------------------------------------------------------------------------------------------------------------------------
// -------------------------------------------------------------------------------------------------------------------------

// ===================================================
// ?
// ===================================================
list($trash, $mx_script_name_temp ) = preg_split(trim('//', $board_config['server_name']), PORTAL_URL);
$mx_script_name = preg_replace('#^\/?(.*?)\/?$#', '\1', trim($mx_script_name_temp));

//
// Setup config parameters
//
$config_name = array( 'toplist_sort_method', 'toplist_display_options', 'toplist_pagination', 'toplist_use_pagination', 'toplist_filter_date', 'toplist_cat_id' );

for( $i = 0; $i < count( $config_name ); $i++ )
{
	$config_value = $mx_block->get_parameters( $config_name[$i] );
	$toplist_config[$config_name[$i]] = $config_value;
}

//
// Get pafiledb target block
//
$toplist_block_id = $mx_block->get_parameters( 'target_block' );
$toplist_page_id = intval($toplist_block_id) > 0 ? get_page_id( $toplist_block_id ) : get_page_id( 'kb.php', true );

// ===================================================
// Include the common file
// ===================================================
include_once( $module_root_path . 'kb/kb_common.' . $phpEx );


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
$actions = array( 'lists' => 'lists' );
$action = 'lists';
// ===================================================
// Lets Build the page
// ===================================================
$page_title = $mx_user->lang('KB_title');

$mx_kb->module($actions[$action]);
$mx_kb->modules[$actions[$action]]->main($action = false);
//
// Some general template vars
//
$template->assign_vars( array(
	'U_PORTAL' => $mx_root_path,
	'L_PORTAL' => "<<",
	'U_KB' => mx_append_sid( $mx_kb->this_mxurl() ),
	'L_KB' => $lang['KB_title']  )
);
$template->pparse('body');

?>