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
 *    $Id: admin_kb_types.php,v 1.17 2005/11/03 12:13:33 jonohlsson Exp $
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
		$module['KB_title']['5_Types_man'] = $file;
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
		$module['KB_title']['5_Types_man'] = 'modules/mx_kb/admin/' . $file;
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

// Load default header

if ( isset( $HTTP_POST_VARS['mode'] ) || isset( $HTTP_GET_VARS['mode'] ) )
{
	$mode = ( isset( $HTTP_POST_VARS['mode'] ) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
}
else
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
		$type_name = trim( $HTTP_POST_VARS['new_type_name'] );

		if ( !$type_name )
		{
			echo "Please put a type name in!";
			exit;
		}

		$sql = "INSERT INTO " . KB_TYPES_TABLE . " (type) VALUES ('$type_name')";

		if ( !( $results = $db->sql_query( $sql ) ) )
		{
			mx_message_die( GENERAL_ERROR, "Could not create type", '', __LINE__, __FILE__, $sql );
		}

		$message = $lang['Type_created'] . '<br /><br />' . sprintf( $lang['Click_return_type_manager'], '<a href="' . append_sid( "admin_kb_types.$phpEx" ) . '">', '</a>' ) . '<br /><br />' . sprintf( $lang['Click_return_admin_index'], '<a href="' . append_sid( $mx_root_path . "admin/index.$phpEx?pane=right" ) . '">', '</a>' );

		mx_message_die( GENERAL_MESSAGE, $message );
		break;

	case ( 'edit' ):

		if ( !$HTTP_POST_VARS['submit'] )
		{
			$type_id = intval( $HTTP_GET_VARS['cat'] );

			$sql = "SELECT * FROM " . KB_TYPES_TABLE . " WHERE id = " . $type_id;

			if ( !( $results = $db->sql_query( $sql ) ) )
			{
				mx_message_die( GENERAL_ERROR, "Could not obtain type", '', __LINE__, __FILE__, $sql );
			}
			if ( $type = $db->sql_fetchrow( $results ) )
			{
				$type = $type['type'];
			} 
			
			// Generate page
			
			$template->set_filenames( array( 'body' => 'admin/kb_type_edit_body.tpl' ) );

			$template->assign_vars( array( 'L_EDIT_TITLE' => $lang['Edit_type'],
					'L_CATEGORY' => $lang['Article_type'],
					'L_CAT_SETTINGS' => $lang['Cat_settings'],
					'L_CREATE' => $lang['Edit'],

					'S_ACTION' => append_sid( $module_root_path . "admin/admin_kb_types.$phpEx?mode=edit" ),
					'CAT_NAME' => $type,

					'S_HIDDEN' => '<input type="hidden" name="typeid" value="' . $type_id . '">' ) 
				);
		}
		else if ( $HTTP_POST_VARS['submit'] )
		{
			$type_id = intval( $HTTP_POST_VARS['typeid'] );
			$type_name = trim( $HTTP_POST_VARS['catname'] );

			if ( !$type_name )
			{
				echo "Please put a type name in!";
				exit;
			}

			$sql = "UPDATE " . KB_TYPES_TABLE . " SET type = '" . $type_name . "' WHERE id = " . $type_id;

			if ( !( $results = $db->sql_query( $sql ) ) )
			{
				mx_message_die( GENERAL_ERROR, "Could not update type", '', __LINE__, __FILE__, $sql );
			}

			$message = $lang['Type_edited'] . '<br /><br />' . sprintf( $lang['Click_return_type_manager'], '<a href="' . append_sid( "admin_kb_types.$phpEx" ) . '">', '</a>' ) . '<br /><br />' . sprintf( $lang['Click_return_admin_index'], '<a href="' . append_sid( $mx_root_path . "admin/index.$phpEx?pane=right" ) . '">', '</a>' );

			mx_message_die( GENERAL_MESSAGE, $message );
		}
		break;

	case ( 'delete' ):

		if ( !$HTTP_POST_VARS['submit'] )
		{
			$type_id = intval( $HTTP_GET_VARS['cat'] );

			$sql = "SELECT *  
       		FROM " . KB_TYPES_TABLE . " WHERE id = '" . $type_id . "'";

			if ( !( $cat_result = $db->sql_query( $sql ) ) )
			{
				mx_message_die( GENERAL_ERROR, "Could not obtain type", '', __LINE__, __FILE__, $sql );
			}

			if ( $type = $db->sql_fetchrow( $cat_result ) )
			{
				$type_name = $type['type'];
			} 
			
			// Generate page
			
			$template->set_filenames( array( 'body' => 'admin/kb_cat_del_body.tpl' ) 
				);

			$template->assign_vars( array( 'L_DELETE_TITLE' => $lang['Type_delete_title'],
					'L_DELETE_DESCRIPTION' => $lang['Type_delete_desc'],
					'L_CAT_DELETE' => $lang['Type_delete_title'],

					'L_CAT_NAME' => $lang['Article_type'],
					'L_MOVE_CONTENTS' => $lang['Change_type'],
					'L_DELETE' => $lang['Change_and_Delete'],

					'S_HIDDEN_FIELDS' => '<input type="hidden" name="typeid" value="' . $type_id . '">',
					'S_SELECT_TO' => get_list_kb( $type_id, 0 ),
					'S_ACTION' => append_sid( $module_root_path . "admin/admin_kb_types.$phpEx?mode=delete" ),

					'CAT_NAME' => $type_name ) 
				);
		}
		else if ( $HTTP_POST_VARS['submit'] )
		{
			$new_type = $HTTP_POST_VARS['move_id'];
			$old_type = $HTTP_POST_VARS['typeid'];

			if ( $new_type )
			{
				$sql = "UPDATE " . KB_ARTICLES_TABLE . " SET article_type = '$new_type' 
			   WHERE article_type = '$old_type'";
				if ( !( $move_result = $db->sql_query( $sql ) ) )
				{
					mx_message_die( GENERAL_ERROR, "Could not update articles", '', __LINE__, __FILE__, $sql );
				}
			}
			$sql = "DELETE FROM " . KB_TYPES_TABLE . " WHERE id = $old_type";

			if ( !( $delete_result = $db->sql_query( $sql ) ) )
			{
				mx_message_die( GENERAL_ERROR, "Could not delete type", '', __LINE__, __FILE__, $sql );
			}

			$message = $lang['Type_deleted'] . '<br /><br />' . sprintf( $lang['Click_return_type_manager'], '<a href="' . append_sid( "admin_kb_types.$phpEx" ) . '">', '</a>' ) . '<br /><br />' . sprintf( $lang['Click_return_admin_index'], '<a href="' . append_sid( $mx_root_path . "admin/index.$phpEx?pane=right" ) . '">', '</a>' );

			mx_message_die( GENERAL_MESSAGE, $message );
		}
		break;

	default: 
		
		// Generate page
		
		$template->set_filenames( array( 'body' => 'admin/kb_type_body.tpl' ) 
			);

		$template->assign_vars( array( 'L_KB_TYPE_TITLE' => $lang['Types_man'],
				'L_KB_TYPE_DESCRIPTION' => $lang['KB_types_description'],

				'L_CREATE_TYPE' => $lang['Create_type'],
				'L_CREATE' => $lang['Create'],
				'L_TYPE' => $lang['Article_type'],
				'L_ACTION' => $lang['Art_action'],

				'S_ACTION' => append_sid( $module_root_path . "admin/admin_kb_types.$phpEx?mode=create" ) ) 
			); 
		// get categories
		$sql = "SELECT *  
       		FROM " . KB_TYPES_TABLE;

		if ( !( $cat_result = $db->sql_query( $sql ) ) )
		{
			mx_message_die( GENERAL_ERROR, "Could not obtain types", '', __LINE__, __FILE__, $sql );
		}

		while ( $type = $db->sql_fetchrow( $cat_result ) )
		{
			$type_id = $type['id'];
			$type_name = $type['type'];

			$temp_url = append_sid( $module_root_path . "admin/admin_kb_types.$phpEx?mode=edit&amp;cat=$type_id" );
			$edit = '<a href="' . $temp_url . '"><img src="' . $phpbb_root_path . $images['icon_edit'] . '" border="0" alt="' . $lang['Edit'] . '"></a>';

			$temp_url = append_sid( $module_root_path . "admin/admin_kb_types.$phpEx?mode=delete&amp;cat=$type_id" );
			$delete = '<a href="' . $temp_url . '"><img src="' . $phpbb_root_path . $images['icon_delpost'] . '" border="0" alt="' . $lang['Delete'] . '"></a>';

			$row_color = ( !( $i % 2 ) ) ? $theme['td_color1'] : $theme['td_color2'];
			$row_class = ( !( $i % 2 ) ) ? $theme['td_class1'] : $theme['td_class2'];

			$template->assign_block_vars( 'typerow', array( 'TYPE' => $type_name,
					'U_EDIT' => $edit,
					'U_DELETE' => $delete,

					'ROW_COLOR' => '#' . $row_color,
					'ROW_CLASS' => $row_class ) 
				);
			$i++;
		}
		break;
}

include( $mx_root_path . 'admin/page_header_admin.' . $phpEx );
$template->pparse( 'body' );
include_once( $mx_root_path . 'admin/page_footer_admin.' . $phpEx );

?>