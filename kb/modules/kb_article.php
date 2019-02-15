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
 *    $Id: kb_article.php,v 1.1 2005/12/08 15:06:46 jonohlsson Exp $
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

class mx_kb_article extends mx_kb_public
{
	function main( $action )
	{
		global $template, $lang, $db, $phpEx, $kb_config, $mx_request_vars, $userdata; 
		global $phpbb_root_path, $mx_root_path, $module_root_path, $is_block, $phpEx, $images;
		global $mx_kb_custom_field, $print_version, $reader_mode;

		//
		// Request vars
		//		
		$start = $mx_request_vars->get('start', MX_TYPE_INT, 0);
		$article_id = $mx_request_vars->request('k', MX_TYPE_INT, '');
		$page_num = $mx_request_vars->request('page_num', MX_TYPE_INT, 1) - 1;
		
		if ( empty( $article_id ) )
		{
			mx_message_die( GENERAL_MESSAGE, $lang['Article_not_exsist'] );
		}

		//
		// Instatiate text tools
		//
		$mx_kb_text_tools = new mx_kb_text_tools();
				
		$sql = "SELECT *
				FROM " . KB_ARTICLES_TABLE . "
				WHERE article_id = $article_id";
		
		if ( !( $result = $db->sql_query( $sql ) ) )
		{
			mx_message_die( GENERAL_ERROR, "Could not obtain article data", '', __LINE__, __FILE__, $sql );
		}
		
		// ===================================================
		// article doesn't exist'
		// ===================================================
		if ( !$kb_row = $db->sql_fetchrow( $result ) )
		{
			mx_message_die( GENERAL_MESSAGE, $lang['Article_not_exsist'] );
		}
		$db->sql_freeresult( $result ); 	

		// ===================================================
		// KB auth for viewing article
		// ===================================================
		if ( ( !$this->auth_user[$kb_row['article_category_id']]['auth_view'] ) )
		{
			/*
			if ( !$userdata['session_logged_in'] )
			{
				mx_redirect(append_sid($mx_root_path . "login.$phpEx?redirect=".pa_this_mxurl("action=file&file_id=" . $file_id), true));
			}
			*/
			$message = $lang['Article_not_exsist'] . '<br /><br />' . sprintf( $lang['Click_return_kb'], '<a href="' . append_sid( this_kb_mxurl() ) . '">', '</a>' ) . '<br /><br />' . sprintf( $lang['Click_return_index'], '<a href="' . append_sid( $phpbb_root_path . "index.$phpEx" ) . '">', '</a>' );
			mx_message_die( GENERAL_MESSAGE, $message );
		}
		
		// ===================================================
		// Prepare article info to display them
		// ===================================================			
		$article_title = $kb_row['article_title'];
		$article_description = $kb_row['article_description'];
		$article_category_id = $kb_row['article_category_id'];
		$article_category_name = $this->cat_rowset[$article_category_id]['category_name'];
		$author_id = $kb_row['article_author_id'];
		$approved = $kb_row['approved'];
		$date = create_date( $board_config['default_dateformat'], $kb_row['article_date'], $board_config['board_timezone'] ); 
			
		//
		// wysiwyg
		//
		if ( $kb_config['allow_wysiwyg'] && file_exists( $mx_root_path . 'modules/tinymce/jscripts/tiny_mce/blank.htm' ))
		{
			$bbcode_on = false;
			$html_on = true;
			$smilies_on = false;
			
			$html_entities_match = array( );
			$html_entities_replace = array( );	
		}
		else 
		{
			$bbcode_on = $kb_config['allow_bbcode'] ? true : false;
			$html_on = $kb_config['allow_html'] ? true : false;
			$smilies_on = $kb_config['allow_smilies'] ? true : false;
			
			$board_config['allow_html_tags'] = $kb_config['allowed_html_tags'];
		}
					
		//
		// Define censored word matches
		//
		$orig_word = array();
		$replacement_word = array();
		obtain_word_list($orig_word, $replacement_word);
			
		//
		// Get vars
		//
		$temp_url = append_sid( this_kb_mxurl( "mode=cat&amp;cat=$article_category_id" ) );
		$category = '<a href="' . $temp_url . '" class="gensmall">' . $article_category_name . '</a>';
			
		if ( $author_id == -1 )
		{
			$author_kb_art = ( $kb_row['username'] == '' ) ? $lang['Guest'] : $kb_row['username'];
		}
		else
		{
			$author_name = $this->get_kb_author( $author_id );
			$temp_url = append_sid( $phpbb_root_path . "profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$author_id" );
			$author_kb_art = '<a href="' . $temp_url . '" class="gensmall">' . $author_name . '</a>';
		}
		
		$art_pages = explode( '[page]', stripslashes( $kb_row['article_body'] ) );
		$article = trim( $art_pages[$page_num] );
		$article = str_replace( '[toc]', '', $article );
		$article = $this->article_formatting( $article );
		
		$type_id = $kb_row['article_type'];
		$type = $this->get_kb_type( $type_id );
		$new_views = $kb_row['views'] + 1;
		$views = '<b>' . $lang['Views'] . '</b> ' . $new_views;
		
		if ( $kb_row['article_rating'] == 0 || $kb_row['article_totalvotes'] == 0 )
		{
			$rating = 0;
			$rating_votes = 0;
			$rating_message = $lang['No_votes'] ;
			$rate_message = '<b>' . $lang['Votes_label'] . '</b> ' . $rating_message;
		}
		else
		{
			$rating = round( $kb_row['article_rating'] / $kb_row['article_totalvotes'], 2 );
			$rating_votes = $kb_row['article_totalvotes'];
			$rating_message = $rating . '/10, ' . $rating_votes . ' ' . $lang['Votes'] ;
			$rate_message = '<b>' . $lang['Votes_label'] . '</b> ' . $rating_message;
		}
		
		if ( $page_num == 0 )
		{
			$sql = "UPDATE " . KB_ARTICLES_TABLE . " SET
				    views = '" . $new_views . "'
				    WHERE article_id = " . $article_id;
		}
		if ( !( $result2 = $db->sql_query( $sql ) ) )
		{
			mx_message_die( GENERAL_ERROR, "Could not update article's views", '', __LINE__, __FILE__, $sql );
		} 
		
		//
		// Was a highlight request part of the URI?
		//
		$original_highlight = '';
		$highlight_match = $highlight = '';
		if (isset($HTTP_GET_VARS['highlight']))
		{
			//
			// Split words and phrases
			//
			$original_highlight = '&highlight='.trim(htmlspecialchars($HTTP_GET_VARS['highlight']));
			$words = explode(' ', trim(htmlspecialchars($HTTP_GET_VARS['highlight'])));
		
			for($i = 0; $i < sizeof($words); $i++)
			{
				if (trim($words[$i]) != '')
				{
					$highlight_match .= (($highlight_match != '') ? '|' : '') . str_replace('*', '\w*', phpbb_preg_quote($words[$i], '#'));
				}
			}
			unset($words);
		
			$highlight = urlencode($HTTP_GET_VARS['highlight']);
			$highlight_match = phpbb_rtrim($highlight_match, "\\");
		}
		
		if ( !$html_on )
		{
			$article = preg_replace( '#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $article );
		} 
		
		//
		// Remove Images and/or links
		//
		if (!$kb_config['allow_images'] || !$kb_config['allow_inks'])
		{
			$article = $mx_kb_text_tools->remove_images_links( $article, $kb_config['allow_images'], $kb_config['no_image_message'], $kb_config['allow_links'], $kb_config['no_link_message'] );
		}
		
		//
		// Parse message
		//
		$bbcode_uid = $kb_row['bbcode_uid'];
		if ( $bbcode_on )
		{
			if ( $bbcode_uid != '' )
			{
				$article = ( $bbcode_on ) ? bbencode_second_pass( $article, $bbcode_uid ) : preg_replace( '/\:[0-9a-z\:]+\]/si', ']', $article );
			}
		}
		$article = make_clickable( $article ); 
		
		//
		// Parse smilies
		//
		if ( $smilies_on )
		{
			$article = mx_smilies_pass( $article );
		} 
		
		//
		// Highlight active words (primarily for search)
		//
		if ($highlight_match)
		{
			//
			// This was shamelessly 'borrowed' from volker at multiartstudio dot de
			// via php.net's annotated manual
			//
			$article = str_replace('\"', '"', substr(@preg_replace('#(\>(((?>([^><]+|(?R)))*)\<))#se', "@preg_replace('#\b(" . $highlight_match . ")\b#i', '<span style=\"color:#" . $theme['fontcolor3'] . "\"><b>\\\\1</b></span>', '\\0')", '>' . $article . '<'), 1, -1));
		} 
		
		//
		// Replace naughty words
		//
		if ( count( $orig_word ) )
		{
			$article_title = preg_replace( $orig_word, $replacement_word, $article_title );
			$article_description = preg_replace( $orig_word, $replacement_word, $article_description );
		
			$article = str_replace( '\"', '"', substr( preg_replace( '#(\>(((?>([^><]+|(?R)))*)\<))#se', "preg_replace(\$orig_word, \$replacement_word, '\\0')", '>' . $article . '<' ), 1, -1 ) );
		} 
		
		//
		// Replace newlines (we use this rather than nl2br because
		// till recently it wasn't XHTML compliant)
		//
		$article = str_replace( "\n", "\n<br />\n", $article ); 
		
		//
		// Highlight active words (primarily for search)
		//
		if ( $highlight_match )
		{ 
			//
			// This was shamelessly 'borrowed' from volker at multiartstudio dot de
			// via php.net's annotated manual
			//
			$article = str_replace( '\"', '"', substr( preg_replace( '#(\>(((?>([^><]+|(?R)))*)\<))#se', "preg_replace('#\b(" . $highlight_match . ")\b#i', '<span style=\"color:#" . $theme['fontcolor3'] . "\"><b>\\\\1</b></span>', '\\0')", '>' . $article . '<' ), 1, -1 ) );
		}
		
		$this->page_title = $article_title;
		
		//
		// Text formatting
		//
		if ( $kb_config['max_subject_chars'] > 0 )
		{
			$article_title = $mx_kb_text_tools->truncate_text( $article_title, $kb_config['max_subject_chars'], true );
		}

		if ( $kb_config['max_description_chars'] > 0 )
		{
			$article_description = $mx_kb_text_tools->truncate_text( $article_description, $kb_config['max_description_chars'], true );
		}
				
		if ( $kb_config['max_chars'] > 0 )
		{
			$article = $mx_kb_text_tools->truncate_text( $article, $kb_config['max_chars'], true );
		}		
		
		if ( $kb_config['formatting_truncate_links'] || $kb_config['formatting_image_resize'] > 0 || $kb_config['formatting_wordwrap'] ) 
		{
			$article = $mx_kb_text_tools->decode( $article, $kb_config['formatting_truncate_links'], intval($kb_config['formatting_image_resize']), $kb_config['formatting_wordwrap'] );
		}
		
		//
		// Edit buttons
		//
		if ( ( $userdata['user_id'] == $author_id && $this->auth_user[$article_category_id]['auth_edit'] ) || $this->auth_user[$article_category_id]['auth_mod'] )
		{
			$temp_url = append_sid( this_kb_mxurl( "mode=edit&amp;k=" . $article_id ) );
			$edit_img = '<a href="' . $temp_url . '"><img src="' . $phpbb_root_path . $images['icon_edit'] . '" alt="' . $lang['Edit_delete_post'] . '" title="' . $lang['Edit_delete_post'] . '" border="0" /></a>';
			$edit = '<a href="' . $temp_url . '">' . $lang['Edit_delete_post'] . '</a>';
		}
		else
		{
			$edit_img = '';
			$edit = '';
		} 
		
		//
		// If this is an allowed article, go ahead and display it
		//
		if ( !$this->auth_user[$article_category_id]['auth_view'] || !$article_title || ( !$approved && !$this->auth_user[$article_category_id]['auth_mod'] ) || ( !$this->ns_auth_cat( $article_category_id ) && !$print_version ) )
		{
			$message = $lang['Article_not_exsist'] . '<br /><br />' . sprintf( $lang['Click_return_kb'], '<a href="' . append_sid( this_kb_mxurl() ) . '">', '</a>' ) . '<br /><br />' . sprintf( $lang['Click_return_index'], '<a href="' . append_sid( $phpbb_root_path . "index.$phpEx" ) . '">', '</a>' );
			mx_message_die( GENERAL_MESSAGE, $message );
		}
		else
		{ 
			//
			// Start output of page
			//
			if ( !$print_version )
			{
				if ( $reader_mode )
				{
					$template->set_filenames( array( 'body' => 'kb_article_reader.tpl' ) );
				}
				else
				{
					$template->set_filenames( array( 'body' => 'kb_article_body.tpl' ) );
				}
			}
			else
			{
				$template->set_filenames( array( 'body' => 'kb_article_body_print.tpl' ) );
			}
			
			/*
			$kb_comment = array();
			$topic_id = $kb_row['topic_id'];
		
			//
			// Populate the kb_comment variable
			//
			$kb_comment = $this->kb_get_data($kb_row, $userdata );
		
			//
			// Compose post header
			//
			$subject = $lang['KB_comment_prefix'] . $kb_comment['article_title'];
			$message_temp = $this->kb_compose_comment( $kb_comment, $kb_custom_field );
				
			$kb_message = $message_temp['message'];
			$kb_update_message = $message_temp['update_message'];
			
			$topic_id_tmp = $kb_row['topic_id'];
			
			//
			// Check if this topic exists. It could have been deleted by accident ;) If so recreate it!
			//
			if ( !empty($topic_id_tmp) )
			{
				$sql = "SELECT *
					FROM " . TOPICS_TABLE . "
					WHERE topic_id = $topic_id_tmp";
				if ( !($result = $db->sql_query($sql)) )
				{
					mx_message_die(GENERAL_ERROR, 'Could not obtain forums information', '', __LINE__, __FILE__, $sql);
				}
			}
			
			//
			// If the query doesn't return any rows this isn't a valid topic. Set to null.
			//
			if ( !($topic_row = $db->sql_fetchrow($result)) )
			{
				$topic_id = 0;
			}	
					
			//
			// If no phpbb topic id is created, create one ;)
			//
			if ( !$topic_id && $approved && $kb_config['use_comments'] && $kb_comment['category_forum_id'] > 0)
			{ 
				//
				// Post
				//
				$topic_data = $this->kb_insert_post( $kb_message, $subject, $kb_comment['category_forum_id'], $kb_comment['article_editor_id'], $kb_comment['article_editor'], $kb_comment['article_editor_sig'], $kb_comment['topic_id'], $kb_update_message );
			
				$sql = "UPDATE " . KB_ARTICLES_TABLE . " SET topic_id = " . $topic_data['topic_id'] . " 
			 	 WHERE article_id = " . $kb_comment['article_id'];
		
				if ( !( $result = $db->sql_query( $sql ) ) )
				{
					mx_message_die( GENERAL_ERROR, "Could not update article data", '', __LINE__, __FILE__, $sql );
				}
				
				$topic_id = $topic_data['topic_id'];
			}
			
			if ( $this->auth_user[$article_category_id]['auth_comment'] && $kb_config['use_comments'] )
			{
				$sql = "SELECT topic_id, topic_replies FROM " . TOPICS_TABLE . " WHERE topic_id = " . $topic_id;
		
				if ( !( $result4 = $db->sql_query( $sql ) ) )
				{
					mx_message_die( GENERAL_ERROR, 'Error getting comments', '', __LINE__, __FILE__, $sql );
				}
				$topic = $db->sql_fetchrow( $result4 );
				$num_of_replies = intval( $topic['topic_replies'] );
		
				$temp_url = append_sid( $phpbb_root_path . "viewtopic.php?t=" . $topic['topic_id'] );
				$comments = '<b>' . $lang['Comments'] . '</b>';
				$comments_img = '<a href="' . $temp_url . '" class="gensmall"> [' . $num_of_replies . ' - ' . $lang['Post_comments'] . ']</a>';
		
				$template->assign_block_vars( 'switch_comments', array() );
			}
			else
			{
				$comments = '';
			}
		
			if ( $kb_config['comments_show'] && $topic_id )
			{ 
				//
				// page number
				//
				if ( isset( $page_num ) )
				{
					$page_numm = "&page_num=" . ( $page_num + 1 ) ;
				}
				else
				{
					$page_numm = '';
				}
		
				$show_num_comments = $kb_config['comments_pagination'];
				$pagination = generate_pagination( this_kb_mxurl( "mode=article&k=$article_id" . $page_numm ), $num_of_replies, $show_num_comments, $start ) . '&nbsp;';
				get_kb_comments( $topic_id, $start, $show_num_comments ); 
			} 
			*/
						
			/*		
			//
			// rate
			//
			if ( $this->auth_user[$article_category_id]['auth_rate'] && $kb_config['use_ratings'])
			{
				$temp_url = append_sid( this_kb_mxurl( "mode=rate&amp;k=" . $article_id ) );
				$rate_img = '<a href="' . $temp_url . '" class="gensmall">' . $lang['ADD_RATING'] . '</a>';
				$rate = '<a href="' . $temp_url . '" class="gensmall">' . $lang['ADD_RATING'] . '</a>';
				
				$template->assign_block_vars( 'switch_ratings', array() );
			}
			else
			{
				$rate_img = '';
				$rate = '';
			}
			*/

			$print_url = append_sid( this_kb_mxurl( "mode=article&amp;k=" . $article_id ."&page_num=".($page_num+1)."&start=".$start ."&print=true", true ) );
		
			$template->assign_vars( array( 
				'PAGINATION' => $pagination,
				'PAGE_NUMBER' => sprintf( $lang['Page_of'], ( floor( $start / $kb_config['comments_pagination'] ) + 1 ), ceil( $num_of_replies / $kb_config['comments_pagination'] ) ),
				'L_GOTO_PAGE' => $lang['Goto_page'],
					
				'L_ARTICLE_DESCRIPTION' => $lang['Article_description'],
				'L_ARTICLE_DATE' => $lang['Date'],
				'L_ARTICLE_TYPE' => $lang['Article_type'],
				'L_ARTICLE_CATEGORY' => $lang['Category'],
				'L_ARTICLE_AUTHOR' => $lang['Author'],
				'L_GOTO_PAGE' => $lang['Goto_page'],
				'L_TOC' => $lang['TOC'],
				'L_COMMENTS' => $lang['Comments_show_title'],
				'L_PRINT' => $lang['Print_version'],
		
				'U_PRINT' => $print_url,
		
				'ARTICLE_TITLE' => $article_title,
				'ARTICLE_AUTHOR' => $author_kb_art,
				'ARTICLE_CATEGORY' => $category,
				'ARTICLE_TEXT' => $article,
				'ARTICLE_DESCRIPTION' => $article_description,
				'ARTICLE_DATE' => $date,
				'ARTICLE_TYPE' => $type,
				'EDIT_IMG' => $edit_img,
				'EDIT' => $edit,
				'VIEWS' => $views,
		
				'RATINGS' => $rate_message,
				'RATE_IMG' => $rate_img,
				'RATE' => $rate,
				'COMMENTS' => $comments,
				'COMMENTS_IMG' => $comments_img
			 ) ); 
			
			//		 
			// article pages table of contents
			//
			if ( count( $art_pages ) > 1 )
			{
				$template->assign_block_vars( 'switch_toc', array() );
		
				$i = 0;
				while ( $i < count( $art_pages ) )
				{
					$page_number = $i + 1;
		
					$art_split = explode( '[toc]', $art_pages[$i] );
					$article_toc = $art_split[0];
		
					//
					// Fix up the toc title
					//
					if ( !$html_on )
					{
						$article_toc = preg_replace( '#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $article_toc );
					} 
					
					//
					// Parse message
					//
					$article_toc = preg_replace( "'\[[\/\!]*?[^\[\]]*?\]'si", "", $article_toc ); // Fixed
					$article_toc = make_clickable( $article_toc ); 
					
					//
					// Parse smilies
					//
					if ( $smilies_on )
					{
						$article_toc = mx_smilies_pass( $article_toc );
					} 
					
					//
					// Replace naughty words
					//
					if ( count( $orig_word ) )
					{
						$article_toc = str_replace( '\"', '"', substr( preg_replace( '#(\>(((?>([^><]+|(?R)))*)\<))#se', "preg_replace(\$orig_word, \$replacement_word, '\\0')", '>' . $article_toc . '<' ), 1, -1 ) );
					} 
					
					// Replace newlines (we use this rather than nl2br because
					// till recently it wasn't XHTML compliant)
					// $article_toc = str_replace("\n", "\n<br />\n", $article_toc);
					$page_toc = $art_pages[$i]; 
					
					//
					// Sync with comments pagination
					//
					$start_pag = $start > -1 ? "&start=" . $start : '';
		
					if ( $page_num != $i )
					{
						if ( !$print_version )
						{
							$temp_url = append_sid( this_kb_mxurl( "mode=article&k=$article_id&page_num=$page_number" . $start_pag . $original_highlight ) );
						}
						else
						{
							$temp_url = append_sid( this_kb_mxurl( "mode=article&k=$article_id&page_num=$page_number&print=true" . $start_pag . $original_highlight, true ) );
						}
						$page_link = '<a href="' . $temp_url . '" class="nav">' . $page_number . ' - ' . $article_toc . '</a>';
					}
					else
					{
						$page_link = $page_number . ' - ' . $article_toc ;
					}
		
					if ( $i < count( $art_pages ) - 1 )
					{
						$page_link .= '<br />';
					}
					
					$template->assign_block_vars( 'switch_toc.pages', array( 'TOC_ITEM' => $page_link ) );
					$i++;
				}
			} 
			
			//
			// article pages TOC navigation
			//
			if ( count( $art_pages ) > 1 )
			{
				$template->assign_block_vars( 'switch_pages', array() );
		
				$start_pag = $start > -1 ? "&start=" . $start : '';
				
				$i = 0;
				while ( $i < count( $art_pages ) )
				{
					$page_number = $i + 1;
					
					if ( $page_num != $i )
					{
						if ( !$print_version )
						{
							$temp_url = append_sid( this_kb_mxurl( "mode=article&k=$article_id&page_num=$page_number" . $start_pag . $original_highlight) );
						}
						else
						{
							$temp_url = append_sid( this_kb_mxurl( "mode=article&k=$article_id&page_num=$page_number&print=true" . $start_pag . $original_highlight, true ) );
						}
						$page_link = '<a href="' . $temp_url . '" class="nav">' . $page_number . '</a>';
					}
					else
					{
						$page_link = $page_number;
					}
		
					if ( $i < count( $art_pages ) - 1 )
					{
						$page_link .= ', ';
					}
					
					$template->assign_block_vars( 'switch_pages.pages', array( 'PAGE_LINK' => $page_link ) );
					$i++;
				}
			}
		}

		//
		// Instantiate custom fields (only used in kb_article)
		//
		include_once( $module_root_path . 'kb/includes/functions_field.' . $phpEx );
		$mx_kb_custom_field = new mx_kb_custom_field();
		$mx_kb_custom_field->init();
		$mx_kb_custom_field->display_data( $article_id );
		
		//
		// Ratings
		//
		if ( $this->ratings[$article_category_id]['activated'] )
		{
			$file_rating = ( $file_data['rating'] != 0 ) ? round( $file_data['rating'], 2 ) . ' / 10' : $lang['Not_rated'];
			
			if ( $this->auth_user[$article_category_id]['auth_rate'] )
			{
				$rate_img = $images['pa_rate'];
			}
			
			$template->assign_block_vars( 'use_ratings', array(
				'L_RATING' => $lang['DlRating'],
				'L_RATE' => $lang['Rate'],
				'L_VOTES' => $lang['Votes'],
				'FILE_VOTES' => $file_data['total_votes'],
				'RATING' => $file_rating,
				
				//
				// Allowed to rate
				//
				'RATE_IMG' => $rate_img,
				'U_RATE' => append_sid( this_kb_mxurl( 'action=rate&file_id=' . $file_id ) ),
			));
		}
			
		//
		// Comments
		//	
		if ( $this->comments[$article_category_id]['activated'] && $this->auth_user[$article_category_id]['auth_comment'])
		{
			$comments_type = $this->comments[$article_category_id]['internal_comments'] ? 'internal' : 'phpbb';
			
			//
			// Instatiate comments
			//			
			include_once( $module_root_path . 'kb/includes/functions_comment.' . $phpEx );
			$mx_kb_comments = new mx_kb_comments();
			$mx_kb_comments->init( $kb_row, $comments_type );
			$mx_kb_comments->display_comments();
		}
				
		/*	
		//
		// Comments
		//
		if ( $this->auth_user[$article_category_id]['auth_comment'] && $kb_config['use_comments'] )
		{
			$template->assign_block_vars( 'switch_comments', array() );
			$mx_kb_comments->generate_link();
			$mx_kb_comments->display_comments();
		}
			
		//
		// Ratings
		//
		if ( $this->auth_user[$article_category_id]['auth_rate'] && $kb_config['use_ratings'])
		{
			$template->assign_block_vars( 'switch_ratings', array() );
			$mx_kb_rate->generate_link();
		}
		*/	

		// ===================================================
		// assign var for top navigation
		// ===================================================
		$this->generate_navigation( $article_category_id );
				
		//
		// User authorisation levels output
		//
		$this->auth_can($article_category_id);
		
		//
		// Get footer quick dropdown jumpbox
		//
		$this->generate_jumpbox( 'auth_view', $article_category_id, $article_category_id, true );			
	}
}

?>