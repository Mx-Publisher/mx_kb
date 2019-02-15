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
 *    $Id: admin_kb_cat.php,v 1.21 2005/12/11 16:18:03 jonohlsson Exp $
 */

/**
 * This program is free software; you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation; either version 2 of the License, or
 *    (at your option) any later version.
 */

if ( file_exists( './../viewtopic.php' ) )
{
	define( 'IN_PHPBB', 1 );
	define( 'IN_PORTAL', 1 );
	define( 'MXBB_MODULE', false );

	$phpbb_root_path = $module_root_path = $mx_root_path = "./../";
	require( $phpbb_root_path . 'extension.inc' );
		
	if ( !empty( $setmodules ) )
	{
		include_once( $phpbb_root_path . 'kb/includes/kb_constants.' . $phpEx );
		$file = basename( __FILE__ );
		$module['KB_title']['2_Cat_man'] = $file;
		return;
	}	

	require( './pagestart.' . $phpEx );
	include( $phpbb_root_path . 'config.'.$phpEx );
	include( $phpbb_root_path . 'includes/functions_admin.'.$phpEx );
	include( $phpbb_root_path . 'includes/functions_search.' . $phpEx );	
	
	include( $phpbb_root_path . 'kb/kb_common.' . $phpEx );
	include( $phpbb_root_path . 'kb/includes/functions_admin.' . $phpEx );
}
else 
{
	define( 'IN_PORTAL', 1 );
	define( 'MXBB_MODULE', true );
	
	if ( !empty( $setmodules ) )
	{
		$mx_root_path = './../';
		$module_root_path = './../modules/mx_kb/';
		require_once( $mx_root_path . 'extension.inc' );
		include_once( $module_root_path . 'kb/includes/kb_constants.' . $phpEx );
		
		$file = basename( __FILE__ );
		$module['KB_title']['2_Cat_man'] = 'modules/mx_kb/admin/' . $file;
		return;
	}	

	$mx_root_path = './../../../';
	$module_root_path = './../';

	define( 'MXBB_27x', file_exists( $mx_root_path . 'mx_login.php' ) );
		
	require( $mx_root_path . 'extension.inc' );	
	require( $mx_root_path . '/admin/pagestart.' . $phpEx );
	include( $phpbb_root_path . 'includes/functions_search.' . $phpEx );
	
	include( $module_root_path . 'kb/kb_common.' . $phpEx );
	include( $module_root_path . 'kb/includes/functions_admin.' . $phpEx );
}

// **********************************************************************
// Read language definition
// **********************************************************************
if ( !file_exists( $module_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_admin.' . $phpEx ) )
{
	include( $module_root_path . 'language/lang_english/lang_admin.' . $phpEx );
}
else
{
	include( $module_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_admin.' . $phpEx );
} 

$mx_kb->init();

$mode = $mx_request_vars->request('mode', MX_TYPE_NO_TAGS, '');

if ( empty($mode) )
{
	if ( $create )
	{
		$mode = 'create';
	}
	else if ( $edit )
	{
		$mode = 'edit';
	}
	else if ( $delete )
	{
		$mode = 'delete';
	}
	else
	{
		$mode = '';
	}
}

switch ( $mode )
{
	case ( 'create' ):

		if ( !$HTTP_POST_VARS['submit'] )
		{
			$new_cat_name = stripslashes( $HTTP_POST_VARS['new_cat_name'] ); 

			//
			// Comments
			//
			$use_comments_yes = "";
			$use_comments_no = "";
			$use_comments_default = "checked=\"checked\"";

			$internal_comments_internal = "";
			$internal_comments_phpbb = "";
			$internal_comments_default = "checked=\"checked\"";
				
			$autogenerate_comments_yes = "";
			$autogenerate_comments_no = "";
			$autogenerate_comments_default = "checked=\"checked\"";

			//
			// Ratings
			//
			$use_ratings_yes = "";
			$use_ratings_no = "";
			$use_ratings_default = "checked=\"checked\"";
				
			//
			// Instructions
			//
			$pretext_show = "";
			$pretext_hide = "";
			$pretext_default = "checked=\"checked\"";
				
			//
			// Notification
			//
			$notify_none = "";
			$notify_pm = "";
			$notify_email = "";
			$notify_default = "checked=\"checked\"";
				
			$notify_group_list = mx_get_groups('', 'notify_group');
							
			//
			// Generate page
			//
			$template->set_filenames( array( 'body' => 'admin/kb_cat_edit_body.tpl' ) );

			$template->assign_block_vars( 'switch_cat', array() );

			$template->assign_vars( array( 
				'S_ACTION' => append_sid( $module_root_path . "admin/admin_kb_cat.$phpEx?mode=create" ),
			
				'L_EDIT_TITLE' => $lang['Create_cat'],
				'L_EDIT_DESCRIPTION' => $lang['Create_description'],
				'L_CATEGORY' => $lang['Category'],
				'L_DESCRIPTION' => $lang['Category_description'],
				'L_NUMBER_ARTICLES' => $lang['Articles'],
				'L_CAT_SETTINGS' => $lang['Cat_settings'],
				'L_CREATE' => $lang['Create'],
				'L_PARENT' => $lang['Parent'],
				'L_NONE' => $lang['None'],

				'PARENT_LIST' => $mx_kb->generate_jumpbox( '', 0, 0, 0, true ),
				'CAT_NAME' => $new_cat_name,
				'DESC' => '',
				'NUMBER_ARTICLES' => '0',

				'L_DEFAULT' => $lang['Use_default'],	
				'L_YES' => $lang['Yes'],
				'L_NO' => $lang['No'],
				'L_NONE' => $lang['Acc_None'],
				
				//
				// Comments
				//
				'L_COMMENTS_TITLE' => $lang['Comments_title'],
				
				'L_USE_COMMENTS' => $lang['Use_comments'],
				'L_USE_COMMENTS_EXPLAIN' => $lang['Use_comments_explain'],
				'S_USE_COMMENTS_YES' => $use_comments_yes,
				'S_USE_COMMENTS_NO' => $use_comments_no,
				'S_USE_COMMENTS_DEFAULT' => $use_comments_default,
				
				'L_INTERNAL_COMMENTS' => $lang['Internal_comments'],
				'L_INTERNAL_COMMENTS_EXPLAIN' => $lang['Internal_comments_explain'],
				'S_INTERNAL_COMMENTS_INTERNAL' => $internal_comments_internal,
				'S_INTERNAL_COMMENTS_PHPBB' => $internal_comments_phpbb,
				'S_INTERNAL_COMMENTS_DEFAULT' => $internal_comments_default,
				'L_INTERNAL_COMMENTS_INTERNAL' => $lang['Internal_comments_internal'],			
				'L_INTERNAL_COMMENTS_PHPBB' => $lang['Internal_comments_phpBB'],				

				'L_FORUM_ID' => $lang['Forum_id'],
				'L_FORUM_ID_EXPLAIN' => $lang['Forum_id_explain'],
				'FORUM_LIST' => get_forums( ),
								
				'L_AUTOGENERATE_COMMENTS' => $lang['Autogenerate_comments'],
				'L_AUTOGENERATE_COMMENTS_EXPLAIN' => $lang['Autogenerate_comments_explain'],
				'S_AUTOGENERATE_COMMENTS_YES' => $autogenerate_comments_yes,
				'S_AUTOGENERATE_COMMENTS_NO' => $autogenerate_comments_no,
				'S_AUTOGENERATE_COMMENTS_DEFAULT' => $autogenerate_comments_default,
				
				//
				// Ratings
				//
				'L_RATINGS_TITLE' => $lang['Ratings_title'],
				
				'L_USE_RATINGS' => $lang['Use_ratings'],
				'L_USE_RATINGS_EXPLAIN' => $lang['Use_ratings_explain'],
				'S_USE_RATINGS_YES' => $use_ratings_yes,
				'S_USE_RATINGS_NO' => $use_ratings_no,	
				'S_USE_RATINGS_DEFAULT' => $use_ratings_default,	
				
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
				'S_NOTIFY_DEFAULT' => $notify_default,
			
				'L_NOTIFY_GROUP' => $lang['Notify_group'],
				'L_NOTIFY_GROUP_EXPLAIN' => $lang['Notify_group_explain'],
				'NOTIFY_GROUP' => $notify_group_list,
						
				//
				// Category permissions
				//
				'L_CAT_PERMISSIONS' => $lang['Category_Permissions'],
				'L_VIEW_LEVEL' => $lang['View_level'],
				'L_UPLOAD_LEVEL' => $lang['Upload_level'],
				'L_RATE_LEVEL' => $lang['Rate_level'],
				'L_COMMENT_LEVEL' => $lang['Comment_level'],
				'L_EDIT_LEVEL' => $lang['Edit_level'],
				'L_DELETE_LEVEL' => $lang['Delete_level'],
				'L_APPROVAL_LEVEL' => $lang['Approval_level'],
				'L_APPROVAL_EDIT_LEVEL' => $lang['Approval_edit_level'],
				'L_GUEST' => $lang['Forum_ALL'],
				'L_REG' => $lang['Forum_REG'],
				'L_PRIVATE' => $lang['Forum_PRIVATE'],
				'L_MOD' => $lang['Forum_MOD'],
				'L_ADMIN' => $lang['Forum_ADMIN'],
					
				'L_DISABLED' => $lang['Disabled'],					
				'VIEW_GUEST' => 'selected="selected"',
				'UPLOAD_REG' => 'selected="selected"',
				'RATE_REG' => 'selected="selected"',
				'COMMENT_REG' => 'selected="selected"',
				'EDIT_REG' => 'selected="selected"',
				'DELETE_MOD' => 'selected="selected"',
				'APPROVAL_DISABLED' => 'selected="selected"',
					
				'S_GUEST' => AUTH_ALL,
				'S_USER' => AUTH_REG,
				'S_PRIVATE' => AUTH_ACL,
				'S_MOD' => AUTH_MOD,
				'S_ADMIN' => AUTH_ADMIN
			));
		}
		else if ( $HTTP_POST_VARS['submit'] )
		{
			$cat_name = trim( $HTTP_POST_VARS['catname'] );

			if ( !$cat_name )
			{
				echo "Please put a category name in!";
			}

			$cat_desc = $HTTP_POST_VARS['catdesc'];
			$parent = intval( $HTTP_POST_VARS['parent'] );
			
			$cat_use_comments = ( isset( $HTTP_POST_VARS['cat_allow_comments'] ) ) ? intval( $HTTP_POST_VARS['cat_allow_comments'] ) : 0;
			$cat_internal_comments = ( isset( $HTTP_POST_VARS['internal_comments'] ) ) ? intval( $HTTP_POST_VARS['internal_comments'] ) : 0;
			$cat_autogenerate_comments = ( isset( $HTTP_POST_VARS['autogenerate_comments'] ) ) ? intval( $HTTP_POST_VARS['autogenerate_comments'] ) : 0;
			$comments_forum_id = intval( $HTTP_POST_VARS['forum_id'] );
	
			$cat_show_pretext = ( isset( $HTTP_POST_VARS['show_pretext'] ) ) ? intval( $HTTP_POST_VARS['show_pretext'] ) : 0;
			
			$cat_use_ratings = ( isset( $HTTP_POST_VARS['cat_allow_ratings'] ) ) ? intval( $HTTP_POST_VARS['cat_allow_ratings'] ) : 0;
			
			$cat_notify = ( isset( $HTTP_POST_VARS['notify'] ) ) ? intval( $HTTP_POST_VARS['notify'] ) : 0;
			$cat_notify_group = ( isset( $HTTP_POST_VARS['notify_group'] ) ) ? intval( $HTTP_POST_VARS['notify_group'] ) : 0;
						
			if ( $comments_forum_id == 0 )
			{
				mx_message_die(GENERAL_MESSAGE , 'Select a Forum');	
			}
			
			$view_level = intval( $HTTP_POST_VARS['auth_view'] );
			$post_level = intval( $HTTP_POST_VARS['auth_post'] );
			$rate_level = intval( $HTTP_POST_VARS['auth_rate'] );
			$comment_level = intval( $HTTP_POST_VARS['auth_comment'] );
			$edit_level = intval( $HTTP_POST_VARS['auth_edit'] );
			$delete_level = intval( $HTTP_POST_VARS['auth_delete'] );
			$approval_level = intval( $HTTP_POST_VARS['auth_approval'] );
			$approval_edit_level = intval( $HTTP_POST_VARS['auth_approval_edit'] );

			$sql = "SELECT MAX(cat_order) AS cat_order
			FROM " . KB_CATEGORIES_TABLE . " WHERE parent = $parent";
			
			if ( !( $result = $db->sql_query( $sql ) ) )
			{
				mx_message_die( GENERAL_ERROR, 'Could not obtain next type id', '', __LINE__, __FILE__, $sql );
			}

			if ( !( $id = $db->sql_fetchrow( $result ) ) )
			{
				mx_message_die( GENERAL_ERROR, 'Could not obtain next type id', '', __LINE__, __FILE__, $sql );
			}
			
			$cat_order = $id['cat_order'] + 10;

			$sql = "INSERT INTO " . KB_CATEGORIES_TABLE . " ( category_name, category_details, number_articles, parent, cat_order, auth_view, auth_post, auth_rate, auth_comment, auth_edit, auth_delete, auth_approval, auth_approval_edit, cat_allow_comments, internal_comments, autogenerate_comments, comments_forum_id, cat_allow_ratings, show_pretext, notify, notify_group )" . " VALUES 
															( '$cat_name', ' $cat_desc', '0',                 '$parent', '$cat_order', '$view_level', '$post_level', '$rate_level', '$comment_level', '$edit_level', '$delete_level', '$approval_level', '$approval_edit_level', '$cat_use_comments', '$cat_internal_comments', '$cat_autogenerate_comments', '$comments_forum_id', '$cat_use_ratings', '$cat_show_pretext', '$cat_notify', '$cat_notify_group')";

			if ( !( $results = $db->sql_query( $sql ) ) )
			{
				mx_message_die( GENERAL_ERROR, "Could not create category", '', __LINE__, __FILE__, $sql );
			}

			$message = $lang['Cat_created'] . '<br /><br />' . sprintf( $lang['Click_return_cat_manager'], '<a href="' . append_sid( "admin_kb_cat.$phpEx" ) . '">', '</a>' ) . '<br /><br />' . sprintf( $lang['Click_return_admin_index'], '<a href="' . append_sid( $mx_root_path . "admin/index.$phpEx?pane=right" ) . '">', '</a>' );

			mx_message_die( GENERAL_MESSAGE, $message );
		}
		break;

	case ( 'edit' ):

		if ( !$HTTP_POST_VARS['submit'] )
		{
			$cat_id = intval( $HTTP_GET_VARS['cat'] );

			$sql = "SELECT * FROM " . KB_CATEGORIES_TABLE . " WHERE category_id = " . $cat_id;

			if ( !( $results = $db->sql_query( $sql ) ) )
			{
				mx_message_die( GENERAL_ERROR, "Could not obtain category information", '', __LINE__, __FILE__, $sql );
			}
			
			if ( $kb_cat = $db->sql_fetchrow( $results ) )
			{
				$cat_name = $kb_cat['category_name'];
				$cat_desc = $kb_cat['category_details'];
				$number_articles = $kb_cat['number_articles'];
				$parent = $kb_cat['parent'];

				//
				// Comments
				//
				$use_comments_yes = ( $kb_cat['cat_allow_comments'] == 1 ) ? "checked=\"checked\"" : "";
				$use_comments_no = ( $kb_cat['cat_allow_comments'] == 0 ) ? "checked=\"checked\"" : "";
				$use_comments_default = ( $kb_cat['cat_allow_comments'] == -1 ) ? "checked=\"checked\"" : "";

				$internal_comments_internal = ( $kb_cat['internal_comments'] == 1 ) ? "checked=\"checked\"" : "";
				$internal_comments_phpbb = ( $kb_cat['internal_comments'] == 0 ) ? "checked=\"checked\"" : "";
				$internal_comments_default = ( $kb_cat['internal_comments'] == -1 ) ? "checked=\"checked\"" : "";
				
				$comments_forum_id = $kb_cat['comments_forum_id'];
				
				$autogenerate_comments_yes = ( $kb_cat['autogenerate_comments'] == 1 ) ? "checked=\"checked\"" : "";
				$autogenerate_comments_no = ( $kb_cat['autogenerate_comments'] == 0) ? "checked=\"checked\"" : "";
				$autogenerate_comments_default = ( $kb_cat['autogenerate_comments'] == -1 ) ? "checked=\"checked\"" : "";

				//
				// Ratings
				//
				$use_ratings_yes = ( $kb_cat['cat_allow_ratings'] == 1) ? "checked=\"checked\"" : "";
				$use_ratings_no = ( $kb_cat['cat_allow_ratings'] == 0) ? "checked=\"checked\"" : "";
				$use_ratings_default = ( $kb_cat['cat_allow_ratings'] == -1 ) ? "checked=\"checked\"" : "";
				
				//
				// Instructions
				//
				$pretext_show = ( $kb_cat['show_pretext'] == 1) ? "checked=\"checked\"" : "";
				$pretext_hide = ( $kb_cat['show_pretext'] == 0) ? "checked=\"checked\"" : "";
				$pretext_default = ( $kb_cat['show_pretext'] == -1 ) ? "checked=\"checked\"" : "";
				
				//
				// Notification
				//
				$notify_none = ( $kb_cat['notify'] == 0 ) ? "checked=\"checked\"" : "";
				$notify_pm = ( $kb_cat['notify'] == 1 ) ? "checked=\"checked\"" : "";
				$notify_email = ( $kb_cat['notify'] == 2 ) ? "checked=\"checked\"" : "";
				$notify_default = ( $kb_cat['notify'] == -1 ) ? "checked=\"checked\"" : "";
				
				$notify_group_list = mx_get_groups($kb_cat['notify_group'], 'notify_group');
			} 
			
			//
			// Generate page
			//
			$template->set_filenames( array( 'body' => 'admin/kb_cat_edit_body.tpl' ) );

			$template->assign_block_vars( 'switch_cat', array() );
			$template->assign_block_vars( 'switch_cat.switch_edit_category', array() );

			$template->assign_vars( array( 
				'L_EDIT_TITLE' => $lang['Edit_cat'],
				'L_EDIT_DESCRIPTION' => $lang['Edit_description'],
				'L_CATEGORY' => $lang['Category'],
				'L_DESCRIPTION' => $lang['Category_desc'],
				'L_NUMBER_ARTICLES' => $lang['Articles'],
				'L_CAT_SETTINGS' => $lang['Cat_settings'],
				'L_CREATE' => $lang['Edit'],

				'L_PARENT' => $lang['Parent'],
				'L_NONE' => $lang['None'],

				'PARENT_LIST' => $mx_kb->generate_jumpbox( '', $cat_id, $parent, true, true ),

				'S_ACTION' => append_sid( $module_root_path . "admin/admin_kb_cat.$phpEx?mode=edit" ),
				'CAT_NAME' => $cat_name,
				'CAT_DESCRIPTION' => $cat_desc,
				'NUMBER_ARTICLES' => $number_articles,

				'L_DEFAULT' => $lang['Use_default'],	
				'L_YES' => $lang['Yes'],
				'L_NO' => $lang['No'],
				'L_NONE' => $lang['Acc_None'],

				//
				// Comments
				//
				'L_COMMENTS_TITLE' => $lang['Comments_title'],
				
				'L_USE_COMMENTS' => $lang['Use_comments'],
				'L_USE_COMMENTS_EXPLAIN' => $lang['Use_comments_explain'],
				'S_USE_COMMENTS_YES' => $use_comments_yes,
				'S_USE_COMMENTS_NO' => $use_comments_no,
				'S_USE_COMMENTS_DEFAULT' => $use_comments_default,

				'L_INTERNAL_COMMENTS' => $lang['Internal_comments'],
				'L_INTERNAL_COMMENTS_EXPLAIN' => $lang['Internal_comments_explain'],
				'S_INTERNAL_COMMENTS_INTERNAL' => $internal_comments_internal,
				'S_INTERNAL_COMMENTS_PHPBB' => $internal_comments_phpbb,
				'S_INTERNAL_COMMENTS_DEFAULT' => $internal_comments_default,
				
				'L_INTERNAL_COMMENTS_INTERNAL' => $lang['Internal_comments_internal'],			
				'L_INTERNAL_COMMENTS_PHPBB' => $lang['Internal_comments_phpBB'],

				'L_FORUM_ID' => $lang['Forum_id'],
				'L_FORUM_ID_EXPLAIN' => $lang['Forum_id_explain'],
				'FORUM_LIST' => get_forums( $comments_forum_id ),
								
				'L_AUTOGENERATE_COMMENTS' => $lang['Autogenerate_comments'],
				'L_AUTOGENERATE_COMMENTS_EXPLAIN' => $lang['Autogenerate_comments_explain'],
				'S_AUTOGENERATE_COMMENTS_YES' => $autogenerate_comments_yes,
				'S_AUTOGENERATE_COMMENTS_NO' => $autogenerate_comments_no,
				'S_AUTOGENERATE_COMMENTS_DEFAULT' => $autogenerate_comments_default,
				
				//
				// Ratings
				//
				'L_RATINGS_TITLE' => $lang['Ratings_title'],

				'L_USE_RATINGS' => $lang['Use_ratings'],
				'L_USE_RATINGS_EXPLAIN' => $lang['Use_ratings_explain'],
				'S_USE_RATINGS_YES' => $use_ratings_yes,
				'S_USE_RATINGS_NO' => $use_ratings_no,	
				'S_USE_RATINGS_DEFAULT' => $use_ratings_default,	
				
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
				'S_NOTIFY_DEFAULT' => $notify_default,
			
				'L_NOTIFY_GROUP' => $lang['Notify_group'],
				'L_NOTIFY_GROUP_EXPLAIN' => $lang['Notify_group_explain'],
				'NOTIFY_GROUP' => $notify_group_list,
						
				//		
				// Cat permissions
				//
				'L_CAT_PERMISSIONS' => $lang['Category_Permissions'],
				'L_VIEW_LEVEL' => $lang['View_level'],
				'L_UPLOAD_LEVEL' => $lang['Upload_level'],
				'L_RATE_LEVEL' => $lang['Rate_level'],
				'L_COMMENT_LEVEL' => $lang['Comment_level'],
				'L_EDIT_LEVEL' => $lang['Edit_level'],
				'L_DELETE_LEVEL' => $lang['Delete_level'],
				'L_APPROVAL_LEVEL' => $lang['Approval_level'],
				'L_APPROVAL_EDIT_LEVEL' => $lang['Approval_edit_level'],
				'L_GUEST' => $lang['Forum_ALL'],
				'L_REG' => $lang['Forum_REG'],
				'L_PRIVATE' => $lang['Forum_PRIVATE'],
				'L_MOD' => $lang['Forum_MOD'],
				'L_ADMIN' => $lang['Forum_ADMIN'],
					
				'L_DISABLED' => $lang['Disabled'],
					
				'VIEW_GUEST' => ( $kb_cat['auth_view'] == AUTH_ALL ) ? 'selected="selected"' : '',
				'VIEW_REG' => ( $kb_cat['auth_view'] == AUTH_REG ) ? 'selected="selected"' : '',
				'VIEW_PRIVATE' => ( $kb_cat['auth_view'] == AUTH_ACL ) ? 'selected="selected"' : '',
				'VIEW_MOD' => ( $kb_cat['auth_view'] == AUTH_MOD ) ? 'selected="selected"' : '',
				'VIEW_ADMIN' => ( $kb_cat['auth_view'] == AUTH_ADMIN ) ? 'selected="selected"' : '',

				'UPLOAD_GUEST' => ( $kb_cat['auth_post'] == AUTH_ALL ) ? 'selected="selected"' : '',
				'UPLOAD_REG' => ( $kb_cat['auth_post'] == AUTH_REG ) ? 'selected="selected"' : '',
				'UPLOAD_PRIVATE' => ( $kb_cat['auth_post'] == AUTH_ACL ) ? 'selected="selected"' : '',
				'UPLOAD_MOD' => ( $kb_cat['auth_post'] == AUTH_MOD ) ? 'selected="selected"' : '',
				'UPLOAD_ADMIN' => ( $kb_cat['auth_post'] == AUTH_ADMIN ) ? 'selected="selected"' : '',

				'RATE_GUEST' => ( $kb_cat['auth_rate'] == AUTH_ALL ) ? 'selected="selected"' : '',
				'RATE_REG' => ( $kb_cat['auth_rate'] == AUTH_REG ) ? 'selected="selected"' : '',
				'RATE_PRIVATE' => ( $kb_cat['auth_rate'] == AUTH_ACL ) ? 'selected="selected"' : '',
				'RATE_MOD' => ( $kb_cat['auth_rate'] == AUTH_MOD ) ? 'selected="selected"' : '',
				'RATE_ADMIN' => ( $kb_cat['auth_rate'] == AUTH_ADMIN ) ? 'selected="selected"' : '',

				'COMMENT_GUEST' => ( $kb_cat['auth_comment'] == AUTH_ALL ) ? 'selected="selected"' : '',
				'COMMENT_REG' => ( $kb_cat['auth_comment'] == AUTH_REG ) ? 'selected="selected"' : '',
				'COMMENT_PRIVATE' => ( $kb_cat['auth_comment'] == AUTH_ACL ) ? 'selected="selected"' : '',
				'COMMENT_MOD' => ( $kb_cat['auth_comment'] == AUTH_MOD ) ? 'selected="selected"' : '',
				'COMMENT_ADMIN' => ( $kb_cat['auth_comment'] == AUTH_ADMIN ) ? 'selected="selected"' : '',

				'EDIT_REG' => ( $kb_cat['auth_edit'] == AUTH_REG ) ? 'selected="selected"' : '',
				'EDIT_PRIVATE' => ( $kb_cat['auth_edit'] == AUTH_ACL ) ? 'selected="selected"' : '',
				'EDIT_MOD' => ( $kb_cat['auth_edit'] == AUTH_MOD ) ? 'selected="selected"' : '',
				'EDIT_ADMIN' => ( $kb_cat['auth_edit'] == AUTH_ADMIN ) ? 'selected="selected"' : '',

				'DELETE_REG' => ( $kb_cat['auth_delete'] == AUTH_REG ) ? 'selected="selected"' : '',
				'DELETE_PRIVATE' => ( $kb_cat['auth_delete'] == AUTH_ACL ) ? 'selected="selected"' : '',
				'DELETE_MOD' => ( $kb_cat['auth_delete'] == AUTH_MOD ) ? 'selected="selected"' : '',
				'DELETE_ADMIN' => ( $kb_cat['auth_delete'] == AUTH_ADMIN ) ? 'selected="selected"' : '',

				'APPROVAL_DISABLED' => ( $kb_cat['auth_approval'] == AUTH_ALL ) ? 'selected="selected"' : '',
				'APPROVAL_MOD' => ( $kb_cat['auth_approval'] == AUTH_MOD ) ? 'selected="selected"' : '',
				'APPROVAL_ADMIN' => ( $kb_cat['auth_approval'] == AUTH_ADMIN ) ? 'selected="selected"' : '',	
					
				'APPROVAL_EDIT_DISABLED' => ( $kb_cat['auth_approval_edit'] == AUTH_ALL ) ? 'selected="selected"' : '',
				'APPROVAL_EDIT_MOD' => ( $kb_cat['auth_approval_edit'] == AUTH_MOD ) ? 'selected="selected"' : '',
				'APPROVAL_EDIT_ADMIN' => ( $kb_cat['auth_approval_edit'] == AUTH_ADMIN ) ? 'selected="selected"' : '',				

				'S_GUEST' => AUTH_ALL,
				'S_USER' => AUTH_REG,
				'S_PRIVATE' => AUTH_ACL,
				'S_MOD' => AUTH_MOD,
				'S_ADMIN' => AUTH_ADMIN,

				'S_HIDDEN' => '<input type="hidden" name="catid" value="' . $cat_id . '">' ) 
			);
		}
		else if ( $HTTP_POST_VARS['submit'] )
		{
			$cat_id = intval( $HTTP_POST_VARS['catid'] );
			$cat_name = trim( $HTTP_POST_VARS['catname'] );
			$cat_desc = $HTTP_POST_VARS['catdesc'];
			$number_articles = intval( $HTTP_POST_VARS['number_articles'] );
			$parent = intval( $HTTP_POST_VARS['parent'] );
			
			$cat_use_comments = ( isset( $HTTP_POST_VARS['cat_allow_comments'] ) ) ? intval( $HTTP_POST_VARS['cat_allow_comments'] ) : 0;
			$cat_internal_comments = ( isset( $HTTP_POST_VARS['internal_comments'] ) ) ? intval( $HTTP_POST_VARS['internal_comments'] ) : 0;
			$cat_autogenerate_comments = ( isset( $HTTP_POST_VARS['autogenerate_comments'] ) ) ? intval( $HTTP_POST_VARS['autogenerate_comments'] ) : 0;
			$comments_forum_id = intval( $HTTP_POST_VARS['forum_id'] );
	
			$cat_show_pretext = ( isset( $HTTP_POST_VARS['show_pretext'] ) ) ? intval( $HTTP_POST_VARS['show_pretext'] ) : 0;
			
			$cat_use_ratings = ( isset( $HTTP_POST_VARS['cat_allow_ratings'] ) ) ? intval( $HTTP_POST_VARS['cat_allow_ratings'] ) : 0;
			
			$cat_notify = ( isset( $HTTP_POST_VARS['notify'] ) ) ? intval( $HTTP_POST_VARS['notify'] ) : 0;
			$cat_notify_group = ( isset( $HTTP_POST_VARS['notify_group'] ) ) ? intval( $HTTP_POST_VARS['notify_group'] ) : 0;
		
			$view_level = intval( $HTTP_POST_VARS['auth_view'] );
			$post_level = intval( $HTTP_POST_VARS['auth_post'] );
			$rate_level = intval( $HTTP_POST_VARS['auth_rate'] );
			$comment_level = intval( $HTTP_POST_VARS['auth_comment'] );
			$edit_level = intval( $HTTP_POST_VARS['auth_edit'] );
			$delete_level = intval( $HTTP_POST_VARS['auth_delete'] );
			$approval_level = intval( $HTTP_POST_VARS['auth_approval'] );
			$approval_edit_level = intval( $HTTP_POST_VARS['auth_approval_edit'] );

			if ( !$cat_name )
			{
				echo "Please put a category name in!";
			}

			$sql = "UPDATE " . KB_CATEGORIES_TABLE . " SET category_name = '" . $cat_name . "', category_details = '" . $cat_desc . "', number_articles = '" . $number_articles . "', parent = '" . $parent . "', auth_view = '" . $view_level . "', auth_post = '" . $post_level . "', auth_rate = '" . $rate_level . "', auth_comment = '" . $comment_level . "', auth_edit = '" . $edit_level . "', auth_delete = '" . $delete_level . "', auth_approval = '" . $approval_level . "', auth_approval_edit = '" . $approval_edit_level . "', cat_allow_comments = '" . $cat_use_comments . "', internal_comments = '" . $cat_internal_comments . "', autogenerate_comments = '" . $cat_autogenerate_comments . "', cat_allow_ratings = '" . $cat_use_ratings . "', notify = '" . $cat_notify . "', notify_group = '" . $cat_notify_group . "', show_pretext = '" . $cat_show_pretext . "', comments_forum_id = '" . $comments_forum_id . "' WHERE category_id = " . $cat_id;

			if ( !( $results = $db->sql_query( $sql ) ) )
			{
				mx_message_die( GENERAL_ERROR, "Could not update category", '', __LINE__, __FILE__, $sql );
			}

			$message = $lang['Cat_edited'] . '<br /><br />' . sprintf( $lang['Click_return_cat_manager'], '<a href="' . append_sid( "admin_kb_cat.$phpEx" ) . '">', '</a>' ) . '<br /><br />' . sprintf( $lang['Click_return_admin_index'], '<a href="' . append_sid( $mx_root_path . "admin/index.$phpEx?pane=right" ) . '">', '</a>' );

			mx_message_die( GENERAL_MESSAGE, $message );
		}
		break;

	case ( 'delete' ):

		if ( !$HTTP_POST_VARS['submit'] )
		{
			$cat_id = $HTTP_GET_VARS['cat'];

			$sql = "SELECT *  
       		FROM " . KB_CATEGORIES_TABLE . " WHERE category_id = '" . $cat_id . "'";

			if ( !( $cat_result = $db->sql_query( $sql ) ) )
			{
				mx_message_die( GENERAL_ERROR, "Could not obtain category information", '', __LINE__, __FILE__, $sql );
			}

			if ( $category = $db->sql_fetchrow( $cat_result ) )
			{
				$cat_name = $category['category_name'];
			} 
			
			//
			// Generate page
			//
			$template->set_filenames( array( 'body' => 'admin/kb_cat_del_body.tpl' ) );

			$template->assign_vars( array( 
				'L_DELETE_TITLE' => $lang['Cat_delete_title'],
				'L_DELETE_DESCRIPTION' => $lang['Cat_delete_desc'],
				'L_CAT_DELETE' => $lang['Cat_delete_title'],
				'L_DELETE_ARTICLES' => $lang['Delete_all_articles'],

				'L_CAT_NAME' => $lang['Article_category'],
				'L_MOVE_CONTENTS' => $lang['Move_contents'],
				'L_DELETE' => $lang['Move_and_Delete'],

				'S_HIDDEN_FIELDS' => '<input type="hidden" name="catid" value="' . $cat_id . '">',
				'S_SELECT_TO' => $mx_kb->generate_jumpbox( '', $cat_id, 0, true, true ),
				'S_ACTION' => append_sid( $module_root_path . "admin/admin_kb_cat.$phpEx?mode=delete" ),

				'CAT_NAME' => $cat_name ) 
			);
		}
		else if ( $HTTP_POST_VARS['submit'] )
		{
			$new_category = $HTTP_POST_VARS['move_id'];
			$old_category = $HTTP_POST_VARS['catid'];

			if ( $new_category != '0' )
			{
				$sql = "UPDATE " . KB_ARTICLES_TABLE . " SET article_category_id = '$new_category' 
			   WHERE article_category_id = '$old_category'";

				if ( !( $move_result = $db->sql_query( $sql ) ) )
				{
					mx_message_die( GENERAL_ERROR, "Could not move articles", '', __LINE__, __FILE__, $sql );
				}

				$sql = "SELECT *  
       		   FROM " . KB_CATEGORIES_TABLE . " WHERE category_id = '$new_category'";

				if ( !( $cat_result = $db->sql_query( $sql ) ) )
				{
					mx_message_die( GENERAL_ERROR, "Could not get category data", '', __LINE__, __FILE__, $sql );
				}

				if ( $new_cat = $db->sql_fetchrow( $cat_result ) )
				{
					$new_articles = $new_cat['number_articles'];
				}

				$sql = "SELECT *  
       		   FROM " . KB_CATEGORIES_TABLE . " WHERE category_id = '$old_category'";

				if ( !( $oldcat_result = $db->sql_query( $sql ) ) )
				{
					mx_message_die( GENERAL_ERROR, "Could not get category data", '', __LINE__, __FILE__, $sql );
				}

				if ( $old_cat = $db->sql_fetchrow( $oldcat_result ) )
				{
					$old_articles = $old_cat['number_articles'];
				}

				$number_articles = $new_articles + $old_articles;

				$sql = "UPDATE " . KB_CATEGORIES_TABLE . " SET number_articles = '" . $number_articles . "' WHERE category_id = " . $new_category;

				if ( !( $number_result = $db->sql_query( $sql ) ) )
				{
					mx_message_die( GENERAL_ERROR, "Could not update articles number", '', __LINE__, __FILE__, $sql );
				}
			}
			else
			{
				$sql = "DELETE FROM " . KB_ARTICLES_TABLE . " 
		   		      WHERE article_category_id = " . $old_category;
				if ( !( $delete__articles = $db->sql_query( $sql ) ) )
				{
					mx_message_die( GENERAL_ERROR, "Could not delete articles", '', __LINE__, __FILE__, $sql );
				}
			}

			$sql = "DELETE FROM " . KB_CATEGORIES_TABLE . " WHERE category_id = $old_category";

			if ( !( $delete_result = $db->sql_query( $sql ) ) )
			{
				mx_message_die( GENERAL_ERROR, "Could not delete category", '', __LINE__, __FILE__, $sql );
			}

			$message = $lang['Cat_deleted'] . '<br /><br />' . sprintf( $lang['Click_return_cat_manager'], '<a href="' . append_sid( "admin_kb_cat.$phpEx" ) . '">', '</a>' ) . '<br /><br />' . sprintf( $lang['Click_return_admin_index'], '<a href="' . append_sid( $mx_root_path . "admin/index.$phpEx?pane=right" ) . '">', '</a>' );

			mx_message_die( GENERAL_MESSAGE, $message );
		}
		break;

	default:

		if ( $mode == "up" )
		{
			$cat_id = $HTTP_GET_VARS['cat'];

			$sql = "SELECT *  
	  	   FROM " . KB_CATEGORIES_TABLE . " 
		   WHERE category_id = $cat_id";

			if ( !( $result = $db->sql_query( $sql ) ) )
			{
				mx_message_die( GENERAL_ERROR, "Could not get category data", '', __LINE__, __FILE__, $sql );
			}

			if ( $category = $db->sql_fetchrow( $result ) )
			{
				$parent = $category['parent'];
				$old_pos = $category['cat_order'];
				$new_pos = $old_pos-10;
			}

			$sql = "UPDATE " . KB_CATEGORIES_TABLE . " SET
	  	   cat_order = '" . $old_pos . "' 
		   WHERE parent = " . $parent . " AND cat_order = " . $new_pos;

			if ( !( $result = $db->sql_query( $sql ) ) )
			{
				mx_message_die( GENERAL_ERROR, "Could not update order", '', __LINE__, __FILE__, $sql );
			}

			$sql = "UPDATE " . KB_CATEGORIES_TABLE . " SET
	  	   cat_order = '" . $new_pos . "' 
		   WHERE category_id = " . $cat_id;

			if ( !( $result = $db->sql_query( $sql ) ) )
			{
				mx_message_die( GENERAL_ERROR, "Could not update order", '', __LINE__, __FILE__, $sql );
			}
		}

		if ( $mode == "down" )
		{
			$cat_id = $HTTP_GET_VARS['cat'];

			$sql = "SELECT *  
	  	   FROM " . KB_CATEGORIES_TABLE . " 
		   WHERE category_id = $cat_id";

			if ( !( $result = $db->sql_query( $sql ) ) )
			{
				mx_message_die( GENERAL_ERROR, "Could not get category data", '', __LINE__, __FILE__, $sql );
			}

			if ( $category = $db->sql_fetchrow( $result ) )
			{
				$parent = $category['parent'];
				$old_pos = $category['cat_order'];
				$new_pos = $old_pos + 10;
			}

			$sql = "UPDATE " . KB_CATEGORIES_TABLE . " SET
	  	   cat_order = '" . $old_pos . "' 
		   WHERE parent = " . $parent . " AND cat_order = " . $new_pos;

			if ( !( $result = $db->sql_query( $sql ) ) )
			{
				mx_message_die( GENERAL_ERROR, "Could not update order", '', __LINE__, __FILE__, $sql );
			}

			$sql = "UPDATE " . KB_CATEGORIES_TABLE . " SET
	  	   cat_order = '" . $new_pos . "' 
		   WHERE category_id = " . $cat_id;

			if ( !( $result = $db->sql_query( $sql ) ) )
			{
				mx_message_die( GENERAL_ERROR, "Could not update order", '', __LINE__, __FILE__, $sql );
			}
		} 
		
		// Generate page
		
		$template->set_filenames( array( 'body' => 'admin/kb_cat_admin_body.tpl' ) );

		$template->assign_vars( array( 
			'L_CAT_TITLE' => $lang['Panel_cat_title'],
			'L_CAT_EXPLAIN' => $lang['Panel_cat_explain'],

			'L_CREATE_CAT' => $lang['Create_cat'],
			'L_CREATE' => $lang['Create'],
			'L_CATEGORY' => $lang['Article_category'],
			'L_ACTION' => $lang['Art_action'],
			'L_ARTICLES' => $lang['Articles'],
			'L_ORDER' => $lang['Update_order'],

			'S_ACTION' => append_sid( $module_root_path . "admin/admin_kb_cat.$phpEx?mode=create" ) ) 
		); 
		
		admin_cat_main( 0 );
		
		break;
		
}

include( $mx_root_path . 'admin/page_header_admin.' . $phpEx );
$template->pparse( 'body' );
include_once( $mx_root_path . 'admin/page_footer_admin.' . $phpEx );

?>