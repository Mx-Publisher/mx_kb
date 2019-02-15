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
 *    $Id: kb_post.php,v 1.1 2005/12/08 15:06:47 jonohlsson Exp $
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

class mx_kb_post extends mx_kb_public
{
	function main( $action )
	{
		global $template, $mx_kb_functions, $lang, $board_config, $phpEx, $kb_config, $db, $images, $userdata, $_POST;
		global $html_entities_match, $html_entities_replace, $unhtml_specialchars_match, $unhtml_specialchars_replace;
		global $mx_root_path, $module_root_path, $phpbb_root_path, $is_block, $phpEx, $mx_request_vars;
		global $HTTP_POST_VARS;

		//
		// Includes
		//
		include_once( $phpbb_root_path . 'includes/bbcode.'.$phpEx);
		include_once( $phpbb_root_path . 'includes/functions_post.' . $phpEx );

		//
		// Request vars
		//
		$article_id = $mx_request_vars->request('k', MX_TYPE_INT, '');
		$kb_post_mode = empty( $article_id ) ? 'add' : 'edit';
		$this->page_title = $kb_post_mode == 'add' ? $lang['Add_article'] : $lang['Edit_article'];
		
		if ( $mx_request_vars->is_request('cat') )
		{
			$category_id = $mx_request_vars->request('cat', MX_TYPE_INT, 0);
			
		}
		else if( $kb_post_mode == 'edit')
		{
			$category_id = $this->get_cat_id($article_id);
		}
		else 
		{
			mx_message_die( GENERAL_MESSAGE, $lang['Category_not_exsist'] );
		}
		
		$delete = $mx_request_vars->request('delete', MX_TYPE_NO_TAGS, '');
		$submit = $mx_request_vars->is_request('article_submit');
		$preview = $mx_request_vars->is_request('preview');
		$cancel = $mx_request_vars->is_request('cancel');
				
		//
		// Instatiate custom fields (only used in kb_article)
		//
		include_once( $module_root_path . 'kb/includes/functions_field.' . $phpEx );
		$mx_kb_custom_field = new mx_kb_custom_field();
		$mx_kb_custom_field->init();
		
		//
		// wysiwyg
		//
		if ( $kb_config['allow_wysiwyg'] && file_exists( $mx_root_path . 'modules/tinymce/jscripts/tiny_mce/blank.htm' ))
		{
			$bbcode_on = false;
			$html_on = true;
			$smilies_on = false;
			$links_on = false;
			$images_on = false;
			
			$html_entities_match = array( );
			$html_entities_replace = array( );	
			
			$template->assign_block_vars( "tinyMCE", array() );
		}
		else 
		{
			$bbcode_on = $kb_config['allow_bbcode'] ? true : false;
			$html_on = $kb_config['allow_html'] ? true : false;
			$smilies_on = $kb_config['allow_smilies'] ? true : false;
			$links_on = $kb_config['allow_links'] ? true : false;
			$images_on = $kb_config['allow_images'] ? true : false;
						
			$board_config['allow_html_tags'] = $kb_config['allowed_html_tags'];
		
			$template->assign_block_vars( 'formatting', array() );
		}
		
		//
		// post article ----------------------------------------------------------------------------ADD/EDIT
		//
		if ( $submit )
		{
			if ( !$mx_request_vars->is_request('article_name') || !$mx_request_vars->is_request('article_desc') || !$mx_request_vars->is_request('message') )
			{
				$message = $lang['Empty_fields'] . '<br /><br />' . sprintf( $lang['Empty_fields_return'], '<a href="' . append_sid( this_kb_mxurl( 'mode=add' ) ) . '">', '</a>' );
				mx_message_die( GENERAL_MESSAGE, $message );
			}

			$article_title = ( !empty( $_POST['article_name'] ) ) ? htmlspecialchars( trim ( $_POST['article_name'] ) ) : '';
			$article_description = ( !empty( $_POST['article_desc'] ) ) ? htmlspecialchars( trim ( $_POST['article_desc'] ) ) : '';
			
			$bbcode_uid = $mx_request_vars->request('bbcode_uid', MX_TYPE_NO_TAGS, '');
			if ( empty( $bbcode_uid ) )
			{
				$bbcode_uid = ( $bbcode_on ) ? make_bbcode_uid() : '';
			}
							
			$article_text = str_replace( '<br />', "\n", $_POST['message'] );
			$article_text = prepare_message( trim($article_text), $html_on, $bbcode_on, $smilies_on, $bbcode_uid );
			$article_text = bbencode_first_pass( $article_text, $bbcode_uid );

			/*
			$article_title = ( !empty( $HTTP_POST_VARS['article_name'] ) ) ? htmlspecialchars( trim ( $HTTP_POST_VARS['article_name'] ) ) : '';
			$article_description = ( !empty( $HTTP_POST_VARS['article_desc'] ) ) ? htmlspecialchars( trim ( $HTTP_POST_VARS['article_desc'] ) ) : '';
			$article_text = ( !empty( $HTTP_POST_VARS['message'] ) ) ? $HTTP_POST_VARS['message'] : '';
			
			//
			// Check message
			//
			if ( !empty( $article_text ) )
			{
				if ( empty( $bbcode_uid ) )
				{
					$bbcode_uid = ( $bbcode_on ) ? make_bbcode_uid() : '';
				}
				$article_text = prepare_message( trim( $article_text ), $html_on, $bbcode_on, $smilies_on, $bbcode_uid );
			}
			*/
			
			$date = time();
			$author_id = $userdata['user_id'] > 0 ? intval( $userdata['user_id'] ) : '-1';
			$type_id = $mx_request_vars->request('type_id', MX_TYPE_INT, '');
			
			$username = $HTTP_POST_VARS['username'];
			
			//
			// Check username
			//
			if (!empty($username))
			{
				$username = phpbb_clean_username($username);
		
				if (!$userdata['session_logged_in'] || ($userdata['session_logged_in'] && $username != $userdata['username']))
				{
					include($phpbb_root_path . 'includes/functions_validate.'.$phpEx);
		
					$result = validate_username($username);
					if ($result['error'])
					{
						$error_msg = (!empty($error_msg)) ? '<br />' . $result['error_msg'] : $result['error_msg'];
						
						mx_message_die(GENERAL_MESSAGE, $error_msg );
					}
				}
				else
				{
					$username = '';
				}
			}
			
			switch ( $kb_post_mode ) 
			{
				case 'edit': // UPDATE Article -------------------------------------------
				
					if ( !($this->auth_user[$category_id]['auth_edit'] || $this->auth_user[$category_id]['auth_mod']) )
					{
						$message = $lang['No_edit'] . '<br /><br />' . sprintf( $lang['Click_return_kb'], '<a href="' . append_sid( this_kb_mxurl() ) . '">', '</a>' ) . '<br /><br />' . sprintf( $lang['Click_return_index'], '<a href="' . append_sid( $mx_root_path . "index.$phpEx" ) . '">', '</a>' );
						mx_message_die( GENERAL_MESSAGE, $message );
					}
					
					//
					// Approve
					//
					if ( $this->auth_user[$category_id]['auth_mod'] || $this->auth_user[$category_id]['auth_approval_edit'] ) // approval auth
					{
						$approve = 1; // approved
					}
					else 
					{
						$approve = 2; // edited unapproved
					}	

					$sql = "UPDATE " . KB_ARTICLES_TABLE . "
							SET article_category_id = '$category_id', 
							article_title = '" . str_replace( "\'", "''", $article_title ) . "',
							article_description = '" . str_replace( "\'", "''", $article_description ) . "',
							article_body = '" . str_replace( "\'", "''", $article_text ) . "',
					        article_type = '" . $approve . "',
					        article_date = '" . $date . "',
					        approved = '" . $type_id . "',
					        bbcode_uid = '" . $bbcode_uid . "'
					    WHERE article_id = ". $article_id;
										
					if ( !( $results = $db->sql_query( $sql ) ) )
					{
						mx_message_die( GENERAL_ERROR, "Could not edit article", '', __LINE__, __FILE__, $sql );
					}
					
					$this->modified( true );		
									
					break;
				
				case 'add': // ADD NEW ---------------------------------------------------------------------------------
		
					if ( !($this->auth_user[$category_id]['auth_post'] || $this->auth_user[$category_id]['auth_mod']) )
					{
						$message = $lang['No_add'] . '<br /><br />' . sprintf( $lang['Click_return_kb'], '<a href="' . append_sid( this_kb_mxurl() ) . '">', '</a>' ) . '<br /><br />' . sprintf( $lang['Click_return_index'], '<a href="' . append_sid( $mx_root_path . "index.$phpEx" ) . '">', '</a>' );
						mx_message_die( GENERAL_MESSAGE, $message );
					}
						
					if ( $this->auth_user[$category_id]['auth_approval'] || $this->auth_user[$category_id]['auth_mod'] )
					{
						$approve = 1; // approved
					}
					else
					{
						$approve = 0; // unapproved
					}		
					
					$sql = "INSERT INTO " . KB_ARTICLES_TABLE . " ( article_category_id , article_title , article_description , article_date , article_author_id , username , bbcode_uid , article_body , article_type , approved, views ) 
				   	VALUES ( '$category_id', '" . str_replace( "\'", "''", $article_title ) . "', '" . str_replace( "\'", "''", $article_description ) . "', '$date', '$author_id', '$username', '$bbcode_uid', '" . str_replace( "\'", "''", $article_text ) . "', '$type_id', '$approve', '0')";
				
					if ( !( $results = $db->sql_query( $sql ) ) )
					{
						mx_message_die( GENERAL_ERROR, "Could not submit aritcle", '', __LINE__, __FILE__, $sql );
					}
					
					//
					// Get new article id
					//
					$sql = "SELECT MAX(article_id) AS new_id FROM " . KB_ARTICLES_TABLE;
					if( !($result = $db->sql_query($sql)) )
					{
						mx_message_die(GENERAL_ERROR, "Couldn't find max article_id", "", __LINE__, __FILE__, $sql);
					}
					$temp_row = $db->sql_fetchrow($result);
					$article_id = $temp_row['new_id'];					
					
					$this->modified( true );
					
					break;
			}
		
			//
			// Update custom fields
			//
			$mx_kb_custom_field->file_update_data( $article_id );

			$this->_kb();

			//
			// Notification
			//
			if ( $kb_config['notify'] > 0 )
			{
				//
				// Instatiate notification
				//
				$mx_kb_notification = new mx_kb_notification();
				$mx_notification_mode = $kb_config['notify'] == 1 ? MX_PM_MODE : MX_MAIL_MODE;
				$mx_kb_notification->init( $mx_notification_mode, $article_id );

				//
				// Now send notification
				//		
				$mx_notification_action = $kb_post_mode == 'add' ? MX_NEW_NOTIFICATION : MX_EDITED_NOTIFICATION;
				$mx_kb_notification->notify( $mx_notification_mode, $mx_notification_action );
				
				if ( $kb_config['notify_group'] > 0 )
				{
					$mx_kb_notification->notify( $mx_notification_mode, $mx_notification_action, - intval($kb_config['notify_group']) );
				}	
			}
			
			/*
			//
			// Autocomment
			//		
			if ( $kb_config['autogenerate_comments'] )
			{
				$title = ( !empty( $_POST['subject'] ) ) ? htmlspecialchars( trim ( $_POST['subject'] ) ) : '';
	
				$comment_bbcode_uid = make_bbcode_uid();
				$comments_text = str_replace( '<br />', "\n", $_POST['message'] );
				$comments_text = prepare_message( trim($comments_text), $html_on, $bbcode_on, $smilies_on, $comment_bbcode_uid );
				$comments_text = bbencode_first_pass( $comments_text, $comment_bbcode_uid );
				
				if ( $length > $kb_config['max_comment_chars'] )
				{
					mx_message_die( GENERAL_ERROR, 'Your comment is too long!<br/>The maximum length allowed in characters is ' . $kb_config['max_comment_chars'] . '' );
				}
	
				if ( $mx_request_vars->is_request('cid') )
				{
					if ( $this->comments[$article_data['article_category_id']]['internal_comments'] )
					{
						$sql = "UPDATE " . KB_COMMENTS_TABLE . "
							SET comments_text = '" . str_replace( "\'", "''", $comments_text ) . "', 
						          comments_title = '" . str_replace( "\'", "''", $title ) . "', 
						          comment_bbcode_uid = '" . $comment_bbcode_uid . "'
						    WHERE comments_id = " . $mx_request_vars->request('cid', MX_TYPE_INT, 0) . "
								AND article_id = ". $item_id;						
					}		
					else 
					{		
						include( $module_root_path . 'kb/includes/functions_comment.' . $phpEx );
						$mx_kb_comments = new mx_kb_comments();
						$mx_kb_comments->init( $item_id );	
										
						$mx_kb_comments->post( 'update', $cid, $title, $comments_text, $userdata['user_id'], $userdata['username'], 0, '', '', $comment_bbcode_uid);
					}
		
				}
				else 
				{
					if ( $this->comments[$article_data['article_category_id']]['internal_comments'] )
					{
						$time = time();
						$poster_id = intval( $userdata['user_id'] );				
						$sql = "INSERT INTO " . KB_COMMENTS_TABLE . "(article_id, comments_text, comments_title, comments_time, comment_bbcode_uid, poster_id) 
							VALUES('$item_id','" . str_replace( "\'", "''", $comments_text ) . "','" . str_replace( "\'", "''", $title ) . "','$time', '$comment_bbcode_uid','$poster_id')";
					}
					else 
					{	
						include( $module_root_path . 'kb/includes/functions_comment.' . $phpEx );
						$mx_kb_comments = new mx_kb_comments();
						$mx_kb_comments->init( $item_id );	
											
						$mx_kb_comments->post( 'insert', '', $title, $comments_text, $userdata['user_id'], $userdata['username'], 0, '', '', $comment_bbcode_uid);
					}
	
				}
			}
			*/
					
			/*		
			$kb_comment = array();
		
			// Populate the kb_comment variable
			$kb_comment = kb_get_data($kb_row, $userdata, $kb_post_mode);
		
			// Compose post header
			$subject = $lang['KB_comment_prefix'] . $kb_comment['article_title'];
			$message_temp = kb_compose_comment( $kb_comment );
				
			$kb_message = $message_temp['message'];
			$kb_update_message = $message_temp['update_message'];
					
			// Insert phpBB post if using kb commenting
			if ( $approve == 1 && $kb_config['use_comments'] && $this->auth_user[$category_id]['auth_comment'])
			{
				$topic_data = kb_insert_post( $kb_message, $subject, $kb_comment['category_forum_id'], $kb_comment['article_editor_id'], $kb_comment['article_editor'], $kb_comment['article_editor_sig'], $kb_comment['topic_id'], $kb_update_message );
				
				$sql = "UPDATE " . KB_ARTICLES_TABLE . " SET topic_id = " . $topic_data['topic_id'] . " 
			 	 WHERE article_id = " . $kb_comment['article_id'];
		
				if ( !( $result = $db->sql_query( $sql ) ) )
				{
					mx_message_die( GENERAL_ERROR, "Could not update article data", '', __LINE__, __FILE__, $sql );
				}
			}

			$kb_notify_info = $kb_post_mode == 'add' ? 'new' : 'edited';
			kb_notify( $kb_config['notify'], $kb_message, $kb_config['admin_id'], $kb_comment['article_editor_id'], $kb_notify_info );
			*/
			
			if ( $approve == 1 )
			{
		     	$message = $lang['Article_submitted'] . '<br /><br />' . sprintf( $lang['Click_return_kb'], '<a href="' . append_sid( this_kb_mxurl() ) . '">', '</a>' ) . '<br /><br />' . sprintf($lang['Click_return_article'], '<a href="' . append_sid(this_kb_mxurl("mode=article&amp;k=" . $article_id)). '">', '</a>') . '<br /><br />' . sprintf( $lang['Click_return_index'], '<a href="' . append_sid( $mx_root_path . "index.$phpEx" ) . '">', '</a>' );
			}
			else
			{
				$message = $lang['Article_submitted_Approve'] . '<br /><br />' . sprintf( $lang['Click_return_kb'], '<a href="' . append_sid( this_kb_mxurl() ) . '">', '</a>' ) . '<br /><br />' . sprintf( $lang['Click_return_index'], '<a href="' . append_sid( $mx_root_path . "index.$phpEx" ) . '">', '</a>' );
			}
			mx_message_die( GENERAL_MESSAGE, $message );
		}

		// ---------------------------------------------------------------------------------------------------------- MAIN FORM
		// ----------------------------------------------------------------------------------------------------------
		// ----------------------------------------------------------------------------------------------------------
		
		//
		// Instatiate text tools
		//
		$mx_kb_text_tools = new mx_kb_text_tools();
				
		//
		// PreText HIDE/SHOW
		//
		if ( $kb_config['show_pretext'] )
		{ 
			// Pull Header/Body info.
			$pt_header = $kb_config['pt_header'];
			$pt_body = $kb_config['pt_body'];
			
			$template->set_filenames( array( 'pretext' => 'kb_post_pretext.tpl' ) );
			
			$template->assign_vars( array( 
				'PRETEXT_HEADER' => $pt_header,
				'PRETEXT_BODY' => $pt_body ) );
			
			$template->assign_var_from_handle( 'KB_PRETEXT_BOX', 'pretext' );
		} 

		//
		// Security
		//
		if ( !$this->auth_user[$category_id]['auth_mod'] )
		{
			if ( $kb_post_mode == 'edit' && !$this->auth_user[$category_id]['auth_edit'] )
			{
				$message = $lang['No_edit'] . '<br /><br />' . sprintf( $lang['Click_return_kb'], '<a href="' . append_sid( this_kb_mxurl() ) . '">', '</a>' ) . '<br /><br />' . sprintf( $lang['Click_return_index'], '<a href="' . append_sid( $mx_root_path . "index.$phpEx" ) . '">', '</a>' );
				mx_message_die( GENERAL_MESSAGE, $message );
			}
			
			if ( $kb_post_mode == 'add' && ( !$this->auth_user[$category_id]['auth_post'] || $kb_config['enable_module'] == 0 ) )
			{
				$message = $lang['No_add'] . '<br /><br />' . sprintf( $lang['Click_return_kb'], '<a href="' . append_sid( this_kb_mxurl() ) . '">', '</a>' ) . '<br /><br />' . sprintf( $lang['Click_return_index'], '<a href="' . append_sid( $mx_root_path . "index.$phpEx" ) . '">', '</a>' );
				mx_message_die( GENERAL_MESSAGE, $message );
			}	
		} 
		
		//
		// First (re)declare basic variables
		//
		if ( $kb_post_mode == 'edit' )
		{
			$sql = "SELECT *
				 FROM " . KB_ARTICLES_TABLE . "
				 WHERE article_id = '" . $article_id . "'";
			
			if ( !( $result = $db->sql_query( $sql ) ) )
			{
				mx_message_die( GENERAL_ERROR, "Could not obtain article data", '', __LINE__, __FILE__, $sql );
			}
		
			$kb_row = $db->sql_fetchrow( $result );
		}
			
		$kb_title = ( isset( $HTTP_POST_VARS['article_name'] ) ) ? htmlspecialchars( trim( stripslashes( $HTTP_POST_VARS['article_name'] ) ) ) : $kb_row['article_title'];
		$kb_desc = ( isset( $HTTP_POST_VARS['article_desc'] ) ) ? htmlspecialchars( trim( stripslashes( $HTTP_POST_VARS['article_desc'] ) ) ): $kb_row['article_description'];
		$kb_text = ( isset( $HTTP_POST_VARS['message'] ) ) ? htmlspecialchars( trim( stripslashes( $HTTP_POST_VARS['message'] ) ) ) : $kb_row['article_body'];
		
		$type_id = ( isset( $HTTP_POST_VARS['type_id'] ) ) ? htmlspecialchars( trim( stripslashes( $HTTP_POST_VARS['type_id'] ) ) ) : $kb_row['article_type'];
		$bbcode_uid = ( isset( $HTTP_POST_VARS['bbcode_uid'] ) ) ? htmlspecialchars( trim( stripslashes( $HTTP_POST_VARS['bbcode_uid'] ) ) ) : $kb_row['bbcode_uid'];
		$username = ( isset( $HTTP_POST_VARS['username'] ) ) ? htmlspecialchars( trim( stripslashes( $HTTP_POST_VARS['username'] ) ) ) : $kb_row['username'];
		
		if ( $preview )
		{
			$preview_title = $kb_title;
			$preview_desc = $kb_desc;
			$preview_text = $kb_text;
			
			$orig_word = array();
			$replacement_word = array();
			obtain_word_list( $orig_word, $replacement_word );
		
			$bbcode_uid = ( $bbcode_on ) ? make_bbcode_uid() : '';
			$preview_text = stripslashes(prepare_message(addslashes(unprepare_message($preview_text)), $html_on, $bbcode_on, $smilies_on, $bbcode_uid)); 

			// Now we run comment suite before checking for smilies
			// so admins can add them in messages if they like
			// and so smilies are not counted as images in sigs.
			// this is done here again incase above conditions are
			// not met.
			if (!$kb_config['allow_images'] || !$kb_config['allow_links'])
			{
				$preview_text = $mx_kb_text_tools->remove_images_links( $preview_text, $kb_config['allow_images'], $kb_config['no_image_message'], $kb_config['allow_links'], $kb_config['no_link_message'] );
			}
						
			if ( $bbcode_on )
			{
				$preview_text = bbencode_second_pass( $preview_text, $bbcode_uid );
			}
		
			if ( count( $orig_word ) )
			{
				$preview_title = preg_replace( $orig_word, $replacement_word, $preview_title );
				$preview_desc = preg_replace( $orig_word, $replacement_word, $preview_desc );
				$preview_text = preg_replace( $orig_word, $replacement_word, $preview_text );
			}
		
			if ( $smilies_on  )
			{
				$preview_text = mx_smilies_pass( $preview_text );
			}
		
			$preview_text = make_clickable( $preview_text );
			
			$preview_text = str_replace( "\n", '<br />', $preview_text );
		
			$template->set_filenames( array( 'preview' => 'kb_post_preview.tpl' ) );
		
			$template->assign_vars( array( 
				'L_PREVIEW' => $lang['Preview'],
				'ARTICLE_TITLE' => $preview_title,
				'ARTICLE_DESC' => $preview_desc,
				'ARTICLE_BODY' => $preview_text,
				'PREVIEW_MESSAGE' => $preview_text ) 
			);
			
			$template->assign_var_from_handle( 'KB_PREVIEW_BOX', 'preview' );
		} 
		
		//
		// show article form - MAIN
		//
		if ( $kb_post_mode == 'edit' )
		{
			$s_hidden_vars = '<input type="hidden" name="k" value="' . $article_id . '"><input type="hidden" name="bbcode_uid" value="' . $bbcode_uid . '"><input type="hidden" name="author_id" value="' . $author_id . '">';
		}
		else 
		{
			$s_hidden_vars = '<input type="hidden" name="cat" value="' . $category_id . '">';
		}
		
		if ( $bbcode_uid != '' )
		{
			$kb_text = preg_replace('/\:(([a-z0-9]:)?)' . $bbcode_uid . '/s', '', $kb_text);
		}
		
		$kb_text = str_replace('<', '&lt;', $kb_text);
		$kb_text = str_replace('>', '&gt;', $kb_text);
		$kb_text = str_replace('<br />', "\n", $kb_text);
			
		//
		// Toggle selection
		//	
		$html_status = ( $html_on ) ? $lang['HTML_is_ON'] : $lang['HTML_is_OFF'];
		$bbcode_status = ( $bbcode_on ) ? $lang['BBCode_is_ON'] : $lang['BBCode_is_OFF'];
		$links_status = ( $links_on ) ? $lang['Links_are_ON'] : $lang['Links_are_OFF'];
		$images_status = ( $images_on ) ? $lang['Images_are_ON'] : $lang['Images_are_OFF'];

		//
		// Smilies toggle selection
		//	
		if ( $smilies_on )
		{
			$smilies_status = $lang['Smilies_are_ON'];
			
			mx_generate_smilies( 'inline', PAGE_POSTING ); 	
		}
		else
		{
			$smilies_status = $lang['Smilies_are_OFF'];
		} 
			
		//
		// set up page
		//
		$template->set_filenames( array( 'body' => 'kb_post_body.tpl' ) );
		
		if ( !$userdata['session_logged_in'] )
		{
			$template->assign_block_vars( 'switch_name', array() );
		}
		
		$kb_action_url = $kb_post_mode == 'add' ? this_kb_mxurl( 'mode=add' ) : this_kb_mxurl( 'mode=edit' );
		$custom_data = $kb_post_mode == 'add' ? $mx_kb_custom_field->display_edit() : $mx_kb_custom_field->display_edit( $article_id );
		
		if ( $custom_data )
		{
			$template->assign_block_vars( 'custom_data_fields', array(
				'L_ADDTIONAL_FIELD' => $lang['Addtional_field'] 
			));
		}
		
		$template->assign_vars( array( 
				'S_ACTION' => $kb_action_url,
				'S_HIDDEN_FIELDS' => $s_hidden_vars,
		
				'ARTICLE_TITLE' => $kb_title,
				'ARTICLE_DESC' => $kb_desc,
				'ARTICLE_BODY' => $kb_text,
				'USERNAME' => $username,
				
				'L_ADD_ARTICLE' => $lang['Add_article'],
				
				'L_ARTICLE_TITLE' => $lang['Article_title'],
				'L_ARTICLE_DESCRIPTION' => $lang['Article_description'],
				'L_ARTICLE_TEXT' => $lang['Article_text'],
				'L_ARTICLE_CATEGORY' => $lang['Category'],
				'L_ARTICLE_TYPE' => $lang['Article_type'],
				'L_SUBMIT' => $lang['Submit'],
				'L_PREVIEW' => $lang['Preview'],
				'L_SELECT_TYPE' => $lang['Select'],
				'L_NAME' => $lang['Username'],
		
				'HTML_STATUS' => $html_status,
				'BBCODE_STATUS' => sprintf( $bbcode_status, '<a href="' . append_sid( "faq.$phpEx?mode=bbcode" ) . '" target="_phpbbcode">', '</a>' ),
				'SMILIES_STATUS' => $smilies_status,
				'LINKS_STATUS' => $links_status,
				'IMAGES_STATUS' => $images_status,
				
				'L_BBCODE_B_HELP' => $lang['bbcode_b_help'],
				'L_BBCODE_I_HELP' => $lang['bbcode_i_help'],
				'L_BBCODE_U_HELP' => $lang['bbcode_u_help'],
				'L_BBCODE_Q_HELP' => $lang['bbcode_q_help'],
				'L_BBCODE_C_HELP' => $lang['bbcode_c_help'],
				'L_BBCODE_L_HELP' => $lang['bbcode_l_help'],
				'L_BBCODE_O_HELP' => $lang['bbcode_o_help'],
				'L_BBCODE_P_HELP' => $lang['bbcode_p_help'],
				'L_BBCODE_W_HELP' => $lang['bbcode_w_help'],
				'L_BBCODE_A_HELP' => $lang['bbcode_a_help'],
				'L_BBCODE_S_HELP' => $lang['bbcode_s_help'],
				'L_BBCODE_F_HELP' => $lang['bbcode_f_help'],
				
				'L_EMPTY_MESSAGE' => $lang['Empty_message'],
				'L_EMPTY_ARTICLE_NAME' => $lang['Empty_article_name'],
				'L_EMPTY_ARTICLE_DESC' => $lang['Empty_article_desc'],
				'L_EMPTY_CAT' => $lang['Empty_category'],
				'L_EMPTY_TYPE' => $lang['Empty_type'],
		
				'L_FONT_COLOR' => $lang['Font_color'],
				'L_COLOR_DEFAULT' => $lang['color_default'],
				'L_COLOR_DARK_RED' => $lang['color_dark_red'],
				'L_COLOR_RED' => $lang['color_red'],
				'L_COLOR_ORANGE' => $lang['color_orange'],
				'L_COLOR_BROWN' => $lang['color_brown'],
				'L_COLOR_YELLOW' => $lang['color_yellow'],
				'L_COLOR_GREEN' => $lang['color_green'],
				'L_COLOR_OLIVE' => $lang['color_olive'],
				'L_COLOR_CYAN' => $lang['color_cyan'],
				'L_COLOR_BLUE' => $lang['color_blue'],
				'L_COLOR_DARK_BLUE' => $lang['color_dark_blue'],
				'L_COLOR_INDIGO' => $lang['color_indigo'],
				'L_COLOR_VIOLET' => $lang['color_violet'],
				'L_COLOR_WHITE' => $lang['color_white'],
				'L_COLOR_BLACK' => $lang['color_black'],
		
				'L_FONT_SIZE' => $lang['Font_size'],
				'L_FONT_TINY' => $lang['font_tiny'],
				'L_FONT_SMALL' => $lang['font_small'],
				'L_FONT_NORMAL' => $lang['font_normal'],
				'L_FONT_LARGE' => $lang['font_large'],
				'L_FONT_HUGE' => $lang['font_huge'],
		
				'L_PAGES' => $lang['L_Pages'],
				'L_PAGES_EXPLAIN' => $lang['L_Pages_explain'],
				
				'L_TOC' => $lang['L_Toc'],
				'L_TOC_EXPLAIN' => $lang['L_Toc_explain'],
				'L_ABSTRACT' => $lang['L_Abstract'],
				'L_ABSTRACT_EXPLAIN' => $lang['L_Abstract_explain'],
				'L_TITLE_FORMAT' => $lang['L_Title_Format'],
				'L_TITLE_FORMAT_EXPLAIN' => $lang['L_Title_Format_explain'],
				'L_SUBTITLE_FORMAT' => $lang['L_Subtitle_Format'],
				'L_SUBTITLE_FORMAT_EXPLAIN' => $lang['L_Subtitle_Format_explain'],
				'L_SUBSUBTITLE_FORMAT' => $lang['L_Subsubtitle_Format'],
				'L_SUBSUBTITLE_FORMAT_EXPLAIN' => $lang['L_Subsubtitle_Format_explain'],
		
				'L_OPTIONS' => $lang['L_Options'],
				'L_FORMATTING' => $lang['L_Formatting'],
		
				'L_BBCODE_CLOSE_TAGS' => $lang['Close_Tags'],
				'L_STYLES_TIP' => $lang['Styles_tip']
				
		) );
		
		$this->get_kb_type_list( $type_id );
		
		if ( $kb_post_mode == 'edit' )
		{
			$template->assign_block_vars( 'switch_edit', array(
				'CAT_LIST' => $this->generate_jumpbox( 'auth_edit', $category_id, $category_id, true )
			));
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