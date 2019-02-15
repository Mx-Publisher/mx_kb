<?php
/**
*
* @package MX-Publisher Module - mx_kb
* @version $Id: admin_settings.php,v 1.9 2008/07/15 22:05:42 jonohlsson Exp $
* @copyright (c) 2002-2006 [Jon Ohlsson, Mohd Basri, wGEric, PHP Arena, pafileDB, CRLin] MX-Publisher Project Team
* @license http://opensource.org/licenses/gpl-license.php GNU General Public License v2
*
*/

if ( !defined( 'IN_PORTAL' ) || !defined( 'IN_ADMIN' ) )
{
	die( "Hacking attempt" );
}

class mx_kb_settings extends mx_kb_admin
{
	function main( $action )
	{
		global $db, $images, $template, $lang, $phpEx, $mx_kb_functions, $mx_kb_cache, $kb_config, $phpbb_root_path, $module_root_path, $mx_root_path, $mx_request_vars, $portal_config;

		//
		// Pull all config data
		//
		$sql = "SELECT *
				 FROM " . KB_CONFIG_TABLE;

		if ( !$result = $db->sql_query( $sql ) )
		{
			mx_message_die( CRITICAL_ERROR, "Could not query knowledge base configuration information", "", __LINE__, __FILE__, $sql );
		}
		else
		{
			while ( $row = $db->sql_fetchrow( $result ) )
			{
				$config_name = $row['config_name'];
				$config_value = $row['config_value'];
				$kb_config[$config_name] = $config_value;

				$new[$config_name] = ( isset( $_POST[$config_name] ) ) ? $_POST[$config_name] : $kb_config[$config_name];

				if ( isset( $_POST['submit'] ) )
				{
					$mx_kb_functions->set_config( $config_name, $new[$config_name] );
				}
			}

			if ( isset( $_POST['submit'] ) )
			{
				$mx_kb_cache->unload();
				$message = $lang['KB_config_updated'] . "<br /><br />" . sprintf( $lang['Click_return_kb_config'], "<a href=\"" . mx_append_sid( "admin_kb.$phpEx?action=settings&mode=config" ) . "\">", "</a>" ) . "<br /><br />" . sprintf( $lang['Click_return_admin_index'], "<a href=\"" . mx_append_sid( $mx_root_path . "admin/index.$phpEx?pane=right" ) . "\">", "</a>" );

				mx_message_die( GENERAL_MESSAGE, $message );
			}
		}

		$template->set_filenames( array( "body" => "admin/kb_config_body.tpl" ) );

		//
		// General Settings
		//
		$module_name = $new['module_name'];

		$enable_module_yes = ( $new['enable_module'] ) ? "checked=\"checked\"" : "";
		$enable_module_no = ( !$new['enable_module'] ) ? "checked=\"checked\"" : "";

		$wysiwyg_path = $new['wysiwyg_path'];

		//
		// Article
		//
		$allow_wysiwyg_yes = ( $new['allow_wysiwyg'] ) ? "checked=\"checked\"" : "";
		$allow_wysiwyg_no = ( !$new['allow_wysiwyg'] ) ? "checked=\"checked\"" : "";

		$allow_html_yes = ( $new['allow_html'] ) ? "checked=\"checked\"" : "";
		$allow_html_no = ( !$new['allow_html'] ) ? "checked=\"checked\"" : "";

		$allowed_html_tags = $new['allowed_html_tags'];

		$allow_bbcode_yes = ( $new['allow_bbcode'] ) ? "checked=\"checked\"" : "";
		$allow_bbcode_no = ( !$new['allow_bbcode'] ) ? "checked=\"checked\"" : "";

		$allow_smilies_yes = ( $new['allow_smilies'] ) ? "checked=\"checked\"" : "";
		$allow_smilies_no = ( !$new['allow_smilies'] ) ? "checked=\"checked\"" : "";

		$allow_images_yes = ( $new['allow_images'] ) ? "checked=\"checked\"" : "";
		$allow_images_no = ( !$new['allow_images'] ) ? "checked=\"checked\"" : "";

		$allow_links_yes = ( $new['allow_links'] ) ? "checked=\"checked\"" : "";
		$allow_links_no = ( !$new['allow_links'] ) ? "checked=\"checked\"" : "";

		$no_image_message = $new['no_image_message'];
		$no_link_message = $new['no_link_message'];

		$max_chars = $new['max_chars'];
		$max_subject_chars = $new['max_subject_chars'];
		$max_desc_chars = $new['max_desc_chars'];

		$format_truncate_links_yes = ( $new['formatting_truncate_links'] ) ? "checked=\"checked\"" : "";
		$format_truncate_links_no = ( !$new['formatting_truncate_links'] ) ? "checked=\"checked\"" : "";

		$format_image_resize = $new['formatting_image_resize'];

		$format_wordwrap_yes = ( $new['formatting_wordwrap'] ) ? "checked=\"checked\"" : "";
		$format_wordwrap_no = ( !$new['formatting_wordwrap'] ) ? "checked=\"checked\"" : "";

		//
		// Appearance
		//
		$pagination = $new['pagination'];

		$sort_method_options = array();
		$sort_method_options = array( "Alphabetic", "Latest", "Toprated", "Most_popular", "Userrank", "Id" );

		$sort_method_list = '<select name="sort_method">';
		for( $j = 0; $j < count( $sort_method_options ); $j++ )
		{
			if ( $new['sort_method'] == $sort_method_options[$j] )
			{
				$status = "selected";
			}
			else
			{
				$status = '';
			}
			$sort_method_list .= '<option value="' . $sort_method_options[$j] . '" ' . $status . '>' . $sort_method_options[$j] . '</option>';
		}
		$sort_method_list .= '</select>';

		$sort_order_options = array();
		$sort_order_options = array( "DESC", "ASC" );

		$sort_order_list = '<select name="sort_order">';

		for( $j = 0; $j < count( $sort_order_options ); $j++ )
		{
			if ( $new['sort_order'] == $sort_order_options[$j] )
			{
				$status = "selected";
			}
			else
			{
				$status = '';
			}
			$sort_order_list .= '<option value="' . $sort_order_options[$j] . '" ' . $status . '>' . $sort_order_options[$j] . '</option>';
		}
		$sort_order_list .= '</select>';

		$header_banner_yes = ( $new['header_banner'] ) ? "checked=\"checked\"" : "";
		$header_banner_no = ( !$new['header_banner'] ) ? "checked=\"checked\"" : "";

		$stats_list_yes = ( $new['stats_list'] ) ? "checked=\"checked\"" : "";
		$stats_list_no = ( !$new['stats_list'] ) ? "checked=\"checked\"" : "";

		$settings_newdays = $new['settings_newdays'];
		$cat_col = $new['cat_col'];

		$use_simple_navigation_yes = ( $new['use_simple_navigation'] ) ? "checked=\"checked\"" : "";
		$use_simple_navigation_no = ( !$new['use_simple_navigation'] ) ? "checked=\"checked\"" : "";

		//
		// Instructions
		//
		$pretext_show = ( $new['show_pretext'] ) ? "checked=\"checked\"" : "";
		$pretext_hide = ( !$new['show_pretext'] ) ? "checked=\"checked\"" : "";

		$pt_header = $new['pt_header'];
		$pt_body = $new['pt_body'];


		//
		// Comments (default settings)
		//
		$use_comments_yes = ( $new['use_comments'] ) ? "checked=\"checked\"" : "";
		$use_comments_no = ( !$new['use_comments'] ) ? "checked=\"checked\"" : "";

		switch ($portal_config['portal_backend'])
		{
			case 'internal':
				$internal_comments_internal = "checked=\"checked\"";
				$internal_comments_phpbb = "";
				$comments_forum_id = 0;

				$del_topic_yes = "";
				$del_topic_no = "checked=\"checked\"";

				$autogenerate_comments_yes = "";
				$autogenerate_comments_no = "checked=\"checked\"";

				$template->assign_vars( array(
					'S_READONLY' => "disabled=\"disabled\"" )
				);
				break;

			default:
				$internal_comments_internal = ( $new['internal_comments'] ) ? "checked=\"checked\"" : "";
				$internal_comments_phpbb = ( !$new['internal_comments'] ) ? "checked=\"checked\"" : "";
				$comments_forum_id = $new['comments_forum_id'];

				$del_topic_yes = ( $new['del_topic'] ) ? "checked=\"checked\"" : "";
				$del_topic_no = ( !$new['del_topic'] ) ? "checked=\"checked\"" : "";

				$autogenerate_comments_yes = ( $new['autogenerate_comments'] ) ? "checked=\"checked\"" : "";
				$autogenerate_comments_no = ( !$new['autogenerate_comments'] ) ? "checked=\"checked\"" : "";
				$template->assign_vars( array(
					'S_READONLY' => "" )
				);
				break;
		}

		$allow_comment_wysiwyg_yes = ( $new['allow_comment_wysiwyg'] ) ? "checked=\"checked\"" : "";
		$allow_comment_wysiwyg_no = ( !$new['allow_comment_wysiwyg'] ) ? "checked=\"checked\"" : "";

		$allow_comment_html_yes = ( $new['allow_comment_html'] ) ? "checked=\"checked\"" : "";
		$allow_comment_html_no = ( !$new['allow_comment_html'] ) ? "checked=\"checked\"" : "";

		$allowed_comment_html_tags = $new['allowed_comment_html_tags'];

		$allow_comment_bbcode_yes = ( $new['allow_comment_bbcode'] ) ? "checked=\"checked\"" : "";
		$allow_comment_bbcode_no = ( !$new['allow_comment_bbcode'] ) ? "checked=\"checked\"" : "";

		$allow_comment_smilies_yes = ( $new['allow_comment_smilies'] ) ? "checked=\"checked\"" : "";
		$allow_comment_smilies_no = ( !$new['allow_comment_smilies'] ) ? "checked=\"checked\"" : "";

		$allow_comment_links_yes = ( $new['allow_comment_links'] ) ? "checked=\"checked\"" : "";
		$allow_comment_links_no = ( !$new['allow_comment_links'] ) ? "checked=\"checked\"" : "";

		$allow_comment_images_yes = ( $new['allow_comment_images'] ) ? "checked=\"checked\"" : "";
		$allow_comment_images_no = ( !$new['allow_comment_images'] ) ? "checked=\"checked\"" : "";

		$no_comment_link_message = $new['no_comment_link_message'];
		$no_comment_image_message = $new['no_comment_image_message'];

		$max_comment_chars = $new['max_comment_chars'];
		$max_comment_subject_chars = $new['max_comment_subject_chars'];

		$format_comment_truncate_links_yes = ( $new['formatting_comment_truncate_links'] ) ? "checked=\"checked\"" : "";
		$format_comment_truncate_links_no = ( !$new['formatting_comment_truncate_links'] ) ? "checked=\"checked\"" : "";

		$format_comment_image_resize = $new['formatting_comment_image_resize'];

		$format_comment_wordwrap_yes = ( $new['formatting_comment_wordwrap'] ) ? "checked=\"checked\"" : "";
		$format_comment_wordwrap_no = ( !$new['formatting_comment_wordwrap'] ) ? "checked=\"checked\"" : "";

		$comments_pag = $new['comments_pagination'];

		//
		// Ratings (default settings)
		//
		$use_ratings_yes = ( $new['use_ratings'] ) ? "checked=\"checked\"" : "";
		$use_ratings_no = ( !$new['use_ratings'] ) ? "checked=\"checked\"" : "";

		$votes_check_ip_yes = ( $new['votes_check_ip'] ) ? "checked=\"checked\"" : "";
		$votes_check_ip_no = ( !$new['votes_check_ip'] ) ? "checked=\"checked\"" : "";

		$votes_check_userid_yes = ( $new['votes_check_userid'] ) ? "checked=\"checked\"" : "";
		$votes_check_userid_no = ( !$new['votes_check_userid'] ) ? "checked=\"checked\"" : "";

		//
		// Notifications
		//
		$notify_none = ( $new['notify'] == 0 ) ? "checked=\"checked\"" : "";
		$notify_pm = ( $new['notify'] == 1 ) ? "checked=\"checked\"" : "";
		$notify_email = ( $new['notify'] == 2 ) ? "checked=\"checked\"" : "";

		$notify_group_list = mx_get_groups($new['notify_group'], 'notify_group');

		$template->assign_vars( array(
			'S_ACTION' => mx_append_sid( "admin_kb.$phpEx?action=settings&mode=config" ),

			'L_CONFIGURATION_TITLE' => $lang['Panel_config_title'],
			'L_CONFIGURATION_EXPLAIN' => $lang['Panel_config_explain'],

			'L_SUBMIT' => $lang['Submit'],
			'L_RESET' => $lang['Reset'],

			'L_YES' => $lang['Yes'],
			'L_NO' => $lang['No'],
			'L_NONE' => $lang['Acc_None'],

			//
			// General
			//
			'L_GENERAL_TITLE' => $lang['General_title'],

			'L_MODULE_NAME' => $lang['Module_name'],
			'L_MODULE_NAME_EXPLAIN' => $lang['Module_name_explain'],
			'MODULE_NAME' => $module_name,

			'L_ENABLE_MODULE' => $lang['Enable_module'],
			'L_ENABLE_MODULE_EXPLAIN' => $lang['Enable_module_explain'],
			'S_ENABLE_MODULE_YES' => $enable_module_yes,
			'S_ENABLE_MODULE_NO' => $enable_module_no,

			'L_WYSIWYG_PATH' => $lang['Wysiwyg_path'],
			'L_WYSIWYG_PATH_EXPLAIN' => $lang['Wysiwyg_path_explain'],
			'WYSIWYG_PATH' => $wysiwyg_path,

			//
			// Article
			//
			'L_ARTICLE_TITLE' => $lang['Article_title'],

			'L_ALLOW_WYSIWYG' => $lang['Allow_Wysiwyg'],
			'L_ALLOW_WYSIWYG_EXPLAIN' => $lang['Allow_Wysiwyg_explain'],
			'S_ALLOW_WYSIWYG_YES' => $allow_wysiwyg_yes,
			'S_ALLOW_WYSIWYG_NO' => $allow_wysiwyg_no,

			'L_ALLOW_HTML' => $lang['Allow_HTML'],
			'L_ALLOW_HTML_EXPLAIN' => $lang['Allow_html_explain'],
			'S_ALLOW_HTML_YES' => $allow_html_yes,
			'S_ALLOW_HTML_NO' => $allow_html_no,

			'L_ALLOW_BBCODE' => $lang['Allow_BBCode'],
			'L_ALLOW_BBCODE_EXPLAIN' => $lang['Allow_bbcode_explain'],
			'S_ALLOW_BBCODE_YES' => $allow_bbcode_yes,
			'S_ALLOW_BBCODE_NO' => $allow_bbcode_no,

			'L_ALLOW_SMILIES' => $lang['Allow_smilies'],
			'L_ALLOW_SMILIES_EXPLAIN' => $lang['Allow_smilies_explain'],
			'S_ALLOW_SMILIES_YES' => $allow_smilies_yes,
			'S_ALLOW_SMILIES_NO' => $allow_smilies_no,

			'L_ALLOWED_HTML_TAGS' => $lang['Allowed_tags'],
			'L_ALLOWED_HTML_TAGS_EXPLAIN' => $lang['Allowed_tags_explain'],
			'ALLOWED_HTML_TAGS' => $allowed_html_tags,

			'L_ALLOW_IMAGES' => $lang['Allow_images'],
			'L_ALLOW_IMAGES_EXPLAIN' => $lang['Allow_images_explain'],
			'S_ALLOW_IMAGES_YES' => $allow_images_yes,
			'S_ALLOW_IMAGES_NO' => $allow_images_no,

			'L_ALLOW_LINKS' => $lang['Allow_links'],
			'L_ALLOW_LINKS_EXPLAIN' => $lang['Allow_links_explain'],
			'S_ALLOW_LINKS_YES' => $allow_links_yes,
			'S_ALLOW_LINKS_NO' => $allow_links_no,

			'L_LINKS_MESSAGE' => $lang['Allow_links_message'],
			'L_LINKS_MESSAGE_EXPLAIN' => $lang['Allow_links_message_explain'],
			'MESSAGE_LINK' => $no_link_message,

			'L_IMAGES_MESSAGE' => $lang['Allow_images_message'],
			'L_IMAGES_MESSAGE_EXPLAIN' => $lang['Allow_images_message_explain'],
			'MESSAGE_IMAGE' => $no_image_message,

			'L_MAX_SUBJECT_CHAR' => $lang['Max_subject_char'],
			'L_MAX_SUBJECT_CHAR_EXPLAIN' => $lang['Max_subject_char_explain'],
			'MAX_SUBJECT_CHAR' => $max_subject_chars,

			'L_MAX_DESC_CHAR' => $lang['Max_desc_char'],
			'L_MAX_DESC_CHAR_EXPLAIN' => $lang['Max_desc_char_explain'],
			'MAX_DESC_CHAR' => $max_desc_chars,

			'L_MAX_CHAR' => $lang['Max_char'],
			'L_MAX_CHAR_EXPLAIN' => $lang['Max_char_explain'],
			'MAX_CHAR' => $max_chars,

			'L_FORMAT_WORDWRAP' => $lang['Format_wordwrap'],
			'L_FORMAT_WORDWRAP_EXPLAIN' => $lang['Format_wordwrap_explain'],
			'S_FORMAT_WORDWRAP_YES' => $format_wordwrap_yes,
			'S_FORMAT_WORDWRAP_NO' => $format_wordwrap_no,

			'L_FORMAT_IMAGE_RESIZE' => $lang['Format_image_resize'],
			'L_FORMAT_IMAGE_RESIZE_EXPLAIN' => $lang['Format_image_resize_explain'],
			'FORMAT_IMAGE_RESIZE' => $format_image_resize,

			'L_FORMAT_TRUNCATE_LINKS' => $lang['Format_truncate_links'],
			'L_FORMAT_TRUNCATE_LINKS_EXPLAIN' => $lang['Format_truncate_links_explain'],
			'S_FORMAT_TRUNCATE_LINKS_YES' => $format_truncate_links_yes,
			'S_FORMAT_TRUNCATE_LINKS_NO' => $format_truncate_links_no,

			//
			// Appearance
			//
			'L_APPEARANCE_TITLE' => $lang['Appearance_title'],

			'L_PAGINATION' => $lang['Article_pag'],
			'L_PAGINATION_EXPLAIN' => $lang['Article_pag_explain'],
			'PAGINATION' => $pagination,

			'L_SORT_METHOD' => $lang['Sort_method'],
			'L_SORT_METHOD_EXPLAIN' => $lang['Sort_method_explain'],
			'SORT_METHOD' => $sort_method_list,

			'L_SORT_ORDER' => $lang['Sort_order'],
			'L_SORT_ORDER_EXPLAIN' => $lang['Sort_order_explain'],
			'SORT_ORDER' => $sort_order_list,

			'L_STATS_LIST' => $lang['Stats_list'],
			'L_STATS_LIST_EXPLAIN' => $lang['Stats_list_explain'],
			'S_STATS_LIST_YES' => $stats_list_yes,
			'S_STATS_LIST_NO' => $stats_list_no,

			'L_HEADER_BANNER' => $lang['Header_banner'],
			'L_HEADER_BANNER_EXPLAIN' => $lang['Header_banner_explain'],
			'S_HEADER_BANNER_YES' => $header_banner_yes,
			'S_HEADER_BANNER_NO' => $header_banner_no,

			'CAT_COL' => $cat_col,
			'L_CAT_COL' => $lang['Cat_col'],

			'S_USE_SIMPLE_NAVIGATION_YES' => $use_simple_navigation_yes,
			'S_USE_SIMPLE_NAVIGATION_NO' => $use_simple_navigation_no,
			'L_USE_SIMPLE_NAVIGATION' => $lang['Use_simple_navigation'],
			'L_USE_SIMPLE_NAVIGATION_EXPLAIN' => $lang['Use_simple_navigation_explain'],

			'L_NFDAYS' => $lang['Nfdays'],
			'L_NFDAYSINFO' => $lang['Nfdaysinfo'],
			'SETTINGS_NEWDAYS' => $settings_newdays,

			//
			// Comments
			//
			'L_COMMENTS_TITLE' => $lang['Comments_title'],
			'L_COMMENTS_TITLE_EXPLAIN' => $lang['Comments_title_explain'],

			'L_USE_COMMENTS' => $lang['Use_comments'],
			'L_USE_COMMENTS_EXPLAIN' => $lang['Use_comments_explain'],
			'S_USE_COMMENTS_YES' => $use_comments_yes,
			'S_USE_COMMENTS_NO' => $use_comments_no,

			'L_INTERNAL_COMMENTS' => $lang['Internal_comments'],
			'L_INTERNAL_COMMENTS_EXPLAIN' => $lang['Internal_comments_explain'],
			'S_INTERNAL_COMMENTS_INTERNAL' => $internal_comments_internal,
			'S_INTERNAL_COMMENTS_PHPBB' => $internal_comments_phpbb,
			'L_INTERNAL_COMMENTS_INTERNAL' => $lang['Internal_comments_internal'],
			'L_INTERNAL_COMMENTS_PHPBB' => $lang['Internal_comments_phpBB'],

			'L_FORUM_ID' => $lang['Forum_id'],
			'L_FORUM_ID_EXPLAIN' => $lang['Forum_id_explain'],
			//'FORUM_LIST' => $this->get_forums( $comments_forum_id, false, 'comments_forum_id' ),
			'FORUM_LIST' => $portal_config['portal_backend'] != 'internal' ? $this->get_forums( $comments_forum_id, false, 'comments_forum_id' ) : 'not available',

			'L_AUTOGENERATE_COMMENTS' => $lang['Autogenerate_comments'],
			'L_AUTOGENERATE_COMMENTS_EXPLAIN' => $lang['Autogenerate_comments_explain'],
			'S_AUTOGENERATE_COMMENTS_YES' => $autogenerate_comments_yes,
			'S_AUTOGENERATE_COMMENTS_NO' => $autogenerate_comments_no,

			'L_ALLOW_COMMENT_WYSIWYG' => $lang['Allow_Wysiwyg'],
			'L_ALLOW_COMMENT_WYSIWYG_EXPLAIN' => $lang['Allow_Wysiwyg_explain'],
			'S_ALLOW_COMMENT_WYSIWYG_YES' => $allow_comment_wysiwyg_yes,
			'S_ALLOW_COMMENT_WYSIWYG_NO' => $allow_comment_wysiwyg_no,

			'L_ALLOW_COMMENT_HTML' => $lang['Allow_HTML'],
			'L_ALLOW_COMMENT_HTML_EXPLAIN' => $lang['Allow_html_explain'],
			'S_ALLOW_COMMENT_HTML_YES' => $allow_comment_html_yes,
			'S_ALLOW_COMMENT_HTML_NO' => $allow_comment_html_no,

			'L_ALLOW_COMMENT_BBCODE' => $lang['Allow_BBCode'],
			'L_ALLOW_COMMENT_BBCODE_EXPLAIN' => $lang['Allow_bbcode_explain'],
			'S_ALLOW_COMMENT_BBCODE_YES' => $allow_comment_bbcode_yes,
			'S_ALLOW_COMMENT_BBCODE_NO' => $allow_comment_bbcode_no,

			'L_ALLOW_COMMENT_SMILIES' => $lang['Allow_smilies'],
			'L_ALLOW_COMMENT_SMILIES_EXPLAIN' => $lang['Allow_smilies_explain'],
			'S_ALLOW_COMMENT_SMILIES_YES' => $allow_comment_smilies_yes,
			'S_ALLOW_COMMENT_SMILIES_NO' => $allow_comment_smilies_no,

			'L_ALLOWED_COMMENT_HTML_TAGS' => $lang['Allowed_tags'],
			'L_ALLOWED_COMMENT_HTML_TAGS_EXPLAIN' => $lang['Allowed_tags_explain'],
			'ALLOWED_COMMENT_HTML_TAGS' => $allowed_comment_html_tags,

			'L_ALLOW_COMMENT_IMAGES' => $lang['Allow_images'],
			'L_ALLOW_COMMENT_IMAGES_EXPLAIN' => $lang['Allow_images_explain'],
			'S_ALLOW_COMMENT_IMAGES_YES' => $allow_comment_images_yes,
			'S_ALLOW_COMMENT_IMAGES_NO' => $allow_comment_images_no,

			'L_ALLOW_COMMENT_LINKS' => $lang['Allow_links'],
			'L_ALLOW_COMMENT_LINKS_EXPLAIN' => $lang['Allow_links_explain'],
			'S_ALLOW_COMMENT_LINKS_YES' => $allow_comment_links_yes,
			'S_ALLOW_COMMENT_LINKS_NO' => $allow_comment_links_no,

			'L_COMMENT_LINKS_MESSAGE' => $lang['Allow_links_message'],
			'L_COMMENT_LINKS_MESSAGE_EXPLAIN' => $lang['Allow_links_message_explain'],
			'COMMENT_MESSAGE_LINK' => $no_comment_link_message,

			'L_COMMENT_IMAGES_MESSAGE' => $lang['Allow_images_message'],
			'L_COMMENT_IMAGES_MESSAGE_EXPLAIN' => $lang['Allow_images_message_explain'],
			'COMMENT_MESSAGE_IMAGE' => $no_comment_image_message,

			'L_COMMENT_MAX_SUBJECT_CHAR' => $lang['Max_subject_char'],
			'L_COMMENT_MAX_SUBJECT_CHAR_EXPLAIN' => $lang['Max_subject_char_explain'],
			'COMMENT_MAX_SUBJECT_CHAR' => $max_comment_subject_chars,

			'L_COMMENT_MAX_CHAR' => $lang['Max_char'],
			'L_COMMENT_MAX_CHAR_EXPLAIN' => $lang['Max_char_explain'],
			'COMMENT_MAX_CHAR' => $max_comment_chars,

			'L_COMMENT_FORMAT_WORDWRAP' => $lang['Format_wordwrap'],
			'L_COMMENT_FORMAT_WORDWRAP_EXPLAIN' => $lang['Format_wordwrap_explain'],
			'S_COMMENT_FORMAT_WORDWRAP_YES' => $format_comment_wordwrap_yes,
			'S_COMMENT_FORMAT_WORDWRAP_NO' => $format_comment_wordwrap_no,

			'L_COMMENT_FORMAT_IMAGE_RESIZE' => $lang['Format_image_resize'],
			'L_COMMENT_FORMAT_IMAGE_RESIZE_EXPLAIN' => $lang['Format_image_resize_explain'],
			'COMMENT_FORMAT_IMAGE_RESIZE' => $format_comment_image_resize,

			'L_COMMENT_FORMAT_TRUNCATE_LINKS' => $lang['Format_truncate_links'],
			'L_COMMENT_FORMAT_TRUNCATE_LINKS_EXPLAIN' => $lang['Format_truncate_links_explain'],
			'S_COMMENT_FORMAT_TRUNCATE_LINKS_YES' => $format_comment_truncate_links_yes,
			'S_COMMENT_FORMAT_TRUNCATE_LINKS_NO' => $format_comment_truncate_links_no,

			'L_COMMENTS_PAG' => $lang['Comments_pag'],
			'L_COMMENTS_PAG_EXPLAIN' => $lang['Comments_pag_explain'],
			'COMMENTS_PAG' => $comments_pag,

			'L_DEL_TOPIC' => $lang['Del_topic'],
			'L_DEL_TOPIC_EXPLAIN' => $lang['Del_topic_explain'],
			'S_DEL_TOPIC_YES' => $del_topic_yes,
			'S_DEL_TOPIC_NO' => $del_topic_no,

			//
			// Ratings
			//
			'L_RATINGS_TITLE' => $lang['Ratings_title'],
			'L_RATINGS_TITLE_EXPLAIN' => $lang['Ratings_title_explain'],

			'L_USE_RATINGS' => $lang['Use_ratings'],
			'L_USE_RATINGS_EXPLAIN' => $lang['Use_ratings_explain'],
			'S_USE_RATINGS_YES' => $use_ratings_yes,
			'S_USE_RATINGS_NO' => $use_ratings_no,

			'L_VOTES_CHECK_IP' => $lang['Votes_check_ip'],
			'L_VOTES_CHECK_IP_EXPLAIN' => $lang['Votes_check_ip_explain'],
			'S_VOTES_CHECK_IP_YES' => $votes_check_ip_yes,
			'S_VOTES_CHECK_IP_NO' => $votes_check_ip_no,

			'L_VOTES_CHECK_USERID' => $lang['Votes_check_userid'],
			'L_VOTES_CHECK_USERID_EXPLAIN' => $lang['Votes_check_userid_explain'],
			'S_VOTES_CHECK_USERID_YES' => $votes_check_userid_yes,
			'S_VOTES_CHECK_USERID_NO' => $votes_check_userid_no,

			//
			// Instructions
			//
			'L_INSTRUCTIONS_TITLE' => $lang['Instructions_title'],

			'L_PRE_TEXT_NAME' => $lang['Pre_text_name'],
			'L_PRE_TEXT_EXPLAIN' => $lang['Pre_text_explain'],
			'S_SHOW_PRETEXT' => $pretext_show,
			'S_HIDE_PRETEXT' => $pretext_hide,
			'S_DEFAULT_PRETEXT' => $pretext_default,

			'L_SHOW' => $lang['Show'],
			'L_HIDE' => $lang['Hide'],

			'L_PRE_TEXT_HEADER' => $lang['Pre_text_header'],
			'L_PT_HEADER' => $pt_header,

			'L_PRE_TEXT_BODY' => $lang['Pre_text_body'],
			'L_PT_BODY' => $pt_body,

			//
			// Notifications
			//
			'L_NOTIFICATIONS_TITLE' => $lang['Notifications_title'],

			'L_NOTIFY' => $lang['Notify'],
			'L_NOTIFY_EXPLAIN' => $lang['Notify_explain'],
			'L_EMAIL' => $lang['Email'],
			'L_PM' => $lang['PM'],

			'S_NOTIFY_NONE' => $notify_none,
			'S_NOTIFY_EMAIL' => $notify_email,
			'S_NOTIFY_PM' => $notify_pm,

			'L_NOTIFY_GROUP' => $lang['Notify_group'],
			'L_NOTIFY_GROUP_EXPLAIN' => $lang['Notify_group_explain'],
			'NOTIFY_GROUP' => $notify_group_list,

		));

		$template->pparse( 'body' );
	}
}
?>