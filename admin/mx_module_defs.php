<?php
/**
*
* @package MX-Publisher Module - mx_kb
* @version $Id: mx_module_defs.php,v 1.18 2008/06/03 20:08:19 jonohlsson Exp $
* @copyright (c) 2002-2006 [Jon Ohlsson, Mohd Basri, wGEric, PHP Arena, pafileDB, CRLin] MX-Publisher Project Team
* @license http://opensource.org/licenses/gpl-license.php GNU General Public License v2
*
*/

if ( !defined( 'IN_PORTAL' ) )
{
	die( "Hacking attempt" );
}

define( 'MXBB_MODULE', true );
define( 'MXBB_27x', @file_exists( $mx_root_path . 'mx_login.'.$phpEx ) );

/********************************************************************************\
| Class: mx_module_defs
| The mx_module_defs object provides additional module block parameters...
\********************************************************************************/
class mx_module_defs
{
	// ------------------------------
	// Private Methods
	//
	//

	// ===================================================
	// define module specific block parameters
	// ===================================================
	function get_parameters($type_row = '')
	{
		global $lang;

		if (empty($type_row))
		{
			$type_row = array();
		}

		$type_row['kb_type_select'] = !empty($lang['ParType_kb_type_select']) ? $lang['ParType_kb_type_select'] : "KB phpBB Source Forums";
		$type_row['kb_quick_cat'] = !empty($lang['ParType_kb_quick_cat']) ? $lang['ParType_kb_quick_cat'] : "KB default category";

		return $type_row;
	}

	// ===================================================
	// Submit custom parameter field and data
	// ===================================================
	function submit_module_parameters( $parameter_data, $block_id )
	{
		global $db, $board_config, $mx_blockcp, $mx_root_path, $phpEx;
		global $html_entities_match, $html_entities_replace;

		$parameter_value = $_POST[$parameter_data['parameter_name']];
		$parameter_opt = '';

		switch ( $parameter_data['parameter_type'] )
		{
			case 'kb_type_select':
				$parameter_value = addslashes( serialize( $parameter_value ) );
				break;

			case 'kb_quick_cat':
				$parameter_value = $_POST[$parameter_data['parameter_id']];
				break;
		}

		return array('parameter_value' => $parameter_value, 'parameter_opt' => $parameter_opt);
	}

	// ===================================================
	// display parameter field and data in the add/edit page
	// ===================================================
	function display_module_parameters( $parameter_data, $block_id )
	{
		global $template, $mx_blockcp, $mx_root_path, $theme, $lang;

		switch ( $parameter_data['parameter_type'] )
		{
			case 'kb_type_select':
				$this->display_edit_KB_type_select( $block_id, $parameter_data['parameter_id'], $parameter_data );
				break;

			case 'kb_quick_cat':
				$this->display_edit_kb_quick_cat( $block_id, $parameter_data['parameter_id'], $parameter_data );
				break;
		}
	}

	function display_edit_kb_quick_cat( $block_id, $parameter_id, $parameter_data )
	{
		global $template, $board_config, $db, $theme, $lang, $images, $mx_blockcp, $mx_root_path, $phpEx, $mx_table_prefix, $table_prefix;
		global $mx_user, $module_root_path;

		//
		// Includes
		//
		$module_root_path = $mx_root_path . $mx_blockcp->module_root_path;
		include_once( $module_root_path . 'kb/includes/kb_constants.' . $phpEx );

		//
		// Get varaibles
		//
		$data = ( !empty( $parameter_data['parameter_value'] ) ) ? $parameter_data['parameter_value'] : '';
		$parameter_datas = $this->generate_jumpbox( 0, 0, array( $data => 1 ), false );

		//
		// Start page proper
		//
		$template->set_filenames(array(
			'parameter' => 'admin/mx_module_parameters.tpl')
		);

		$template->assign_block_vars( 'select', array(
			'PARAMETER_TITLE' 			=> ( !empty($lang[$parameter_data['parameter_name']]) ) ? $lang[$parameter_data['parameter_name']] : $parameter_data['parameter_name'],
			'PARAMETER_TITLE_EXPLAIN' 	=> ( !empty($lang[$parameter_data['parameter_name']. "_explain"]) ) ? '<br />' . $lang[$parameter_data['parameter_name']. "_explain"] : '',
			'L_NONE' 					=> $lang['None'],

			'SELECT_LIST'				=> $parameter_datas,

				'FIELD_NAME' 			=> ( !empty($lang[$parameter_data['parameter_name']]) ) ? $lang[$parameter_data['parameter_name']] : $parameter_data['parameter_name'],
				'FIELD_ID' 				=> $parameter_data['parameter_id'],
				'FIELD_DESCRIPTION' 	=> ( !empty($lang["ParType_".$parameter_data['parameter_type']]) ) ? $lang["ParType_".$parameter_data['parameter_type']] : ''
			));

		$template->pparse('parameter');
	}

	function display_edit_KB_type_select( $block_id, $parameter_id, $parameter_data )
	{
		global $template, $board_config, $db, $theme, $lang, $images, $mx_blockcp, $mx_root_path, $phpEx, $mx_table_prefix, $table_prefix;
		global $mx_user, $module_root_path;

		//
		// Includes
		//
		$module_root_path = $mx_root_path . $mx_blockcp->module_root_path;
		include_once( $module_root_path . "kb/includes/kb_constants.$phpEx" );
		include_once( $module_root_path . "kb/includes/kb_defs.$phpEx" );

		$template->set_filenames(array(
			'parameter' => 'admin/mx_module_parameters.tpl')
		);

		// Get number of forums in db
		$sql = "SELECT *
			FROM " . NEWS_CAT_TABLE . "
			ORDER BY $cat_extract_order";
		if ( !( $result = $db->sql_query( $sql ) ) )
		{
			mx_message_die( GENERAL_ERROR, "Couldn't obtain forums information.", "", __LINE__, __FILE__, $sql );
		}

		$forums = $db->sql_fetchrowset( $result );

		$kb_type_select_data = ( !empty( $parameter_data['parameter_value'] ) ) ? unserialize($parameter_data['parameter_value']) : array();

		//
		// Check that some categories exist
		//
		$categories = array ( 0 => array ( 0 => '1', 'cat_id' => '1', 1 => 'KB', 'cat_title' => 'KB', ) );

		if ( $total_categories = count( $categories ) )
		{
			//
			// Check that some forums exist (these were queried earlier)
			//
			if ( $total_forums = count( $forums ) )
			{
				$template->assign_block_vars( 'switch_forums_phpbb', array( 'COLSPAN' => count( $item_types_array ) + 2
						) );

				for( $i = 0; $i < $total_categories; $i++ )
				{
					$template->assign_block_vars( 'catrow', array( 'CAT_ID' => $categories[$i]['cat_id'],
							'COLSPAN' => count( $item_types_array ) + 1,
							'CAT_NAME' => $categories[$i]['cat_title']
							) );

					for( $j = 0; $j < $total_forums; $j++ )
					{
						if ( $forums[$j]['cat_id'] == '' )
						{
							$template->assign_block_vars( 'catrow.forumrow_phpbb', array( 'FORUM_ID' => $forums[$j][$catt_id],
									'FORUM_NAME' => $forums[$j][$catt_name],
									'FORUM_DESC' => $forums[$j][$catt_desc],

									'CHECKED' => ( $kb_type_select_data[$forums[$j][$cool_array_category_id]]['forum_news'] ? 'CHECKED' : '' )
									) );
						}
					}
				}
			}
		}

		$template->assign_vars(array(
			'NAME' =>  $lang[$parameter_data['parameter_name']],
			'SELSCT_NAME' =>  $parameter_data['parameter_name'],
			'PARAMETER_TITLE' => ( !empty($lang[$parameter_data['parameter_name']]) ) ? $lang[$parameter_data['parameter_name']] : $parameter_data['parameter_name'],
			'PARAMETER_TYPE' => ( !empty($lang["ParType_".$parameter_data['parameter_type']]) ) ? $lang["ParType_".$parameter_data['parameter_type']] : '',
			'PARAMETER_TYPE_EXPLAIN' => ( !empty($lang["ParType_".$parameter_data['parameter_type'] . "_info"]) ) ? '<br />' . $lang["ParType_".$parameter_data['parameter_type'] . "_info"]  : '',

			'SCRIPT_PATH' => $module_root_path,
			'I_ANNOUNCE' => $images['kb_folder_announce'],
			'I_STICKY' => $images['kb_folder_sticky'],
			'I_NORMAL' => $images['kb_folder'],
		));

		$template->pparse('parameter');
	}

	// ===================================================
	// Jump menu function
	// $cat_id : to handle parent cat_id
	// $depth : related to function to generate tree
	// $default : the cat you wanted to be selected
	// $for_file: TRUE high category ids will be -1
	// $check_upload: if true permission for upload will be checked
	// ===================================================
	function generate_jumpbox( $cat_id = 0, $depth = 0, $default = '', $for_file = false, $check_upload = false )
	{
		global $db;

		static $cat_rowset = false;

		$sql = 'SELECT *
			FROM ' . KB_CATEGORIES_TABLE . '
			ORDER BY cat_order ASC';

		if ( !( $result = $db->sql_query( $sql ) ) )
		{
			mx_message_die( GENERAL_ERROR, 'Couldnt Query categories info', '', __LINE__, __FILE__, $sql );
		}
		$cat_rowset_temp = $db->sql_fetchrowset( $result );

		$db->sql_freeresult( $result );

		$cat_rowset = array();
		foreach( $cat_rowset_temp as $row )
		{
			$cat_rowset[$row['category_id']] = $row;
		}

		//
		// Generate list
		//

		$cat_list .= '';

		$pre = str_repeat( '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $depth );

		$temp_cat_rowset = $cat_rowset;

		if ( !empty( $temp_cat_rowset ) )
		{
			foreach ( $temp_cat_rowset as $temp_cat_id => $cat )
			{
				if ( $cat['parent'] == $cat_id )
				{
					if ( is_array( $default ) )
					{
						if ( isset( $default[$cat['category_id']] ) )
						{
							$sel = ' selected="selected"';
						}
						else
						{
							$sel = '';
						}
					}
					$cat_pre = ( !$cat['cat_allow_file'] ) ? '+ ' : '- ';
					$sub_cat_id = ( $for_file ) ? ( ( !$cat['cat_allow_file'] ) ? -1 : $cat['category_id'] ) : $cat['category_id'];
					$cat_class = ( !$cat['cat_allow_file'] ) ? 'class="greyed"' : '';
					$cat_list .= '<option value="' . $sub_cat_id . '"' . $sel . ' ' . $cat_class . ' />' . $pre . $cat_pre . $cat['category_name'] . '</option>';
					$cat_list .= $this->generate_jumpbox( $cat['category_id'], $depth + 1, $default, $for_file, $check_upload );
				}
			}
			return $cat_list;
		}
		else
		{
			return;
		}
	}
}
?>