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
 *    $Id: kb_constants.php,v 1.1 2005/12/08 15:06:46 jonohlsson Exp $
 */

/**
 * This program is free software; you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation; either version 2 of the License, or
 *    (at your option) any later version.
 */

if ( !MXBB_MODULE )
{
	$server_protocol = ($board_config['cookie_secure']) ? 'https://' : 'http://';
	$server_name = preg_replace('#^\/?(.*?)\/?$#', '\1', trim($board_config['server_name']));
	$server_port = ($board_config['server_port'] <> 80) ? ':' . trim($board_config['server_port']) : '';
	$script_name = preg_replace('#^\/?(.*?)\/?$#', '\1', trim($board_config['script_path']));
	$script_name = ($script_name == '') ? $script_name : '/' . $script_name;
		
	define( 'PORTAL_URL', $server_protocol . $server_name . $server_port . $script_name . '/' );
	define( 'PHPBB_URL', PORTAL_URL );
	
	$reader_mode = false;
	$kb_config['news_operate_mode'] = false;
	$mx_table_prefix = $table_prefix;
	$is_block = false;
}

// ---------------------------------------------------------------------START
// This file defines specific constants for the module
// -------------------------------------------------------------------------
define( 'PAGE_KB', -501 );
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

// Field Types
define( 'INPUT', 0 );
define( 'TEXTAREA', 1 );
define( 'RADIO', 2 );
define( 'SELECT', 3 );
define( 'SELECT_MULTIPLE', 4 );
define( 'CHECKBOX', 5 );

// **********************************************************************
// Read language definition
// **********************************************************************
if ( !file_exists( $module_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_main.' . $phpEx ) )
{
	include( $module_root_path . 'language/lang_english/lang_main.' . $phpEx );
	$link_language = 'lang_english';
}
else
{
	include( $module_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_main.' . $phpEx );
	$link_language = 'lang_' . $board_config['default_lang'];
} 

// **********************************************************************
// Read theme definition
// **********************************************************************
if ( file_exists( $module_root_path . "templates/" . $theme['template_name'] . "/images" ) )
{
	// ----------
	$current_template_images = $module_root_path . "templates/" . $theme['template_name'] . "/images" ;
	// ----------
}
else
{
	// ----------
	$current_template_images = $module_root_path . "templates/" . "subSilver" . "/images" ;
	// ----------
} 

// **********************************************************************
// Read image language in theme definition
// **********************************************************************
$link_language = file_exists( "$current_template_images/$link_language/kb.gif" ) ? $link_language : 'lang_english';

$images['icon_approve'] = "$current_template_images/icon_approve.gif";
$images['icon_unapprove'] = "$current_template_images/icon_unapprove.gif";
$images['kb_title'] = "$current_template_images/$link_language/kb.gif";
$images['kb_search'] = "$current_template_images/" . $link_language . "/icon_kb_search.gif";
$images['kb_stats'] = "$current_template_images/" . $link_language . "/icon_kb_stats.gif";
$images['kb_toplist'] = "$current_template_images/" . $link_language . "/icon_kb_toplist.gif";
$images['kb_upload'] = "$current_template_images/" . $link_language . "/icon_kb_post.gif";
$images['kb_rate'] = "$current_template_images/" . $link_language . "/icon_kb_rate.gif";
$images['kb_comment_post'] = "$current_template_images/" . $link_language . "/icon_kb_post_comment.gif";

if ( !MXBB_MODULE || MXBB_27x )
{
	$kb_module_version = "Knowledge Base MOD v. 2.0.x";
	$kb_module_author = "Haplo/Jon";
	$kb_module_orig_author = "wGEric";
}
else 
{
	$mxbb_footer_addup[] = 'mxBB Knowledge Base Module';
}




?>