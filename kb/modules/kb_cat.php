<?php
/**
*
* @package MX-Publisher Module - mx_kb
* @version $Id: kb_cat.php,v 1.11 2008/06/03 20:10:32 jonohlsson Exp $
* @copyright (c) 2002-2006 [wGEric, Jon Ohlsson] mxBB Project Team
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
class mx_kb_cat extends mx_kb_public
{
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $action
	 */
	function main($action = false)
	{
		global $template, $lang, $db, $phpEx, $kb_config, $mx_request_vars, $userdata;
		global $mx_root_path, $module_root_path, $is_block;

		//
		// Request vars
		//
		$start = $mx_request_vars->get('start', MX_TYPE_INT, 0);
		$category_id = $mx_request_vars->request('cat', MX_TYPE_INT, '');

		if ( empty( $category_id ) )
		{
			mx_message_die( GENERAL_MESSAGE, $lang['Category_not_exsist'] );
		}

		//
		// Sorting of items
		//
		if ( isset( $_REQUEST['sort_method'] ) )
		{
			switch ( $_REQUEST['sort_method'] )
			{
				case 'Id':
				case 'viewall':
					$this->sort_method = 't.article_id';
					$this->sort_method_extra = 't.article_type' . " DESC, " ;
				break;
				case 'Latest':
					$this->sort_method = 't.article_date';
					$this->sort_method_extra = 't.article_type' . " DESC, " ;
				break;
				case 'Toprated':
					$this->sort_method = 'rating';
					$this->sort_method_extra = 't.article_type' . " DESC, " ;
				break;
				case 'Most_popular':
					$this->sort_method = 't.views';
					$this->sort_method_extra = 't.article_type' . " DESC, " ;
				break;
				case 'Userrank':
					$this->sort_method = 'u.user_rank';
					$this->sort_method_extra = 't.article_type' . " DESC, " ;
				break;
				case 'Alphabetic':
					$this->sort_method = 't.article_title';
					$this->sort_method_extra = 't.article_type' . " DESC, " ;
				break;
			}
		}

		if ( isset( $_REQUEST['sort_order'] ) )
		{
			switch ( $_REQUEST['sort_order'] )
			{
				case 'ASC':
					$this->sort_order = 'ASC';
				break;
				case 'DESC':
					$this->sort_order = 'DESC';
				break;
			}
		}

		// =======================================================
		// If user not allowed to view article listing (read) and there is no sub Category
		// or the user is not allowed to view these category we gave him a nice message.
		// =======================================================
		$show_category = false;
		if ( isset( $this->subcat_rowset[$category_id] ) )
		{
			foreach( $this->subcat_rowset[$category_id] as $sub_cat_id => $sub_cat_row )
			{
				if ( $this->auth_user[$sub_cat_id]['auth_view'] )
				{
					$show_category = true;
					break;
				}
			}
		}

		if ( ( !$this->auth_user[$category_id]['auth_view'] ) && ( !$show_category ) )
		{
			if ( !$userdata['session_logged_in'] )
			{
				// mx_redirect(mx_append_sid($mx_root_path . "login.$phpEx?redirect=". $this->this_mxurl("mode=cat&cat=" . $cat_id, true), true));
			}

			$message = $lang['Not_authorized'];
			mx_message_die( GENERAL_MESSAGE, $message );
		}

		if ( !isset( $this->cat_rowset[$category_id] ) )
		{
			$message = $lang['Category_not_exsist'] . '<br /><br />' . sprintf( $lang['Click_return_kb'], '<a href="' . mx_append_sid( $this->this_mxurl() ) . '">', '</a>' ) . '<br /><br />' . sprintf( $lang['Click_return_index'], '<a href="' . mx_append_sid( $mx_root_path . "index.$phpEx" ) . '">', '</a>' );
			mx_message_die( GENERAL_MESSAGE, $message );
		}

		//
		// Validate Comments Setup
		//
		if ( $this->comments[$category_id]['activated'] && !$this->comments[$category_id]['internal_comments'] && $this->comments[$category_id]['comments_forum_id'] < 1 )
		{
			//
			// Commenting is enabled but no category forum id specified
			//
			$message = $lang['No_cat_comments_forum_id'];
			mx_message_die(GENERAL_MESSAGE, $message);
		}

		//
		// Vars
		//
		$category_name = $this->cat_rowset['category_name'];
		$this->page_title = $category_name;

		$template->set_filenames( array( 'body' => 'kb_cat_body.tpl' ) );

		$template->assign_vars( array(
			'U_CAT' => mx_append_sid( $this->this_mxurl( 'mode=cat&cat=' . $category_id ) ),
			'L_CATEGORIES' => $lang['All']
			)
		);

		// ===================================================
		// assign var for top navigation
		// ===================================================
		$this->generate_navigation( $category_id );

		//
		// User authorisation levels output
		//
		$this->auth_can($category_id);

		//
		// get sub-cats
		//
		if ( isset( $this->subcat_rowset[$category_id] ) )
		{
			$this->display_categories( $category_id );
		}

		//
		// Get articles
		//
		$this->display_items( $start, $category_id );

		//
		// Get footer quick dropdown jumpbox
		//
		//$this->generate_jumpbox( 'auth_view', $category_id, $category_id, true );
		$this->generate_jumpbox( 0, 0, array( $category_id => 1 ));
	}
}
?>