<?php
/**
*
* @package MX-Publisher Module - mx_kb
* @version $Id: kb_common.php,v 1.14 2008/09/14 00:29:46 orynider Exp $
* @copyright (c) 2002-2006 [Jon Ohlsson, Mohd Basri, wGEric, PHP Arena, pafileDB, CRLin] MX-Publisher Project Team
* @license http://opensource.org/licenses/gpl-license.php GNU General Public License v2
*
*/

if ( !defined( 'IN_PORTAL' ) )
{
	die( "Hacking attempt" );
}

/*
// ===================================================
// addslashes to vars if magic_quotes_gpc is off
// ===================================================
if ( !@function_exists( 'slash_input_data' ) )
{
	function slash_input_data( $data )
	{
		if ( is_array( $data ) )
		{
			foreach ( $data as $k => $v )
			{
				$data[$k] = ( is_array( $v ) ) ? slash_input_data( $v ) : addslashes( $v );
			}
		}
		return $data;
	}
}

// ===================================================
// to make it work with php version under 4.1 and other stuff
// ===================================================
if ( @phpversion() < '4.1' )
{
	$_GET = $HTTP_GET_VARS;
	$_POST = $HTTP_POST_VARS;
	$_COOKIE = $HTTP_COOKIE_VARS;
	$_SERVER = $HTTP_SERVER_VARS;
	$_ENV = $HTTP_ENV_VARS;
	$_FILES = $HTTP_POST_FILES;
	$_SESSION = $HTTP_SESSION_VARS;
}

if ( !isset( $_REQUEST ) )
{
	$_REQUEST = array_merge( $_GET, $_POST, $_COOKIE );
}

if ( !get_magic_quotes_gpc() )
{
	$_GET = slash_input_data( $_GET );
	$_POST = slash_input_data( $_POST );
	$_COOKIE = slash_input_data( $_COOKIE );
	$_REQUEST = slash_input_data( $_REQUEST );
}
*/

// ===================================================
// Include Files
// ===================================================
include_once( $module_root_path . 'kb/includes/kb_constants.' . $phpEx );

//
// Load addon tools
//
// - Class module_cache
// - Class mx_custom_fields
// - Class mx_notification
// - Class mx_text
// - Class mx_text_formatting
//
if ( !MXBB_MODULE )
{
	include_once( $mx_mod_path . 'includes/functions_tools.' . $phpEx );
}
else
{
	include_once( $mx_root_path . 'includes/mx_functions_tools.' . $phpEx );
}



// **********************************************************************
// If phpBB mod read language definition
// **********************************************************************

if ( !MXBB_MODULE )
{
	if ( !file_exists( $module_root_path . 'pafiledb/language/lang_' . $board_config['default_lang'] . '/lang_main.' . $phpEx ) )
	{
		include( $module_root_path . 'kb/language/lang_english/lang_main.' . $phpEx );
	}
	else
	{
		include( $module_root_path . 'kb/language/lang_' . $board_config['default_lang'] . '/lang_main.' . $phpEx );
	}
}

include_once( $module_root_path . 'kb/includes/functions.' . $phpEx );
include_once( $module_root_path . 'kb/includes/functions_auth.' . $phpEx );
//include_once( $module_root_path . 'kb/includes/functions_mx.' . $phpEx );
include_once( $module_root_path . 'kb/includes/functions_kb.' . $phpEx );
//
// Load a wrapper for common phpBB2 functions
//
if ( defined('MXBB_28x') )
{
	include_once( $mx_root_path . 'includes/shared/phpbb2/includes/functions.' . $phpEx );
}

//
// We need XS templates, also when ran as a phpBB2 MOD
//
if ( !MXBB_MODULE )
{
	include_once( $module_root_path . 'kb/includes/template.' . $phpEx ); // Include XS template
	$template = new Template($module_root_path . 'templates/'. $theme['template_name']);
}

// ===================================================
// Load classes
// ===================================================
$mx_kb_cache = new module_cache($module_root_path . 'kb/');
$mx_kb_functions = new mx_kb_functions();

if ( $mx_kb_cache->exists( 'config' ) )
{
	$kb_config = $mx_kb_cache->get( 'config' );
}
else
{
	$kb_config = $mx_kb_functions->kb_config();
	$mx_kb_cache->put( 'config', $kb_config );
}

//
// options
//
$kb_wysiwyg = false;
if ( $kb_config['wysiwyg'] ) // Html Textblock
{
	if ( file_exists( $mx_root_path . 'modules/mx_shared/tinymce/jscripts/tiny_mce/tiny_mce.js' ) )
	{
		$bbcode_on = false;
		$html_on = true;
		$smilies_on = false;
		$kb_wysiwyg = true;
	}
}

if ( !$kb_wysiwyg )
{
	$bbcode_on = $kb_config['allow_bbcode'] ? true : false;
	$html_on = $kb_config['allow_html'] ? true : false;
	$smilies_on = $kb_config['allow_smilies'] ? true : false;
}

if (defined( 'IN_ADMIN' ))
{
	include_once( $module_root_path . 'kb/includes/functions_admin.' . $phpEx );
	$mx_kb = new mx_kb_admin();
}
else
{
	$mx_kb = new mx_kb_public();
}
?>