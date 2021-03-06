<?php
/**
*
* @package MX-Publisher Module - mx_kb
* @version $Id: kb_article_reader.php,v 1.26 2008/06/03 20:08:21 jonohlsson Exp $
* @copyright (c) 2002-2006 [Jon Ohlsson, Mohd Basri, wGEric, PHP Arena, pafileDB, CRLin] MX-Publisher Project Team
* @license http://opensource.org/licenses/gpl-license.php GNU General Public License v2
*
*/

if ( file_exists( './viewtopic.' . $phpEx ) ) // -------------------------------------------- phpBB MOD MODE
{
	define( 'MXBB_MODULE', false );
	define( 'IN_PHPBB', true );
	define( 'IN_PORTAL', true );

	// When run as a phpBB mod these paths are identical ;)
	$phpbb_root_path = $module_root_path = $mx_root_path = './';

	$phpEx = substr(strrchr(__FILE__, '.'), 1);
	include( $phpbb_root_path . 'common.' . $phpEx );

	define( 'PAGE_KB', -500 ); // If this id generates a conflict with other mods, change it ;)

	// Start session management
	$userdata = session_pagestart( $user_ip, PAGE_KB );
	init_userprefs( $userdata );
	// End session management
}
else // --------------------------------------------------------------------------------- MX-Publisher Module MODE
{
	define( 'MXBB_MODULE', true );

	if ( !function_exists( 'read_block_config' ) )
	{
		define( 'IN_PORTAL', true );
		$mx_root_path 		= './../../';
		$module_root_path 	= './';

		$phpEx = substr(strrchr(__FILE__, '.'), 1);
		include_once( $mx_root_path . 'common.' . $phpEx );

		// Start session management
		$mx_user->init($user_ip, PAGE_INDEX);
		// End session management

		define( 'MXBB_27x', file_exists( $mx_root_path . 'mx_login.' . $phpEx ) );

		if ( !isset( $_GET['print'] ) )
		{
			include_once( $module_root_path . 'kb/includes/kb_pages.' . $phpEx );
			$mx_get_page = new kb_pages();
			$mx_get_page->init('kb_article_reader.php');

			$start = ( isset( $_GET['start'] ) ) ? intval( $_GET['start'] ) : 0;

			$url = '';
			if ( empty( $mx_get_page->item_id ) )
			{
				$url = PORTAL_URL . 'index.php?page=' . $mx_get_page->page_id . '&mode=cat&cat=' . $mx_get_page->cat_id;
			}
			else if ( !empty( $mx_get_page->item_id ) )
			{
				$url = PORTAL_URL . 'index.php?page=' . $mx_get_page->page_id . '&mode=article&k=' . $mx_get_page->item_id;
			}

			if ( !empty( $url ) && !$mx_get_page->error )
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
				if( !defined('IN_PORTAL') )
				{
					die("Hacking attempt");
				}

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
		if( !defined('IN_PORTAL') || !is_object($mx_block))
		{
			die("Hacking attempt");
		}

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
// Start
// -------------------------------------------------------------------------------------------------------------------------
// -------------------------------------------------------------------------------------------------------------------------

// ===================================================
// Include the common file
// ===================================================
include_once( $module_root_path . 'kb/kb_common.' . $phpEx );

// ===================================================
// Get mode variables, otherwise set it to the main
// ===================================================
$mode = $mx_request_vars->request('mode', MX_TYPE_NO_TAGS, 'article');
$print_version = $mx_request_vars->is_request('print');
$kb_config['reader_mode'] = true;

// ===================================================
// Is admin?
// ===================================================
$is_admin = ( ( $userdata['user_level'] == ADMIN  ) && $userdata['session_logged_in'] ) ? true : 0;

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
	'article' => 'article',
	'search' => 'search',
	'rate' => 'rate',
	'stats' => 'stats',
	'post_comment' => 'post_comment',
);

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
	//$mx_kb_functions->page_header( $page_title );
}

$template->assign_vars( array(
	'U_PORTAL' => $mx_root_path,
	'L_PORTAL' => "<<",
	'U_KB' => mx_append_sid( $mx_kb->this_mxurl() ),
	'L_KB' => $lang['KB_title']  )
);

$template->pparse( 'body' );

//
// load module footer
//
if ( !$print_version )
{
	//$mx_kb_functions->page_footer();
}

if ( !$is_block && !$print_version )
{
	include( $mx_root_path . 'includes/page_tail.' . $phpEx );
}
?>