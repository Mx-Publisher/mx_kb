<?php
/**
*
* @package MX-Publisher Module - mx_kb
* @version $Id: functions_comment.php,v 1.15 2008/07/10 22:53:53 jonohlsson Exp $
* @copyright (c) 2002-2006 [wGEric, Jon Ohlsson] mxBB Project Team
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
class mx_kb_comments extends mx_comments
{
	/**
	 * Init Comment vars.
	 *
	 * @param unknown_type $item_data
	 * @param unknown_type $comments_type
	 */
	function init( $item_data, $comments_type = 'internal' )
	{
		global $mx_kb, $kb_config, $db, $images;

		if ( !is_object($mx_kb) || empty($kb_config) )
		{
			mx_message_die(GENERAL_ERROR, 'Bad global arguments');
		}

		if (!is_array($item_data) && !empty($item_data))
		{
			$sql = 'SELECT *
				FROM ' . KB_ARTICLES_TABLE . "
				WHERE article_id = $item_data";

			if ( !( $result = $db->sql_query( $sql ) ) )
			{
				mx_message_die( GENERAL_ERROR, 'Couldn\'t get article info', '', __LINE__, __FILE__, $sql );
			}

			$item_data = $db->sql_fetchrow( $result );
		}

		$this->comments_type = $comments_type == 'internal' ? 'internal' : 'phpbb';
		$this->cat_id = $item_data['article_category_id'];
		$this->item_id = $item_data['article_id'];

		$this->topic_id = $item_data['topic_id'];

		$this->item_table = KB_ARTICLES_TABLE;
		$this->comments_table = KB_COMMENTS_TABLE;
		$this->table_field_id = 'article_id';

		//
		// Auth
		//
		$this->forum_id = $mx_kb->modules[$mx_kb->module_name]->comments[$this->cat_id]['comments_forum_id'];

		$this->auth['auth_view'] = $mx_kb->modules[$mx_kb->module_name]->auth_user[$this->cat_id]['auth_view_comment'];
		$this->auth['auth_post'] = $mx_kb->modules[$mx_kb->module_name]->auth_user[$this->cat_id]['auth_post_comment'];
		$this->auth['auth_edit'] = $mx_kb->modules[$mx_kb->module_name]->auth_user[$this->cat_id]['auth_edit_comment'];
		$this->auth['auth_delete'] = $mx_kb->modules[$mx_kb->module_name]->auth_user[$this->cat_id]['auth_delete_comment'];
		$this->auth['auth_mod'] = $mx_kb->modules[$mx_kb->module_name]->auth_user[$this->cat_id]['auth_mod'];

		//
		// Pagination
		//
		$this->pagination_action = 'mode=article';
		$this->pagination_target = 'k=';

		$this->pagination_num = empty($show_num_comments) ? $this->pagination_num : $show_num_comments;
		$this->u_pagination = $mx_kb->this_mxurl( $this->pagination_action . "&" . $this->pagination_target . $this->item_id  );

		//
		// Configs
		//
		$this->allow_wysiwyg = $kb_config['allow_wysiwyg'];

		$this->allow_comment_wysiwyg = $kb_config['allow_comment_wysiwyg'];
		$this->allow_comment_bbcode = $kb_config['allow_comment_bbcode'];
		$this->allow_comment_html = $kb_config['allow_comment_html'];
	 	$this->allow_comment_smilies = $kb_config['allow_comment_smilies'];
	 	$this->allow_comment_links = $kb_config['allow_comment_links'];
	 	$this->allow_comment_images = $kb_config['allow_comment_images'];

	 	$this->no_comment_image_message = $kb_config['no_comment_image_message'];
	 	$this->no_comment_link_message = $kb_config['no_comment_link_message'];

		$this->max_comment_subject_chars = $kb_config['max_comment_subject_chars'];
		$this->max_comment_chars = $kb_config['max_comment_chars'];

		$this->formatting_comment_truncate_links = $kb_config['formatting_comment_truncate_links'];
		$this->formatting_comment_image_resize = $kb_config['formatting_comment_image_resize'];
		$this->formatting_comment_wordwrap = $kb_config['formatting_comment_wordwrap'];

		//
		// Define comments images
		//
		$this->images = array(
			'icon_minipost' => $images['kb_icon_minipost'],
			//'comment_post' => $images['kb_comment_post'],
			'comment_post' => 'kb_comment_post', // Button
			//'icon_edit' => $images['kb_icon_edit'],
			'icon_edit' => 'kb_icon_edit', // Button
			//'icon_delpost' => $images['kb_icon_delpost'],
			'icon_delpost' => 'kb_icon_delpost' // Button
		);

		$this->u_post = $mx_kb->this_mxurl( 'mode=post_comment&item_id=' . $this->item_id . '&cat_id=' . $this->cat_id);
		$this->u_edit = $mx_kb->this_mxurl( 'mode=post_comment&item_id=' . $this->item_id . '&cat_id=' . $this->cat_id );
		$this->u_delete = $mx_kb->this_mxurl( "mode=post_comment&delete=do&item_id=".$this->item_id . '&cat_id=' . $this->cat_id );
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $ranks
	 */
	function obtain_ranks( &$ranks )
	{
		global $db, $mx_kb_cache;

		if (PORTAL_BACKEND != 'internal')
		{
			if ( $mx_kb_cache->exists( 'ranks' ) )
			{
				$ranks = $mx_kb_cache->get( 'ranks' );
			}
			else
			{
				$sql = "SELECT *
					FROM " . RANKS_TABLE . "
					ORDER BY rank_special, rank_min";

				if ( !( $result = $db->sql_query( $sql ) ) )
				{
					mx_message_die( GENERAL_ERROR, "Could not obtain ranks information.", '', __LINE__, __FILE__, $sql );
				}

				$ranks = array();
				while ( $row = $db->sql_fetchrow( $result ) )
				{
					$ranks[] = $row;
				}

				$db->sql_freeresult( $result );
				$mx_kb_cache->put( 'ranks', $ranks );
			}
		}
	}
}
?>