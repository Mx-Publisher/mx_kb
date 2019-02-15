<?php
/**
*
* @package MX-Publisher Module - mx_kb
* @version $Id: kb_post_comment.php,v 1.18 2008/09/14 00:29:47 orynider Exp $
* @copyright (c) 2002-2006 [Jon Ohlsson, Mohd Basri, wGEric, PHP Arena, pafileDB, CRLin] MX-Publisher Project Team
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
class mx_kb_post_comment extends mx_kb_public
{
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $action
	 */
	function main( $action )
	{
		global $template, $mx_kb_functions, $lang, $board_config, $phpEx, $kb_config, $db, $images, $userdata;
		global $html_entities_match, $html_entities_replace, $unhtml_specialchars_match, $unhtml_specialchars_replace;
		global $mx_root_path, $module_root_path, $phpbb_root_path, $is_block, $mx_request_vars;
		global $mx_block, $theme, $mx_bbcode;

		//
		// Go full page
		//
		$mx_block->full_page = true;

		//
		// Request vars
		//
		$cid = $mx_request_vars->request('cid', MX_TYPE_INT, 0);

		if ( $mx_request_vars->is_request('item_id') && $mx_request_vars->is_request('cat_id') )
		{
			$item_id = $mx_request_vars->request('item_id', MX_TYPE_INT, 0);
			$cat_id = $mx_request_vars->request('cat_id', MX_TYPE_INT, 0);
		}
		else
		{
			mx_message_die( GENERAL_MESSAGE, $lang['File_not_exist'] );
		}

		$delete = $mx_request_vars->request('delete', MX_TYPE_NO_TAGS, '');
		$submit = $mx_request_vars->is_request('submit');
		$preview = $mx_request_vars->is_request('preview');

		$sql = "SELECT *
			FROM " . KB_ARTICLES_TABLE . "
			WHERE article_id = '" . $item_id . "'";

		if ( !( $result = $db->sql_query( $sql ) ) )
		{
			mx_message_die( GENERAL_ERROR, 'Couldnt select download', '', __LINE__, __FILE__, $sql );
		}

		if ( !$article_data = $db->sql_fetchrow( $result ) )
		{
			mx_message_die( GENERAL_MESSAGE, $lang['Article_not_exsist'] );
		}

		$db->sql_freeresult( $result );

		if ( ( !$this->auth_user[$article_data['article_category_id']]['auth_post_comment'] ) )
		{
			if ( !$userdata['session_logged_in'] )
			{
				// mx_redirect(mx_append_sid($mx_root_path . "login.$phpEx?redirect=".$this->this_mxurl("action=post_comment&item_id=" . $item_id), true));
			}

			$message = $lang['Sorry_auth_comment'];
			mx_message_die( GENERAL_MESSAGE, $message );
		}

		if ( $mx_request_vars->is_get('cid') )
		{
			if ( $this->comments[$article_data['article_category_id']]['internal_comments'] )
			{
				//
				// Query internal comment to edit
				//
				$sql = 'SELECT c.*, u.*
					FROM ' . KB_COMMENTS_TABLE . ' AS c
						LEFT JOIN ' . USERS_TABLE . " AS u ON c.poster_id = u.user_id
					WHERE c.article_id = '" . $item_id . "'
					AND c.comments_id = '" . $mx_request_vars->request('cid', MX_TYPE_INT, '') . "'";

				$comment_arg_title = 'comments_title';
				$comment_arg_message = 'comments_text';
				$comment_arg_bbcode_uid = 'comment_bbcode_uid';
			}
			else
			{
				//
				// Query internal comment to edit
				// Note: cid = post_id
				//
				$sql = "SELECT u.username, u.user_id, u.user_posts, u.user_from, u.user_website, u.user_email, u.user_icq, u.user_aim, u.user_yim, u.user_regdate, u.user_msnm, u.user_viewemail, u.user_rank, u.user_sig, u.user_sig_bbcode_uid, u.user_avatar, u.user_avatar_type, u.user_allowavatar, u.user_allowsmile, p.*,  pt.post_text, pt.post_subject, pt.bbcode_uid
					FROM " . POSTS_TABLE . " p, " . USERS_TABLE . " u, " . POSTS_TEXT_TABLE . " pt
					WHERE pt.post_id = p.post_id
						AND u.user_id = p.poster_id
						AND p.post_id = '" . $mx_request_vars->request('cid', MX_TYPE_INT, '') . "'";

				$comment_arg_title = 'post_subject';
				$comment_arg_message = 'post_text';
				$comment_arg_bbcode_uid = 'bbcode_uid';
			}

			if ( !( $result = $db->sql_query( $sql ) ) )
			{
				mx_message_die( GENERAL_ERROR, 'Couldnt select comments', '', __LINE__, __FILE__, $sql );
			}

			$comment_row = $db->sql_fetchrow( $result );
		}

		$comment_title = $preview || isset($_POST['subject']) ? $_POST['subject'] : $comment_row[$comment_arg_title];
		$comment_body = $preview || isset($_POST['message']) ? $_POST['message'] : $comment_row[$comment_arg_message];
		$bbcode_uid = $preview ? '' : $comment_row[$comment_arg_bbcode_uid];

		//
		// wysiwyg
		//
		if ( $kb_config['allow_comment_wysiwyg'] && file_exists( $mx_root_path . $kb_config['wysiwyg_path'] . 'tinymce/jscripts/tiny_mce/tiny_mce.js' ))
		{
			//
			// Toggles
			//
			$allow_wysiwyg = true;
			$bbcode_on = false;
			$html_on = true;
			$smilies_on = false;
			$links_on = false;
			$images_on = false;

			$langcode = mx_get_langcode();

			if ($mx_block->auth_mod)
         	{
				$template->assign_block_vars( "tinyMCE_admin", array(
					'PATH' => $mx_root_path,
					'LANG' => !empty($langcode) ? $langcode : $_SERVER['HTTP_ACCEPT_LANGUAGE'],
					'TEMPLATE' => $mx_root_path . 'templates/'. $theme['template_name'] . '/' . $theme['head_stylesheet']
				));
         	}
         	else
         	{
				$template->assign_block_vars( "tinyMCE", array(
					'PATH' => $mx_root_path,
					'LANG' => !empty($langcode) ? $langcode : $_SERVER['HTTP_ACCEPT_LANGUAGE'],
					'TEMPLATE' => $mx_root_path . 'templates/'. $theme['template_name'] . '/' . $theme['head_stylesheet']
				));
         	}
		}
		else
		{
			//
			// Toggles
			//
			$allow_wysiwyg = false;
			$html_on = ( $kb_config['allow_comment_html'] ) ? true : 0;
			$bbcode_on = ( $kb_config['allow_comment_bbcode'] ) ? true : 0;
			$smilies_on = ( $kb_config['allow_comment_smilies'] ) ? true : 0;
			$links_on = ( $kb_config['allow_comment_links'] ) ? true : 0;
			$images_on = ( $kb_config['allow_comment_images'] ) ? true : 0;

			$board_config['allow_html_tags'] = $kb_config['allowed_comment_html_tags'];

			if ($smilies_on)
			{
				$mx_bbcode->generate_smilies( 'inline', PAGE_POSTING );
			}
		}

		//
		// Instantiate the mx_text and mx_text_formatting classes
		//
		$mx_text = new mx_text();
		$mx_text->init($html_on, $bbcode_on, $smilies_on);

		$mx_text_formatting = new mx_text_formatting();
		//
		// Allow all html tags
		// Fix: Setting 'emtpy' enables all
		//
		$mx_text->allow_all_html_tags = $allow_wysiwyg;

		// =======================================================
		// Delete
		// =======================================================
		if ( $delete == 'do' )
		{
			$sql = 'SELECT *
				FROM ' . KB_ARTICLES_TABLE . "
				WHERE article_id = $item_id";

			if ( !( $result = $db->sql_query( $sql ) ) )
			{
				mx_message_die( GENERAL_ERROR, 'Couldn\'t get article info', '', __LINE__, __FILE__, $sql );
			}
			$article_info = $db->sql_fetchrow( $result );

			if ( ( $this->auth_user[$article_info['article_category_id']]['auth_delete_comment'] && $article_info['article_author_id'] == $userdata['user_id'] ) || $this->auth_user[$article_info['article_category_id']]['auth_mod'] )
			{
				if ( $this->comments[$article_data['article_category_id']]['internal_comments'] )
				{
					$sql = 'DELETE FROM ' . KB_COMMENTS_TABLE . "
					WHERE comments_id = $cid";

					if ( !( $db->sql_query( $sql ) ) )
					{
						mx_message_die( GENERAL_ERROR, 'Couldnt delete comment', '', __LINE__, __FILE__, $sql );
					}
				}
				else
				{
					include( $module_root_path . 'kb/includes/functions_comment.' . $phpEx );
					$mx_kb_comments = new mx_kb_comments();
					$mx_kb_comments->init( $article_info, 'phpbb' );
					$mx_kb_comments->post('delete', $cid);
				}

				$this->_kb();
				$message = $lang['Comment_deleted'] . '<br /><br />' . sprintf( $lang['Click_return_article'], '<a href="' . mx_append_sid( $this->this_mxurl( "mode=article&k=$item_id" ) ) . '">', '</a>' );
				mx_message_die( GENERAL_MESSAGE, $message );
			}
			else
			{
				$message = sprintf( $lang['Sorry_auth_delete'], $this->auth_user[$cat_id]['auth_upload_type'] );
				mx_message_die( GENERAL_MESSAGE, $message );
			}
		}

		// =======================================================
		// Submit
		// =======================================================
		if ( $submit )
		{
			$this->update_add_comment($article_data, $item_id, $cid, '', '', $html_on, $bbcode_on, $smilies_on, $allow_wysiwyg);

			$message = $lang['Comment_posted'] . '<br /><br />' . sprintf( $lang['Click_return_article'], '<a href="' . mx_append_sid( $this->this_mxurl( 'mode=article&k=' . $item_id ) ) . '">', '</a>' );
			mx_message_die( GENERAL_MESSAGE, $message );
		}

		// =======================================================
		// Main
		// =======================================================

		$html_status = ( $html_on ) ? $lang['HTML_is_ON'] : $lang['HTML_is_OFF'];
		$bbcode_status = ( $bbcode_on ) ? $lang['BBCode_is_ON'] : $lang['BBCode_is_OFF'];
		$smilies_status = ( $smilies_on ) ? $lang['Smilies_are_ON'] : $lang['Smilies_are_OFF'];
		$links_status = ( $links_on ) ? $lang['Links_are_ON'] : $lang['Links_are_OFF'];
		$images_status = ( $images_on ) ? $lang['Images_are_ON'] : $lang['Images_are_OFF'];

		if ( $preview )
		{
			//
			// Encode for preview
			//
			$preview_title = $mx_text->encode_preview_simple($comment_title);
			$preview_text = $mx_text->encode_preview($comment_body);

			if (!$kb_config['allow_images'] || !$kb_config['allow_links'])
			{
				$preview_text = $mx_text_formatting->remove_images_links( $preview_text, $kb_config['allow_images'], $kb_config['no_image_message'], $kb_config['allow_links'], $kb_config['no_link_message'] );
			}

			$template->assign_block_vars( 'preview', array() );

			$template->assign_vars( array(
				'L_PREVIEW' => $lang['Preview'],
				'PREVIEW' => true,
				'SUBJECT' => $preview_title,
				'PRE_COMMENT' => $preview_text )
			);

			//
			// Decode for form editing
			//
			$comment_title = $mx_text->decode_simple($comment_title, true);
			$comment_body = $mx_text->decode($comment_body, '', true);
		}
		else
		{
			//
			// Decode for form editing
			//
			$comment_title = $mx_text->decode_simple($comment_title);
			$comment_body = $mx_text->decode($comment_body, $bbcode_uid);

		}

		if ( $mx_request_vars->is_request('cid') )
		{
			$hidden_form_fields = '<input type="hidden" name="mode" value="post_comment">
						<input type="hidden" name="cat_id" value="' . $cat_id . '">
						<input type="hidden" name="item_id" value="' . $item_id . '">
						<input type="hidden" name="cid" value="' . $mx_request_vars->request('cid', MX_TYPE_INT, '') . '">
						<input type="hidden" name="comment" value="post">';
		}
		else
		{
			//
			// New comment
			//
			$comment_title = '';
			$comment_body = '';

			$hidden_form_fields = '<input type="hidden" name="mode" value="post_comment">
						<input type="hidden" name="cat_id" value="' . $cat_id . '">
						<input type="hidden" name="item_id" value="' . $item_id . '">
						<input type="hidden" name="comment" value="post">';
		}

		$template->set_filenames( array( 'body' => 'kb_comment_posting.tpl' ) );

		//
		// Output the data to the template
		//
		$template->assign_vars( array(
			'HTML_STATUS' => $html_status,
			'BBCODE_STATUS' => sprintf($bbcode_status, '<a href="' . PHPBB_URL . mx_append_sid("faq.$phpEx?mode=bbcode") . '" target="_phpbbcode">', '</a>'),
			'SMILIES_STATUS' => $smilies_status,
			'LINKS_STATUS' => $links_status,
			'IMAGES_STATUS' => $images_status,
			'FILE_NAME' => $article_data['file_name'],
			'DOWNLOAD' => $kb_config['module_name'],
			'MESSAGE_LENGTH' => $kb_config['max_comment_chars'],

			'TITLE' => $comment_title,
			'COMMENT' => $comment_body,

			'L_COMMENT_ADD' => $lang['Comment_add'],
			'L_COMMENT' => $lang['Message_body'],
			'L_COMMENT_TITLE' => $lang['Subject'],
			'L_OPTIONS' => $lang['Options'],
			'L_COMMENT_EXPLAIN' => sprintf( $lang['Comment_explain'], $kb_config['max_comment_chars'] ),
			'L_PREVIEW' => $lang['Preview'],
			'L_SUBMIT' => $lang['Submit'],
			'L_DOWNLOAD' => $lang['Download'],

			'L_INDEX' => "<<",
			'L_CHECK_MSG_LENGTH' => $lang['Check_message_length'],
			'L_MSG_LENGTH_1' => $lang['Msg_length_1'],
			'L_MSG_LENGTH_2' => $lang['Msg_length_2'],
			'L_MSG_LENGTH_3' => $lang['Msg_length_3'],
			'L_MSG_LENGTH_4' => $lang['Msg_length_4'],
			'L_MSG_LENGTH_5' => $lang['Msg_length_5'],
			'L_MSG_LENGTH_6' => $lang['Msg_length_6'],

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
			'L_STYLES_TIP' => $lang['Styles_tip'],

			'U_INDEX' => mx_append_sid( $mx_root_path . 'index.' . $phpEx ),
			'U_DOWNLOAD_HOME' => mx_append_sid( $this->this_mxurl() ),
			'U_FILE_NAME' => mx_append_sid( $this->this_mxurl( 'mode=article&item_id=' . $item_id ) ),
			'S_POST_ACTION' => mx_append_sid( $this->this_mxurl() ),
			'S_HIDDEN_FORM_FIELDS' => $hidden_form_fields )
		);

		if ( $bbcode_on )
		{
			$template->assign_block_vars( 'switch_bbcodes', array());
		}

		// ===================================================
		// assign var for navigation
		// ===================================================
		$this->generate_navigation( $article_data['article_category_id'] );
	}
}
?>