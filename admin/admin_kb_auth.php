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
 *    $Id: admin_kb_auth.php,v 1.12 2005/12/08 15:04:25 jonohlsson Exp $
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
		$module['KB_title']['4_Permissions'] = $file;
		return;
	}	

	require( './pagestart.' . $phpEx );
	include( $phpbb_root_path . 'config.'.$phpEx );
	include( $phpbb_root_path . 'includes/functions_admin.'.$phpEx );
	include( $phpbb_root_path . 'includes/functions_search.' . $phpEx );
		
	include( $phpbb_root_path . 'kb/kb_common.' . $phpEx );
	
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
		$module['KB_title']['4_Permissions'] = 'modules/mx_kb/admin/' . $file;
		return;
	}	
	
	$mx_root_path = './../../../';
	$module_root_path = './../';
	
	define( 'MXBB_27x', file_exists( $mx_root_path . 'mx_login.php' ) );

	require( $mx_root_path . 'extension.inc' );	
	require( $mx_root_path . '/admin/pagestart.' . $phpEx );
	include( $phpbb_root_path . 'includes/functions_search.' . $phpEx );
	
	include( $module_root_path . 'kb/kb_common.' . $phpEx );	
}

$mx_kb->init();

//
// Start adminCP
//
if ( !isset( $HTTP_POST_VARS['submit'] ) )
{
	$s_kb_cat_list = $mx_kb->generate_jumpbox( '', 0, 0, 0, true ); 
	$template->set_filenames( array( 'body' => 'admin/kb_cat_select_body.tpl' ) );

	$template->assign_vars( array( 'L_KB_AUTH_TITLE' => $lang['KB_Auth_Title'],
			'L_KB_AUTH_EXPLAIN' => $lang['KB_Auth_Explain'],
			'L_SELECT_CAT' => $lang['Select_a_Category'],
			'S_KB_ACTION' => append_sid( "admin_kb_auth.$phpEx" ),
			'L_LOOK_UP_CAT' => $lang['Look_up_Category'], 
			'CAT_SELECT_TITLE' => $s_kb_cat_list ) 
		);

	include( $mx_root_path . 'admin/page_header_admin.' . $phpEx );
	$template->pparse( 'body' );
	include( $mx_root_path . 'admin/page_footer_admin.' . $phpEx );
}
else
{
	if ( !isset( $HTTP_GET_VARS['cat_id'] ) )
	{
		$cat_id = intval( $HTTP_POST_VARS['cat_id'] );

		$template->set_filenames( array( 'body' => 'admin/kb_cat_auth_body.tpl' ) );

		$template->assign_vars( array( 'L_ALBUM_AUTH_TITLE' => $lang['Album_Auth_Title'],
				'L_ALBUM_AUTH_EXPLAIN' => $lang['Album_Auth_Explain'],
				'L_SUBMIT' => $lang['Submit'],
				'L_RESET' => $lang['Reset'],

				'L_GROUPS' => $lang['Usergroups'],

				'L_VIEW' => $lang['View'],
				'L_UPLOAD' => $lang['Upload'],
				'L_RATE' => $lang['Rate'],
				'L_COMMENT' => $lang['Comment'],
				'L_EDIT' => $lang['Edit'],
				'L_DELETE' => $lang['Delete'],
				// 'L_APPROVAL' => $lang['Approval'],
				// 'L_APPROVAL_EDIT' => $lang['Approval_edit'],

				'L_IS_MODERATOR' => $lang['Is_Moderator'],
				'S_ALBUM_ACTION' => append_sid( "admin_kb_auth.$phpEx?cat_id=$cat_id" ), 
				) 
			); 
			
		//
		// Get the list of phpBB usergroups
		//
		$sql = "SELECT group_id, group_name
				FROM " . GROUPS_TABLE . "
				WHERE group_single_user <> " . true . "
				ORDER BY group_name ASC";
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
		$sql = "SELECT category_id, category_name, auth_view_groups, auth_post_groups, auth_rate_groups, auth_comment_groups, auth_edit_groups, auth_delete_groups, auth_approval_groups, auth_approval_edit_groups, auth_moderator_groups
				FROM " . KB_CATEGORIES_TABLE . "
				WHERE category_id = '$cat_id'";
		if ( !$result = $db->sql_query( $sql ) )
		{
			mx_message_die( GENERAL_ERROR, 'Could not get Category information', '', __LINE__, __FILE__, $sql );
		}

		$thiscat = $db->sql_fetchrow( $result );

		$view_groups = @explode( ',', $thiscat['auth_view_groups'] );
		$post_groups = @explode( ',', $thiscat['auth_post_groups'] );
		$rate_groups = @explode( ',', $thiscat['auth_rate_groups'] );
		$comment_groups = @explode( ',', $thiscat['auth_comment_groups'] );
		$edit_groups = @explode( ',', $thiscat['auth_edit_groups'] );
		$delete_groups = @explode( ',', $thiscat['auth_delete_groups'] );
		// $approval_groups = @explode( ',', $thiscat['auth_approval_groups'] );
		// $approval_edit_groups = @explode( ',', $thiscat['auth_approval_edit_groups'] );

		$moderator_groups = @explode( ',', $thiscat['auth_moderator_groups'] );

		for ( $i = 0; $i < count( $groupdata ); $i++ )
		{
			$template->assign_block_vars( 'grouprow', array( 'GROUP_ID' => $groupdata[$i]['group_id'],
					'GROUP_NAME' => $groupdata[$i]['group_name'],

					'VIEW_CHECKED' => ( in_array( $groupdata[$i]['group_id'], $view_groups ) ) ? 'checked="checked"' : '',

					'POST_CHECKED' => ( in_array( $groupdata[$i]['group_id'], $post_groups ) ) ? 'checked="checked"' : '',

					'RATE_CHECKED' => ( in_array( $groupdata[$i]['group_id'], $rate_groups ) ) ? 'checked="checked"' : '',

					'COMMENT_CHECKED' => ( in_array( $groupdata[$i]['group_id'], $comment_groups ) ) ? 'checked="checked"' : '',

					'EDIT_CHECKED' => ( in_array( $groupdata[$i]['group_id'], $edit_groups ) ) ? 'checked="checked"' : '',

					'DELETE_CHECKED' => ( in_array( $groupdata[$i]['group_id'], $delete_groups ) ) ? 'checked="checked"' : '',
					
					// 'APPROVAL_CHECKED' => ( in_array( $groupdata[$i]['group_id'], $approval_groups ) ) ? 'checked="checked"' : '',
					
					// 'APPROVAL_EDIT_CHECKED' => ( in_array( $groupdata[$i]['group_id'], $approval_edit_groups ) ) ? 'checked="checked"' : '',

					'MODERATOR_CHECKED' => ( in_array( $groupdata[$i]['group_id'], $moderator_groups ) ) ? 'checked="checked"' : '' ) 
				);
		}

		include( $mx_root_path . 'admin/page_header_admin.' . $phpEx );
		$template->pparse( 'body' );
		include( $mx_root_path . 'admin/page_footer_admin.' . $phpEx );
	}
	else
	{
		$cat_id = intval( $HTTP_GET_VARS['cat_id'] );

		$view_groups = @implode( ',', $HTTP_POST_VARS['view'] );
		$post_groups = @implode( ',', $HTTP_POST_VARS['post'] );
		$rate_groups = @implode( ',', $HTTP_POST_VARS['rate'] );
		$comment_groups = @implode( ',', $HTTP_POST_VARS['comment'] );
		$edit_groups = @implode( ',', $HTTP_POST_VARS['edit'] );
		$delete_groups = @implode( ',', $HTTP_POST_VARS['delete'] );
		// $approval_groups = @implode( ',', $HTTP_POST_VARS['approval'] );
		// $approval_edit_groups = @implode( ',', $HTTP_POST_VARS['approval_edit'] );

		$moderator_groups = @implode( ',', $HTTP_POST_VARS['moderator'] );

		$sql = "UPDATE " . KB_CATEGORIES_TABLE . "
				SET auth_view_groups = '$view_groups', auth_post_groups = '$post_groups', auth_rate_groups = '$rate_groups', auth_comment_groups = '$comment_groups', auth_edit_groups = '$edit_groups', auth_delete_groups = '$delete_groups', auth_approval_groups = '$approval_groups', auth_approval_edit_groups = '$approval_edit_groups',	auth_moderator_groups = '$moderator_groups'
				WHERE category_id = '$cat_id'";
		if ( !$result = $db->sql_query( $sql ) )
		{
			mx_message_die( GENERAL_ERROR, 'Could not update KB config table', '', __LINE__, __FILE__, $sql );
		} 
		
		$message = $lang['KB_Auth_successfully'] . '<br /><br />' . sprintf( $lang['Click_return_KB_auth'], '<a href="' . append_sid( "admin_kb_auth.$phpEx" ) . '">', '</a>' ) . '<br /><br />' . sprintf( $lang['Click_return_admin_index'], '<a href="' . append_sid( $mx_root_path . "admin/index.$phpEx?pane=right" ) . '">', '</a>' );

		mx_message_die( GENERAL_MESSAGE, $message );
	}
}

?>