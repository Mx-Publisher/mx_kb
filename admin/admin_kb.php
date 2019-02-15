<?php
/**
*
* @package MX-Publisher Module - mx_kb
* @version $Id: admin_kb.php,v 1.8 2013/06/17 15:54:47 orynider Exp $
* @copyright (c) 2002-2006 [Jon Ohlsson, Mohd Basri, wGEric, PHP Arena, pafileDB, CRLin] MX-Publisher Project Team
* @license http://opensource.org/licenses/gpl-license.php GNU General Public License v2
*
*/

$phpEx = substr(strrchr(__FILE__, '.'), 1);

if ( @file_exists( './../viewtopic.'.$phpEx ) )
{
	@define( 'IN_PHPBB', 1 );
	@define( 'IN_PORTAL', 1 );	
	@define( 'MXBB_MODULE', false );

	//
	// Main paths
	//
	$phpbb_root_path = $module_root_path = $mx_root_path = "./../";
	$mx_mod_path = $phpbb_root_path . 'mx_mod/';

	//
	// Left Pane Paths
	//
	$setmodules_admin_path = '';
	$setmodules_module_path = "./../";

	require_once( $phpbb_root_path . 'extension.inc' );
	require_once( $mx_mod_path . 'includes/functions_required.' . $phpEx );
}
else
{
	@define( 'MXBB_MODULE', true );

	//
	// Main paths
	//
	$mx_root_path = './../../../';
	$module_root_path = './../../../modules/mx_kb/';

	//
	// Left Pane Paths
	//
	$setmodules_root_path = './../';
	$setmodules_module_path = 'modules/mx_kb/';
	$setmodules_admin_path = $setmodules_module_path . 'admin/';

	@define( 'MXBB_27x', file_exists( $setmodules_root_path . 'mx_login.php' ) );

	$phpEx = substr(strrchr(__FILE__, '.'), 1);
}

if ( !empty( $setmodules ) )
{
	$filename = basename( __FILE__ );
	$module['KB_title']['1_Configuration'] 	= $setmodules_admin_path . $filename . "?action=settings";
	$module['KB_title']['2_Cat_man'] 	= $setmodules_admin_path . $filename . "?action=cat_manage";
	//$module['KB_title']['3_Art_man'] = $setmodules_admin_path . $filename . "?action=article_manage";
	$module['KB_title']['4_Permissions'] 	= $setmodules_admin_path . $filename . "?action=auth_manage";
	$module['KB_title']['5_Types_man'] 	= $setmodules_admin_path . $filename . "?action=types_manage";
	$module['KB_title']['6_Custom_Field'] 	= $setmodules_admin_path . $filename . "?action=custom_manage";
	return;
}

// Includes
require( $mx_root_path . '/admin/pagestart.' . $phpEx );
include_once( $setmodules_root_path . $setmodules_module_path . 'kb/includes/kb_constants.' . $phpEx );
include( $module_root_path . 'kb/kb_common.' . $phpEx );

// **********************************************************************
// Read language definition
// **********************************************************************
if ( !MXBB_MODULE )
{
	if ( !file_exists( $module_root_path . 'kb/language/lang_' . $board_config['default_lang'] . '/lang_admin.' . $phpEx ) )
	{
		include( $module_root_path . 'kb/language/lang_english/lang_admin.' . $phpEx );
	}
	else
	{
		include( $module_root_path . 'kb/language/lang_' . $board_config['default_lang'] . '/lang_admin.' . $phpEx );
	}
}
else
{
	if ( !file_exists( $module_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_admin.' . $phpEx ) )
	{
		include( $module_root_path . 'language/lang_english/lang_admin.' . $phpEx );
	}
	else
	{
		include( $module_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_admin.' . $phpEx );
	}
}

//
// Get action variable other wise set it to the main
//
$action = ( isset( $_REQUEST['action'] ) ) ? htmlspecialchars( $_REQUEST['action'] ) : 'setting';

//
// an array of all expected actions
//
$actions = array(
	'settings' => 'settings',
	'cat_manage' => 'cat_manage',
	//'article_manage' => 'article_manage',
	'auth_manage' => 'auth_manage',
	'types_manage' => 'types_manage',
	'custom_manage' => 'custom_manage' );

//
// Lets Build the page
//
$mx_kb->adminmodule( $actions[$action] );
$mx_kb->modules[$actions[$action]]->main( $action );

$mx_kb->modules[$actions[$action]]->_kb();

include_once( $mx_root_path . 'admin/page_footer_admin.' . $phpEx );
?>