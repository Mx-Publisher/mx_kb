<?php
/**
*
* @package MX-Publisher Module - mx_kb
* @version $Id: db_install.php,v 1.58 2013/04/04 12:24:26 orynider Exp $
* @copyright (c) 2002-2006 [Jon Ohlsson, Mohd Basri, wGEric, PHP Arena, pafileDB, CRLin] MX-Publisher Project Team
* @license http://opensource.org/licenses/gpl-license.php GNU General Public License v2
*
*/

@define( 'IN_PORTAL', true );
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

$mx_module_version = '2.2.5';
$mx_module_copy = 'Original phpBB <i>Knowledge Base</i> MOD by <a href="http://www.phpbb.com/phpBB/viewtopic.php?t=89202" target="_phpbb" >wGEric/Jon Ohlsson</a> :: Adapted for MX-Publisher by [Jon Ohlsson] <a href="http://www.mx-publisher.com" target="_blank">The MX-Publisher Development Team</a>';

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

	$sql = array(
		"DROP TABLE IF EXISTS " . $mx_table_prefix . "kb_articles ",
		"DROP TABLE IF EXISTS " . $mx_table_prefix . "kb_categories ",
		//"DROP TABLE IF EXISTS " . $mx_table_prefix . "pub_auth ",
		//"DROP TABLE IF EXISTS " . $mx_table_prefix . "pub_comments ",
		"DROP TABLE IF EXISTS " . $mx_table_prefix . "kb_config ",
		"DROP TABLE IF EXISTS " . $mx_table_prefix . "kb_types ",
		//"DROP TABLE IF EXISTS " . $mx_table_prefix . "pub_custom ",
		//"DROP TABLE IF EXISTS " . $mx_table_prefix . "pub_customdata ",
		//"DROP TABLE IF EXISTS " . $mx_table_prefix . "pub_download_info ",
		//"DROP TABLE IF EXISTS " . $mx_table_prefix . "pub_license ",
		"DROP TABLE IF EXISTS " . $mx_table_prefix . "kb_wordlist ",
		"DROP TABLE IF EXISTS " . $mx_table_prefix . "kb_results ",
		"DROP TABLE IF EXISTS " . $mx_table_prefix . "kb_wordmatch ",
		//"DROP TABLE IF EXISTS " . $mx_table_prefix . "kb_votes ",
		//"DROP TABLE IF EXISTS " . $mx_table_prefix . "pub_mirrors ",
		//"DROP TABLE IF EXISTS " . $mx_table_prefix . "pub_files ",
		
		// --------------------------------------------------------
		// Table structure for table `kb_categories`
		"CREATE TABLE " . $mx_table_prefix . "kb_categories (
			`category_id` int(10) NOT NULL auto_increment,
			`category_name` mediumtext,
			`category_details` mediumtext,
			`parent` int(50) default NULL,
			`parents_data` mediumtext,

			`cat_order` int(50) default NULL,
			`cat_allow_file` tinyint(2) NOT NULL default '1',

			`cat_allow_comments` tinyint(2) NOT NULL default '-1',
			`internal_comments` tinyint(2) NOT NULL default '-1',
			`autogenerate_comments` tinyint(2) NOT NULL default '-1',
			`comments_forum_id` mediumint(8) NOT NULL default '-1',

			`cat_allow_ratings` tinyint(2) NOT NULL default '-1',

			`show_pretext` tinyint(2) NOT NULL default '-1',

			`notify` tinyint(2) NOT NULL default '-1',
			`notify_group` mediumint(8) NOT NULL default '-1',

			`number_articles` mediumint(8) NOT NULL default '-1',
			`cat_last_article_id` mediumint(8) unsigned NOT NULL default '0',
			`cat_last_article_name` varchar(255) NOT NULL default '',
			`cat_last_article_time` INT(50) UNSIGNED DEFAULT '0' NOT NULL,

			`auth_view` tinyint(2) NOT NULL DEFAULT '0',
			`auth_post` tinyint(2) NOT NULL DEFAULT '0',
			`auth_rate` tinyint(2) NOT NULL DEFAULT '0',
			`auth_view_comment` tinyint(2) NOT NULL DEFAULT '0',
			`auth_post_comment` tinyint(2) NOT NULL DEFAULT '0',
			`auth_edit_comment` tinyint(2) NOT NULL DEFAULT '0',
			`auth_delete_comment` tinyint(2) NOT NULL DEFAULT '2',
			`auth_edit` tinyint(2) NOT NULL DEFAULT '0',
			`auth_delete` tinyint(2) NOT NULL DEFAULT '2',
			`auth_approval` tinyint(2) NOT NULL DEFAULT '0',
			`auth_approval_edit` tinyint(2) NOT NULL DEFAULT '0',

			`auth_view_groups` varchar(255) NOT NULL default '0',
			`auth_post_groups` varchar(255) NOT NULL default '0',
			`auth_rate_groups` varchar(255) NOT NULL default '0',
			`auth_view_comment_groups` varchar(255) NOT NULL default '0',
			`auth_post_comment_groups` varchar(255) NOT NULL default '0',
			`auth_edit_comment_groups` varchar(255) NOT NULL default '0',
			`auth_delete_comment_groups` varchar(255) NOT NULL default '0',
			`auth_edit_groups` varchar(255) NOT NULL default '0',
			`auth_delete_groups` varchar(255) NOT NULL default '0',
			`auth_approval_groups` varchar(255) NOT NULL default '0', 
			`auth_approval_edit_groups` varchar(255) NOT NULL default '0', 

			`auth_moderator_groups` varchar(255) NOT NULL default '0',

			PRIMARY KEY  (`category_id`)
		) ",
		 
		//
		// Insert
		//
		"INSERT INTO `" . $mx_table_prefix . "kb_categories` (`category_id`, `category_name`, `category_details`, `parent`, `parents_data`, `cat_order`, `cat_allow_file`, `cat_allow_comments`, `internal_comments`, `autogenerate_comments`, `comments_forum_id`, `cat_allow_ratings`, `show_pretext`, `notify`, `notify_group`, `number_articles`, `cat_last_article_id`, `cat_last_article_name`, `cat_last_article_time`, `auth_view`, `auth_post`, `auth_rate`, `auth_view_comment`, `auth_post_comment`, `auth_edit_comment`, `auth_delete_comment`, `auth_edit`, `auth_delete`, `auth_approval`, `auth_approval_edit`, `auth_view_groups`, `auth_post_groups`, `auth_rate_groups`, `auth_view_comment_groups`, `auth_post_comment_groups`, `auth_edit_comment_groups`, `auth_delete_comment_groups`, `auth_edit_groups`, `auth_delete_groups`, `auth_approval_groups`, `auth_approval_edit_groups`, `auth_moderator_groups`) VALUES
																				(1, 'Test Category', 'Just a test category', 0, '10', -1, -1, -1, -1, -1, -1, -1, -1, -1, 0, 0, 0, '0', 0, 0, 0, 0, 0, 2, 0, 2, 0, 0, 0, 0, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0')",

		// --------------------------------------------------------
		// Table structure for table `phpbb_pub_articles`
		"CREATE TABLE " . $mx_table_prefix . "kb_articles (
						article_id mediumint(8) unsigned NOT NULL auto_increment,
  						article_title varchar(255) binary NOT NULL default '',
  						article_description varchar(255) binary NOT NULL default '',
  						article_category_id mediumint(8) unsigned NOT NULL default '0',
  						approved tinyint(1) unsigned NOT NULL default '0',

 						article_body longtext NOT NULL,
   						bbcode_uid varchar(10) binary NOT NULL default '',
 						article_type mediumint(8) unsigned NOT NULL default '0',

  						article_date int(50) default NULL,
  						article_author_id mediumint(8) NOT NULL,
						username VARCHAR(255),
  						topic_id mediumint(8) unsigned NOT NULL default '0',
  						views BIGINT(8) NOT NULL DEFAULT '0',

  						PRIMARY KEY (article_id)
		)",
		
		// --------------------------------------------------------
		// Table structure for table `phpbb_pub_files`
		// _files or _attachaments table only required in pafiledb 
		// or mx_publisher mxp modules or phpbb extensions.
		
		// --------------------------------------------------------
		// Table structure for table `phpbb_kb_config`
		"CREATE TABLE " . $mx_table_prefix . "kb_config (
		  `config_name` varchar(155) NOT NULL DEFAULT '',
		  `config_value` varchar(155) NOT NULL DEFAULT '',
		  `is_dynamic` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
		  PRIMARY KEY (`config_name`),
		  KEY `is_dynamic` (`is_dynamic`)
		)",

		// --------------------------------------------------------
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
			  KEY comment_bbcode_uid (comment_bbcode_uid)
		)",

		"CREATE TABLE " . $mx_table_prefix . "kb_types (
  				 	       id mediumint(8) unsigned NOT NULL auto_increment,
  						   type varchar(255) binary DEFAULT '' NOT NULL,
  						   KEY id (id)
		)",

		"INSERT INTO " . $mx_table_prefix . "kb_types VALUES (1, 'Test Type 1')",

		"CREATE TABLE " . $mx_table_prefix . "kb_votes (
			user_id mediumint(8) NOT NULL default '0',
			votes_ip varchar(50) NOT NULL default '0',
			votes_article int(50) NOT NULL default '0',
			rate_point tinyint(3) unsigned NOT NULL default '0',
			KEY user_id (user_id)
		)",

		// --------------------------------------------------------
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
		"CREATE TABLE " . $mx_table_prefix . "kb_customdata (
						  customdata_file int(50) NOT NULL default '0',
						  customdata_custom int(50) NOT NULL default '0',
						  data text NOT NULL
		)",

		//
		// Insert Configs
		//

		// General
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('enable_module', '1')", // allow_new
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('module_name', 'Knowledge Base')", // NEW
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('wysiwyg_path', 'modules/mx_shared/')",

		// Articles
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('formatting_wordwrap', '1')", // formatting_fixup
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('formatting_image_resize', '300')", // NEW
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('formatting_truncate_links', '1')", // NEW
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('allow_wysiwyg', '0')", // NEW
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('allow_html', '1')",
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('allow_bbcode', '1')",
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('allow_smilies', '1')",
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('allowed_html_tags', 'b,i,u,a')",
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('allow_links', '1')",
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('allow_images', '0')",
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('no_image_message', '[No image please]')",
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('no_link_message', '[No links please]')",
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('max_subject_chars', '100')", // NEW
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('max_desc_chars', '500')", // NEW
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('max_chars', '0')", // NEW

		// Appearance
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('sort_method', 'Alphabetic')", // news_sort
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('sort_order', 'ASC')", // news_sort_par
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('pagination', '10')",  // art_pagination

		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('stats_list', '1')",
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('header_banner', '0')",

		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('use_simple_navigation', '1')",
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('cat_col', '2')",
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('settings_newdays', '1')",

		// Comments
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('use_comments', '0')", // comments_show
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('internal_comments', '1')", // NEW
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('formatting_comment_wordwrap', '1')", // formatting_comment_fixup
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('formatting_comment_image_resize', '300')", // NEW
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('formatting_comment_truncate_links', '1')", // NEW
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('max_comment_subject_chars', '50')", // NEW
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('max_comment_chars', '5000')", // NEW
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('allow_comment_wysiwyg', '0')",
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('allow_comment_html', '1')",
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('allow_comment_bbcode', '1')",
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('allow_comment_smilies', '1')",
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('allow_comment_links', '1')",
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('allow_comment_images', '0')",
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('no_comment_image_message', '[No image please]')",
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('no_comment_link_message', '[No links please]')",
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('allowed_comment_html_tags', 'b,i,u,a')", // NEW
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('del_topic', '1')",
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('autogenerate_comments', '1')",	// bump_post
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('comments_pagination', '5')",
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('comments_forum_id', '0')", // New

		// Ratings
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('use_ratings', '0')",
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('votes_check_userid', '1')",
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('votes_check_ip', '1')",

		// Instructions
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('show_pretext',0)",
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('pt_header','Article Submission Instructions')",
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('pt_body','Please check your references and include as much information as you can.')",

		// Notifications
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('notify', '0')", // updated
		"INSERT INTO " . $mx_table_prefix . "kb_config (`config_name`, `config_value`) VALUES ('notify_group', '0')",	// admin_id

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