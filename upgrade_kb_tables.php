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
 *    $Id: upgrade_kb_tables.php,v 1.4 2005/10/01 14:13:46 jonohlsson Exp $
 */

/**
 * This program is free software; you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation; either version 2 of the License, or
 *    (at your option) any later version.
 */

define('IN_PHPBB', 1);
define( 'MXBB_MODULE', false ); // Switch for making this run as a phpBB mod or mxBB module
$phpbb_root_path = './';

include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'config.'.$phpEx);
include($phpbb_root_path . 'includes/constants.'.$phpEx);
include($phpbb_root_path . 'common.'.$phpEx);	
include($phpbb_root_path . 'includes/db.'.$phpEx);
include($phpbb_root_path . 'includes/kb_constants.'.$phpEx);

// A few additional checks to avoid blunders during install/upgrade
define( 'phpBBroot_ok', file_exists( $phpbb_root_path . 'viewtopic.php' ) );
define( 'kbconstants_ok', defined( 'KB_CUSTOM_TABLE' ) );

if ( !phpBBroot_ok ) 
{
	message_die(GENERAL_ERROR, "This install/upgrade script should be uploaded to the phpBB root.");
}

if ( !kbconstants_ok ) 
{
	message_die(GENERAL_ERROR, "You haven't uploaded latest kb_constants.php.");
}

$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);

if ( $userdata['user_level'] != ADMIN )
{
	message_die(GENERAL_ERROR, "You must be an Administrator to use this page.");
}

define('KB_VERSION','KB MOD 2.0.2');
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<title>Install File for MOD's</title>
<head>
<meta http-equiv="Content-Type" content="text/html;">
<meta http-equiv="Content-Style-Type" content="text/css">
<style type="text/css">

</style>
</head>
<body bgcolor="#FFFFFF" text="#000000" link="#006699" vlink="#5584AA">

<table width="100%" border="0" cellspacing="0" cellpadding="10" align="center"> 
	<tr>
		<td><table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td><a href="./index.php"><img src="./templates/subSilver/images/logo_phpBB.gif" border="0" alt="Forum Home" vspace="1" /></a></td>
				<td align="center" width="100%" valign="middle"><span class="maintitle">Installing Knowledge Base</span></td>
			</tr>
		</table></td>
	</tr>
</table>

<br clear="all" />

<h2>Information</h2>

<?php

// get the phpBB version
$sql = "SELECT config_value  
	FROM " . CONFIG_TABLE . " 
	WHERE config_name = 'version'";
if ( !($result = $db->sql_query($sql)) )
{
	die("Couldn't obtain version info");
}
$row = $db->sql_fetchrow($result);
$phpBB_version = $row['config_value'] ;
$sql = array();

// output some info
echo '<p>Database type &nbsp;  :: <b>' . SQL_LAYER . '</b><br />';

echo 'phpBB version &nbsp;  :: <b>2' . $phpBB_version . '</b><br />';

echo 'Upgrading Knowledge Base to  :: <b>' . KB_VERSION . '</b></p> From KB Beta 0.7.6 + mxaddon 1.x' ."\n";

?>

<br clear="all" />

<h3>What are you going to do ?</h3>
This file is used to do the changes to your database (adding/modifying a table) to make the MOD working properly.
If you have any problem during this part, you can contact me to get support. Now, if you are ready, click on the button.

<br clear="all" />

<center>
	<form action="upgrade_kb_tables.php" method=POST>
		<input type="submit" name="submit" value="submit" class="liteoption" />
	</form>
</center>

<?php

$submit = ( isset($HTTP_POST_VARS['submit']) ) ? $HTTP_POST_VARS['submit'] : 0;
$res_message = "";

// Upgrade checks
	$upgrade_101 = 0;
	$upgrade_102 = 0;
	$upgrade_103 = 0;
	$upgrade_200 = 0;
	$upgrade_201 = 0;
	$upgrade_202 = 0;
	
// validate before 1.01
if( !$result = $db->sql_query("SELECT article_rating from ".KB_ARTICLES_TABLE))
{
	$upgrade_101 = 1;
	$res_message .= "Upgrading to v. 1.01...<br />";
}
else
{
	$res_message .= "v. 1.01 : installed<br />";
}

// validate before 1.02
if( !$result = $db->sql_query("SELECT votes_userid from ".KB_VOTES_TABLE))
{
	$upgrade_102 = 1;
	$res_message .= "Upgrading to v. 1.02...<br />";
}
else
{
	$res_message .= "v. 1.02 : installed<br />";
}

// validate before 1.03
$result = $db->sql_query( "SELECT config_value from " . KB_CONFIG_TABLE . " WHERE config_name = 'comments_pagination'" );
if (  $db->sql_numrows( $result ) == 0 )
{
	$upgrade_103 = 1;
	$res_message .= "Upgrading to v. 1.03...<br />";
}
else
{
	$res_message .= "v. 1.03 : installed <br />";
}

// validate before 2.00
$result = $db->sql_query( "SELECT config_value from " . KB_CONFIG_TABLE . " WHERE config_name = 'wysiwyg'" );
if(  $db->sql_numrows( $result ) == 0 )
{
	$upgrade_200 = 1;
	$res_message .= "Upgrading to v. 2.00...<br />";
}
else
{
	$res_message .= "v. 2.00 : installed <br />";
}

// validate before 2.01
$result = $db->sql_query( "SELECT config_value from " . KB_CONFIG_TABLE . " WHERE config_name = 'wysiwyg_path'" );
if(  $db->sql_numrows( $result ) == 0 )
{
	$upgrade_201 = 1;
	$res_message .= "Upgrading to v. 2.01...<br />";
}
else
{
	$res_message .= "v. 2.01 : installed <br />";
}

	$upgrade_202 = 1;
	$res_message .= "Upgrading to v. 2.02...<br />";
	
if ( $submit )
{
	
	switch ( SQL_LAYER )
	{
		case 'mysql':
		case 'mysql4':
			if ( $upgrade_101 == 1 )
			{
		    	$sql[] = 'ALTER TABLE ' . KB_ARTICLES_TABLE . ' ADD article_rating double(6,4) NOT NULL default "0.0000" ;';
		    	$sql[] = 'ALTER TABLE ' . KB_ARTICLES_TABLE . ' ADD article_totalvotes int(255) NOT NULL default "0" ;';
		    	
				$sql[] = 'INSERT INTO '.KB_CONFIG_TABLE.' (config_name, config_value) VALUES ("allow_rating", "0") ;';
				$sql[] = 'INSERT INTO '.KB_CONFIG_TABLE.' (config_name, config_value) VALUES ("allow_anonymos_rating", "0") ;';
				$sql[] = 'INSERT INTO '.KB_CONFIG_TABLE.' (config_name, config_value) VALUES ("comments_show", "1") ;';
				$sql[] = 'INSERT INTO '.KB_CONFIG_TABLE.' (config_name, config_value) VALUES ("mod_group", "0") ;';
				$sql[] = 'INSERT INTO '.KB_CONFIG_TABLE.' (config_name, config_value) VALUES ("bump_post", "1") ;';
				$sql[] = 'INSERT INTO '.KB_CONFIG_TABLE.' (config_name, config_value) VALUES ("stats_list", "1") ;';
				$sql[] = 'INSERT INTO '.KB_CONFIG_TABLE.' (config_name, config_value) VALUES ("header_banner", "1") ;';
				
				$sql[] = 'CREATE TABLE '.KB_VOTES_TABLE.' (
  					votes_ip varchar(50) NOT NULL default "0",
  					votes_userid int(50) NOT NULL default "0",
  					votes_file int(50) NOT NULL default "0"
					  ) TYPE=MyISAM';
			}
			
			if ( $upgrade_102 == 1 )
			{
				$sql[] = 'INSERT INTO '.KB_CONFIG_TABLE.' (config_name, config_value) VALUES ("votes_check_userid", "1");';
				$sql[] = 'INSERT INTO '.KB_CONFIG_TABLE.' (config_name, config_value) VALUES ("votes_check_ip", "1");';
				$sql[] = 'INSERT INTO '.KB_CONFIG_TABLE.' (config_name, config_value) VALUES ("art_pagination", "5");';
		    	// $sql[] = 'ALTER TABLE ' . KB_VOTES_TABLE . ' ADD votes_userid int(50) NOT NULL default "0" AFTER votes_ip';
			}


			if ( $upgrade_103 == 1 )
			{
				$sql[] = 'INSERT INTO '.KB_CONFIG_TABLE.' (config_name, config_value) VALUES ("comments_pagination", "5");';
				$sql[] = 'INSERT INTO '.KB_CONFIG_TABLE.' (config_name, config_value) VALUES ("news_sort", "Alphabetic");';
				$sql[] = 'INSERT INTO '.KB_CONFIG_TABLE.' (config_name, config_value) VALUES ("news_sort_par", "ASC");';
			}
			
			if ( $upgrade_200 == 1 )
			{	
				$sql[] = 'ALTER TABLE ' . KB_CATEGORIES_TABLE . ' ADD auth_view tinyint(3) NOT NULL DEFAULT "0" ;';
				$sql[] = 'ALTER TABLE ' . KB_CATEGORIES_TABLE . ' ADD auth_post tinyint(3) NOT NULL DEFAULT "0" ;';
				$sql[] = 'ALTER TABLE ' . KB_CATEGORIES_TABLE . ' ADD auth_rate tinyint(3) NOT NULL DEFAULT "0" ;';
				$sql[] = 'ALTER TABLE ' . KB_CATEGORIES_TABLE . ' ADD auth_comment tinyint(3) NOT NULL DEFAULT "0" ;';
				$sql[] = 'ALTER TABLE ' . KB_CATEGORIES_TABLE . ' ADD auth_edit tinyint(3) NOT NULL DEFAULT "0" ;';
				$sql[] = 'ALTER TABLE ' . KB_CATEGORIES_TABLE . ' ADD auth_delete tinyint(3) NOT NULL DEFAULT "2" ;';
				$sql[] = 'ALTER TABLE ' . KB_CATEGORIES_TABLE . ' ADD auth_approval tinyint(3) NOT NULL DEFAULT "0" ;';
				$sql[] = 'ALTER TABLE ' . KB_CATEGORIES_TABLE . ' ADD auth_approval_edit tinyint(3) NOT NULL DEFAULT "0" ;';
				$sql[] = 'ALTER TABLE ' . KB_CATEGORIES_TABLE . ' ADD auth_view_groups varchar(255) ;';
				$sql[] = 'ALTER TABLE ' . KB_CATEGORIES_TABLE . ' ADD auth_post_groups varchar(255) ;';
				$sql[] = 'ALTER TABLE ' . KB_CATEGORIES_TABLE . ' ADD auth_rate_groups varchar(255) ;';
				$sql[] = 'ALTER TABLE ' . KB_CATEGORIES_TABLE . ' ADD auth_comment_groups varchar(255) ;';
				$sql[] = 'ALTER TABLE ' . KB_CATEGORIES_TABLE . ' ADD auth_edit_groups varchar(255) ;';
				$sql[] = 'ALTER TABLE ' . KB_CATEGORIES_TABLE . ' ADD auth_delete_groups varchar(255) ;';
				$sql[] = 'ALTER TABLE ' . KB_CATEGORIES_TABLE . ' ADD auth_approval_groups varchar(255) ;';
				$sql[] = 'ALTER TABLE ' . KB_CATEGORIES_TABLE . ' ADD auth_approval_edit_groups varchar(255) ;';
				$sql[] = 'ALTER TABLE ' . KB_CATEGORIES_TABLE . ' ADD auth_moderator_groups varchar(255) ;';
				$sql[] = 'ALTER TABLE ' . KB_CATEGORIES_TABLE . ' ADD comments_forum_id tinyint(3) NOT NULL DEFAULT "-1" ;';
		
				$sql[] = 'UPDATE ' . KB_CONFIG_TABLE . '
		        				SET config_name  = "use_comments",
				 					config_value = "1"
		        				WHERE config_name = "comments" ;';
								
				$sql[] = 'UPDATE ' . KB_CONFIG_TABLE . '
		        				SET config_name  = "use_ratings",
				 					config_value = "1"
		        				WHERE config_name = "allow_rating" ;';	
									
				$sql[] = 'INSERT INTO '.KB_CONFIG_TABLE.' (config_name, config_value) VALUES ("wysiwyg", "0");';
				$sql[] = 'INSERT INTO '.KB_CONFIG_TABLE.' (config_name, config_value) VALUES ("allow_html", "1");';
				$sql[] = 'INSERT INTO '.KB_CONFIG_TABLE.' (config_name, config_value) VALUES ("allow_bbcode", "1");';
				$sql[] = 'INSERT INTO '.KB_CONFIG_TABLE.' (config_name, config_value) VALUES ("allow_smilies", "1");';
				$sql[] = 'INSERT INTO '.KB_CONFIG_TABLE.' (config_name, config_value) VALUES ("allowed_html_tags", "b,i,u,a");';
				
				$sql[] = 'CREATE TABLE ' . KB_CUSTOM_TABLE . ' (
								  custom_id int(50) NOT NULL auto_increment,
								  custom_name text NOT NULL,
								  custom_description text NOT NULL,
								  data text NOT NULL,
								  field_order int(20) NOT NULL default "0",
								  field_type tinyint(2) NOT NULL default "0",
								  regex varchar(255) NOT NULL default "",
								  PRIMARY KEY  (custom_id)
				) TYPE=MyISAM;'; 
				
				$sql[] = 'CREATE TABLE ' . KB_CUSTOM_DATA_TABLE . ' (
								  customdata_file int(50) NOT NULL default "0",
								  customdata_custom int(50) NOT NULL default "0",
								  data text NOT NULL
				) TYPE=MyISAM;';
				
			}
			
			if ( $upgrade_201 == 1 )
			{
				$sql[] = 'INSERT INTO '.KB_CONFIG_TABLE.' (config_name, config_value) VALUES ("wysiwyg_path", "modules/");';
				$sql[] = 'INSERT INTO '.KB_CONFIG_TABLE.' (config_name, config_value) VALUES ("formatting_fixup", "0");';
				$sql[] = 'ALTER TABLE ' . KB_ARTICLES_TABLE . ' MODIFY article_author_id mediumint(8) NOT NULL ;';
			}
			
			if ( $upgrade_202 == 1 ) // Old fix for those upgraded from old old version
			{
				// Upgrade the config table to avoid duplicate entries
		    	$sql[] = 'ALTER TABLE '. KB_CONFIG_TABLE.' MODIFY config_name VARCHAR(255) NOT NULL default "" ;';
		    	$sql[] = 'ALTER TABLE '. KB_CONFIG_TABLE.' MODIFY config_value VARCHAR(255) NOT NULL default "" ;';
				$sql[] = 'ALTER TABLE '. KB_CONFIG_TABLE.' DROP PRIMARY KEY, ADD PRIMARY KEY (config_name) ;';
			}
			
			break;
		
		case 'mssql':
		case 'mssql-odbc':
			
		default:
			die("/!\ No Database Abstraction Layer (DBAL) found /!\\");
			break;
	}
	echo("<h2>Adding/modifying tables to your database</h2>\n");
	for ($i=0; $i < count($sql); $i++)
	{
		echo("Running query :: " . $sql[$i]);
		flush();

		if ( !($result = $db->sql_query($sql[$i])) )
		{
			$error_code = TRUE;
			$error = $db->sql_error();

			echo(" -> <b><span class=\"error\">ERROR - QUERY FAILED</span></b> ----> <u>" . $error['message'] . "</u><br /><br />\n\n");
		}
		else
		{
			echo(" -> <b><span class=\"ok\">GOOD - QUERY OK</span></b><br /><br />\n\n");
		}
	}


		if ( $error_code )
		{
			$res_message .= "<br />At least one query failed : check the error message and contact me if you need help to resolve the problem. <br />";
		}
		else
		{
			$res_message .= "<br />All the queries have been successfully done - Enjoy. <br />";
		}

		echo("\n<br />\n<b>COMPLETE - INSTALLATION IS ENDED</b><br />\n");
		echo($res_message . "<br />");
		echo("<br /><b>NOW, DELETE THIS FILE FROM YOUR SERVER</b><br />\n");
}

?>
</body>
</html>
