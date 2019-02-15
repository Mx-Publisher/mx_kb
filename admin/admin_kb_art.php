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
 *    $Id: admin_kb_art.php,v 1.27 2005/12/08 15:04:23 jonohlsson Exp $
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
	require_once( $phpbb_root_path . 'extension.inc' );
		
	if ( !empty( $setmodules ) )
	{
		include_once( $phpbb_root_path . 'kb/includes/kb_constants.' . $phpEx );
		$file = basename( __FILE__ );
		$module['KB_title']['3_Art_man'] = $file;
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
		$module['KB_title']['3_Art_man'] = 'modules/mx_kb/admin/' . $file;
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

// **********************************************************************
// Read language definition
// **********************************************************************
if ( !file_exists( $module_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_admin.' . $phpEx ) )
{
	include( $module_root_path . 'language/lang_english/lang_admin.' . $phpEx );
}
else
{
	include( $module_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_admin.' . $phpEx );
} 

$mx_kb->init();

$mode = $mx_request_vars->request('mode', MX_TYPE_NO_TAGS, '');
$start = $mx_request_vars->request('start', MX_TYPE_INT, 0);
$category_id = $mx_request_vars->request('cat', MX_TYPE_INT, 0);
$article_id = $mx_request_vars->request('a', MX_TYPE_INT, 0);

if( empty( $mode ) )
{
	if ( $approve )
	{
		$mode = 'approve';
	}
	else if ( $unapprove )
	{
		$mode = 'unapprove';
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
	case 'approve':
		
		$sql = "SELECT * FROM " . KB_ARTICLES_TABLE . " WHERE article_id = " . $article_id;
				
		if ( !( $results = $db->sql_query( $sql ) ) )
		{
			mx_message_die( GENERAL_ERROR, "Could not obtain article data", '', __LINE__, __FILE__, $sql );
		}
		
		$kb_row = $db->sql_fetchrow( $results );
						
		$topic_sql = '';
		
		/*
		$kb_comment = array();
					
		// Populate the kb_comment variable
		$kb_comment = $this->kb_get_data($kb_row, $userdata );
			
		// Compose post header
		$subject = $lang['KB_comment_prefix'] . $kb_comment['article_title'];
		$message_temp = $this->kb_compose_comment( $kb_comment );
						
		$kb_message = $message_temp['message'];
		$kb_update_message = $message_temp['update_message'];		
				
		if ( $kb_config['use_comments'] )
		{
			if ( !$kb_row['topic_id'] )
			{ 
				// Post
				$topic_data = kb_insert_post( $kb_message, $subject, $kb_comment['category_forum_id'], $kb_comment['article_editor_id'], $kb_comment['article_editor'], $kb_comment['article_editor_sig'], $kb_comment['topic_id'], $kb_update_message );
				
				$topic_sql = ", topic_id = " . $topic_data['topic_id'];
			}
		}
		*/
				
		$sql = "UPDATE " . KB_ARTICLES_TABLE . " SET approved = 1 " . $topic_sql . "
		 WHERE article_id = " . $article_id;
		
		if ( !( $result = $db->sql_query( $sql ) ) )
		{
			mx_message_die( GENERAL_ERROR, "Could not update article data", '', __LINE__, __FILE__, $sql );
		}
		
		/*
		$this->kb_notify( $kb_config['notify'], $kb_message, $kb_config['admin_id'], $kb_comment['article_editor_id'], 'approved' );
		*/
				
		$mx_kb->modified( true );
		$mx_kb->_kb();
				
		$message = $lang['Article_approved'] . '<br /><br />' . sprintf( $lang['Click_return_article_manager'], '<a href="' . append_sid( this_kb_mxurl( "page=$page_id&mode=cat&cat=$category_id&start=$start" ) ) . '">', '</a>' ) ;
		mx_message_die( GENERAL_MESSAGE, $message );
		
		break;

	case 'unapprove':

		$sql = "UPDATE " . KB_ARTICLES_TABLE . " SET approved = 0
		 WHERE article_id = " . $article_id;
		
		if ( !( $result = $db->sql_query( $sql ) ) )
		{
			mx_message_die( GENERAL_ERROR, "Could not update article data", '', __LINE__, __FILE__, $sql );
		}
		
		$mx_kb->modified( true );
		$mx_kb->_kb();
				
		$message = $lang['Article_unapproved'] . '<br /><br />' . sprintf( $lang['Click_return_article_manager'], '<a href="' . append_sid( this_kb_mxurl( "page=$page_id&mode=cat&cat=$category_id&start=$start") ) . '">', '</a>' ) ;
		mx_message_die( GENERAL_MESSAGE, $message );
				
		break;

	case 'delete':

		if ( $HTTP_GET_VARS['c'] == "yes" )
		{
			$sql = "SELECT *  
					FROM " . KB_ARTICLES_TABLE . "
					WHERE article_id = " . $article_id;

			if ( !( $result = $db->sql_query( $sql ) ) )
			{
				mx_message_die( GENERAL_ERROR, "Could not obtain article category", '', __LINE__, __FILE__, $sql );
			}
		
			$article_info = $db->sql_fetchrow( $result );
		
			if ( $this->comments[$article_info['article_category_id']]['activated'] && !$this->comments[$article_info['article_category_id']]['internal_comments'] && $kb_config['del_topic'] && $article_info['topic_id'] )
			{
				include( $module_root_path . 'kb/includes/functions_comment.' . $phpEx );
				$mx_kb_comments = new mx_kb_comments();				
				$mx_kb_comments->init( $article_info, 'phpbb' );	
				$mx_kb_comments->post('delete', $article_info['topic_id']);						
			}
		
			$sql = "DELETE FROM  " . KB_ARTICLES_TABLE . " WHERE article_id = " . $article_id;

			if ( !( $result = $db->sql_query( $sql ) ) )
			{
				mx_message_die( GENERAL_ERROR, "Could not delete article data", '', __LINE__, __FILE__, $sql );
			}
		
			$message = $lang['Article_deleted'] . '<br /><br />' . sprintf( $lang['Click_return_article_manager'], '<a href="' . append_sid( this_kb_mxurl("page=$page_id&mode=cat&cat=$category_id&start=$start") ) . '">', '</a>' ) ;

			$mx_kb->modified( true );
			$mx_kb->_kb();
			
			$message = $lang['Article_deleted'] . '<br /><br />' . sprintf( $lang['Click_return_article_manager'], '<a href="' . append_sid( "admin_kb_art.$phpEx" ) . "&amp;start=" . $start  . '">', '</a>' ) . '<br /><br />' . sprintf( $lang['Click_return_admin_index'], '<a href="' . append_sid( $mx_root_path . "admin/index.$phpEx?pane=right" ) . '">', '</a>' );
			mx_message_die( GENERAL_MESSAGE, $message );
		}
		else
		{
			$message = $lang['Confirm_art_delete'] . '<br /><br />' . sprintf( $lang['Confirm_art_delete_yes'], '<a href="' . append_sid( "admin_kb_art.$phpEx" ) . "&amp;mode=delete&amp;c=yes&amp;a=" . $article_id . "&amp;start=" . $start  . '">', '</a>' ) . '<br /><br />' . sprintf( $lang['Confirm_art_delete_no'], '<a href="' . append_sid( "admin_kb_art.$phpEx" )  . "&amp;start=" . $start . '">', '</a>' );
			mx_message_die( GENERAL_MESSAGE, $message );
		}
		break;

	default: 
		
		//
		// Generate page
		//
		$template->set_filenames( array( 'body' => 'admin/kb_art_body.tpl' ) );
		
		//
		// edited articles
		//
		$mx_kb->display_articles( '', 2, 'editrow', $start ); 
		
		//
		// need to be approved
		//
		$mx_kb->display_articles( '', 0, 'notrow', $start ); 
		
		//
		// Articles that are approved
		//
		$total_articles = $mx_kb->display_articles( '', 1, 'approverow', $start, $kb_config['pagination'] );

		//
		// Pagination
		//
		$sql_pag = "SELECT count(article_id) AS total
			FROM " . KB_ARTICLES_TABLE . "
			WHERE "; 

		$sql_pag .= " approved = '1'";
	
		if ( !( $result = $db->sql_query( $sql_pag ) ) )
		{
			mx_message_die( GENERAL_ERROR, 'Error getting total articles', '', __LINE__, __FILE__, $sql );
		}
	
		if ( $total = $db->sql_fetchrow( $result ) )
		{
			$total_articles = $total['total'];
			$pagination = generate_pagination( append_sid ( "admin_kb_art.$phpEx" ), $total_articles, $kb_config['pagination'], $start ) . '&nbsp;';
		}
		
		if ( $total_articles > 0 )
		{
			$template->assign_block_vars( 'pagination', array() );
		}

		$template->assign_vars( array( 
			'PAGINATION' => $pagination,
			'PAGE_NUMBER' => sprintf( $lang['Page_of'], ( floor( $start / $kb_config['pagination'] ) + 1 ), ceil( $total_articles / $kb_config['pagination'] ) ),
			'L_GOTO_PAGE' => $lang['Goto_page'],
				
			'L_ARTICLE' => $lang['Article'],
			'L_ARTICLE_CAT' => $lang['Category'],
			'L_ARTICLE_TYPE' => $lang['Article_type'],
			'L_ARTICLE_AUTHOR' => $lang['Author'],
			'L_ACTION' => $lang['Art_action'],

			'L_APPROVED' => $lang['Art_approved'],
			'L_NOT_APPROVED' => $lang['Art_not_approved'],
			'L_EDITED' => $lang['Art_edit'],

			'L_ART_TITLE' => $lang['Panel_art_title'],
			'L_ART_EXPLAIN' => $lang['Panel_art_explain']
		)); 
		break;
}

include( $mx_root_path . 'admin/page_header_admin.' . $phpEx );
$template->pparse( 'body' );
include_once( $mx_root_path . 'admin/page_footer_admin.' . $phpEx );

?>