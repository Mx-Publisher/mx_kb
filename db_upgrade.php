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
 *    $Id: db_upgrade.php,v 1.27 2005/12/08 15:04:26 jonohlsson Exp $
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

$sql = array();
// Precheck
if ( $result = $db->sql_query( "SELECT config_name from " . $mx_table_prefix . "kb_config" ) )
{ 
	// Upgrade checks
	$upgrade_101 = 0;
	$upgrade_102 = 0;
	$upgrade_103 = 0;
	$upgrade_104 = 0;
	$upgrade_105 = 0;
	$upgrade_106 = 0;
	$upgrade_107 = 0;
	$upgrade_108 = 0;
	$upgrade_109 = 0;
	$upgrade_200 = 0;
	$upgrade_201 = 0;
	$upgrade_202 = 0;

	$message = "<b>Upgrading!</b><br/><br/>"; 
	// validate before 1.05
	if ( !$result = $db->sql_query( "SELECT article_rating from " . $mx_table_prefix . "kb_articles" ) )
	{
		$upgrade_105 = 1;
		$message .= "<b>Upgrading to v. 1.05...</b><br/><br/>";
	}
	else
	{
		$message .= "<b>Validating v. 1.05...ok</b><br/><br/>";
	} 
	// validate before 1.06
	if ( !$result = $db->sql_query( "SELECT votes_userid from " . $mx_table_prefix . "kb_votes" ) )
	{
		$upgrade_106 = 1;
		$message .= "<b>Validating v. 1.06...ok</b><br/><br/>";
	}
	else
	{
		$message .= "<b>Validating v. 1.06...ok</b><br/><br/>";
	} 
	// validate before 1.07
	$result = $db->sql_query( "SELECT config_value from " . $mx_table_prefix . "kb_config WHERE config_name = 'comments_pagination'" );
	if ( $db->sql_numrows( $result ) == 0 )
	{
		$upgrade_107 = 1;
		$message .= "<b>Validating v. 1.07...ok</b><br/><br/>";
	}
	else
	{
		$message .= "<b>Validating v. 1.07...ok</b><br/><br/>";
	} 
	// validate before 1.08
	$result = $db->sql_query( "SELECT parameter_id from " . $mx_table_prefix . "parameter WHERE parameter_name = 'kb_type_select'" );

	if ( $db->sql_numrows( $result ) == 0 )
	{
		$upgrade_108 = 1;
		$message .= "<b>Validating v. 1.08...ok</b><br/><br/>";
	}
	else
	{
		$message .= "<b>Validating v. 1.08...ok</b><br/><br/>";
	} 
	// validate before 1.09
	if ( !$result = $db->sql_query( "SELECT auth_edit_groups from " . $mx_table_prefix . "kb_categories" ) )
	{
		$upgrade_109 = 1;
		$message .= "<b>Upgrading to v. 1.09...</b><br/><br/>";
	}
	else
	{
		$message .= "<b>Validating v. 1.09...ok</b><br/><br/>";
	} 
	// validate before 2.00
	$result = $db->sql_query( "SELECT config_value from " . $mx_table_prefix . "kb_config WHERE config_name = 'wysiwyg'" );
	if ( $db->sql_numrows( $result ) == 0 )
	{
		$upgrade_200 = 1;
		$message .= "<b>Upgrading to v. 2.00...ok</b><br/><br/>";
	}
	else
	{
		$message .= "<b>Validating v. 2.00...ok</b><br/><br/>";
	} 	

	// validate before 2.01
	$result = $db->sql_query( "SELECT config_value from " . $mx_table_prefix . "kb_config WHERE config_name = 'wysiwyg_path'" );
	if ( $db->sql_numrows( $result ) == 0 )
	{
		$upgrade_201 = 1;
		$message .= "<b>Upgrading to v. 2.01...ok</b><br/><br/>";
	}
	else
	{
		$message .= "<b>Validating v. 2.01...ok</b><br/><br/>";
	}	
	
		$upgrade_202 = 1;
		$message .= "<b>Upgrading to v. 2.02...ok</b><br/><br/>";	
		
	// validate before 2.0.3
	$result = $db->sql_query( "SELECT config_value from " . $mx_table_prefix . "kb_config WHERE config_name = 'internal_comments'" );
	if ( $db->sql_numrows( $result ) == 0 )
	{
		$upgrade_203 = 1;
		$message .= "<b>Upgrading to v. 2.0.3...ok</b><br/><br/>";
	}
	else
	{
		$message .= "<b>Validating v. 2.0.3...ok</b><br/><br/>";
	}		
	
	// ------------------------------------------------------------------------------------------------------
	if ( $upgrade_105 == 1 )
	{
		$sql[] = "CREATE TABLE " . $mx_table_prefix . "kb_votes (
  			votes_ip varchar(50) NOT NULL default '0',
  			votes_file int(50) NOT NULL default '0'
			) TYPE=MyISAM";

		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('allow_rating', '0')";
		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('allow_anonymos_rating', '0')";
		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('comments_show', '1')";
		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('mod_group', '0')";
		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('bump_post', '1')";
		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('stats_list', '1')";
		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('header_banner', '1')";

		$sql[] = "ALTER TABLE " . $mx_table_prefix . "kb_articles ADD  article_rating double(6,4) NOT NULL default '0.0000' ";
		$sql[] = "ALTER TABLE " . $mx_table_prefix . "kb_articles ADD  article_totalvotes int(255) NOT NULL default '0' ";
	}

	if ( $upgrade_106 == 1 )
	{
		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('votes_check_userid', '1')";
		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('votes_check_ip', '1')";
		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('art_pagination', '5')";
		$sql[] = "ALTER TABLE " . $mx_table_prefix . "kb_votes ADD votes_userid int(50) NOT NULL default '0' AFTER votes_ip ";
	}

	if ( $upgrade_107 == 1 )
	{
		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('comments_pagination', '5')";
		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('news_sort', 'Alphabetic')";
		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('news_sort_par', 'ASC')";
	}

	if ( $upgrade_108 == 1 )
	{
		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('comments_pagination', '5')";
		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('news_sort', 'Alphabetic')";
		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('news_sort_par', 'ASC')"; 
		
	}
	
	if ( $upgrade_109 == 1 )
	{	
					  	$sql[] = "ALTER TABLE " . $mx_table_prefix . "kb_categories ADD auth_view tinyint(3) NOT NULL DEFAULT '0' ";
						$sql[] = "ALTER TABLE " . $mx_table_prefix . "kb_categories ADD auth_post tinyint(3) NOT NULL DEFAULT '0' ";
						$sql[] = "ALTER TABLE " . $mx_table_prefix . "kb_categories ADD auth_rate tinyint(3) NOT NULL DEFAULT '0' ";
						$sql[] = "ALTER TABLE " . $mx_table_prefix . "kb_categories ADD auth_comment tinyint(3) NOT NULL DEFAULT '0' ";
						$sql[] = "ALTER TABLE " . $mx_table_prefix . "kb_categories ADD auth_edit tinyint(3) NOT NULL DEFAULT '0' ";
						$sql[] = "ALTER TABLE " . $mx_table_prefix . "kb_categories ADD auth_delete tinyint(3) NOT NULL DEFAULT '2' ";
						$sql[] = "ALTER TABLE " . $mx_table_prefix . "kb_categories ADD auth_approval tinyint(3) NOT NULL DEFAULT '0' ";
						$sql[] = "ALTER TABLE " . $mx_table_prefix . "kb_categories ADD auth_approval_edit tinyint(3) NOT NULL DEFAULT '0' ";
						$sql[] = "ALTER TABLE " . $mx_table_prefix . "kb_categories ADD auth_view_groups varchar(255) ";
						$sql[] = "ALTER TABLE " . $mx_table_prefix . "kb_categories ADD auth_post_groups varchar(255) ";
						$sql[] = "ALTER TABLE " . $mx_table_prefix . "kb_categories ADD auth_rate_groups varchar(255) ";
						$sql[] = "ALTER TABLE " . $mx_table_prefix . "kb_categories ADD auth_comment_groups varchar(255) ";
						$sql[] = "ALTER TABLE " . $mx_table_prefix . "kb_categories ADD auth_edit_groups varchar(255) ";
						$sql[] = "ALTER TABLE " . $mx_table_prefix . "kb_categories ADD auth_delete_groups varchar(255) ";
						$sql[] = "ALTER TABLE " . $mx_table_prefix . "kb_categories ADD auth_approval_groups varchar(255) ";
						$sql[] = "ALTER TABLE " . $mx_table_prefix . "kb_categories ADD auth_approval_edit_groups varchar(255) ";
						$sql[] = "ALTER TABLE " . $mx_table_prefix . "kb_categories ADD auth_moderator_groups varchar(255) ";
						$sql[] = "ALTER TABLE " . $mx_table_prefix . "kb_categories ADD	comments_forum_id tinyint(3) NOT NULL DEFAULT '-1' ";

						$sql[] = "UPDATE " . $mx_table_prefix . "kb_config" . "
        				SET config_name  = 'use_comments',
		 					config_value = '1'
        				WHERE config_name = 'comments'";
						
						$sql[] = "UPDATE " . $mx_table_prefix . "kb_config" . "
        				SET config_name  = 'use_ratings',
		 					config_value = '1'
        				WHERE config_name = 'allow_rating'";						
	}					
	
	if ( $upgrade_200 == 1 )
	{	
		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('wysiwyg', '0')";
		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('allow_html', '1')";
		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('allow_bbcode', '1')";
		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('allow_smilies', '1')";
		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('allowed_html_tags', 'b,i,u,a')";
		
		// --------------------------------------------------------
		// Table structure for table `phpbb_pa_custom`
		$sql[] = "CREATE TABLE " . $mx_table_prefix . "kb_custom (
						  custom_id int(50) NOT NULL auto_increment,
						  custom_name text NOT NULL,
						  custom_description text NOT NULL,
						  data text NOT NULL,
						  field_order int(20) NOT NULL default '0',
						  field_type tinyint(2) NOT NULL default '0',
						  regex varchar(255) NOT NULL default '',
						  PRIMARY KEY  (custom_id)
		)"; 
		// --------------------------------------------------------
		// Table structure for table `phpbb_pa_customdata`
		$sql[] = "CREATE TABLE " . $mx_table_prefix . "kb_customdata (
						  customdata_file int(50) NOT NULL default '0',
						  customdata_custom int(50) NOT NULL default '0',
						  data text NOT NULL
		)";

	}

	if ( $upgrade_201 == 1 )
	{
		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('wysiwyg_path', 'modules/')";
		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('formatting_fixup', '0')";
		$sql[] = "ALTER TABLE " . $mx_table_prefix . "kb_articles MODIFY article_author_id mediumint(8) NOT NULL ";
	}	
	
	if ( $upgrade_202 == 1 ) // Old fix for those upgraded from old old version
	{
		// Upgrade the config table to avoid duplicate entries
		$sql[] = "ALTER TABLE " . $mx_table_prefix . "kb_config MODIFY config_name VARCHAR(255) NOT NULL default '' ";
		$sql[] = "ALTER TABLE " . $mx_table_prefix . "kb_config MODIFY config_value VARCHAR(255) NOT NULL default '' ";
		$sql[] = "ALTER TABLE " . $mx_table_prefix . "kb_config DROP PRIMARY KEY, ADD PRIMARY KEY (config_name) ";	
	}
	
	if ( $upgrade_203 == 1 ) 
	{
		// --------------------------------------------------------
		// Table structure for table `phpbb_pa_comments`
		$sql[] = "CREATE TABLE " . $mx_table_prefix . "kb_comments (
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
		)";
				
		// Config table
		$sql[] = "UPDATE " . $mx_table_prefix . "kb_config" . " SET config_name = 'enable_module' WHERE config_name = 'allow_new'";
		$sql[] = "UPDATE " . $mx_table_prefix . "kb_config" . " SET config_name = 'pagination' WHERE config_name = 'art_pagination'";
		$sql[] = "UPDATE " . $mx_table_prefix . "kb_config" . " SET config_name = 'sort_method' WHERE config_name = 'news_sort'";
		$sql[] = "UPDATE " . $mx_table_prefix . "kb_config" . " SET config_name = 'sort_order' WHERE config_name = 'news_sort_par'";
		$sql[] = "UPDATE " . $mx_table_prefix . "kb_config" . " SET config_name = 'autogenerate_comments' WHERE config_name = 'bump_post'";
		$sql[] = "UPDATE " . $mx_table_prefix . "kb_config" . " SET config_name = 'notify_group' WHERE config_name = 'admin_id'";
		
		$sql[] = "DELETE FROM " . $mx_table_prefix . "kb_config" . " WHERE config_name = 'comments_show'";
		$sql[] = "DELETE FROM " . $mx_table_prefix . "kb_config" . " WHERE config_name = 'wysiwyg'";
		$sql[] = "DELETE FROM " . $mx_table_prefix . "kb_config" . " WHERE config_name = 'wysiwyg_comments'";
		$sql[] = "DELETE FROM " . $mx_table_prefix . "kb_config" . " WHERE config_name = 'allow_wysiwyg_comments'";
		$sql[] = "DELETE FROM " . $mx_table_prefix . "kb_config" . " WHERE config_name = 'allow_wysiwyg'";
		$sql[] = "DELETE FROM " . $mx_table_prefix . "kb_config" . " WHERE config_name = 'formatting_fixup'";
		$sql[] = "DELETE FROM " . $mx_table_prefix . "kb_config" . " WHERE config_name = 'formatting_comment_fixup'";
		$sql[] = "DELETE FROM " . $mx_table_prefix . "kb_config" . " WHERE config_name = 'approve_new'";
		$sql[] = "DELETE FROM " . $mx_table_prefix . "kb_config" . " WHERE config_name = 'approve_edit'";
		$sql[] = "DELETE FROM " . $mx_table_prefix . "kb_config" . " WHERE config_name = 'allow_edit'";
		$sql[] = "DELETE FROM " . $mx_table_prefix . "kb_config" . " WHERE config_name = 'allow_anon'";
		$sql[] = "DELETE FROM " . $mx_table_prefix . "kb_config" . " WHERE config_name = 'forum_id'";
		$sql[] = "DELETE FROM " . $mx_table_prefix . "kb_config" . " WHERE config_name = 'allow_anonymos_rating'";
		$sql[] = "DELETE FROM " . $mx_table_prefix . "kb_config" . " WHERE config_name = 'mod_group'";
		
		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('module_name', 'Knowledge Base')";
		
		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('allow_wysiwyg', '1')";
		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('allow_links', '1')";
		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('allow_images', '1')";
		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('no_image_message', '[No image please]')";
		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('no_link_message', '[No links please]')";
		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('formatting_wordwrap', '1')";
		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('formatting_image_resize', '300')";
		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('formatting_truncate_links', '1')";
		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('max_subject_chars', '100')";
		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('max_desc_chars', '500')";				
		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('max_chars', '0')";
				
		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('internal_comments', '1')";
		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('formatting_comment_wordwrap', '1')";
		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('formatting_comment_image_resize', '300')";
		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('formatting_comment_truncate_links', '1')";
		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('max_comment_subject_chars', '50')";
		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('max_comment_chars', '5000')";		
		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('allowed_comment_html_tags', 'b,i,u,a')";
		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('allow_comment_wysiwyg', '0')";
		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('allow_comment_html', '1')";
		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('allow_comment_bbcode', '1')";
		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('allow_comment_smilies', '1')";
		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('allow_comment_links', '1')";
		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('allow_comment_images', '0')";
		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('no_comment_image_message', '[No image please]')";
		$sql[] = "INSERT INTO " . $mx_table_prefix . "kb_config VALUES ('no_comment_link_message', '[No links please]')";		

				
		// Categories table		
		$sql[] = "ALTER TABLE " . $mx_table_prefix . "kb_categories ADD cat_allow_comments tinyint(2) NOT NULL default '-1' ";
		$sql[] = "ALTER TABLE " . $mx_table_prefix . "kb_categories ADD internal_comments tinyint(2) NOT NULL DEFAULT '-1' ";
		$sql[] = "ALTER TABLE " . $mx_table_prefix . "kb_categories ADD autogenerate_comments tinyint(2) NOT NULL DEFAULT '-1' ";
		$sql[] = "ALTER TABLE " . $mx_table_prefix . "kb_categories ADD comments_forum_id mediumint(8) NOT NULL DEFAULT '-1' ";

		$sql[] = "ALTER TABLE " . $mx_table_prefix . "kb_categories ADD cat_allow_ratings tinyint(2) NOT NULL default '-1' ";
		
		$sql[] = "ALTER TABLE " . $mx_table_prefix . "kb_categories ADD show_pretext tinyint(2) NOT NULL default '-1' ";
		
		$sql[] = "ALTER TABLE " . $mx_table_prefix . "kb_categories ADD notify tinyint(2) NOT NULL DEFAULT '-1' ";
		$sql[] = "ALTER TABLE " . $mx_table_prefix . "kb_categories ADD notify_group mediumint(8) NOT NULL DEFAULT '-1' ";
		
		$sql[] = "ALTER TABLE " . $mx_table_prefix . "kb_categories ADD cat_last_article_id mediumint(8) unsigned NOT NULL default '0' ";
		$sql[] = "ALTER TABLE " . $mx_table_prefix . "kb_categories ADD cat_last_article_name varchar(255) NOT NULL default '' ";
		$sql[] = "ALTER TABLE " . $mx_table_prefix . "kb_categories ADD cat_last_article_time INT(50) UNSIGNED DEFAULT '0' NOT NULL ";
		
		$sql[] = "ALTER TABLE " . $mx_table_prefix . "kb_categories MODIFY auth_view_groups smallint(5) NOT NULL default '0' ";
		$sql[] = "ALTER TABLE " . $mx_table_prefix . "kb_categories MODIFY auth_post_groups smallint(5) NOT NULL default '0' ";
		$sql[] = "ALTER TABLE " . $mx_table_prefix . "kb_categories MODIFY auth_rate_groups smallint(5) NOT NULL default '0' ";
		$sql[] = "ALTER TABLE " . $mx_table_prefix . "kb_categories MODIFY auth_comment_groups smallint(5) NOT NULL default '0' ";
		$sql[] = "ALTER TABLE " . $mx_table_prefix . "kb_categories MODIFY auth_edit_groups smallint(5) NOT NULL default '0' ";
		$sql[] = "ALTER TABLE " . $mx_table_prefix . "kb_categories MODIFY auth_delete_groups smallint(5) NOT NULL default '0' ";
		$sql[] = "ALTER TABLE " . $mx_table_prefix . "kb_categories MODIFY auth_approval_groups smallint(5) NOT NULL default '0' ";
		$sql[] = "ALTER TABLE " . $mx_table_prefix . "kb_categories MODIFY auth_approval_edit_groups smallint(5) NOT NULL default '0' ";
		$sql[] = "ALTER TABLE " . $mx_table_prefix . "kb_categories MODIFY auth_moderator_groups smallint(5) NOT NULL default '0' ";
		
		// Number of articles can be -1 when syncing
		$sql[] = "ALTER TABLE " . $mx_table_prefix . "kb_categories MODIFY number_articles mediumint(8) NOT NULL";
		
		//
		// Drop unused tables
		//
		$sql[] = "DROP TABLE " . $mx_table_prefix . "kb_wordlist ";
		$sql[] = "DROP TABLE " . $mx_table_prefix . "kb_results ";
		$sql[] = "DROP TABLE " . $mx_table_prefix . "kb_wordmatch ";		
						
	}	
	else
	{
		$message .= "<b>Nothing to upgrade...</b><br/><br/>";
	}

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
	// If not installed
	$message = "<b>Module is not installed...and thus cannot be upgraded ;)</b><br/><br/>";
}

echo "<br /><br />";
echo "<table  width=\"90%\" align=\"center\" cellpadding=\"4\" cellspacing=\"1\" border=\"0\" class=\"forumline\">";
echo "<tr><th class=\"thHead\" align=\"center\">Module Installation/Upgrading/Uninstalling Information - module specific db tables</th></tr>";
echo "<tr><td class=\"row1\"  align=\"left\"><span class=\"gen\">" . $message . "</span></td></tr>";
echo "</table><br />";

?>