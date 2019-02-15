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
 *    $Id: kb_cat.php,v 1.1 2005/12/08 15:06:46 jonohlsson Exp $
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

class mx_kb_cat extends mx_kb_public
{
	function main( $action )
	{
		global $template, $lang, $db, $phpEx, $kb_config, $mx_request_vars, $userdata; 
		global $mx_root_path, $module_root_path, $is_block, $phpEx; 

		//
		// Request vars
		//		
		$start = $mx_request_vars->get('start', MX_TYPE_INT, 0);
		$category_id = $mx_request_vars->request('cat', MX_TYPE_INT, '');
		
		if ( empty( $category_id ) )
		{
			mx_message_die( GENERAL_MESSAGE, $lang['Category_not_exsist'] );
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

		if ( ( !$this->auth_user[$category_id]['auth_mod'] ) && ( !$show_category ) )
		{
			if ( !$userdata['session_logged_in'] )
			{
				// mx_redirect(append_sid($mx_root_path . "login.$phpEx?redirect=". pa_this_mxurl("action=category&cat_id=" . $cat_id, true), true));
			}

			$message = $lang['Not_authorized'];
			mx_message_die( GENERAL_MESSAGE, $message );
		}

		if ( !isset( $this->cat_rowset[$category_id] ) )
		{
			$message = $lang['Category_not_exsist'] . '<br /><br />' . sprintf( $lang['Click_return_kb'], '<a href="' . append_sid( this_kb_mxurl() ) . '">', '</a>' ) . '<br /><br />' . sprintf( $lang['Click_return_index'], '<a href="' . append_sid( $mx_root_path . "index.$phpEx" ) . '">', '</a>' );
			mx_message_die( GENERAL_MESSAGE, $message );
		} 
				
		//
		// Validate Comments Setup
		//
		if ( $this->comments[$category_id]['activated'] && !$this->comments[$category_id]['internal_comments'] && $this->cat_rowset[$category_id]['comments_forum_id'] < 1 ) 
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
		
		//
		// Pagination
		//
		$sql_pag = "SELECT count(article_id) AS total
			FROM " . KB_ARTICLES_TABLE . "
			WHERE "; 
		$sql_pag .= " article_category_id = '$category_id'";
		
		if ( !( $result = $db->sql_query( $sql_pag ) ) )
		{
			mx_message_die( GENERAL_ERROR, 'Error getting total articles', '', __LINE__, __FILE__, $sql );
		}
		
		if ( $total = $db->sql_fetchrow( $result ) )
		{
			$total_articles = $total['total'];
			$pagination = generate_pagination( this_kb_mxurl( "mode=cat&cat=$category_id" ), $total_articles, $kb_config['pagination'], $start ) . '&nbsp;';
		}
		
		if ( $total_articles > 0 )
		{
			$template->assign_block_vars( 'pagination', array() );
		}
			
		$template->assign_vars( array( 
			'PAGINATION' => $pagination,
			'PAGE_NUMBER' => sprintf( $lang['Page_of'], ( floor( $start / $kb_config['pagination'] ) + 1 ), ceil( $total_articles / $kb_config['pagination'] ) ),
			'L_GOTO_PAGE' => $lang['Goto_page'],
			'L_CATEGORY_NAME' => $category_name,
			'L_ARTICLE' => $lang['Article'],
			'L_ARTICLE_TYPE' => $lang['Article_type'],
			'L_ARTICLE_CATEGORY' => $lang['Category'],
			'L_ARTICLE_DATE' => $lang['Date'],
			'L_ARTICLE_AUTHOR' => $lang['Author'],
			'L_VIEWS' => $lang['Views'],
			'L_VOTES' => $lang['Votes'],

			'L_CATEGORY' => $lang['Category_sub'],
			'L_ARTICLES' => $lang['Articles'],
		
			'U_CAT' => append_sid( this_kb_mxurl( 'mode=cat&cat=' . $category_id ) ) ) 
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
		$this->display_articles( $category_id, '1', 'articlerow', $start, $kb_config['pagination'] );
			
		//
		// Get footer quick dropdown jumpbox
		//
		$this->generate_jumpbox( 'auth_view', $category_id, $category_id, true );
	}
}
?>