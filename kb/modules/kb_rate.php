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
 *    $Id: kb_rate.php,v 1.1 2005/12/08 15:06:47 jonohlsson Exp $
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

class mx_kb_rate extends mx_kb_public
{
	function main( $action )
	{
		global $template, $lang, $db, $phpEx, $kb_config, $mx_request_vars, $userdata; 
		global $mx_root_path, $module_root_path, $is_block, $phpEx;

		//
		// Request vars
		//		
		$start = $mx_request_vars->get('start', MX_TYPE_INT, 0);
		$article_id = $mx_request_vars->request('k', MX_TYPE_INT, 0);
		$rating = $mx_request_vars->request('rating', MX_TYPE_INT, 0);
		$rate = $mx_request_vars->request('rate', MX_TYPE_NO_TAGS, '');

		if ( empty( $article_id ) )
		{
			mx_message_die( GENERAL_MESSAGE, $lang['Article_not_exsist'] );
		}
				
		$template->set_filenames( array( 'body' => 'kb_rate_body.tpl' ) );
		
		$sql = "SELECT * 
				FROM " . KB_ARTICLES_TABLE . " 
				WHERE article_id = '" . $article_id . "'";
		
		if ( !( $result = $db->sql_query( $sql ) ) )
		{
			mx_message_die( GENERAL_ERROR, 'Couldnt Query Article info', '', __LINE__, __FILE__, $sql );
		}
		
		if ( !$article = $db->sql_fetchrow( $result ) )
		{
			mx_message_die( GENERAL_MESSAGE, $lang['Article_not_exsist'] );
		}

		$db->sql_freeresult( $result );

		$category_id = $article['article_category_id'];

		if ( !$this->auth_user[$category_id]['auth_rate'] || !$kb_config['use_ratings'] )
		{
			//
			// The user is not authed to read this cat ...
			//
			$message = $lang['Not_authorized'];
			mx_message_die(GENERAL_MESSAGE, $message);
		}
		
		$ipaddy = getenv ( "REMOTE_ADDR" );
		
		if ( $kb_config['votes_check_ip'] == 1 )
		{
			$sql = "SELECT * FROM " . KB_VOTES_TABLE . " WHERE votes_ip = '" . $ipaddy . "' AND votes_file = '" . $article_id . "'";
			if ( !( $result = $db->sql_query( $sql ) ) )
			{
				mx_message_die( GENERAL_ERROR, 'Couldnt Query rate ip', '', __LINE__, __FILE__, $sql );
			}
		
			if ( $db->sql_numrows( $result ) > 0 )
			{
				$template->assign_vars( array( "META" => '<meta http-equiv="refresh" content="3;url=' . append_sid( this_kb_mxurl( "action=url&amp;k=" . $article_id ) ) . '">' ) 
					);
				$message = $lang['Rerror'] . "<br /><br />" . sprintf( $lang['Click_return_rate'], "<a href=\"" . append_sid( this_kb_mxurl( "mode=article&amp;k=$article_id" ) ) . "\">", "</a>" );
				mx_message_die( GENERAL_MESSAGE, $message );
			}
		}
		
		if ( $kb_config['votes_check_userid'] == 1 )
		{
			$sql = "SELECT * FROM " . KB_VOTES_TABLE . " WHERE votes_userid = '" . $userdata['user_id'] . "' AND votes_file = '" . $article_id . "'";
			if ( !( $result = $db->sql_query( $sql ) ) )
			{
				mx_message_die( GENERAL_ERROR, 'Couldnt Query rate ip', '', __LINE__, __FILE__, $sql );
			}
		
			if ( $db->sql_numrows( $result ) > 0 )
			{
				$template->assign_vars( array( "META" => '<meta http-equiv="refresh" content="3;url=' . append_sid( this_kb_mxurl( "action=url&amp;k=" . $article_id ) ) . '">' ) 
					);
				$message = $lang['Rerror'] . "<br /><br />" . sprintf( $lang['Click_return_rate'], "<a href=\"" . append_sid( this_kb_mxurl( "mode=article&amp;k=$article_id" ) ) . "\">", "</a>" );
				mx_message_die( GENERAL_MESSAGE, $message );
			}
		}
		
		if ( $rate == 'dorate' )
		{
			$conf = str_replace( "{filename}", $article['article_title'], $lang['Rconf'] );
			$conf = str_replace( "{rate}", $rating, $conf );
		
			if ( $article['article_totalvotes'] == 1 )
			{
				$add = 0;
			}
			else
			{
				$add = 1;
			}
		
			$sql = "UPDATE " . KB_ARTICLES_TABLE . " SET article_rating=article_rating+" . $rating . ", article_totalvotes=article_totalvotes+1 WHERE article_id = '" . $article_id . "'";
		
			if ( !( $update = $db->sql_query( $sql ) ) )
			{
				mx_message_die( GENERAL_ERROR, 'Couldnt Update rating table', '', __LINE__, __FILE__, $sql );
			}
		
			$ipaddy = getenv ( "REMOTE_ADDR" );
		
			$sql = "INSERT INTO " . KB_VOTES_TABLE . " VALUES('" . $ipaddy . "', '" . $userdata['user_id'] . "', '" . $article_id . "')";
		
			if ( !( $insert = $db->sql_query( $sql ) ) )
			{
				mx_message_die( GENERAL_ERROR, 'Couldnt Update rating table', '', __LINE__, __FILE__, $sql );
			}
		
			$sql = "SELECT * FROM " . KB_ARTICLES_TABLE . " WHERE article_id = '" . $article_id . "'";
		
			if ( !( $result = $db->sql_query( $sql ) ) )
			{
				mx_message_die( GENERAL_ERROR, 'Couldnt Update rating table', '', __LINE__, __FILE__, $sql );
			}
		
			$article = $db->sql_fetchrow( $result );
		
			if ( $article['article_rating'] == 0 or $article['article_totalvotes'] == 0 )
			{
				$nrating = 0;
			}
			else
			{
				$nrating = round( $article['article_rating'] / ( $article['article_totalvotes'] ), 3 );
			}
		
			$conf = str_replace( "{newrating}", $nrating, $conf );
		
			$template->assign_vars( array( "META" => '<meta http-equiv="refresh" content="3;url=' . append_sid( this_kb_mxurl( "action=url&amp;k=" . $article_id ) ) . '">' ) 
				);
			if ( !$reader_mode )
			{
				$message = $conf . "<br /><br />" . sprintf( $lang['Click_return_rate'], "<a href=\"" . append_sid( this_kb_mxurl( "mode=article&amp;k=$article_id" ) ) . "\">", "</a>" ) . "<br /><br />" . sprintf( $lang['Click_return_forum'], "<a href=\"" . append_sid( "index.$phpEx?page=$page_id&amp;mode=cat&amp;cat=$category_id" ) . "\">", "</a>" );
			}
			else
			{
				$message = $conf . "<br /><br />" . sprintf( $lang['Click_return_rate'], "<a href=\"" . append_sid( this_kb_mxurl( "mode=article&amp;k=$article_id" ) ) . "\">", "</a>" );
			}
		
			mx_message_die( GENERAL_MESSAGE, $message );
		}
		else
		{
			$rateinfo = str_replace( "{filename}", $article['article_title'], $lang['Rateinfo'] );
		
			$template->assign_block_vars( "rate", array() );
			
			//
			// Send variables to template (the associated *.tpl file)
			//
			/*
			$template->assign_vars( array( 
				'PATH' => $path_kb
			));
			*/
							
			$template->assign_vars( array( 
				'S_RATE_ACTION' => append_sid( this_kb_mxurl( ) ),
				'L_RATE' => $lang['Rate'],
				'L_RERROR' => $lang['Rerror'],
				'L_R1' => $lang['R1'],
				'L_R2' => $lang['R2'],
				'L_R3' => $lang['R3'],
				'L_R4' => $lang['R4'],
				'L_R5' => $lang['R5'],
				'L_R6' => $lang['R6'],
				'L_R7' => $lang['R7'],
				'L_R8' => $lang['R8'],
				'L_R9' => $lang['R9'],
				'L_R10' => $lang['R10'],
				'RATEINFO' => $rateinfo,
				'ID' => $article_id ) 
			);
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