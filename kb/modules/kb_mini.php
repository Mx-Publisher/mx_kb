<?php
/**
*
* @package MX-Publisher Module - mx_kb
* @version $Id: kb_mini.php,v 1.10 2008/06/03 20:10:34 jonohlsson Exp $
* @copyright (c) 2002-2006 [Jon Ohlsson, Mohd Basri, wGEric, PHP Arena, pafileDB, CRLin] MX-Publisher Project Team
* @license http://opensource.org/licenses/gpl-license.php GNU General Public License v2
*
*/

if ( !defined( 'IN_PORTAL' ) )
{
	die( "Hacking attempt" );
}

/**
 * Enter description here...
 *
 */
class mx_kb_mini extends mx_kb_public
{
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $action
	 */
	function main( $action )
	{
		global $template, $lang, $phpEx, $kb_config, $userdata;
		global $mx_root_path, $module_root_path, $is_block, $mx_request_vars, $mini_config;
		global $page_id;

		// =======================================================
		// Request vars
		// =======================================================
		$start = $mx_request_vars->request('mini_start', MX_TYPE_INT, 0);
		$cat_id = $mx_request_vars->request('cat', MX_TYPE_INT, $mini_config['mini_default_cat_id']);

		if ( empty( $cat_id ) )
		{
			//mx_message_die( GENERAL_MESSAGE, $lang['Cat_not_exist'] );
		}

		//
		// Sorting of items
		//
		$sort_method = $kb_config['sort_method'];
		$sort_order = $kb_config['sort_order'];

		// =======================================================
		// If user not allowed to view file listing (read) and there is no sub Category
		// or the user is not allowed to view these category we gave him a nice message.
		// =======================================================
		$show_category = false;
		if ( isset( $this->subcat_rowset[$cat_id] ) )
		{
			foreach( $this->subcat_rowset[$cat_id] as $sub_cat_id => $sub_cat_row )
			{
				if ( $this->auth_user[$sub_cat_id]['auth_view'] )
				{
					$show_category = true;
					break;
				}
			}
		}

		if ( ( !$this->auth_user[$cat_id]['auth_view'] ) && ( !$show_category ) )
		{
			if ( !$userdata['session_logged_in'] )
			{
				// mx_redirect(mx_append_sid($mx_root_path . "login.$phpEx?redirect=". $this->this_mxurl("action=category&cat_id=" . $cat_id, true), true));
			}

			$message = sprintf( $lang['Sorry_auth_view'], $this->auth_user[$cat_id]['auth_read_type'] );
			mx_message_die( GENERAL_MESSAGE, $message );
		}

		if ( !isset( $this->cat_rowset[$cat_id] ) )
		{
			//mx_message_die( GENERAL_MESSAGE, $lang['Cat_not_exist'] );
		}

		$no_file_message = true;

		//
		// get sub-cats
		//
		if ( isset( $this->subcat_rowset[$cat_id] ) )
		{
			$no_file_message = false;
			$kb_config['use_simple_navigation'] = 'CAT_NAV_SIMPLE';
			$this->display_categories( $cat_id );
		}

		$filelist = false;

		$template->set_filenames( array( 'body' => 'kb_mini.tpl' ) );

		$sort_options_list = unserialize($mini_config['mini_display_options']);
		$kb_config['pagination'] = $mini_config['mini_pagination'];

		//if ($mx_request_vars->is_request('cat'))
		if (true)
		{
			$total_num_items = $this->display_items( $start, $cat_id, $sort_options_list );
		}

		$template->assign_vars( array(
			'U_KB' => mx_append_sid( $this->this_mxurl() ),
			'L_KB' => $kb_config['module_name'],

			'L_QUICK_JUMP' => $lang['Quick_jump'],
			'BLOCK_JUMPMENU' => $this->generate_jumpbox( $mini_config['mini_default_cat_id'], 0, array( $cat_id => 1 ) ),

			'MX_PAGE' => $page_id,
			'S_JUMPBOX_ACTION' => mx_append_sid( $this->this_mxurl( ) ),
			'BLOCK_PAGINATION' => mx_generate_pagination(mx_append_sid( $this->this_mxurl( 'cat=' . $cat_id, false, false ) ), $total_num_items, $mini_config['mini_pagination'], $start, true, true, true, false, 'mini_start'),
		));
	}
}
?>