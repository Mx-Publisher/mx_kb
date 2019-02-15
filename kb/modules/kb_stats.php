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
 *    $Id: kb_stats.php,v 1.1 2005/12/08 15:06:47 jonohlsson Exp $
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

class mx_kb_stats extends mx_kb_public
{
	function main( $action )
	{
		global $template, $lang, $db, $phpEx, $kb_config, $mx_request_vars, $userdata; 
		global $mx_root_path, $module_root_path, $is_block, $phpEx;
		
		//
		// Request vars
		//
		$start = $mx_request_vars->get('start', MX_TYPE_INT, 0);
		$stats = $mx_request_vars->request('stats', MX_TYPE_NO_TAGS, '');	
			
		$this->generate_jumpbox( 'auth_view', 0, 0, true );
		
		$template->set_filenames( array( 'body' => 'kb_stats_body.tpl' ) );
		
		if ( $stats == 'toprated' )
		{
			$path_kb = $lang['Top_toprated'];
		}
		elseif ( $stats == 'latest' )
		{
			$path_kb = $lang['Top_latest'];
		}
		elseif ( $stats == 'mostpopular' )
		{
			$path_kb = $lang['Top_most_popular'];
		} 
		
		$template->assign_vars( array( 
			'L_CATEGORY_NAME' => $category_name,
			'L_ARTICLE' => $lang['Article'],
			'L_CAT' => $lang['Category'],
			'L_ARTICLE_TYPE' => $lang['Article_type'],
			'L_ARTICLE_CATEGORY' => $lang['Category'],
			'L_ARTICLE_DATE' => $lang['Date'],
			'L_ARTICLE_AUTHOR' => $lang['Author'],
			'L_VIEWS' => $lang['Views'],
			'L_VOTES' => $lang['Votes'],
			'L_CATEGORY' => $lang['Category_sub'],
			'L_ARTICLES' => $lang['Articles'],
			'PATH' => '&raquo; ' . $path_kb,
			'U_CAT' => append_sid( this_kb_mxurl( 'mode=cat&cat=' . $category_id ) ) 
		));
		
		$this->display_stats( $stats, '1', 'articlerow', $start, $kb_config['pagination'] );
		
		//
		// Stats pagination is inactivated for now ;)
		//
		if ( $total_articles > 0 )
		{
			// $pagination = generate_pagination( this_kb_mxurl( "mode=cat&cat=$category_id" ), $total_articles, $kb_config['pagination'], $start ) . '&nbsp;';
		}
		
		if ( $total_articles > 0 )
		{
			// $template->assign_block_vars( 'pagination', array() );
		}
		
		//
		// Get footer quick dropdown jumpbox
		//
		$this->generate_jumpbox( 'auth_view', 0, 0, true );			
		
	}
}
?>