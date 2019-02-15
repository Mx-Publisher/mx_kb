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
 *    $Id: install_kb_tables.php,v 1.4 2005/10/01 14:13:46 jonohlsson Exp $
 */

/**
 * This program is free software; you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation; either version 2 of the License, or
 *    (at your option) any later version.
 */

define('IN_PHPBB', 1);
$phpbb_root_path = './';
define( 'MXBB_MODULE', false ); // Switch for making this run as a phpBB mod or mxBB module

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

echo 'Knowledge Base MOD version  :: <b>' . KB_VERSION . '</b></p>' ."\n";

?>

<br clear="all" />

<h3>What are you going to do ?</h3>
This file is used to do the changes to your database (adding/modifying a table) to make the MOD working properly.
If you have any problem during this part, you can contact me to get support. Now, if you are ready, click on the button.

<br clear="all" />

<center>
	<form action="install_kb_tables.php" method=POST>
		<input type="submit" name="submit" value="submit" class="liteoption" />
	</form>
</center>

<?php

$submit = ( isset($HTTP_POST_VARS['submit']) ) ? $HTTP_POST_VARS['submit'] : 0;

if ( $submit )
{
	switch ( SQL_LAYER )
	{
		case 'mysql':
		case 'mysql4':
		    $sql[]= 'CREATE TABLE '. KB_ARTICLES_TABLE .' (
						article_id mediumint(8) unsigned NOT NULL auto_increment,
  						article_category_id mediumint(8) unsigned NOT NULL default "0",
  						article_title varchar(255) binary NOT NULL default "",
  						article_description varchar(255) binary NOT NULL default "",
  						article_date varchar(255) binary NOT NULL default "",
  						article_author_id mediumint(8) NOT NULL,
						username VARCHAR(255),
  						bbcode_uid varchar(10) binary NOT NULL default "",
  						article_body text NOT NULL,
  						article_type mediumint(8) unsigned NOT NULL default "0",
  						approved tinyint(1) unsigned NOT NULL default "0",
  						topic_id mediumint(8) unsigned NOT NULL default "0",
  						views BIGINT(8) NOT NULL DEFAULT "0",
  						article_rating double(6,4) NOT NULL default "0.0000",
  						article_totalvotes int(255) NOT NULL default "0",
  						KEY article_id (article_id)
				    ) TYPE=MyISAM;';
					
			$sql[] = 'INSERT INTO '. KB_ARTICLES_TABLE .' VALUES (
				   	      1, 1, "Test Article", "This is a test article for your KB", "1057708235", 2, "", "93074f48a9", "This is a test article for your Knowledge Base. This MOD is based on code written by wGEric &lt; eric@egcnetwork.com &gt; (Eric Faerber) - http://eric.best-1.biz/, now supervised by _Haplo &lt; jonohlsson@hotmail.com &gt; (Jon Ohlsson) - http://www.mx-system.com/ \r\n\r\nBe sure you add categories and article types in the ACP and also change the Configuration to your liking.\r\n\r\nHave fun and enjoy your new Knowledge Base!  :D", 1, 1, 0, 0,0,0
					  );';

			$sql[] = 'CREATE TABLE '.KB_CATEGORIES_TABLE.' (
  			 	   	    category_id mediumint(8) unsigned NOT NULL auto_increment, 
  						category_name VARCHAR(255) binary NOT NULL, 
  						category_details VARCHAR(255) binary NOT NULL, 
 						number_articles mediumint(8) unsigned NOT NULL,
						parent mediumint(8) unsigned,
						cat_order mediumint(8) unsigned NOT NULL,
					  	auth_view tinyint(3) NOT NULL DEFAULT "0",
						auth_post tinyint(3) NOT NULL DEFAULT "0",
						auth_rate tinyint(3) NOT NULL DEFAULT "0",
						auth_comment tinyint(3) NOT NULL DEFAULT "0",
						auth_edit tinyint(3) NOT NULL DEFAULT "0",
						auth_delete tinyint(3) NOT NULL DEFAULT "2",
						auth_approval tinyint(3) NOT NULL DEFAULT "0",
						auth_approval_edit tinyint(3) NOT NULL DEFAULT "0",
						auth_view_groups varchar(255),
						auth_post_groups varchar(255),
						auth_rate_groups varchar(255),
						auth_comment_groups varchar(255),
						auth_edit_groups varchar(255),
						auth_delete_groups varchar(255),
						auth_approval_groups varchar(255),
						auth_approval_edit_groups varchar(255),
						auth_moderator_groups varchar(255),
						comments_forum_id tinyint(3) NOT NULL DEFAULT "-1",
  						KEY category_id (category_id)
					) TYPE=MyISAM';
					
			  
		    $sql[] = 'INSERT INTO '.KB_CATEGORIES_TABLE.' VALUES (1, "Test Category 1", "This is a test category", "0", "0", "10", "0", "0", "0", "0", "0", "2", "0", "0", "", "", "", "", "", "", "", "", "", "0" );';

			$sql[] = 'CREATE TABLE '.KB_CONFIG_TABLE.' (
			  			config_name VARCHAR(255) NOT NULL default "", 
						config_value varchar(255) NOT NULL default "",
						PRIMARY KEY  (config_name)
						) TYPE=MyISAM';
			
			$sql[] = 'INSERT INTO '.KB_CONFIG_TABLE.' (config_name, config_value) VALUES ("allow_new", "1")';
			$sql[] = 'INSERT INTO '.KB_CONFIG_TABLE.' (config_name, config_value) VALUES ("notify", "1")';
			$sql[] = 'INSERT INTO '.KB_CONFIG_TABLE.' (config_name, config_value) VALUES ("admin_id", "2")';
			$sql[] = 'INSERT INTO '.KB_CONFIG_TABLE.' (config_name, config_value) values("show_pretext",0);';
			$sql[] = 'INSERT INTO '.KB_CONFIG_TABLE.' (config_name, config_value) values("pt_header","Article Submission Instructions");';
			$sql[] = 'INSERT INTO '.KB_CONFIG_TABLE.' (config_name, config_value) values("pt_body","Please check your references and include as much information as you can.");';
			$sql[] = 'INSERT INTO '.KB_CONFIG_TABLE.' (config_name, config_value) VALUES ("use_comments", "1");';
			$sql[] = 'INSERT INTO '.KB_CONFIG_TABLE.' (config_name, config_value) VALUES ("del_topic", "1");';
			$sql[] = 'INSERT INTO '.KB_CONFIG_TABLE.' (config_name, config_value) VALUES ("use_ratings", "0");';
			$sql[] = 'INSERT INTO '.KB_CONFIG_TABLE.' (config_name, config_value) VALUES ("comments_show", "1");';
			$sql[] = 'INSERT INTO '.KB_CONFIG_TABLE.' (config_name, config_value) VALUES ("bump_post", "1");';
			$sql[] = 'INSERT INTO '.KB_CONFIG_TABLE.' (config_name, config_value) VALUES ("stats_list", "1");';
			$sql[] = 'INSERT INTO '.KB_CONFIG_TABLE.' (config_name, config_value) VALUES ("header_banner", "1");';

			$sql[] = 'INSERT INTO '.KB_CONFIG_TABLE.' (config_name, config_value) VALUES ("votes_check_userid", "1");';
			$sql[] = 'INSERT INTO '.KB_CONFIG_TABLE.' (config_name, config_value) VALUES ("votes_check_ip", "1");';
			$sql[] = 'INSERT INTO '.KB_CONFIG_TABLE.' (config_name, config_value) VALUES ("art_pagination", "5");';

			$sql[] = 'INSERT INTO '.KB_CONFIG_TABLE.' (config_name, config_value) VALUES ("comments_pagination", "5");';
			$sql[] = 'INSERT INTO '.KB_CONFIG_TABLE.' (config_name, config_value) VALUES ("news_sort", "Alphabetic");';
			$sql[] = 'INSERT INTO '.KB_CONFIG_TABLE.' (config_name, config_value) VALUES ("news_sort_par", "ASC");';

			$sql[] = 'INSERT INTO '.KB_CONFIG_TABLE.' (config_name, config_value) VALUES ("wysiwyg", "0");';
			$sql[] = 'INSERT INTO '.KB_CONFIG_TABLE.' (config_name, config_value) VALUES ("wysiwyg_path", "modules/");';
			$sql[] = 'INSERT INTO '.KB_CONFIG_TABLE.' (config_name, config_value) VALUES ("allow_html", "1");';
			$sql[] = 'INSERT INTO '.KB_CONFIG_TABLE.' (config_name, config_value) VALUES ("allow_bbcode", "1");';
			$sql[] = 'INSERT INTO '.KB_CONFIG_TABLE.' (config_name, config_value) VALUES ("allow_smilies", "1");';
			$sql[] = 'INSERT INTO '.KB_CONFIG_TABLE.' (config_name, config_value) VALUES ("formatting_fixup", "0");';
			$sql[] = 'INSERT INTO '.KB_CONFIG_TABLE.' (config_name, config_value) VALUES ("allowed_html_tags", "b,i,u,a");';
					
			$sql[] = 'CREATE TABLE ' . KB_TYPES_TABLE . ' (
  				 	       	id mediumint(8) unsigned NOT NULL auto_increment, 
  						   	type varchar(255) binary DEFAULT "" NOT NULL, 
  						   	KEY id (id)
					  ) TYPE=MyISAM';
					  
			$sql[] = 'INSERT INTO '. KB_TYPES_TABLE . ' VALUES (1, "Test Type 1")';

			$sql[] = 'CREATE TABLE '. KB_VOTES_TABLE . ' (
  							votes_ip varchar(50) NOT NULL default "0",
  							votes_userid int(50) NOT NULL default "0",
  							votes_file int(50) NOT NULL default "0"
					  ) TYPE=MyISAM';

			$sql[] = 'CREATE TABLE ' . KB_SEARCH_TABLE . ' (
	  				 	    search_id int(11) unsigned NOT NULL default "0",
	  						session_id varchar(32) NOT NULL default "",
	  						search_array text NOT NULL,
	  						PRIMARY KEY  (search_id),
	  						KEY session_id (session_id)
					   ) TYPE=MyISAM;';

			$sql[] = 'CREATE TABLE ' . KB_WORD_TABLE . ' (
	  				 	   	word_text varchar(50) binary NOT NULL default "",
		  					word_id mediumint(8) unsigned NOT NULL auto_increment,
		  					word_common tinyint(1) unsigned NOT NULL default "0",
		  					PRIMARY KEY  (word_text),
		  					KEY word_id (word_id)
					   ) TYPE=MyISAM;';

			$sql[] = 'CREATE TABLE ' . KB_MATCH_TABLE . ' (
		  				 	article_id mediumint(8) unsigned NOT NULL default "0",
		  					word_id mediumint(8) unsigned NOT NULL default "0",
		  					title_match tinyint(1) NOT NULL default "0",
		  					KEY post_id (article_id),
		  					KEY word_id (word_id)
					  	) TYPE=MyISAM;';	

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

		$error_message = "";

		if ( $error_code )
		{
			$error_message .= "<br />At least one query failed : check the error message and contact me if you need help to resolve the problem. <br />";
		}
		else
		{
			$error_message .= "<br />All the queries have been successfully done - Enjoy. <br />";
		}

		echo("\n<br />\n<b>COMPLETE - INSTALLATION IS ENDED</b><br />\n");
		echo($error_message . "<br />");
		echo("<br /><b>NOW, DELETE THIS FILE FROM YOUR SERVER</b><br />\n");
}

?>
</body>
</html>
