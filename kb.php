<?php
/**
*
* @package MX-Publisher Module - mx_kb
* @version $Id: kb.php,v 1.52 2008/07/15 22:05:41 jonohlsson Exp $
* @copyright (c) 2002-2006 [Jon Ohlsson, Mohd Basri, wGEric, PHP Arena, pafileDB, CRLin] MX-Publisher Project Team
* @license http://opensource.org/licenses/gpl-license.php GNU General Public License v2
*
*/

$phpEx = substr(strrchr(__FILE__, '.'), 1);

if ( !defined('PORTAL_BACKEND') && @file_exists( './viewtopic.' . $phpEx ) ) // -------------------------------------------- phpBB MOD MODE
{
	define( 'MXBB_MODULE', false );
	define( 'IN_PHPBB', true );
	define( 'IN_PORTAL', true );

	// When run as a phpBB mod these paths are identical ;)
	$phpbb_root_path = $module_root_path = $mx_root_path = './';
	$mx_mod_path = $phpbb_root_path . 'mx_mod/';

	//Check for cash mod
	if (file_exists($phpbb_root_path . 'includes/functions_cash.'.$phpEx))
	{
		define('IN_CASHMOD', true);
	}

	include( $phpbb_root_path . 'common.' . $phpEx );

	@ini_set( 'display_errors', '1' );
	error_reporting (E_ERROR | E_WARNING | E_PARSE); // This will NOT report uninitialized variables

	include_once( $mx_mod_path . 'includes/functions_required.' . $phpEx );
	include_once( $mx_mod_path . 'includes/functions_core.' . $phpEx );

	define( 'PAGE_KB', -502 ); // If this id generates a conflict with other mods, change it ;)

	//
	// Instatiate the mx_cache class
	//
	$mx_cache = new mx_cache();

	//
	// Get MX-Publisher config settings
	//
	$portal_config = $mx_cache->obtain_mxbb_config();

	//
	// instatiate the mx_request_vars class
	//
	$mx_request_vars = new mx_request_vars();

	$is_block = false;

	if ( file_exists("./modcp.$phpEx") ) // phpBB2
	{
		define('PORTAL_BACKEND', 'phpbb2');
		$tplEx = 'tpl';

		mx_cache::load_file('bbcode');
		mx_cache::load_file('functions_post');

		// Start session management
		$userdata = session_pagestart( $user_ip, PAGE_DOWNLOAD );
		init_userprefs( $userdata );
		// End session management


	}
	else if ( @file_exists("./mcp.$phpEx") ) // phpBB3
	{
		define('PORTAL_BACKEND', 'phpbb3');
		$tplEx = 'html';

		//
		// Start session management
		//
		$user->session_begin();
		$userdata = $user->data;
		$user->setup();
		//
		// End session management
		//

		//
		// Get phpBB config settings
		//
		$board_config = $config;
	}
	else
	{
		die('Copy this file in phpbb_root_path were is your viewtopic.php file!!!');
	}
}
else // --------------------------------------------------------------------------------- MX-Publisher Module MODE
{
	define( 'MXBB_MODULE', true );
		
	if ( !function_exists( 'read_block_config' ) )
	{
		define( 'IN_PORTAL', true );
		
		$mx_root_path 		= './../../';
		$module_root_path 	= './';
	
		include_once( $mx_root_path . 'common.' . $phpEx );
	
		//
		// Start session, user and style (template + theme) management
		// - populate $userdata, $lang, $theme, $images and initiate $template.
		//
		$mx_user->init($user_ip, PAGE_INDEX);
		// End session management


		//
		// Initiate user style (template + theme) management
		// - populate $theme, $images and initiate $template.
		//
		$mx_user->init_style();

		// session id check
		if (!$mx_request_vars->is_empty_request('sid'))
		{
			$sid = $mx_request_vars->request('sid', MX_TYPE_NO_TAGS);
		}
		else
		{
			$sid = '';
		}
		define( 'MXBB_27x', file_exists( $mx_root_path . 'mx_login.' . $phpEx ) );

		include_once( $module_root_path . 'kb/includes/kb_pages.' . $phpEx );
		$mx_get_page = new kb_pages();
		$mx_get_page->init('kb.php');

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

		if (isset($_GET['print']))
		{
			$url .= '&print=true';
		}

		if ( !empty( $url ) && !$mx_get_page->error)
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
	else
	{
		if( !defined('IN_PORTAL') || !is_object($mx_block))
		{
			die("Hacking attempt");
		}

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

		//Check for cash mod
		if (file_exists($phpbb_root_path . 'includes/functions_cash.'.$phpEx))
		{
			define('IN_CASHMOD', true);
		}

		$is_block = true;
		global $images;
	}
	define( 'MXBB_27x', @file_exists( $mx_root_path . 'mx_login.'.$phpEx ) );
	define( 'MXBB_28x', @file_exists( $mx_root_path . 'includes/sessions/index.htm' ) );
}

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

// ===================================================
// Include the common file
// ===================================================
include_once( $module_root_path . 'kb/kb_common.' . $phpEx );

// ===================================================
// Get mode variables, otherwise set it to the main
// ===================================================
$mode = $mx_request_vars->request('mode', MX_TYPE_NO_TAGS, 'main');
$print_version = $mx_request_vars->is_request('print');
$kb_config['reader_mode'] = false;

// ===================================================
// Is admin?
// ===================================================
switch (PORTAL_BACKEND)
{
	case 'internal':
	case 'phpbb2':
		$is_admin = ( ( $userdata['user_level'] == ADMIN  ) && $userdata['session_logged_in'] ) ? true : 0;
	break;
	case 'phpbb3':
		$is_admin = ( $userdata['user_type'] == USER_FOUNDER ) ? true : 0;
	break;
}

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
	'cat' => 'cat',
	'add' => 'post',
	'search' => 'search',
	'edit' => 'post',
	'rate' => 'rate',
	'stats' => 'stats',
	'mcp' => 'mcp',
	'post_comment' => 'post_comment',
	'main' => 'main' );

// ===================================================
// Lets Build the page
// ===================================================
$page_title = $mx_user->lang('KB_title');

$mx_kb->module($actions[$mode]);
$mx_kb->modules[$actions[$mode]]->main($mode);

//
// load module header
//
if ( !$print_version )
{
	$mx_kb_functions->page_header();
}

//
// Some general template vars
//
$template->assign_vars( array(
	'U_PORTAL' => $mx_root_path,
	'L_PORTAL' => "<<",
	'U_KB' => mx_append_sid( $mx_kb->this_mxurl() ),
	'L_KB' => $lang['KB_title']  )
);

if ( $print_version)
{
	ob_start();
}

$template->pparse('body');

if ( $print_version )
{
	$print_contents = ob_get_contents();
	ob_end_clean();
	die($print_contents);
}

//
// load module footer
//
if ( !$print_version )
{
	$mx_kb_functions->page_footer();
}

?>