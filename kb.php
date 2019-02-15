<?php
/** ------------------------------------------------------------------------
 *		Subject				: mxBB - a fully modular portal and CMS (for phpBB) 
 *		Author				: Jon Ohlsson and the mxBB Team
 *		Credits				: The phpBB Group & Marc Morisette, wGeric
 *		Copyright          	: (C) 2002-2005 mxBB Portal
 *		Email             	: jon@mxbb-portal.com
 *		Project site		: www.mxbb-portal.com
 * -------------------------------------------------------------------------
 * 
 *    $Id: kb.php,v 1.27 2005/12/16 03:28:10 mennonitehobbit Exp $
 */

/**
 * This program is free software; you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation; either version 2 of the License, or
 *    (at your option) any later version.
 */

if ( file_exists( './viewtopic.' . $phpEx ) ) // -------------------------------------------- phpBB MOD MODE
{
	define( 'MXBB_MODULE', false ); 
	define( 'IN_PHPBB', true );
	define( 'IN_PORTAL', true );
	
	// When run as a phpBB mod these paths are identical ;)
	$phpbb_root_path = $module_root_path = $mx_root_path = './';
	
	include( $phpbb_root_path . 'extension.inc' );
	include( $phpbb_root_path . 'common.' . $phpEx );
	
	define( 'PAGE_KB', -501 ); // If this id generates a conflict with other mods, change it ;)	
	
	// Start session management
	$userdata = session_pagestart( $user_ip, PAGE_KB );
	init_userprefs( $userdata );
	// End session management
}
else // --------------------------------------------------------------------------------- mxBB Module MODE
{
	define( 'MXBB_MODULE', true ); 
	
	if ( !function_exists( 'read_block_config' ) )
	{
		define( 'IN_PORTAL', true );
		$mx_root_path = './../../';
		include_once( $mx_root_path . 'extension.inc' );
		include_once( $mx_root_path . 'common.' . $phpEx ); 
		
		// Start session management
		$userdata = session_pagestart( $user_ip, PAGE_INDEX );
		mx_init_userprefs( $userdata ); 
		// End session management

		define( 'MXBB_27x', file_exists( $mx_root_path . 'mx_login.' . $phpEx ) );
		
		if ( !isset( $HTTP_GET_VARS['print'] ) )
		{
			include_once( $module_root_path . 'includes/kb_constants.' . $phpEx );
			include_once( $module_root_path . 'includes/kb_pages.' . $phpEx );

			$start = ( isset( $HTTP_GET_VARS['start'] ) ) ? intval( $HTTP_GET_VARS['start'] ) : 0;
			
			$url = '';
			if ( empty( $article_id ) )
			{
				$url = PORTAL_URL . 'index.php?page=' . $kb_pages . '&mode=cat&cat=' . $cat_id;
			}
			else if ( !empty( $article_id ) )
			{
				$url = PORTAL_URL . 'index.php?page=' . $kb_pages . '&mode=article&k=' . $article_id;
			}	
			
			if ( !empty( $url ) && !$kb_error )
			{
				if ( !empty( $db ) )
				{
					$db->sql_close();
				} 
			
				if ( @preg_match( '/Microsoft|WebSTAR|Xitami/', getenv( 'SERVER_SOFTWARE' ) ) )
				{
					header( 'Refresh: 0; URL=' . $url );
					echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">' . "\n" . '<html><head>' . "\n" . '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">' . "\n" . '<meta http-equiv="refresh" content="0; url=' . $url . '">' . "\n" . '<title>Redirect</title>' . "\n" . '<script language="javascript" type="text/javascript">' . "\n" . '<!--' . "\n" . 'if( document.images ) {' . "\n" . "\t" . 'parent.location.replace("' . $url . '");' . "\n" . '} else {' . "\n" . "\t" . 'parent.location.href = "' . $url . '";' . "\n" . '}' . "\n" . '// -->' . "\n" . '</script>' . "\n" . '</head>' . "\n" . '<body>' . "\n" . '<div align="center">If your browser does not support meta redirection please click ' . '<a href="' . $url . '">HERE</a> to be redirected</div>' . "\n" . '</body></html>';
					exit;
				}
				@header( 'Location: ' . $url );	
			}
			else 
			{
				if ( MXBB_27x )
				{
					mx_message_die( GENERAL_MESSAGE, $lang['Standalone_Not_Supported'] );
				}	
				else 
				{			
					die('No article or redirect');	
				}
			}
		}
	}
	else
	{ 
		define( 'MXBB_27x', file_exists( $mx_root_path . 'mx_login.' . $phpEx ) );

		//
		// Read Block Settings (default mode)
		//
		$title = !empty( $mx_block->block_info['block_title'] ) ? $mx_block->block_info['block_title'] : $lang['KB_title'];
		$desc = $mx_block->block_info['block_desc'];
		$block_size = ( isset( $block_size ) && !empty( $block_size ) ? $block_size : '100%' );
	
		//
		// Extract 'what posts to view info', the cool Array ;)
		//
		$kb_type_select_var = $mx_block->get_parameters( 'kb_type_select' );
		$kb_type_select_data = ( !empty( $kb_type_select_var ) ) ? unserialize( $kb_type_select_var ) : array();
			
		$is_block = true;
		global $images;
	}	
}

// -------------------------------------------------------------------------------------------------------------------------
// -------------------------------------------------------------------------------------------------------------------------
// -------------------------------------------------------------------------------------------------------------------------

// ===================================================
// Include the common file
// ===================================================
include_once( $module_root_path . 'kb/kb_common.' . $phpEx );

// ===================================================
// Get action variable other wise set it to the main
// ===================================================
$mode = $mx_request_vars->request('mode', MX_TYPE_NO_TAGS, 'main');

//
// Get more variables
//
$print_version = $mx_request_vars->request('print', MX_TYPE_NO_TAGS, '');

$is_admin = ( ( $userdata['user_level'] == ADMIN  ) && $userdata['session_logged_in'] ) ? true : 0;
$reader_mode = false;

// ===================================================
// if the database disabled give them a nice message
// ===================================================
if ( intval( $kb_config['module_enable'] ) )
{
	mx_message_die( GENERAL_MESSAGE, $lang['pafiledb_disable'] );
}

// ===================================================
// an array of all expected actions
// ===================================================
$actions = array( 
	'article' => 'article',
	'cat' => 'cat',
	'add' => 'post',
	'search' => 'search',
	'edit' => 'post',
	'rate' => 'rate',
	'stats' => 'stats',
	'moderate' => 'moderator',
	'post_comment' => 'post_comment',	
	'main' => 'main' );
	
// ===================================================
// Lets Build the page
// ===================================================
if ( !$is_block && !$print_version)
{
	include( $mx_root_path . 'includes/page_header.' . $phpEx );
}
	
$mx_kb->module( $actions[$mode] );
$mx_kb->modules[$actions[$mode]]->main( $mode );

//
// load module header
//
if ( !$print_version )
{
	kb_page_header( $page_title );
}

$template->pparse( 'body' );

//
// load module footer
//
if ( !$print_version )
{
	kb_page_footer();
}

if ( !$is_block && !$print_version )
{
	include( $mx_root_path . 'includes/page_tail.' . $phpEx );
}
?>