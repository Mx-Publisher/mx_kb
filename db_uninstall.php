<?php
/**
*
* @package MX-Publisher Module - mx_kb
* @version $Id: db_uninstall.php,v 1.20 2008/06/03 20:08:19 jonohlsson Exp $
* @copyright (c) 2002-2006 [Jon Ohlsson, Mohd Basri, wGEric, PHP Arena, pafileDB, CRLin] MX-Publisher Project Team
* @license http://opensource.org/licenses/gpl-license.php GNU General Public License v2
*
*/

define( 'IN_PORTAL', true );
if ( !defined( 'IN_ADMIN' ) )
{
	$mx_root_path = './../../';
	$phpEx = substr(strrchr(__FILE__, '.'), 1);
	include( $mx_root_path . 'common.' . $phpEx );
	// Start session management
	$mx_user->init($user_ip, PAGE_INDEX);

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

	//"DROP TABLE " . $mx_table_prefix . "kb_wordlist ",

	//"DROP TABLE " . $mx_table_prefix . "kb_results ",

	//"DROP TABLE " . $mx_table_prefix . "kb_wordmatch ",

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