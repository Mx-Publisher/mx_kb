<?php
/***************************************************************************
 *                                 kb_add.php
 *                            -------------------
 *   begin                : Sunday, Mar 31, 2003
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   $Id: kb_add.php,v 1.5 2004/01/04 15:56:56 jonohlsson Exp $
 *
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

// MX
if ( !defined('IN_PORTAL') )
{
	die("Hacking attempt");
}

  $category_id = ( isset($HTTP_GET_VARS['cat']) ) ? $HTTP_GET_VARS['cat'] : $HTTP_POST_VARS['cat'];

  //show article form
	if ( !$HTTP_POST_VARS['article_submit'] || $HTTP_POST_VARS['preview'] )
	{
	   $page_title = $lang['Add_article'];
	    if ( !$is_block )
		 {
		   include($mx_root_path . 'includes/page_header.'.$phpEx);
		 }	
	   make_jumpbox($phpbb_root_path .'viewforum.'.$phpEx,'');

	   //
	   // HTML toggle selection
	   //
	   if ( $board_config['allow_html'] )
	   {
	   	  $html_status = $lang['HTML_is_ON'];
	   }
	   else
	   {
	   	  $html_status = $lang['HTML_is_OFF'];
	   }

	   //
	   // BBCode toggle selection
	   //
	   if ( $board_config['allow_bbcode'] )
	   {
	      $bbcode_status = $lang['BBCode_is_ON'];
	   }
	   else
	   {
	   	  $bbcode_status = $lang['BBCode_is_OFF'];
           }

	   //
	   // Smilies toggle selection
	   //
	   if ( $board_config['allow_smilies'] )
	   {
	   	  $smilies_status = $lang['Smilies_are_ON'];
	   }
	   else
	   {
	   	   $smilies_status = $lang['Smilies_are_OFF'];
	   }
	   
	   // Generate smilies listing for page output
	   mx_generate_smilies('inline', PAGE_POSTING);
	   
	   //load header
	   include ($module_root_path ."includes/kb_header.".$phpEx);
	
	   if ( !$is_admin )
	   {
	       if ( !$userdata['session_logged_in'] && $kb_config['allow_anon'] != ALLOW_ANON )
	   	   {
		       $message = $lang['No_add'] . '<br /><br />' . sprintf($lang['Click_return_kb'], '<a href="' . append_sid(this_kb_mxurl()) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid($mx_root_path . "index.$phpEx") . '">', '</a>');

	    	   message_die(GENERAL_MESSAGE, $message);
		   }
	   }
	
	   //set up page
	   $template->set_filenames(array(
		  'body' => 'kb_add_body.tpl')
	   );
	   
	   if ( !$userdata['session_logged_in'] && $kb_config['allow_anon'] == ALLOW_ANON )
	   {
	       $template->assign_block_vars('switch_name',array());
	   }
	
	   $template->assign_vars(array(
		  'L_ADD_ARTICLE' => $lang['Add_article'],
		  'L_ARTICLE_TITLE' => $lang['Article_title'],
		  'L_ARTICLE_DESCRIPTION' => $lang['Article_description'],
		  'L_ARTICLE_TEXT' => $lang['Article_text'],
		  'L_ARTICLE_TYPE' => $lang['Article_type'],
		  'L_SUBMIT' => $lang['Submit'],
		  'L_PREVIEW' => $lang['Preview'],
		  'L_SELECT' => $lang['Select'],		  
		  'L_NAME' => $lang['Username'],
		  
		  'S_ACTION' => this_kb_mxurl('mode=add'),
		  'HTML_STATUS' => $html_status,
		  'BBCODE_STATUS' => sprintf($bbcode_status, '<a href="' . append_sid("faq.$phpEx?mode=bbcode") . '" target="_phpbbcode">', '</a>'), 
		  'SMILIES_STATUS' => $smilies_status,
		  'S_HIDDEN_FIELDS' => '<input type="hidden" name="cat" value="' . $category_id . '">',
		
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

		  'L_BBCODE_CLOSE_TAGS' => $lang['Close_Tags'], 
		  'L_STYLES_TIP' => $lang['Styles_tip'])
	   );
	   get_kb_type_list($type_id);	
	}
	
	//BEGIN - PreText HIDE/SHOW
	if ( $kb_config['show_pretext'] ) 
	{
		// Pull Header/Body info.		
       	$pt_header = $kb_config['pt_header'];		
		$pt_body = $kb_config['pt_body'];		
		$template->set_filenames(array('pretext' => 'kb_add_pretext.tpl'));
		$template->assign_vars(array(
			'PRETEXT_HEADER' => $pt_header,
			'PRETEXT_BODY' => $pt_body ));
		$template->assign_var_from_handle('KB_PRETEXT_BOX', 'pretext');
	}
	//END - PreText HIDE/SHOW
	
	if( $HTTP_POST_VARS['preview'] )
	{
		$orig_word = array();
		$replacement_word = array();
		obtain_word_list($orig_word, $replacement_word);

		$message = $HTTP_POST_VARS['message'];
		
		$bbcode_uid = make_bbcode_uid();

		$preview_message = stripslashes(prepare_message($message, $html_on, $bbcode_on, $smilies_on, $bbcode_uid)); 

		$message = stripslashes($message);

		if ( $row['bbcode_uid'] != '' )
		{
			$message = preg_replace('/\:(([a-z0-9]:)?)' . $row['bbcode_uid'] . '/s', '', $message);
		}

		$preview_message = bbencode_first_pass($preview_message, $bbcode_uid);
		
		$preview_message = bbencode_second_pass($preview_message, $bbcode_uid);

		$preview_message = make_clickable($preview_message);

		if( $smilies_on )
		{
			$preview_message = mx_smilies_pass($preview_message);
		}

		$preview_message = str_replace("\n", '<br />', $preview_message);

		$template->set_filenames(array(
			'preview' => 'kb_add_preview.tpl')
		);

		$template->assign_vars(array(
			'ARTICLE_TITLE' => $HTTP_POST_VARS['article_name'],
			'ARTICLE_DESC' => $HTTP_POST_VARS['article_desc'],
			'ARTICLE_BODY' => $message,
			'USERNAME' => $HTTP_POST_VARS['username'],
			
			'PREVIEW_MESSAGE' => $preview_message)
		);
		$template->assign_var_from_handle('KB_PREVIEW_BOX', 'preview');
	}
	
	
	//post article
	if ( $HTTP_POST_VARS['article_submit'] )
	{
	
	   $page_title = $lang['Add_article'];
	    if ( !$is_block )
		 {
		   include($mx_root_path . 'includes/page_header.'.$phpEx);
		 }
	   make_jumpbox($phpbb_root_path .'viewforum.'.$phpEx,'');
	   
	   //load header
	   include ($module_root_path ."includes/kb_header.".$phpEx);
	   
	   if ( !$HTTP_POST_VARS['article_name'] || !$HTTP_POST_VARS['article_desc'] || !$HTTP_POST_VARS['message'] )
	   {
	   	  echo "<br /><br /><center>Please fill out all parts of the form.  <a href=".this_kb_mxurl('mode=add').">Click Here</a> to go back to the form.</center>";
		  exit;
	   }
	   $article_text = $HTTP_POST_VARS['message'];
	   
	   $bbcode_uid = make_bbcode_uid();
	   $error_msg = '';	      
	   $article_text = bbencode_first_pass($article_text, $bbcode_uid);
     	$category = $category_id;
	   $title = $HTTP_POST_VARS['article_name'];
	   $description = $HTTP_POST_VARS['article_desc'];
	   $date = time();
	   $author_id = $userdata['user_id'];	   
	   $type = $HTTP_POST_VARS['type_id'];
	   $username = $HTTP_POST_VARS['username'];
	   
	   if ( ( !$kb_config['approve_new'] ) || ( $is_admin ) || ( $userdata['user_level'] == MOD ) )
	   {
	   	  $approve = 1;
		  update_kb_number($category, '+ 1');
	   }
	   else
	   {
	   	  $approve = 2;	   
	   }	   

//	   $sql = "SELECT MAX(article_id) AS total
//			FROM " . KB_ARTICLES_TABLE;
//	   if ( !($result = $db->sql_query($sql)) )
//	   {
//		  message_die(GENERAL_ERROR, 'Could not obtain next article id', '', __LINE__, __FILE__, $sql);
//		}
//
//		if ( !($id = $db->sql_fetchrow($result)) )
//		{
//				message_die(GENERAL_ERROR, 'Could not obtain next article id', '', __LINE__, __FILE__, $sql);
//		}
//		$article_id = $id['total'] + 1;
	   
	   $sql = "INSERT INTO " . KB_ARTICLES_TABLE . " ( article_category_id , article_title , article_description , article_date , article_author_id , username , bbcode_uid , article_body , article_type , approved, views ) 
	   VALUES ( '$category', '$title', '$description', '$date', '$author_id', '$username', '$bbcode_uid', '$article_text', '$type', '$approve', '0')";   

	   if ( !($results = $db->sql_query($sql)) )
	   {
	       message_die(GENERAL_ERROR, "Could not submit aritcle", '', __LINE__, __FILE__, $sql);
	   }

	   if (  !$approve || $approve == 2)
	   {	   
	        email_kb_admin($kb_config['notify']);
			
	   }
	   
	   if ( $approve == 1 && $kb_config['comments'] )
	   {		  
		  $sql = "SELECT * FROM " . KB_ARTICLES_TABLE . " WHERE article_date = '" . $date . "'";
	   	  if ( !($results = $db->sql_query($sql)) )
	   	  {
	       	 message_die(GENERAL_ERROR, "Could not get aritcle id", '', __LINE__, __FILE__, $sql);
	   	  }
	   	  $row = $db->sql_fetchrow($results);
		  
		  $article_id = $row['article_id'];
		  
		  // choose a user
//		  $user_id = $kb_config['admin_id'];
		  $user_id = $userdata['user_id'];

		  // initialise the userdata
		  $sql = "SELECT * FROM " . USERS_TABLE . " WHERE user_id = $user_id";
		  if ( !($result = $db->sql_query($sql)) )
		  {
	  	      message_die(CRITICAL_ERROR, 'Could not obtain lastvisit data from user table', '', __LINE__, __FILE__, $sql);
		  }
		  $user = $db->sql_fetchrow($result);
		  init_userprefs($user);
	
		  $kb_cat = get_kb_cat($row['article_category_id']);
		  $type = get_kb_type($row['article_type']);
		  $author = get_kb_author($row['article_author_id']);
	
		  $temp_url = PORTAL_URL . ( $is_block ? "index.$phpEx?page=$page&mode=" : "modules/mx_kb/kb.$phpEx?mode=" ) . "article&k=".$article_id;
		  $message = "[b]" . $lang['Category'] . ":[/b] "  . addslashes($kb_cat['category_name']) . "\n";
		  $message .= "[b]" . $lang['Article_type'] . ":[/b] " . $type . "\n\n";
		  $message .= "[b]" . $lang['Article_title'] . ":[/b] " . addslashes($row['article_title']) . "\n";
		  $message .= "[b]" . $lang['Author'] . ":[/b] " . $author . "\n";
		  $message .= "[b]" . $lang['Article_description'] . ":[/b] " . addslashes($row['article_description']) . "\n\n";
		  $message .= "[b][url=" . $temp_url . "]" . $lang['Read_full_article'] . "[/url][/b]";
		  // fix for 0.76
		  $message = addslashes($message);
	
		  $subject = '[ KB ] ' . addslashes($row['article_title']);
		  $forum_id = $kb_config['forum_id'];
	
		  $topic_data = insert_post($message, $subject, $forum_id, $user['user_id'], $user['username'], $user['user_attachsig']);
		  
		  $sql = "UPDATE " . KB_ARTICLES_TABLE .
		     " SET topic_id = " . $topic_data['topic_id'] . " 
		 	 WHERE article_id = " . $article_id;
		 
		  if ( !($result = $db->sql_query($sql)) )
		  {
   	   	  	  message_die(GENERAL_ERROR, "Could not update article data", '', __LINE__, __FILE__, $sql);
	      }
	   }
	   if ($approve == 1)
	   {
	       add_kb_words($article_id, $row['article_body']);
		   $message = $lang['Article_submitted'] . '<br /><br />' . sprintf($lang['Click_return_kb'], '<a href="' . append_sid(this_kb_mxurl()) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid($mx_root_path . "index.$phpEx") . '">', '</a>');
	   }
  	   	else
	   	{
	   	$message = $lang['Article_submitted_Approve'] . '<br /><br />' . sprintf($lang['Click_return_kb'], '<a href="' . append_sid(this_kb_mxurl()) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid($mx_root_path . "index.$phpEx") . '">', '</a>');
		}
		
	   message_die(GENERAL_MESSAGE, $message);	   
	}	
?>