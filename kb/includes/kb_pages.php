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
 *    $Id: kb_pages.php,v 1.1 2005/12/08 15:06:46 jonohlsson Exp $
 */

/**
 * This program is free software; you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation; either version 2 of the License, or
 *    (at your option) any later version.
 */

if ( MXBB_27x )
{	

	$page_id = get_page_id( 'kb_article_reader.php', true );

	if ( !$page_id )
	{
		$page_id = get_page_id( 'kb.php', true );
	}
	
	if ( !empty( $page_id ) )
	{
		$kb_pages = $page_id;
		$kb_error = false;
	}
	else 
	{
		$kb_error = true;
	}
	// Start initial var setup
	
	$cat_id = $article_id = '';
	
	if ( isset( $HTTP_GET_VARS['cat'] ) || isset( $HTTP_POST_VARS['cat'] ) )
	{
		$cat_id = ( isset( $HTTP_GET_VARS['cat'] ) ) ? intval( $HTTP_GET_VARS['cat'] ) : intval( $HTTP_POST_VARS['cat'] );
	}
	else if ( isset( $HTTP_GET_VARS['k'] ) || isset( $HTTP_POST_VARS['k'] ) )
	{
		$article_id = ( isset( $HTTP_GET_VARS['k'] ) ) ? intval( $HTTP_GET_VARS['k'] ) : intval( $HTTP_POST_VARS['k'] );
	}	
}	 
else 
{
	// Note: This piece of code snippet is somewhat ugly and needs cleaning up...still it works...
	// What it does?
	// Well if given a direct kb article link, it finds on what portal page the kb block is located. 
	// Since we can have different kb blocks on different portal pages displaying different kb categories/articles, this check is needed ;)
	// Oh, do not blame markus for this code ;) 
	
	if ( empty( $_SESSION['kb_setup'] ) )
	{ 
		$news_setup = array();
		
		$sql = "SELECT col.page_id, blk.block_id, sys.parameter_value, fnc.function_file 
	    		FROM " . COLUMN_BLOCK_TABLE . " bct,
				" . COLUMN_TABLE . " col,
				" . BLOCK_TABLE . " blk,
				" . BLOCK_SYSTEM_PARAMETER_TABLE . " sys,
				" . FUNCTION_TABLE . " fnc,
				" . PARAMETER_TABLE . " par
	    		WHERE col.column_id = bct.column_id
				AND blk.function_id = fnc.function_id
				AND par.function_id = fnc.function_id
	      		AND blk.block_id    = bct.block_id
				AND blk.block_id    = sys.block_id
				AND par.parameter_name 	= 'kb_type_select'
	      		ORDER BY page_id, block_id";
		
		if ( !$kb_result = $db->sql_query( $sql ) )
		{
			mx_message_die( GENERAL_ERROR, "Could not query modules information", "", __LINE__, __FILE__, $sql );
		}
	
		while ( $kb_rows = $db->sql_fetchrow( $kb_result ) )
		{
				$page_id = $kb_rows['page_id'];
				$block_id = $kb_rows['block_id']; 
	
					$kb_select_par = $kb_rows['parameter_value'];
	
					// Extract 'what posts to view info', the cool Array ;)
					$kb_type_select_data = ( !empty( $kb_select_par ) ) ? unserialize($kb_select_par) : array();
					
					
					$kb_config['news_mode_operate'] = true; 
					
					if ( is_array($kb_type_select_data) )
					{
						$news_setup[$page_id] = $kb_type_select_data;
						$news_mode[$page_id] = $kb_rows['function_file'];
					}
					
		}
		
		$page_to_kb = array();
		while ( list( $page_idd, $news_setup_roww ) = each( $news_setup ) )
		{
			while ( list( $cat_idd, $news_forum_roww ) = each( $news_setup_roww ) )
			{
				if ( $news_forum_roww['forum_news'] == 1 )
				{
					$page_to_kb[$cat_idd] = ( empty( $page_to_kb[$cat_idd] ) || $news_mode[$page_idd] == 'kb_article_reader.php' ) ? $page_idd : $page_to_kb[$cat_idd];
				}
			}
			
		}
	
		$_SESSION['kb_setup'] = $page_to_kb;
	}
	
	// Start initial var setup
	
	$cat_id = $article_id = $sql = '';
	if ( isset( $HTTP_GET_VARS['cat'] ) || isset( $HTTP_POST_VARS['cat'] ) )
	{
		$cat_id = ( isset( $HTTP_GET_VARS['cat'] ) ) ? intval( $HTTP_GET_VARS['cat'] ) : intval( $HTTP_POST_VARS['cat'] );
	}
	else if ( isset( $HTTP_GET_VARS['k'] ) || isset( $HTTP_POST_VARS['k'] ) )
	{
		$article_id = ( isset( $HTTP_GET_VARS['k'] ) ) ? intval( $HTTP_GET_VARS['k'] ) : intval( $HTTP_POST_VARS['k'] );
		
		$sql = "SELECT article_category_id
		FROM " . KB_ARTICLES_TABLE . " 
		WHERE article_id = $article_id";
		
		if ( !( $result = $db->sql_query( $sql ) ) )
		{
			mx_message_die( GENERAL_ERROR, "no info - error", '', __LINE__, __FILE__, $sql );
		}
		
		if ( !( $row = $db->sql_fetchrow( $result ) ) )
		{
			//mx_message_die( GENERAL_MESSAGE, 'article_not_exist' );
		}
		$cat_id = $row['article_category_id'];
	}
	
	if ( !empty($cat_id) )
	{
		$kb_pages = $_SESSION['kb_setup'][$cat_id];
		$kb_error = false;
	}
	else 
	{
		$kb_error = true;
	}
}
?>