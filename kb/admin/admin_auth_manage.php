<?php
/**
*
* @package MX-Publisher Module - mx_kb
* @version $Id: admin_auth_manage.php,v 1.9 2008/07/15 22:05:41 jonohlsson Exp $
* @copyright (c) 2002-2006 [Jon Ohlsson, Mohd Basri, wGEric, PHP Arena, pafileDB, CRLin] MX-Publisher Project Team
* @license http://opensource.org/licenses/gpl-license.php GNU General Public License v2
*
*/

if ( !defined( 'IN_PORTAL' ) || !defined( 'IN_ADMIN' ) )
{
	die( "Hacking attempt" );
}

class mx_kb_auth_manage extends mx_kb_admin
{
	function main( $action )
	{
		global $db, $images, $template, $lang, $phpEx, $mx_kb_functions, $mx_kb_cache, $kb_config, $phpbb_root_path, $module_root_path, $mx_root_path, $mx_request_vars;

		//
		// Start adminCP
		//
		if ( !isset( $_POST['submit'] ) )
		{
			//$s_kb_cat_list = $this->generate_jumpbox( '', 0, 0, 0, true );
			$s_kb_cat_list = $this->generate_jumpbox( );
			$template->set_filenames( array( 'body' => 'admin/kb_cat_select_body.tpl' ) );

			$template->assign_vars( array(
				'L_AUTH_TITLE' => $lang['KB_Auth_Title'],
				'L_AUTH_EXPLAIN' => $lang['KB_Auth_Explain'],
				'L_SELECT_CAT' => $lang['Select_a_Category'],
				'S_ACTION' => mx_append_sid( "admin_kb.$phpEx?action=auth_manage" ),
				'L_LOOK_UP_CAT' => $lang['Look_up_Category'],
				'CAT_SELECT_TITLE' => $s_kb_cat_list
			));

			$template->pparse( 'body' );
		}
		else
		{
			if ( !isset( $_GET['cat_id'] ) )
			{
				$cat_id = $mx_request_vars->request('cat_id', MX_TYPE_INT, '');

				$template->set_filenames( array( 'body' => 'admin/kb_cat_auth_body.tpl' ) );

				$template->assign_vars( array(
					'L_AUTH_TITLE' => $lang['Album_Auth_Title'],
					'L_AUTH_EXPLAIN' => $lang['Album_Auth_Explain'],
					'L_SUBMIT' => $lang['Submit'],
					'L_RESET' => $lang['Reset'],

					'L_GROUPS' => $lang['Usergroups'],

					'L_VIEW' => $lang['View'],
					'L_EDIT' => $lang['Edit'],
					'L_DELETE' => $lang['Delete'],
					'L_UPLOAD' => $lang['Upload'],
					'L_RATE' => $lang['Rate'],
					'L_VIEW_COMMENT' => $lang['View_Comment_level'],
					'L_POST_COMMENT' => $lang['Post_Comment_level'],
					'L_EDIT_COMMENT' => $lang['Edit_Comment_level'],
					'L_DELETE_COMMENT' => $lang['Delete_Comment_level'],

					'L_IS_MODERATOR' => $lang['Is_Moderator'],
					'S_ACTION' => mx_append_sid( "admin_kb.$phpEx?action=auth_manage&cat_id=$cat_id" ),
				));

				//
				// Get the list of phpBB usergroups
				//
				switch (PORTAL_BACKEND)
				{
					case 'internal':
					case 'phpbb2':
						$sql = "SELECT group_id, group_name
							FROM " . GROUPS_TABLE . "
							WHERE group_single_user <> " . TRUE . "
							ORDER BY group_name ASC";
						break;
					case 'phpbb3':
						$sql = "SELECT group_id, group_name
							FROM " . GROUPS_TABLE . "
							WHERE group_name NOT IN ('BOTS', 'GUESTS')
							ORDER BY group_name ASC";
						break;
				}

				if ( !( $result = $db->sql_query( $sql ) ) )
				{
					mx_message_die( GENERAL_ERROR, 'Could not get group list', '', __LINE__, __FILE__, $sql );
				}

				while ( $kb_row = $db->sql_fetchrow( $result ) )
				{
					$groupdata[] = $kb_row;
				}

				//
				// Get info of this cat
				//
				$sql = "SELECT *
						FROM " . KB_CATEGORIES_TABLE . "
						WHERE category_id = '$cat_id'";
				if ( !$result = $db->sql_query( $sql ) )
				{
					mx_message_die( GENERAL_ERROR, 'Could not get Category information', '', __LINE__, __FILE__, $sql );
				}

				$thiscat = $db->sql_fetchrow( $result );

				$view_groups = @explode( ',', $thiscat['auth_view_groups'] );
				$edit_groups = @explode( ',', $thiscat['auth_edit_groups'] );
				$delete_groups = @explode( ',', $thiscat['auth_delete_groups'] );
				$post_groups = @explode( ',', $thiscat['auth_post_groups'] );
				$rate_groups = @explode( ',', $thiscat['auth_rate_groups'] );
				$comment_view_groups = @explode( ',', $thiscat['auth_view_comment_groups'] );
				$comment_post_groups = @explode( ',', $thiscat['auth_post_comment_groups'] );
				$comment_edit_groups = @explode( ',', $thiscat['auth_edit_comment_groups'] );
				$comment_delete_groups = @explode( ',', $thiscat['auth_delete_comment_groups'] );

				$moderator_groups = @explode( ',', $thiscat['auth_moderator_groups'] );

				for ( $i = 0; $i < count( $groupdata ); $i++ )
				{
					$template->assign_block_vars( 'grouprow', array(
						'GROUP_ID' => $groupdata[$i]['group_id'],
						'GROUP_NAME' => $groupdata[$i]['group_name'],
						'VIEW_CHECKED' => $thiscat['auth_view'] != AUTH_ACL ? 'disabled' : (( in_array( $groupdata[$i]['group_id'], $view_groups ) ) ? 'checked="checked"' : ''),
						'EDIT_CHECKED' => $thiscat['auth_edit'] != AUTH_ACL ? 'disabled' : (( in_array( $groupdata[$i]['group_id'], $edit_groups ) ) ? 'checked="checked"' : ''),
						'DELETE_CHECKED' => $thiscat['auth_delete'] != AUTH_ACL ? 'disabled' : (( in_array( $groupdata[$i]['group_id'], $delete_groups ) ) ? 'checked="checked"' : ''),
						'POST_CHECKED' => $thiscat['auth_post'] != AUTH_ACL ? 'disabled' : (( in_array( $groupdata[$i]['group_id'], $post_groups ) ) ? 'checked="checked"' : ''),
						'RATE_CHECKED' => $thiscat['auth_rate'] != AUTH_ACL ? 'disabled' : (( in_array( $groupdata[$i]['group_id'], $rate_groups ) ) ? 'checked="checked"' : ''),
						'COMMENT_VIEW_CHECKED' => $thiscat['auth_view_comment'] != AUTH_ACL ? 'disabled' : (( in_array( $groupdata[$i]['group_id'], $comment_view_groups ) ) ? 'checked="checked"' : ''),
						'COMMENT_POST_CHECKED' => $thiscat['auth_post_comment'] != AUTH_ACL ? 'disabled' : (( in_array( $groupdata[$i]['group_id'], $comment_post_groups ) ) ? 'checked="checked"' : ''),
						'COMMENT_EDIT_CHECKED' => $thiscat['auth_edit_comment'] != AUTH_ACL ? 'disabled' : (( in_array( $groupdata[$i]['group_id'], $comment_edit_groups ) ) ? 'checked="checked"' : ''),
						'COMMENT_DELETE_CHECKED' => $thiscat['auth_delete_comment'] != AUTH_ACL ? 'disabled' : (( in_array( $groupdata[$i]['group_id'], $comment_delete_groups ) ) ? 'checked="checked"' : ''),
						'MODERATOR_CHECKED' => (( in_array( $groupdata[$i]['group_id'], $moderator_groups ) ) ? 'checked="checked"' : '')
					));
				}

				$template->pparse( 'body' );
			}
			else
			{
				$cat_id = $mx_request_vars->request('cat_id', MX_TYPE_INT, '');

				$view_groups = @implode( ',', $_POST['view'] );
				$edit_groups = @implode( ',', $_POST['edit'] );
				$delete_groups = @implode( ',', $_POST['delete'] );
				$post_groups = @implode( ',', $_POST['post'] );
				$rate_groups = @implode( ',', $_POST['rate'] );
				$comment_view_groups = @implode( ',', $_POST['view_comment'] );
				$comment_post_groups = @implode( ',', $_POST['post_comment'] );
				$comment_edit_groups = @implode( ',', $_POST['edit_comment'] );
				$comment_delete_groups = @implode( ',', $_POST['delete_comment'] );
				$moderator_groups = @implode( ',', $_POST['moderator'] );

				$sql = "UPDATE " . KB_CATEGORIES_TABLE . "
						SET auth_view_groups = '$view_groups', auth_post_groups = '$post_groups', auth_rate_groups = '$rate_groups', auth_view_comment_groups = '$comment_view_groups', auth_post_comment_groups = '$comment_post_groups', auth_edit_comment_groups = '$comment_edit_groups', auth_delete_comment_groups = '$comment_delete_groups', auth_edit_groups = '$edit_groups', auth_delete_groups = '$delete_groups', auth_approval_groups = '$approval_groups', auth_approval_edit_groups = '$approval_edit_groups',	auth_moderator_groups = '$moderator_groups'
						WHERE category_id = '$cat_id'";
				if ( !$result = $db->sql_query( $sql ) )
				{
					mx_message_die( GENERAL_ERROR, 'Could not update KB config table', '', __LINE__, __FILE__, $sql );
				}

				$message = $lang['KB_Auth_successfully'] . '<br /><br />' . sprintf( $lang['Click_return_KB_auth'], '<a href="' . mx_append_sid( "admin_kb.$phpEx?action=auth_manage" ) . '">', '</a>' ) . '<br /><br />' . sprintf( $lang['Click_return_admin_index'], '<a href="' . mx_append_sid( $mx_root_path . "admin/index.$phpEx?pane=right" ) . '">', '</a>' );

				mx_message_die( GENERAL_MESSAGE, $message );
			}
		}
	}
}
?>