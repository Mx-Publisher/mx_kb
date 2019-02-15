<?php
/**
*
* @package MX-Publisher Module - mx_kb
* @version $Id: functions_admin.php,v 1.10 2008/06/03 20:10:15 jonohlsson Exp $
* @copyright (c) 2002-2006 [wGEric, Jon Ohlsson] mxBB Project Team
* @license http://opensource.org/licenses/gpl-license.php GNU General Public License v2
*
*/

if ( !defined( 'IN_PORTAL' ) )
{
	die( "Hacking attempt" );
}

/**
 * Public mx_kb class.
 *
 */
class mx_kb_admin extends mx_kb_public
{
	/**
	 * load admin module
	 *
	 * @param unknown_type $module_name send module name to load it
	 */
	function adminmodule( $module_name )
	{
		if ( !class_exists( 'kb_' . $module_name ) )
		{
			global $module_root_path, $phpEx;

			$this->module_name = $module_name;

			require_once( $module_root_path . 'kb/admin/admin_' . $module_name . '.' . $phpEx );
			eval( '$this->modules[' . $module_name . '] = new mx_kb_' . $module_name . '();' );

			if ( method_exists( $this->modules[$module_name], 'init' ) )
			{
				$this->modules[$module_name]->init();
			}
		}
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $cat_parent
	 * @param unknown_type $depth
	 */
	function admin_cat_main( $cat_parent = 0, $depth = 0 )
	{
		global $phpbb_root_path, $template, $phpEx, $lang, $images, $module_root_path, $theme;

		$pre = str_repeat( '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $depth );
		if ( isset( $this->subcat_rowset[$cat_parent] ) )
		{
			foreach( $this->subcat_rowset[$cat_parent] as $category_id => $category )
			{
				$category_details = '<span class="gensmall">' . $category['category_details'] . '</span>';

				$category_id = $category['category_id'];
				$category_name = $category['category_name'];
				$temp_url = mx_append_sid( $module_root_path . "kb.$phpEx?mode=cat&amp;cat=$category_id" );
				$category_link = '<a href="' . $temp_url . '" class="nav" target="_blank">' . $category_name . '</a>';

				$temp_url = mx_append_sid( $module_root_path . "admin/admin_kb.$phpEx?action=cat_manage&mode=edit&amp;cat=$category_id" );
				$edit = '<a href="' . $temp_url . '">'.$lang['Edit'].'</a>';

				$temp_url = mx_append_sid( $module_root_path . "admin/admin_kb.$phpEx?action=cat_manage&mode=delete&amp;cat=$category_id" );
				$delete = '<a href="' . $temp_url . '" class="gen">'.$lang['Delete'].'</a>';

				$temp_url = mx_append_sid( $module_root_path . "admin/admin_kb.$phpEx?action=cat_manage&mode=up&amp;cat=$category_id" );
				$up = '<a href="' . $temp_url . '" class="gen">' . $lang['Move_up'] . '</a>';

				$temp_url = mx_append_sid( $module_root_path . "admin/admin_kb.$phpEx?action=cat_manage&mode=down&amp;cat=$category_id" );
				$down = '<a href="' . $temp_url . '" class="gen">' . $lang['Move_down'] . '</a>';

				$row_class = ( !( $ss % 2 ) ) ? $theme['td_class1'] : $theme['td_class2'];

				$template->assign_block_vars( 'catrow', array(
					'CATEGORY' => $category_link,
					'CAT_DESCRIPTION' => $category_details,

					'U_EDIT' => $edit,
					'U_DELETE' => $delete,
					'U_UP' => $up,
					'U_DOWN' => $down,

					'ROW_CLASS' => $row_class,
					'PRE' => $pre
				));

				$this->admin_cat_main( $category_id, $depth + 1 );
			}
			return;
		}
		return;
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $sel_id
	 * @param unknown_type $use_default_option
	 * @param unknown_type $select_name
	 * @return unknown
	 */
	function get_forums( $sel_id = 0, $use_default_option = false, $select_name = 'forum_id' )
	{
		global $db, $lang;

		$sql = "SELECT forum_id, forum_name
			FROM " . FORUMS_TABLE;

		if ( !$result = $db->sql_query( $sql ) )
		{
			mx_message_die( GENERAL_ERROR, "Couldn't get list of forums", "", __LINE__, __FILE__, $sql );
		}

		$forumlist = '<select name="'.$select_name.'">';

		if ( $sel_id == 0 )
		{
			$forumlist .= '<option value="0" selected >'.$lang['Select_topic_id'].'</option>';
		}

		if ( $use_default_option )
		{
			$status = $sel_id == "-1" ? "selected" : "";
			$forumlist .= '<option value="-1" '.$status.' >::'.$lang['Use_default'].'::</option>';
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

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $id
	 * @param unknown_type $select
	 * @return unknown
	 */
	function get_list_kb( $id, $select )
	{
		global $db;

		$idfield = 'id';
		$namefield = 'type';

		$sql = "SELECT *
			FROM " . KB_TYPES_TABLE;

		if ( $select == 0 )
		{
			$sql .= " WHERE $idfield <> $id";
		}

		if ( !$result = $db->sql_query( $sql ) )
		{
			mx_message_die( GENERAL_ERROR, "Couldn't get list of types", "", __LINE__, __FILE__, $sql );
		}

		$typelist = "";

		while ( $row = $db->sql_fetchrow( $result ) )
		{
			$typelist .= "<option value=\"$row[$idfield]\"$s>" . $row[$namefield] . "</option>\n";
		}

		return( $typelist );
	}
}
?>