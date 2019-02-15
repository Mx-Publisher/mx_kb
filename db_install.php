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
 *    $Id: db_install.php,v 1.34 2005/12/08 15:04:26 jonohlsson Exp $
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

$mx_module_version = 'mxBB Knowledge Base Module v. 2.0.2';
$mx_module_copy = 'Based on phpBB mod by <a href="http://www.phpbb.com/phpBB/viewtopic.php?t=89202" target="_phpbb" >wGEric/Jon</a>';
	
// For compatibility with core 2.7.+
define( 'MXBB_27x', file_exists( $mx_root_path . 'mx_login.php' ) );

if ( MXBB_27x )
{
	include_once( $mx_root_path . 'modules/mx_kb/includes/functions_kb_mx.' . $phpEx );
}

// If fresh install
if ( !$result = $db->sql_query( "SELECT config_name from " . $mx_table_prefix . "kb_config" ) )
{
	$message = "<b>This is a new module installation!</b><br/><br/>";

	$sql = array( "DROP TABLE IF EXISTS " . $mx_table_prefix . "kb_articles ",
		"DROP TABLE IF EXISTS " . $mx_table_prefix . "kb_categories ",
		"DROP TABLE IF EXISTS " . $mx_table_prefix . "kb_config ",
		"DROP TABLE IF EXISTS " . $mx_table_prefix . "kb_types ",
		"DROP TABLE IF EXISTS " . $mx_table_prefix . "kb_wordlist ",
		"DROP TABLE IF EXISTS " . $mx_table_prefix . "kb_results ",
		"DROP TABLE IF EXISTS " . $mx_table_prefix . "kb_wordmatch ",
		"DROP TABLE IF EXISTS " . $mx_table_prefix . "kb_votes ",

		"CREATE TABLE " . $mx_table_prefix . "kb_articles (
						article_id mediumint(8) unsigned NOT NULL auto_increment,
  						article_category_id mediumint(8) unsigned NOT NULL default '0',
  						article_title varchar(255) binary NOT NULL default '',
  						article_description varchar(255) binary NOT NULL default '',
  						article_date varchar(255) binary NOT NULL default '',
  						article_author_id mediumint(8) NOT NULL,
						username VARCHAR(255),
  						bbcode_uid varchar(10) binary NOT NULL default '',
  						article_body text NOT NULL,
  						article_type mediumint(8) unsigned NOT NULL default '0',
  						approved tinyint(1) unsigned NOT NULL default '0',
  						topic_id mediumint(8) unsigned NOT NULL default '0',
  						views BIGINT(8) NOT NULL DEFAULT '0',
  						article_rating double(6,4) NOT NULL default '0.0000',
  						article_totalvotes int(255) NOT NULL default '0',
  						KEY article_id (article_id)
		)",

		"CREATE TABLE " . $mx_table_prefix . "kb_categories (
  			 	   	    category_id mediumint(8) unsigned NOT NULL auto_increment, 
  						category_name VARCHAR(255) binary NOT NULL, 
  						category_details VARCHAR(255) binary NOT NULL, 
						parent mediumint(8) unsigned,
						cat_order mediumint(8) unsigned NOT NULL,
					    
						cat_allow_comments tinyint(2) NOT NULL default '1',	
						internal_comments tinyint(2) NOT NULL default '-1',
						autogenerate_comments tinyint(2) NOT NULL default '-1',
						comments_forum_id mediumint(8) NOT NULL DEFAULT '-1',
		
					    cat_allow_ratings tinyint(2) NOT NULL default '-1',
		
					    show_pretext tinyint(2) NOT NULL default '-1',
		
						notify tinyint(2) NOT NULL default '-1',
						notify_group mediumint(8) unsigned NOT NULL default '-1',		
						
 						number_articles mediumint(8) NOT NULL,
						cat_last_article_id mediumint(8) unsigned NOT NULL default '0',
						cat_last_article_name varchar(255) NOT NULL default '',
						cat_last_article_time INT(50) UNSIGNED DEFAULT '0' NOT NULL,
		
					  	auth_view tinyint(3) NOT NULL DEFAULT '0',
						auth_post tinyint(3) NOT NULL DEFAULT '0',
						auth_rate tinyint(3) NOT NULL DEFAULT '0',
						auth_comment tinyint(3) NOT NULL DEFAULT '0',
						auth_edit tinyint(3) NOT NULL DEFAULT '0',
						auth_delete tinyint(3) NOT NULL DEFAULT '2',
						auth_approval tinyint(3) NOT NULL DEFAULT '0',
						auth_approval_edit tinyint(3) NOT NULL DEFAULT '0',
		
						auth_view_groups smallint(5) NOT NULL default '0',
						auth_post_groups smallint(5) NOT NULL default '0',
						auth_rate_groups smallint(5) NOT NULL default '0',
						auth_comment_groups smallint(5) NOT NULL default '0',
						auth_edit_groups smallint(5) NOT NULL default '0',
						auth_delete_groups smallint(5) NOT NULL default '0',
						auth_approval_groups smallint(5) NOT NULL default '0',
						auth_approval_edit_groups smallint(5) NOT NULL default '0',
		
						auth_moderator_groups smallint(5) NOT NULL default '0',
						
  						KEY category_id (category_id)
		)",

		"INSERT INTO " . $mx_table_prefix . "kb_categories VALUES (1, 'Test Category 1', 'This is a test category', '0', '10', '-1', '-1','-1','-1','-1','-1','-1','-1', '', '0', '', '0', '0', '0', '0', '0', '0', '2', '0', '0', '', '', '', '', '', '', '', '', '0' )",

		"CREATE TABLE " . $mx_table_prefix . "kb_config (
  			 	   	    config_name VARCHAR(255) NOT NULL default '', 
						config_value varchar(255) NOT NULL default '',
						PRIMARY KEY  (config_name)
		)",

		//
		// Insert Configs
		//
		
		// General
		"INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('enable_module', '1')", // allow_new
		"INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('module_name', 'Knowledge Base')", // NEW
		"INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('wysiwyg_path', 'modules/')",

		// Articles
		"INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('formatting_wordwrap', '1')", // formatting_fixup
		"INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('formatting_image_resize', '300')", // NEW
		"INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('formatting_truncate_links', '1')", // NEW
		"INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('allow_wysiwyg', '0')", // NEW
		"INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('allow_html', '1')",
		"INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('allow_bbcode', '1')",
		"INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('allow_smilies', '1')",
		"INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('allowed_html_tags', 'b,i,u,a')",
		"INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('allow_links', '1')",
		"INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('allow_images', '0')",
		"INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('no_image_message', '[No image please]')",
		"INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('no_link_message', '[No links please]')",
		"INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('max_subject_chars', '100')", // NEW
		"INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('max_desc_chars', '500')", // NEW				
		"INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('max_chars', '0')", // NEW				
		
		// Appearance
		"INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('stats_list', '1')",
		"INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('header_banner', '1')", 
		"INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('pagination', '5')",  // art_pagination
		"INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('sort_method', 'Alphabetic')", // news_sort
		"INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('sort_order', 'ASC')", // news_sort_par
		
		// Comments
		"INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('use_comments', '1')", // comments_show
		"INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('internal_comments', '1')", // NEW
		"INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('formatting_comment_wordwrap', '1')", // formatting_comment_fixup
		"INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('formatting_comment_image_resize', '300')", // NEW
		"INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('formatting_comment_truncate_links', '1')", // NEW
		"INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('max_comment_subject_chars', '50')", // NEW
		"INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('max_comment_chars', '5000')", // NEW
		"INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('allow_comment_wysiwyg', '0')",
		"INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('allow_comment_html', '1')",
		"INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('allow_comment_bbcode', '1')",		
		"INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('allow_comment_smilies', '1')",
		"INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('allow_comment_links', '1')",
		"INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('allow_comment_images', '0')",
		"INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('no_comment_image_message', '[No image please]')",
		"INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('no_comment_link_message', '[No links please]')",
		"INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('allowed_comment_html_tags', 'b,i,u,a')", // NEW
		"INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('del_topic', '1')",
		"INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('autogenerate_comments', '1')",	// bump_post
		"INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('comments_pagination', '5')",
		
		// Ratings
		"INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('use_ratings', '0')",
		"INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('votes_check_userid', '1')",
		"INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('votes_check_ip', '1')",	
				
		// Instructions
		"INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('show_pretext',0)",
		"INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('pt_header','Article Submission Instructions')",
		"INSERT INTO " . $mx_table_prefix . "kb_config values ('pt_body','Please check your references and include as much information as you can.')",
		
		// Notifications
		"INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('notify', 'pm')", // updated
		"INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('notify_group', '0')",	// admin_id	

		// --------------------------------------------------------
		// Table structure for table `phpbb_pa_comments`
		"CREATE TABLE " . $mx_table_prefix . "kb_comments (
			  comments_id int(10) NOT NULL auto_increment,
			  article_id int(10) NOT NULL default '0',
			  comments_text text NOT NULL,
			  comments_title text NOT NULL,
			  comments_time int(50) NOT NULL default '0',
			  comment_bbcode_uid varchar(10) default NULL,
			  poster_id mediumint(8) NOT NULL default '0',
			  PRIMARY KEY  (comments_id),
			  KEY comments_id (comments_id),
			  FULLTEXT KEY comment_bbcode_uid (comment_bbcode_uid)
		)", 
				
		"CREATE TABLE " . $mx_table_prefix . "kb_types (
  				 	       id mediumint(8) unsigned NOT NULL auto_increment, 
  						   type varchar(255) binary DEFAULT '' NOT NULL, 
  						   KEY id (id)
		)",

		"INSERT INTO " . $mx_table_prefix . "kb_types VALUES (1, 'Test Type 1')",

		"CREATE TABLE " . $mx_table_prefix . "kb_votes (
  						votes_ip varchar(50) NOT NULL default '0',
  						votes_userid varchar(50) NOT NULL default '0',
  						votes_file int(50) NOT NULL default '0'
		)",

		// --------------------------------------------------------
		// Table structure for table `phpbb_pa_custom`
		"CREATE TABLE " . $mx_table_prefix . "kb_custom (
						  custom_id int(50) NOT NULL auto_increment,
						  custom_name text NOT NULL,
						  custom_description text NOT NULL,
						  data text NOT NULL,
						  field_order int(20) NOT NULL default '0',
						  field_type tinyint(2) NOT NULL default '0',
						  regex varchar(255) NOT NULL default '',
						  PRIMARY KEY  (custom_id)
		)", 
		// --------------------------------------------------------
		// Table structure for table `phpbb_pa_customdata`
		"CREATE TABLE " . $mx_table_prefix . "kb_customdata (
						  customdata_file int(50) NOT NULL default '0',
						  customdata_custom int(50) NOT NULL default '0',
						  data text NOT NULL
		)" 
				 
		);

	if ( !MXBB_27x )
	{
		$sql[] = "UPDATE " . $mx_table_prefix . "module" . "
				    SET module_version  = '" . $mx_module_version . "',
				      module_copy  = '" . $mx_module_copy . "'
				    WHERE module_id = '" . $mx_module_id . "'";
	}
			
	$message .= mx_do_install_upgrade( $sql );
}
else
{ 
	// If already installed
	$message = "<b>Module is already installed...consider upgrading ;)</b><br/><br/>";
}

echo "<br /><br />";
echo "<table  width=\"90%\" align=\"center\" cellpadding=\"4\" cellspacing=\"1\" border=\"0\" class=\"forumline\">";
echo "<tr><th class=\"thHead\" align=\"center\">Module Installation/Upgrading/Uninstallation Information - module specific DB tables</th></tr>";
echo "<tr><td class=\"row1\"  align=\"left\"><span class=\"gen\">" . $message . "</span></td></tr>";
echo "</table><br />";

?>