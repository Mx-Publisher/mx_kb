<?php
/**
*
* @package MX-Publisher Module - mx_kb
* @version $Id: admin_cat_manage.php,v 1.10 2008/07/15 22:05:42 jonohlsson Exp $
* @copyright (c) 2002-2006 [Jon Ohlsson, Mohd Basri, wGEric, PHP Arena, pafileDB, CRLin] MX-Publisher Project Team
* @license http://opensource.org/licenses/gpl-license.php GNU General Public License v2
*
*/

if ( !defined( 'IN_PORTAL' ) || !defined( 'IN_ADMIN' ) )
{
	die( "Hacking attempt" );
}

class mx_kb_cat_manage extends mx_kb_admin
{
	function main( $action )
	{
		global $db, $images, $template, $lang, $phpEx, $mx_kb_functions, $mx_kb_cache, $kb_config, $phpbb_root_path, $module_root_path, $mx_root_path, $mx_request_vars, $portal_config;

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

		$cat_auth_levels = array( 'ALL', 'REG', 'PRIVATE', 'MOD', 'ADMIN' );
		$cat_auth_const = array( AUTH_ALL, AUTH_REG, AUTH_ACL, AUTH_MOD, AUTH_ADMIN );

		$cat_auth_approval_levels = array( 'NONE', 'MOD', 'ADMIN' );
		$cat_auth_approval_const = array( AUTH_ALL, AUTH_MOD, AUTH_ADMIN );

		$global_auth = array( 'auth_view', 'auth_edit', 'auth_delete', 'auth_post', 'auth_rate', 'auth_view_comment', 'auth_post_comment', 'auth_edit_comment', 'auth_delete_comment' );
		$global_approval_auth = array( 'auth_approval', 'auth_approval_edit' );

		$auth_select = array();
		$auth_select_approval = array();

		switch ( $mode )
		{
			case ( 'create' ):

				if ( !$_POST['submit'] )
				{
					$new_cat_name = stripslashes( $_POST['new_cat_name'] );

					$checked_yes = ' checked';
					$checked_no = '';

					//
					// Comments
					//
					$use_comments_yes = "";
					$use_comments_no = "";
					$use_comments_default = "checked=\"checked\"";

					$internal_comments_internal = "";
					$internal_comments_phpbb = "";
					$internal_comments_default = "checked=\"checked\"";

					$comments_forum_id = '-1';

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
					// Permissions
					//
					$cat_rowset['auth_view'] = 0;
					$cat_rowset['auth_post'] = 1;
					$cat_rowset['auth_edit'] = 1;
					$cat_rowset['auth_delete'] = 2;
					$cat_rowset['auth_approval'] = 0;
					$cat_rowset['auth_approval_edit'] = 0;
					$cat_rowset['auth_rate'] = 1;
					$cat_rowset['auth_view_comment'] = 1;
					$cat_rowset['auth_post_comment'] = 1;
					$cat_rowset['auth_edit_comment'] = 1;
					$cat_rowset['auth_delete_comment'] = 5;

					foreach( $global_auth as $auth )
					{
						$auth_select[$auth] = '&nbsp;<select name="' . $auth . '">';
						for( $k = 0; $k < count( $cat_auth_levels ); $k++ )
						{
							$selected = ( $cat_rowset[$auth] == $cat_auth_const[$k] ) ? ' selected="selected"' : '';
							$auth_select[$auth] .= '<option value="' . $cat_auth_const[$k] . '"' . $selected . '>' . $lang['Cat_' . $cat_auth_levels[$k]] . '</option>';
						}
						$auth_select[$auth] .= '</select>&nbsp;';
					}

					foreach( $global_approval_auth as $auth )
					{
						$auth_select_approval[$auth] = '&nbsp;<select name="' . $auth . '">';
						for( $k = 0; $k < count( $cat_auth_approval_levels ); $k++ )
						{
							$selected = ( $cat_rowset[$auth] == $cat_auth_approval_const[$k] ) ? ' selected="selected"' : '';
							$auth_select_approval[$auth] .= '<option value="' . $cat_auth_approval_const[$k] . '"' . $selected . '>' . $lang['Cat_' . $cat_auth_approval_levels[$k]] . '</option>';
						}
						$auth_select_approval[$auth] .= '</select>&nbsp;';
					}

					//
					// Generate page
					//
					$template->set_filenames( array( 'body' => 'admin/kb_cat_edit_body.tpl' ) );

					$template->assign_block_vars( 'switch_cat', array() );

					$template->assign_vars( array(
						'S_ACTION' => mx_append_sid( "admin_kb.$phpEx?action=cat_manage&mode=create" ),

						'L_EDIT_TITLE' => $lang['Create_cat'],
						'L_EDIT_DESCRIPTION' => $lang['Create_description'],
						'L_CATEGORY' => $lang['Category'],
						'L_DESCRIPTION' => $lang['Category_description'],
						'L_NUMBER_ITEMS' => $lang['Articles'],
						'L_CAT_SETTINGS' => $lang['Cat_settings'],
						'L_CREATE' => $lang['Create'],
						'L_PARENT' => $lang['Parent'],
						'L_NONE' => $lang['None'],

						'CHECKED_YES' => $checked_yes,
						'CHECKED_NO' => $checked_no,
						'L_CAT_ALLOWFILE' => $lang['Allow_file'],
						'L_CAT_ALLOWFILE_INFO' => $lang['Allow_file_info'],

						//'PARENT_LIST' => $this->generate_jumpbox( '', 0, 0, 0, true ),
						'PARENT_LIST' => $this->generate_jumpbox( ),
						'CAT_NAME' => $new_cat_name,
						'DESC' => '',
						'NUMBER_ITEMS' => '0',

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
						'FORUM_LIST' => $portal_config['portal_backend'] != 'internal' ? $this->get_forums( $comments_forum_id, true, 'comments_forum_id' ) : 'not available',
						//'FORUM_LIST' => $this->get_forums( $comments_forum_id, true, 'comments_forum_id' ),

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
						'S_VIEW_LEVEL' => $auth_select['auth_view'],

						'L_EDIT_LEVEL' => $lang['Edit_level'],
						'S_EDIT_LEVEL' => $auth_select['auth_edit'],

						'L_DELETE_LEVEL' => $lang['Delete_level'],
						'S_DELETE_LEVEL' => $auth_select['auth_delete'],

						'L_UPLOAD_LEVEL' => $lang['Upload_level'],
						'S_UPLOAD_LEVEL' => $auth_select['auth_post'],

						'L_APPROVAL_LEVEL' => $lang['Approval_level'],
						'S_APPROVAL_LEVEL' => $auth_select_approval['auth_approval'],

						'L_APPROVAL_EDIT_LEVEL' => $lang['Approval_edit_level'],
						'S_APPROVAL_EDIT_LEVEL' => $auth_select_approval['auth_approval_edit'],

						'L_RATE_LEVEL' => $lang['Rate_level'],
						'S_RATE_LEVEL' => $auth_select['auth_rate'],

						'L_VIEW_COMMENT_LEVEL' => $lang['View_Comment_level'],
						'S_VIEW_COMMENT_LEVEL' => $auth_select['auth_view_comment'],

						'L_POST_COMMENT_LEVEL' => $lang['Post_Comment_level'],
						'S_POST_COMMENT_LEVEL' => $auth_select['auth_post_comment'],

						'L_EDIT_COMMENT_LEVEL' => $lang['Edit_Comment_level'],
						'S_EDIT_COMMENT_LEVEL' => $auth_select['auth_edit_comment'],

						'L_DELETE_COMMENT_LEVEL' => $lang['Delete_Comment_level'],
						'S_DELETE_COMMENT_LEVEL' => $auth_select['auth_delete_comment'],

						'L_DISABLED' => $lang['Disabled'],

					));
				}
				else if ( $_POST['submit'] )
				{
					$cat_name = trim( $_POST['catname'] );

					if ( !$cat_name )
					{
						echo "Please put a category name in!";
					}

					$cat_desc = $_POST['catdesc'];
					$parent = intval( $_POST['parent'] );
					$cat_allow_file = intval( $_POST['cat_allow_file'] );

					$cat_use_comments = ( isset( $_POST['cat_allow_comments'] ) ) ? intval( $_POST['cat_allow_comments'] ) : 0;
					$cat_internal_comments = ( isset( $_POST['internal_comments'] ) ) ? intval( $_POST['internal_comments'] ) : 0;
					$cat_autogenerate_comments = ( isset( $_POST['autogenerate_comments'] ) ) ? intval( $_POST['autogenerate_comments'] ) : 0;
					$comments_forum_id = intval( $_POST['comments_forum_id'] );

					$cat_show_pretext = ( isset( $_POST['show_pretext'] ) ) ? intval( $_POST['show_pretext'] ) : 0;

					$cat_use_ratings = ( isset( $_POST['cat_allow_ratings'] ) ) ? intval( $_POST['cat_allow_ratings'] ) : 0;

					$cat_notify = ( isset( $_POST['notify'] ) ) ? intval( $_POST['notify'] ) : 0;
					$cat_notify_group = ( isset( $_POST['notify_group'] ) ) ? intval( $_POST['notify_group'] ) : 0;

					if ( $comments_forum_id == 0 )
					{
						mx_message_die(GENERAL_MESSAGE , 'Select a Forum');
					}

					$view_level = intval( $_POST['auth_view'] );
					$edit_level = intval( $_POST['auth_edit'] );
					$delete_level = intval( $_POST['auth_delete'] );
					$post_level = intval( $_POST['auth_post'] );
					$approval_level = intval( $_POST['auth_approval'] );
					$approval_edit_level = intval( $_POST['auth_approval_edit'] );

					$rate_level = intval( $_POST['auth_rate'] );
					$comment_view_level = intval( $_POST['auth_view_comment'] );
					$comment_post_level = intval( $_POST['auth_post_comment'] );
					$comment_edit_level = intval( $_POST['auth_edit_comment'] );
					$comment_delete_level = intval( $_POST['auth_delete_comment'] );

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

					$sql = "INSERT INTO " . KB_CATEGORIES_TABLE . " ( category_name, category_details, number_articles, cat_allow_file, parent, cat_order, auth_view, auth_post, auth_rate, auth_view_comment, auth_post_comment, auth_edit_comment, auth_delete_comment, auth_edit, auth_delete, auth_approval, auth_approval_edit, cat_allow_comments, internal_comments, autogenerate_comments, comments_forum_id, cat_allow_ratings, show_pretext, notify, notify_group )" . " VALUES
																	( '$cat_name', '$cat_desc', '0', '$cat_allow_file',              '$parent', '$cat_order', '$view_level', '$post_level', '$rate_level', '$comment_view_level', '$comment_post_level', '$comment_edit_level', '$comment_delete_level', '$edit_level', '$delete_level', '$approval_level', '$approval_edit_level', '$cat_use_comments', '$cat_internal_comments', '$cat_autogenerate_comments', '$comments_forum_id', '$cat_use_ratings', '$cat_show_pretext', '$cat_notify', '$cat_notify_group')";

					if ( !( $results = $db->sql_query( $sql ) ) )
					{
						mx_message_die( GENERAL_ERROR, "Could not create category", '', __LINE__, __FILE__, $sql );
					}

					$message = $lang['Cat_created'] . '<br /><br />' . sprintf( $lang['Click_return_cat_manager'], '<a href="' . mx_append_sid( "admin_kb.$phpEx?action=cat_manage" ) . '">', '</a>' ) . '<br /><br />' . sprintf( $lang['Click_return_admin_index'], '<a href="' . mx_append_sid( $mx_root_path . "admin/index.$phpEx?pane=right" ) . '">', '</a>' );

					mx_message_die( GENERAL_MESSAGE, $message );
				}
				break;

			case ( 'edit' ):

				if ( !$_POST['submit'] )
				{
					$cat_id = intval( $_GET['cat'] );

					$cat_name = $this->cat_rowset[$cat_id]['category_name'];
					$cat_desc = $this->cat_rowset[$cat_id]['category_details'];

					$checked_yes = ( $this->cat_rowset[$cat_id]['cat_allow_file'] ) ? ' checked' : '';
					$checked_no = ( !$this->cat_rowset[$cat_id]['cat_allow_file'] ) ? ' checked' : '';

					$number_articles = $this->items_in_cat($cat_id);
					$parent = $this->cat_rowset[$cat_id]['parent'];

					//
					// Comments
					//
					$use_comments_yes = ( $this->cat_rowset[$cat_id]['cat_allow_comments'] == 1 ) ? "checked=\"checked\"" : "";
					$use_comments_no = ( $this->cat_rowset[$cat_id]['cat_allow_comments'] == 0 ) ? "checked=\"checked\"" : "";
					$use_comments_default = ( $this->cat_rowset[$cat_id]['cat_allow_comments'] == -1 ) ? "checked=\"checked\"" : "";

					$internal_comments_internal = ( $this->cat_rowset[$cat_id]['internal_comments'] == 1 ) ? "checked=\"checked\"" : "";
					$internal_comments_phpbb = ( $this->cat_rowset[$cat_id]['internal_comments'] == 0 ) ? "checked=\"checked\"" : "";
					$internal_comments_default = ( $this->cat_rowset[$cat_id]['internal_comments'] == -1 ) ? "checked=\"checked\"" : "";

					$comments_forum_id = $this->cat_rowset[$cat_id]['comments_forum_id'];

					$autogenerate_comments_yes = ( $this->cat_rowset[$cat_id]['autogenerate_comments'] == 1 ) ? "checked=\"checked\"" : "";
					$autogenerate_comments_no = ( $this->cat_rowset[$cat_id]['autogenerate_comments'] == 0) ? "checked=\"checked\"" : "";
					$autogenerate_comments_default = ( $this->cat_rowset[$cat_id]['autogenerate_comments'] == -1 ) ? "checked=\"checked\"" : "";

					//
					// Ratings
					//
					$use_ratings_yes = ( $this->cat_rowset[$cat_id]['cat_allow_ratings'] == 1) ? "checked=\"checked\"" : "";
					$use_ratings_no = ( $this->cat_rowset[$cat_id]['cat_allow_ratings'] == 0) ? "checked=\"checked\"" : "";
					$use_ratings_default = ( $this->cat_rowset[$cat_id]['cat_allow_ratings'] == -1 ) ? "checked=\"checked\"" : "";

					//
					// Instructions
					//
					$pretext_show = ( $this->cat_rowset[$cat_id]['show_pretext'] == 1) ? "checked=\"checked\"" : "";
					$pretext_hide = ( $this->cat_rowset[$cat_id]['show_pretext'] == 0) ? "checked=\"checked\"" : "";
					$pretext_default = ( $this->cat_rowset[$cat_id]['show_pretext'] == -1 ) ? "checked=\"checked\"" : "";

					//
					// Notification
					//
					$notify_none = ( $this->cat_rowset[$cat_id]['notify'] == 0 ) ? "checked=\"checked\"" : "";
					$notify_pm = ( $this->cat_rowset[$cat_id]['notify'] == 1 ) ? "checked=\"checked\"" : "";
					$notify_email = ( $this->cat_rowset[$cat_id]['notify'] == 2 ) ? "checked=\"checked\"" : "";
					$notify_default = ( $this->cat_rowset[$cat_id]['notify'] == -1 ) ? "checked=\"checked\"" : "";

					$notify_group_list = mx_get_groups($this->cat_rowset[$cat_id]['notify_group'], 'notify_group');

					//
					// Permissions
					//
					foreach( $global_auth as $auth )
					{
						$auth_select[$auth] = '&nbsp;<select name="' . $auth . '">';
						for( $k = 0; $k < count( $cat_auth_levels ); $k++ )
						{
							$selected = ( $this->cat_rowset[$cat_id][$auth] == $cat_auth_const[$k] ) ? ' selected="selected"' : '';
							$auth_select[$auth] .= '<option value="' . $cat_auth_const[$k] . '"' . $selected . '>' . $lang['Cat_' . $cat_auth_levels[$k]] . '</option>';
						}
						$auth_select[$auth] .= '</select>&nbsp;';
					}

					foreach( $global_approval_auth as $auth )
					{
						$auth_select_approval[$auth] = '&nbsp;<select name="' . $auth . '">';
						for( $k = 0; $k < count( $cat_auth_approval_levels ); $k++ )
						{
							$selected = ( $this->cat_rowset[$cat_id][$auth] == $cat_auth_approval_const[$k] ) ? ' selected="selected"' : '';
							$auth_select_approval[$auth] .= '<option value="' . $cat_auth_approval_const[$k] . '"' . $selected . '>' . $lang['Cat_' . $cat_auth_approval_levels[$k]] . '</option>';
						}
						$auth_select_approval[$auth] .= '</select>&nbsp;';
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
						'L_NUMBER_ITEMS' => $lang['Articles'],
						'L_CAT_SETTINGS' => $lang['Cat_settings'],
						'L_CREATE' => $lang['Edit'],

						'L_PARENT' => $lang['Parent'],
						'L_NONE' => $lang['None'],

						'CHECKED_YES' => $checked_yes,
						'CHECKED_NO' => $checked_no,
						'L_CAT_ALLOWFILE' => $lang['Allow_file'],
						'L_CAT_ALLOWFILE_INFO' => $lang['Allow_file_info'],

						//'PARENT_LIST' => $this->generate_jumpbox( '', $cat_id, $parent, true, true ),
						'PARENT_LIST' => $this->generate_jumpbox( 0, 0, array( $parent => 1 )),

						'S_ACTION' => mx_append_sid( "admin_kb.$phpEx?action=cat_manage&mode=edit" ),
						'CAT_NAME' => $cat_name,
						'CAT_DESCRIPTION' => $cat_desc,
						'NUMBER_ITEMS' => $number_articles,

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
						'FORUM_LIST' => $this->get_forums( $comments_forum_id, true, 'comments_forum_id' ),

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
						'S_VIEW_LEVEL' => $auth_select['auth_view'],

						'L_EDIT_LEVEL' => $lang['Edit_level'],
						'S_EDIT_LEVEL' => $auth_select['auth_edit'],

						'L_DELETE_LEVEL' => $lang['Delete_level'],
						'S_DELETE_LEVEL' => $auth_select['auth_delete'],

						'L_UPLOAD_LEVEL' => $lang['Upload_level'],
						'S_UPLOAD_LEVEL' => $auth_select['auth_post'],

						'L_APPROVAL_LEVEL' => $lang['Approval_level'],
						'S_APPROVAL_LEVEL' => $auth_select_approval['auth_approval'],

						'L_APPROVAL_EDIT_LEVEL' => $lang['Approval_edit_level'],
						'S_APPROVAL_EDIT_LEVEL' => $auth_select_approval['auth_approval_edit'],

						'L_RATE_LEVEL' => $lang['Rate_level'],
						'S_RATE_LEVEL' => $auth_select['auth_rate'],

						'L_VIEW_COMMENT_LEVEL' => $lang['View_Comment_level'],
						'S_VIEW_COMMENT_LEVEL' => $auth_select['auth_view_comment'],

						'L_POST_COMMENT_LEVEL' => $lang['Post_Comment_level'],
						'S_POST_COMMENT_LEVEL' => $auth_select['auth_post_comment'],

						'L_EDIT_COMMENT_LEVEL' => $lang['Edit_Comment_level'],
						'S_EDIT_COMMENT_LEVEL' => $auth_select['auth_edit_comment'],

						'L_DELETE_COMMENT_LEVEL' => $lang['Delete_Comment_level'],
						'S_DELETE_COMMENT_LEVEL' => $auth_select['auth_delete_comment'],

						'L_DISABLED' => $lang['Disabled'],

						'S_HIDDEN' => '<input type="hidden" name="catid" value="' . $cat_id . '">' )
					);
				}
				else if ( $_POST['submit'] )
				{
					$cat_id = intval( $_POST['catid'] );
					$cat_name = trim( $_POST['catname'] );
					$cat_desc = $_POST['catdesc'];
					$number_articles = intval( $_POST['number_articles'] );
					$parent = intval( $_POST['parent'] );
					$cat_allow_file = intval( $_POST['cat_allow_file'] );

					$cat_use_comments = ( isset( $_POST['cat_allow_comments'] ) ) ? intval( $_POST['cat_allow_comments'] ) : 0;
					$cat_internal_comments = ( isset( $_POST['internal_comments'] ) ) ? intval( $_POST['internal_comments'] ) : 0;
					$cat_autogenerate_comments = ( isset( $_POST['autogenerate_comments'] ) ) ? intval( $_POST['autogenerate_comments'] ) : 0;
					$comments_forum_id = intval( $_POST['comments_forum_id'] );

					$cat_show_pretext = ( isset( $_POST['show_pretext'] ) ) ? intval( $_POST['show_pretext'] ) : 0;

					$cat_use_ratings = ( isset( $_POST['cat_allow_ratings'] ) ) ? intval( $_POST['cat_allow_ratings'] ) : 0;

					$cat_notify = ( isset( $_POST['notify'] ) ) ? intval( $_POST['notify'] ) : 0;
					$cat_notify_group = ( isset( $_POST['notify_group'] ) ) ? intval( $_POST['notify_group'] ) : 0;

					$view_level = intval( $_POST['auth_view'] );
					$edit_level = intval( $_POST['auth_edit'] );
					$delete_level = intval( $_POST['auth_delete'] );
					$post_level = intval( $_POST['auth_post'] );
					$approval_level = intval( $_POST['auth_approval'] );
					$approval_edit_level = intval( $_POST['auth_approval_edit'] );

					$rate_level = intval( $_POST['auth_rate'] );
					$comment_view_level = intval( $_POST['auth_view_comment'] );
					$comment_post_level = intval( $_POST['auth_post_comment'] );
					$comment_edit_level = intval( $_POST['auth_edit_comment'] );
					$comment_delete_level = intval( $_POST['auth_delete_comment'] );

					if ( !$cat_name )
					{
						echo "Please put a category name in!";
					}

					$sql = "UPDATE " . KB_CATEGORIES_TABLE . " SET category_name = '" . $cat_name . "', category_details = '" . $cat_desc . "', number_articles = '" . $number_articles . "', cat_allow_file = '" . $cat_allow_file . "', parent = '" . $parent . "', auth_view = '" . $view_level . "', auth_post = '" . $post_level . "', auth_rate = '" . $rate_level . "', auth_view_comment = '" . $comment_view_level . "', auth_post_comment = '" . $comment_post_level . "', auth_edit_comment = '" . $comment_edit_level . "', auth_delete_comment = '" . $comment_delete_level . "', auth_edit = '" . $edit_level . "', auth_delete = '" . $delete_level . "', auth_approval = '" . $approval_level . "', auth_approval_edit = '" . $approval_edit_level . "', cat_allow_comments = '" . $cat_use_comments . "', internal_comments = '" . $cat_internal_comments . "', autogenerate_comments = '" . $cat_autogenerate_comments . "', cat_allow_ratings = '" . $cat_use_ratings . "', notify = '" . $cat_notify . "', notify_group = '" . $cat_notify_group . "', show_pretext = '" . $cat_show_pretext . "', comments_forum_id = '" . $comments_forum_id . "' WHERE category_id = " . $cat_id;

					if ( !( $results = $db->sql_query( $sql ) ) )
					{
						mx_message_die( GENERAL_ERROR, "Could not update category", '', __LINE__, __FILE__, $sql );
					}

					$message = $lang['Cat_edited'] . '<br /><br />' . sprintf( $lang['Click_return_cat_manager'], '<a href="' . mx_append_sid( "admin_kb.$phpEx?action=cat_manage" ) . '">', '</a>' ) . '<br /><br />' . sprintf( $lang['Click_return_admin_index'], '<a href="' . mx_append_sid( $mx_root_path . "admin/index.$phpEx?pane=right" ) . '">', '</a>' );

					mx_message_die( GENERAL_MESSAGE, $message );
				}
				break;

			case ( 'delete' ):

				if ( !$_POST['submit'] )
				{
					$cat_id = $_GET['cat'];

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
						'L_DELETE_ITEMS' => $lang['Delete_all_articles'],

						'L_CAT_NAME' => $lang['Article_category'],
						'L_MOVE_CONTENTS' => $lang['Move_contents'],
						'L_DELETE' => $lang['Move_and_Delete'],

						'S_HIDDEN_FIELDS' => '<input type="hidden" name="catid" value="' . $cat_id . '">',
						//'S_SELECT_TO' => $this->generate_jumpbox( '', $cat_id, 0, true, true ),
						'S_SELECT_TO' => $this->generate_jumpbox( 0, 0, array( $cat_id => 1 )),
						'S_ACTION' => mx_append_sid( "admin_kb.$phpEx?action=cat_manage&mode=delete" ),

						'CAT_NAME' => $cat_name )
					);
				}
				else if ( $_POST['submit'] )
				{
					$new_category = $_POST['move_id'];
					$old_category = $_POST['catid'];

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

					$message = $lang['Cat_deleted'] . '<br /><br />' . sprintf( $lang['Click_return_cat_manager'], '<a href="' . mx_append_sid( "admin_kb.$phpEx?action=cat_manage" ) . '">', '</a>' ) . '<br /><br />' . sprintf( $lang['Click_return_admin_index'], '<a href="' . mx_append_sid( $mx_root_path . "admin/index.$phpEx?pane=right" ) . '">', '</a>' );

					mx_message_die( GENERAL_MESSAGE, $message );
				}
				break;

			default:

				if ( $mode == "up" )
				{
					$cat_id = $_GET['cat'];

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
					$cat_id = $_GET['cat'];

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
					'L_ITEMS' => $lang['Articles'],
					'L_ORDER' => $lang['Update_order'],

					'S_ACTION' => mx_append_sid( "admin_kb.$phpEx?action=cat_manage&mode=create" ) )
				);

				$this->admin_cat_main( 0 );

				break;
		}

		$template->pparse( 'body' );
	}
}
?>