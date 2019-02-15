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
 *    $Id: functions_admin.php,v 1.1 2005/12/11 16:18:04 jonohlsson Exp $
 */

/**
 * This program is free software; you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation; either version 2 of the License, or
 *    (at your option) any later version.
 */
 
if ( !defined( 'IN_PORTAL' ) )
{
	die( "Hacking attempt" );
}

function admin_cat_main( $cat_parent = 0, $depth = 0 )
{
	global $mx_kb, $phpbb_root_path, $template, $phpEx, $lang, $images, $module_root_path;

	$pre = str_repeat( '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $depth );
	if ( isset( $mx_kb->subcat_rowset[$cat_parent] ) )
	{
		foreach( $mx_kb->subcat_rowset[$cat_parent] as $category_id => $category )
		{
			$category_details = $category['category_details'];
			$category_articles = $category['number_articles'];

			$category_id = $category['category_id'];
			$category_name = $category['category_name'];
			$temp_url = append_sid( $module_root_path . "kb.$phpEx?mode=cat&amp;cat=$category_id" );
			$category_link = '<a href="' . $temp_url . '" class="gen">' . $category_name . '</a>';

			$temp_url = append_sid( $module_root_path . "admin/admin_kb_cat.$phpEx?mode=edit&amp;cat=$category_id" );
			$edit = '<a href="' . $temp_url . '"><img src="' . $phpbb_root_path . $images['icon_edit'] . '" border="0" alt="' . $lang['Edit'] . '"></a>';

			$temp_url = append_sid( $module_root_path . "admin/admin_kb_cat.$phpEx?mode=delete&amp;cat=$category_id" );
			$delete = '<a href="' . $temp_url . '" class="gen"><img src="' . $phpbb_root_path . $images['icon_delpost'] . '" border="0" alt="' . $lang['Delete'] . '"></a>';

			$temp_url = append_sid( $module_root_path . "admin/admin_kb_cat.$phpEx?mode=up&amp;cat=$category_id" );
			$up = '<a href="' . $temp_url . '" class="gen">' . $lang['Move_up'] . '</a>';

			$temp_url = append_sid( $module_root_path . "admin/admin_kb_cat.$phpEx?mode=down&amp;cat=$category_id" );
			$down = '<a href="' . $temp_url . '" class="gen">' . $lang['Move_down'] . '</a>';

			$row_color = ( !( $ss % 2 ) ) ? $theme['td_color1'] : $theme['td_color2'];
			$row_class = ( !( $ss % 2 ) ) ? $theme['td_class1'] : $theme['td_class2'];

			$template->assign_block_vars( 'catrow', array( 
				'CATEGORY' => $category_link,
				'CAT_DESCRIPTION' => $category_details,
				'CAT_ARTICLES' => $category_articles,

				'U_EDIT' => $edit,
				'U_DELETE' => $delete,
				'U_UP' => $up,
				'U_DOWN' => $down,

				'ROW_COLOR' => '#' . $row_color,
				'ROW_CLASS' => $row_class,
				'PRE' => $pre
			));
						
			admin_cat_main( $category_id, $depth + 1 );
		}
		return;
	}
	return;
}

function get_forums( $sel_id = 0 )
{
	global $db;

	$sql = "SELECT forum_id, forum_name
		FROM " . FORUMS_TABLE;

	if ( !$result = $db->sql_query( $sql ) )
	{
		mx_message_die( GENERAL_ERROR, "Couldn't get list of forums", "", __LINE__, __FILE__, $sql );
	}

	$forumlist = '<select name="forum_id">';

	if ( $sel_id == 0 )
	{
		$forumlist .= '<option value="0" selected > Select a Forum !</option>';
	}
	
	while ( $row = $db->sql_fetchrow( $result ) )
	{
		if ( $sel_id == $row['forum_id'] )
		{
			$status = "selected";
		}
		else
		{
			$status = '';
		}
		$forumlist .= '<option value="' . $row['forum_id'] . '" ' . $status . '>' . $row['forum_name'] . '</option>';
	}

	$forumlist .= '</select>';

	return $forumlist;
}

?>