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
 *    $Id: db_uninstall.php,v 1.14 2005/12/08 15:04:26 jonohlsson Exp $
 */

/**
 * This program is free software; you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation; either version 2 of the License, or
 *    (at your option) any later version.
 */
 
define( 'IN_PORTAL', true );
if ( !defined( 'IN_ADMIN' ) )
{
	$mx_root_path = './../../';
	include( $mx_root_path . 'extension.inc' );
	include( $mx_root_path . 'common.' . $phpEx ); 
	// Start session management
	$userdata = session_pagestart( $user_ip, PAGE_INDEX );
	mx_init_userprefs( $userdata );

	if ( !$userdata['session_logged_in'] )
	{
		die( "Hacking attempt(1)" );
	}

	if ( $userdata['user_level'] != ADMIN )
	{
		die( "Hacking attempt(2)" );
	} 
	// End session management
}

// For compatibility with core 2.7.+
define( 'MXBB_27x', file_exists( $mx_root_path . 'mx_login.php' ) );

if ( MXBB_27x )
{
	include_once( $mx_root_path . 'modules/mx_kb/includes/functions_kb_mx.' . $phpEx );
}

$sql = array( 
	"DROP TABLE " . $mx_table_prefix . "kb_articles ",

	"DROP TABLE " . $mx_table_prefix . "kb_categories ",

	"DROP TABLE " . $mx_table_prefix . "kb_config ",

	"DROP TABLE " . $mx_table_prefix . "kb_types ",

	"DROP TABLE " . $mx_table_prefix . "kb_wordlist ",

	"DROP TABLE " . $mx_table_prefix . "kb_results ",

	"DROP TABLE " . $mx_table_prefix . "kb_wordmatch ",

	"DROP TABLE " . $mx_table_prefix . "kb_votes ",
	 
	"DROP TABLE " . $mx_table_prefix . "kb_custom ",
	
	"DROP TABLE " . $mx_table_prefix . "kb_comments ",
	 
	"DROP TABLE " . $mx_table_prefix . "kb_customdata " 

	);

echo "<br /><br />";
echo "<table  width=\"90%\" align=\"center\" cellpadding=\"4\" cellspacing=\"1\" border=\"0\" class=\"forumline\">";
echo "<tr><th class=\"thHead\" align=\"center\">Module Installation/Upgrading/Uninstalling Information - module specific db tables</th></tr>";
echo "<tr><td class=\"row1\"  align=\"left\"><span class=\"gen\">" . mx_do_install_upgrade( $sql ) . "</span></td></tr>";
echo "</table><br />";

?>