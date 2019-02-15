<?php
/**
*
* @package MX-Publisher Module - mx_kb
* @version $Id: kb_constants.php,v 1.15 2012/10/25 12:59:06 orynider Exp $
* @copyright (c) 2002-2006 [wGEric, Jon Ohlsson] mxBB Project Team
* @license http://opensource.org/licenses/gpl-license.php GNU General Public License v2
*
*/

if( !defined('IN_PORTAL') )
{
	die("Hacking attempt");
}

if ( !MXBB_MODULE )
{
	$server_protocol = ($board_config['cookie_secure']) ? 'https://' : 'http://';
	$server_name = preg_replace('#^\/?(.*?)\/?$#', '\1', trim($board_config['server_name']));
	$server_port = ($board_config['server_port'] <> 80) ? ':' . trim($board_config['server_port']) : '';
	$script_name = preg_replace('#^\/?(.*?)\/?$#', '\1', trim($board_config['script_path']));
	$script_name = ($script_name == '') ? $script_name : '/' . $script_name;

	define( 'PORTAL_URL', $server_protocol . $server_name . $server_port . $script_name . '/' );
	define( 'PHPBB_URL', PORTAL_URL );

	$kb_config['news_operate_mode'] = false;
	$mx_table_prefix = $table_prefix;
	$is_block = false;
}

// -------------------------------------------------------------------------
// This file defines specific constants for the module
// -------------------------------------------------------------------------
define('PAGE_KB_DEFAULT', -42);
define('PAGE_KB', -42); // If this id generates a conflict with other mods, change it ;);

define( 'KB_ARTICLES_TABLE', $mx_table_prefix . 'kb_articles' );
define( 'KB_CATEGORIES_TABLE', $mx_table_prefix . 'kb_categories' );
define( 'KB_CONFIG_TABLE', $mx_table_prefix . 'kb_config' );
define( 'KB_TYPES_TABLE', $mx_table_prefix . 'kb_types' );
define( 'KB_WORD_TABLE', $mx_table_prefix . 'kb_wordlist' );
define( 'KB_SEARCH_TABLE', $mx_table_prefix . 'kb_results' );
define( 'KB_MATCH_TABLE', $mx_table_prefix . 'kb_wordmatch' );
define( 'KB_VOTES_TABLE', $mx_table_prefix . 'kb_votes' );
define( 'KB_COMMENTS_TABLE', $mx_table_prefix . 'kb_comments' );
define( 'KB_CUSTOM_TABLE', $mx_table_prefix . 'kb_custom' );
define( 'KB_CUSTOM_DATA_TABLE', $mx_table_prefix . 'kb_customdata' );

// -------------------------------------------------------------------------
// Field Types
// -------------------------------------------------------------------------
define( 'INPUT', 0 );
define( 'TEXTAREA', 1 );
define( 'RADIO', 2 );
define( 'SELECT', 3 );
define( 'SELECT_MULTIPLE', 4 );
define( 'CHECKBOX', 5 );

// -------------------------------------------------------------------------
// Footer Copyrights
// -------------------------------------------------------------------------
if ( !MXBB_MODULE || MXBB_27x )
{
	$kb_module_version = "Knowledge Base MOD v. 0.9.0";
	$kb_module_author = "Jon Ohlsson";
	$kb_module_orig_author = "wGEric";
}
else
{
	if (!$_GET['print']) // Do not "fix" with request wrapper!!
	{
		$mx_user->set_module_default_style('_core'); // For compatibility with core 2.8.x
	}

	if (is_object($mx_page))
	{
		// -------------------------------------------------------------------------
		// Extend User Style with module lang and images
		// Usage:  $mx_user->extend(LANG, IMAGES)
		// Switches:
		// - LANG: MX_LANG_MAIN (default), MX_LANG_ADMIN, MX_LANG_ALL, MX_LANG_NONE
		// - IMAGES: MX_IMAGES (default), MX_IMAGES_NONE
		// -------------------------------------------------------------------------
		if (!$_GET['print']) // Do not "fix" with request wrapper!!
		{
			$mx_user->extend();
		}

		$mx_page->add_copyright( 'MXP Knowledge Base Module' );
	}
}
?>