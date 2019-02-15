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
 *    $Id: kb_moderator.php,v 1.1 2005/12/08 15:06:46 jonohlsson Exp $
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

class mx_kb_moderator extends mx_kb_public
{
	function main( $action )
	{
		global $template, $lang, $db, $phpEx, $kb_config, $board_config, $mx_request_vars, $userdata; 
		global $mx_root_path, $module_root_path, $phpbb_root_path, $is_block, $phpEx;
		
		//
		// Include admin functions
		//		
		include( $phpbb_root_path . 'includes/functions_admin.' . $phpEx );

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
		
		//
		// Request vars
		//		
		$start = $mx_request_vars->get('start', MX_TYPE_INT, 0);
		$category_id = $mx_request_vars->request('cat', MX_TYPE_INT, 0);
		$article_id = $mx_request_vars->request('a', MX_TYPE_INT, 0);
		$page_id = $mx_request_vars->request('page', MX_TYPE_INT, 0);
		$ref_stats = $mx_request_vars->is_request('ref');
		
		if ( !( ($this->auth_user[$category_id]['auth_delete'] || $this->auth_user[$category_id]['auth_mod']) && $userdata['session_logged_in'] ) )
		{
			$message = $lang['No_add'] . '<br /><br />' . sprintf( $lang['Click_return_kb'], '<a href="' . append_sid( this_kb_mxurl() ) . '">', '</a>' ) . '<br /><br />' . sprintf( $lang['Click_return_index'], '<a href="' . append_sid( $mx_root_path . "index.$phpEx" ) . '">', '</a>' );
			mx_message_die( GENERAL_MESSAGE, $message );
		}
		
		if ( $mx_request_vars->is_request('action') )
		{
			$action = $mx_request_vars->request('action', MX_TYPE_NO_TAGS, '');
		}
		else
		{
			if ( $approve && $this->auth_user[$category_id]['auth_mod'])
			{
				$action = 'approve';
			}
			else if ( $unapprove && $this->auth_user[$category_id]['auth_mod'])
			{
				$action = 'unapprove';
			}
			else if ( $delete && ( $this->auth_user[$category_id]['auth_mod'] || $this->auth_user[$category_id]['auth_delete']) )
			{
				$action = 'delete';
			}
			else
			{
				$action = '';
			}
		}
		
		switch ( $action )
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
				
				$this->modified( true );
				$this->_kb();
				
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
		
				$this->modified( true );
				$this->_kb();
				
				$message = $lang['Article_unapproved'] . '<br /><br />' . sprintf( $lang['Click_return_article_manager'], '<a href="' . append_sid( this_kb_mxurl( "page=$page_id&mode=cat&cat=$category_id&start=$start") ) . '">', '</a>' ) ;
				mx_message_die( GENERAL_MESSAGE, $message );
				
				break;
		
			case 'delete':
		
				if ( $mx_request_vars->get('c') == "yes" )
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
		
					$this->modified( true );
					$this->_kb();
					
					mx_message_die( GENERAL_MESSAGE, $message );
				}
				else
				{
					$category_id = ( $ref_stats ? 1 : $category_id );
		
					$message = $lang['Confirm_art_delete'] . '<br /><br />' . sprintf( $lang['Confirm_art_delete_yes'], '<a href="' . append_sid( this_kb_mxurl( "mode=moderate&action=delete&page=$page_id&cat=$category_id&c=yes&a=$article_id&start=$start" ) ) . '">', '</a>' ) . '<br /><br />' . sprintf( $lang['Confirm_art_delete_no'], '<a href="' . append_sid( $mx_root_path . "index.$phpEx?page=$page_id&mode=cat&cat=$category_id&start=$start" ) . '">', '</a>' );
					mx_message_die( GENERAL_MESSAGE, $message );
				}
				
				break;
		}

		// ===================================================
		// assign var for top navigation
		// ===================================================
		$this->generate_navigation( $category_id );
				
		//
		// User authorisation levels output
		//
		$this->auth_can($category_id);
		
		//
		// Get footer quick dropdown jumpbox
		//
		$this->generate_jumpbox( 'auth_view', $category_id, $category_id, true );		
	}
}

?>