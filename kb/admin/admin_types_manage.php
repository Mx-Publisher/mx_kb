<?php
/**
*
* @package MX-Publisher Module - mx_kb
* @version $Id: admin_types_manage.php,v 1.9 2008/07/15 22:05:42 jonohlsson Exp $
* @copyright (c) 2002-2006 [Jon Ohlsson, Mohd Basri, wGEric, PHP Arena, pafileDB, CRLin] MX-Publisher Project Team
* @license http://opensource.org/licenses/gpl-license.php GNU General Public License v2
*
*/

if ( !defined( 'IN_PORTAL' ) || !defined( 'IN_ADMIN' ) )
{
	die( "Hacking attempt" );
}

class mx_kb_types_manage extends mx_kb_admin
{
	function main( $action )
	{
		global $db, $images, $template, $lang, $phpEx, $mx_kb_functions, $mx_kb_cache, $kb_config, $phpbb_root_path, $module_root_path, $mx_root_path, $mx_request_vars;

		//
		// Load mode
		//
		if ( isset( $_POST['mode'] ) || isset( $_GET['mode'] ) )
		{
			$mode = ( isset( $_POST['mode'] ) ) ? $_POST['mode'] : $_GET['mode'];
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
				$type_name = trim( $_POST['new_type_name'] );

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

				$message = $lang['Type_created'] . '<br /><br />' . sprintf( $lang['Click_return_type_manager'], '<a href="' . mx_append_sid( "admin_kb.$phpEx?action=types_manage" ) . '">', '</a>' ) . '<br /><br />' . sprintf( $lang['Click_return_admin_index'], '<a href="' . mx_append_sid( $mx_root_path . "admin/index.$phpEx?pane=right" ) . '">', '</a>' );
				mx_message_die( GENERAL_MESSAGE, $message );
				break;

			case ( 'edit' ):

				if ( !$_POST['submit'] )
				{
					$type_id = intval( $_GET['cat'] );

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

					$template->assign_vars( array(
						'L_EDIT_TITLE' => $lang['Edit_type'],
						'L_CATEGORY' => $lang['Article_type'],
						'L_CAT_SETTINGS' => $lang['Cat_settings'],
						'L_CREATE' => $lang['Edit'],

						'S_ACTION' => mx_append_sid( "admin_kb.$phpEx?action=types_manage&mode=edit" ),
						'CAT_NAME' => $type,

						'S_HIDDEN' => '<input type="hidden" name="typeid" value="' . $type_id . '">'
					));
				}
				else if ( $_POST['submit'] )
				{
					$type_id = intval( $_POST['typeid'] );
					$type_name = trim( $_POST['catname'] );

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

					$message = $lang['Type_edited'] . '<br /><br />' . sprintf( $lang['Click_return_type_manager'], '<a href="' . mx_append_sid( "admin_kb.$phpEx?action=types_manage" ) . '">', '</a>' ) . '<br /><br />' . sprintf( $lang['Click_return_admin_index'], '<a href="' . mx_append_sid( $mx_root_path . "admin/index.$phpEx?pane=right" ) . '">', '</a>' );
					mx_message_die( GENERAL_MESSAGE, $message );
				}
				break;

			case ( 'delete' ):

				if ( !$_POST['submit'] )
				{
					$type_id = intval( $_GET['cat'] );

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

					//
					// Generate page
					//
					$template->set_filenames( array( 'body' => 'admin/kb_cat_del_body.tpl' ) );

					$template->assign_vars( array(
						'L_DELETE_TITLE' => $lang['Type_delete_title'],
						'L_DELETE_DESCRIPTION' => $lang['Type_delete_desc'],
						'L_CAT_DELETE' => $lang['Type_delete_title'],

						'L_CAT_NAME' => $lang['Article_type'],
						'L_MOVE_CONTENTS' => $lang['Change_type'],
						'L_DELETE' => $lang['Change_and_Delete'],

						'S_HIDDEN_FIELDS' => '<input type="hidden" name="typeid" value="' . $type_id . '">',
						'S_SELECT_TO' => $this->get_list_kb( $type_id, 0 ),
						'S_ACTION' => mx_append_sid( "admin_kb.$phpEx?action=types_manage&mode=delete" ),

						'CAT_NAME' => $type_name
					));
				}
				else if ( $_POST['submit'] )
				{
					$new_type = $_POST['move_id'];
					$old_type = $_POST['typeid'];

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

					$message = $lang['Type_deleted'] . '<br /><br />' . sprintf( $lang['Click_return_type_manager'], '<a href="' . mx_append_sid( "admin_kb.$phpEx?action=types_manage" ) . '">', '</a>' ) . '<br /><br />' . sprintf( $lang['Click_return_admin_index'], '<a href="' . mx_append_sid( $mx_root_path . "admin/index.$phpEx?pane=right" ) . '">', '</a>' );
					mx_message_die( GENERAL_MESSAGE, $message );
				}
				break;

			default:

				// Generate page

				$template->set_filenames( array( 'body' => 'admin/kb_type_body.tpl' ) );

				$template->assign_vars( array(
					'L_TYPE_TITLE' => $lang['Types_man'],
					'L_TYPE_DESCRIPTION' => $lang['KB_types_description'],

					'L_CREATE_TYPE' => $lang['Create_type'],
					'L_CREATE' => $lang['Create'],
					'L_TYPE' => $lang['Article_type'],
					'L_ACTION' => $lang['Art_action'],

					'S_ACTION' => mx_append_sid( "admin_kb.$phpEx?action=types_manage&mode=create" )
				));

				//
				// get types
				//
				$sql = "SELECT * FROM " . KB_TYPES_TABLE;

				if ( !( $cat_result = $db->sql_query( $sql ) ) )
				{
					mx_message_die( GENERAL_ERROR, "Could not obtain types", '', __LINE__, __FILE__, $sql );
				}

				while ( $type = $db->sql_fetchrow( $cat_result ) )
				{
					$type_id = $type['id'];
					$type_name = $type['type'];

					$temp_url = mx_append_sid( "admin_kb.$phpEx?action=types_manage&mode=edit&amp;cat=$type_id" );
					$edit = '<a href="' . $temp_url . '"><img src="' . $images['kb_icon_edit'] . '" border="0" alt="' . $lang['Edit'] . '"></a>';

					$temp_url = mx_append_sid( "admin_kb.$phpEx?action=types_manage&mode=delete&amp;cat=$type_id" );
					$delete = '<a href="' . $temp_url . '"><img src="' . $images['kb_icon_delpost'] . '" border="0" alt="' . $lang['Delete'] . '"></a>';

					$row_color = ( !( $i % 2 ) ) ? $theme['td_color1'] : $theme['td_color2'];
					$row_class = ( !( $i % 2 ) ) ? $theme['td_class1'] : $theme['td_class2'];

					$template->assign_block_vars( 'typerow', array(
						'TYPE' => $type_name,
						'U_EDIT' => $edit,
						'U_DELETE' => $delete,

						'ROW_COLOR' => '#' . $row_color,
						'ROW_CLASS' => $row_class
					));
					$i++;
				}
				break;
		}

		$template->pparse( 'body' );
	}
}
?>