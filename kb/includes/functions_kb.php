<?php
/**
*
* @package MX-Publisher Module - mx_kb
* @version $Id: functions_kb.php,v 1.26 2008/06/03 20:10:19 jonohlsson Exp $
* @copyright (c) 2002-2006 [wGEric, Jon Ohlsson] MX-Publisher Project Team
* @license http://opensource.org/licenses/gpl-license.php GNU General Public License v2
*
*/

if ( !defined( 'IN_PORTAL' ) )
{
	die( "Hacking attempt" );
}

/**
 * mx_kb class.
 *
 */
class mx_kb extends mx_kb_auth
{
	var $cat_rowset = array();
	var $subcat_rowset = array();
	var $total_cat = 0;

	var $comments = array();
	var $ratings = array();
	var $information = array();
	var $notification = array();

	var $modified = false;
	var $error = array();

	var $page_title = '';
	var $jumpbox = '';
	var $auth_can_list = '';
	var $navigation = '';

	var $debug = false; // Toggle debug output on/off
	var $debug_msg = array();

	//
	// mx_kb specific
	//
	var $sort_method = '';
	var $sort_method_extra = '';
	var $sort_order = '';

	var $reader_mode = false;
	/**
	 * Prepare data.
	 *
	 */
	function __construct()
	{
		global $db, $userdata, $mx_request_vars, $debug, $kb_config, $mx_root_path, $module_root_path, $phpEx;

		$this->debug('mx_kb->__construct', basename( __FILE__ ));
		$this->db = $db;
		$this->request = $mx_request_vars;
		$this->mx_root_path = $mx_root_path;
		$this->module_root_path = $module_root_path;
		$this->php_ext = $phpEx;
		
	}
	
	/**
	 * Prepare data.
	 *
	 */
	function init()
	{
		global $db, $userdata, $mx_request_vars, $debug, $kb_config, $mx_root_path, $module_root_path, $phpEx;

		$this->debug('mx_kb->init', basename( __FILE__ ));
		
		unset( $this->cat_rowset );
		unset( $this->subcat_rowset );
		unset( $this->comments );
		unset( $this->ratings );
		unset( $this->information );
		unset( $this->notification );

		$sql = 'SELECT *
			FROM ' . KB_CATEGORIES_TABLE . '
			ORDER BY cat_order ASC';

		if ( !( $result = $db->sql_query( $sql ) ) )
		{
			mx_message_die( GENERAL_ERROR, 'Couldnt Query categories info', '', __LINE__, __FILE__, $sql );
		}
		$cat_rowset = $db->sql_fetchrowset( $result );

		$db->sql_freeresult( $result );

		$this->auth( KB_AUTH_ALL, AUTH_LIST_ALL, $userdata, $cat_rowset );

		for( $i = 0; $i < count( $cat_rowset ); $i++ )
		{
			if ( $this->auth_user[$cat_rowset[$i]['category_id']]['auth_view'] )
			{
				$this->cat_rowset[$cat_rowset[$i]['category_id']] = $cat_rowset[$i];
				$this->subcat_rowset[$cat_rowset[$i]['parent']][$cat_rowset[$i]['category_id']] = $cat_rowset[$i];
				$this->total_cat++;

				//
				// Comments
				// Note: some settings are category dependent, but may use default config settings
				//
				$this->comments[$cat_rowset[$i]['category_id']]['activated'] = $cat_rowset[$i]['cat_allow_comments'] == -1 ? ($kb_config['use_comments'] == 1 ? true : false ) : ( $cat_rowset[$i]['cat_allow_comments'] == 1 ? true : false );

				switch(PORTAL_BACKEND)
				{
					case 'internal':
						$this->comments[$cat_rowset[$i]['category_id']]['internal_comments'] = true; // phpBB or internal comments
						$this->comments[$cat_rowset[$i]['category_id']]['autogenerate_comments'] = false; // autocreate comments when updated
						$this->comments[$cat_rowset[$i]['category_id']]['comments_forum_id'] = 0; // phpBB target forum (only used for phpBB comments)
					break;

					default:
						$this->comments[$cat_rowset[$i]['category_id']]['internal_comments'] = $cat_rowset[$i]['internal_comments'] == -1 ? ($kb_config['internal_comments'] == 1 ? true : false ) : ( $cat_rowset[$i]['internal_comments'] == 1 ? true : false ); // phpBB or internal comments
						$this->comments[$cat_rowset[$i]['category_id']]['autogenerate_comments'] = $cat_rowset[$i]['autogenerate_comments'] == -1 ? ($kb_config['autogenerate_comments'] == 1 ? true : false ) : ( $cat_rowset[$i]['autogenerate_comments'] == 1 ? true : false ); // autocreate comments when updated
						$this->comments[$cat_rowset[$i]['category_id']]['comments_forum_id'] = $cat_rowset[$i]['comments_forum_id'] < 1 ? ( intval($kb_config['comments_forum_id']) ) : ( intval($cat_rowset[$i]['comments_forum_id']) ); // phpBB target forum (only used for phpBB comments)
					break;
				}

				if ($this->comments[$cat_rowset[$i]['category_id']]['activated'] && !$this->comments[$cat_rowset[$i]['category_id']]['internal_comments'] && intval($this->comments[$cat_rowset[$i]['category_id']]['comments_forum_id']) < 1)
				{
					$this->comments[$cat_rowset[$i]['cat_id']]['internal_comments'] = true; // autocreate comments when updated
				}
				
				if ($this->comments[$cat_rowset[$i]['cat_id']]['activated'] && !$this->comments[$cat_rowset[$i]['cat_id']]['internal_comments'] && intval($this->comments[$cat_rowset[$i]['cat_id']]['comments_forum_id']) < 1)
				{
				 	mx_message_die(GENERAL_ERROR, 'Init Failure, phpBB comments with no target forum_id :(<br> Category: ' . $cat_rowset[$i]['category_name'] . ' Forum_id: ' . $this->comments[$cat_rowset[$i]['category_id']]['comments_forum_id']);
				}

				//
				// Ratings
				//
				$this->ratings[$cat_rowset[$i]['category_id']]['activated'] = $cat_rowset[$i]['cat_allow_ratings'] == -1 ? ($kb_config['use_ratings'] == 1 ? true : false ) : ( $cat_rowset[$i]['cat_allow_ratings'] == 1 ? true : false );

				//
				// Information
				//
				$this->information[$cat_rowset[$i]['category_id']]['activated'] = $cat_rowset[$i]['show_pretext'] == -1 ? ($kb_config['show_pretext'] == 1 ? true : false ) : ( $cat_rowset[$i]['show_pretext'] == 1 ? true : false ); // phpBB or internal ratings

				//
				// Notification
				//
				$this->notification[$cat_rowset[$i]['category_id']]['activated'] = $cat_rowset[$i]['notify'] == -1 ? (intval($kb_config['notify'])) : ( intval($cat_rowset[$i]['notify']) ); // -1, 0, 1, 2
				$this->notification[$cat_rowset[$i]['category_id']]['notify_group'] = $cat_rowset[$i]['notify_group'] == -1 || $cat_rowset[$i]['notify_group'] == 0 ? (intval($kb_config['notify_group'])) : ( intval($cat_rowset[$i]['notify_group']) ); // Group_id
			}
		}

		$this->sort_order = $kb_config['sort_order'];

		switch ( $kb_config['sort_method'] )
		{
			case 'Id':
				$this->sort_method = 't.article_id';
				$this->sort_method_extra = 't.article_type' . " DESC, " ;
			break;
			case 'Latest':
				$this->sort_method = 't.article_date';
				$this->sort_method_extra = 't.article_type' . " DESC, " ;
			break;
			case 'Toprated':
				$this->sort_method = 'rating';
				$this->sort_method_extra = 't.article_type' . " DESC, " ;
			break;
			case 'Most_popular':
				$this->sort_method = 't.views';
				$this->sort_method_extra = 't.article_type' . " DESC, " ;
			break;
			case 'Userrank':
				$this->sort_method = 'u.user_rank';
				$this->sort_method_extra = 't.article_type' . " DESC, " ;
			break;
			case 'Alphabetic':
				$this->sort_method = 't.article_title';
				$this->sort_method_extra = 't.article_type' . " DESC, " ;
			break;
		}

		$this->reader_mode = $kb_config['reader_mode'];
		$this->app_mode = $kb_config['app_mode'];
	}

	/**
	 * Clean up
	 *
	 */
	function _kb()
	{
		$this->debug('mx_kb->_kb', basename( __FILE__ ));

		if ( $this->modified )
		{
			$this->sync_all();
		}
	}

	/**
	 * Add debug message.
	 *
	 * @param unknown_type $debug_msg
	 * @param unknown_type $file
	 * @param unknown_type $line_break
	 */
	function debug($debug_msg, $file = '', $line_break = true)
	{
		if ($this->debug)
		{
			$module_name = !empty($this->module_name) ? $this->module_name . ' :: ' : '';
			$file = !empty($file) ? ' (' . $file . ')' : '';
			$line_break = $line_break ? '<br>' : '';
			$this->debug_msg[] = $line_break . $module_name . $debug_msg . $file ;
		}
	}

	/**
	 * Display debug message.
	 *
	 * @return unknown
	 */
	function display_debug()
	{
		if ($this->debug)
		{
			$debug_message = '';
			foreach ($this->debug_msg as $key => $value)
			{
				$debug_message .= $value;
			}

			return $debug_message;
		}
	}

	/**
	 * Sync All.
	 *
	 */
	function sync_all()
	{
		$this->debug('mx_kb->sync_all', basename( __FILE__ ));

		foreach( $this->cat_rowset as $cat_id => $void )
		{
			$this->sync( $cat_id, false );
		}
		$this->init();
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $cat_id
	 * @param unknown_type $init
	 */
	function sync($cat_id, $init = true)
	{
		global $db;

		$this->debug('mx_kb->sync', basename( __FILE__ ));

		$cat_nav = array();
		$this->category_nav( $this->cat_rowset[$cat_id]['parent'], $cat_nav );

		$sql = 'UPDATE ' . KB_CATEGORIES_TABLE . "
			SET parents_data = ''
			WHERE parent = " . $this->cat_rowset[$cat_id]['parent'];

		if ( !( $db->sql_query( $sql ) ) )
		{
			mx_message_die( GENERAL_ERROR, 'Couldnt Query categories info', '', __LINE__, __FILE__, $sql );
		}

		$sql = 'UPDATE ' . KB_CATEGORIES_TABLE . "
				SET number_articles = '-1',
				cat_last_article_id = '0',
				cat_last_article_name = '',
				cat_last_article_time = '0'
				WHERE category_id = '" . $cat_id . "'";

		if ( !( $db->sql_query( $sql ) ) )
		{
			mx_message_die( GENERAL_ERROR, 'Couldnt Query category info', '', __LINE__, __FILE__, $sql );
		}
		if ( $init )
		{
			$this->init();
		}
		return;
	}
	
	/**
	 * Dummy function
	 */
	function message_die($msg_code, $msg_text = '', $msg_title = '', $err_line = '', $err_file = '', $sql = '')
	{		
		//
		// Get SQL error if we are debugging. Do this as soon as possible to prevent
		// subsequent queries from overwriting the status of sql_error()
		//
		if (DEBUG && ($msg_code == GENERAL_ERROR || $msg_code == CRITICAL_ERROR))
		{
				
			if ( isset($sql) )
			{
				//$sql_error = array(@print_r(@$this->db->sql_error($sql)));				
				$sql_error['message'] = $sql_error['message'] ? $sql_error['message'] : '<br /><br />SQL : ' . $sql; 
				$sql_error['code'] = $sql_error['code'] ? $sql_error['code'] : 0;			
			}
			else
			{
				$sql_error = array(@print_r(@$this->db->sql_error_returned));				
				$sql_error['message'] = $sql_error['message'] ? $sql_error['message'] : '<br /><br />SQL : ' . $sql; 
				$sql_error['code'] = $sql_error['code'] ? $sql_error['code'] : 0;					
			}			
			
			$debug_text = '';

			if ( isset($sql_error['message']) )
			{
				$debug_text .= '<br /><br />SQL Error : ' . $sql_error['code'] . ' ' . $sql_error['message'];
			}

			if ( isset($sql_store) )
			{
				$debug_text .= "<br /><br />$sql_store";
			}

			if ( isset($err_line) && isset($err_file) )
			{
				$debug_text .= '</br /><br />Line : ' . $err_line . '<br />File : ' . $err_file;
			}
		}		
		
		switch($msg_code)
		{
			case GENERAL_MESSAGE:
				if ( $msg_title == '' )
				{
					$msg_title = $this->user->lang('Information');
				}
			break;

			case CRITICAL_MESSAGE:
				if ( $msg_title == '' )
				{
					$msg_title = $this->user->lang('Critical_Information');
				}
			break;

			case GENERAL_ERROR:
				if ( $msg_text == '' )
				{
					$msg_text = $this->user->lang('An_error_occured');
				}

				if ( $msg_title == '' )
				{
					$msg_title = $this->user->lang('General_Error');
				}
			break;

			case CRITICAL_ERROR:

				if ($msg_text == '')
				{
					$msg_text = $this->user->lang('A_critical_error');
				}

				if ($msg_title == '')
				{
					$msg_title = 'phpBB : <b>' . $this->user->lang('Critical_Error') . '</b>';
				}
			break;
		}
		
		//
		// Add on DEBUG info if we've enabled debug mode and this is an error. This
		// prevents debug info being output for general messages should DEBUG be
		// set TRUE by accident (preventing confusion for the end user!)
		//
		if ( DEBUG && ( $msg_code == GENERAL_ERROR || $msg_code == CRITICAL_ERROR ) )
		{
			if ( $debug_text != '' )
			{
				$msg_text = $msg_text . '<br /><br /><b><u>DEBUG MODE</u></b> ' . $debug_text;
			}
		}		
		
		trigger_error($msg_title . ': ' . $msg_text);
	}  
	
	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $parent_id
	 * @param unknown_type $cat_nav
	 */
	function category_nav( $parent_id, $cat_nav )
	{
		if ( !empty( $this->cat_rowset[$parent_id] ) )
		{
			$this->category_nav( $this->cat_rowset[$parent_id]['parent'], $cat_nav );
			$cat_nav[$parent_id] = $this->cat_rowset[$parent_id]['category_name'];
		}
		return;
	}

	/**
	 * No cat?
	 *
	 * @return unknown
	 */
	function cat_empty()
	{
		$this->debug('mx_kb->cat_empty', basename( __FILE__ ));

		return ( $this->total_cat == 0 ) ? true : false;
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $true_false
	 */
	function modified( $true_false = false )
	{
		$this->debug('mx_kb->modified', basename( __FILE__ ));

		$this->modified = $true_false;
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $cat_id
	 * @return unknown
	 */
	function items_in_cat( $cat_id )
	{
		$this->debug('mx_kb->items_in_cat', basename( __FILE__ ));

		if ( $this->cat_rowset[$cat_id]['number_articles'] == -1 || $this->modified )
		{
			global $db;

			$sql = 'SELECT COUNT(article_id) as total
				FROM ' . KB_ARTICLES_TABLE . "
				WHERE approved = '1'
				AND article_category_id IN (" . $this->gen_cat_ids( $cat_id ) . ')
				ORDER BY article_date DESC';

			if ( !( $result = $db->sql_query( $sql ) ) )
			{
				mx_message_die( GENERAL_ERROR, 'Couldnt Query Articles info', '', __LINE__, __FILE__, $sql );
			}

			$number_of_items = 0;
			if ( $row = $db->sql_fetchrow( $result ) )
			{
				$number_of_items = $row['total'];
			}

			$sql = 'UPDATE ' . KB_CATEGORIES_TABLE . "
					SET number_articles = $number_of_items
					WHERE category_id = $cat_id";

			if ( !( $result = $db->sql_query( $sql ) ) )
			{
				mx_message_die( GENERAL_ERROR, 'Couldnt Query Files info', '', __LINE__, __FILE__, $sql );
			}
		}
		else
		{
			$number_of_items = $this->cat_rowset[$cat_id]['number_articles'];
		}

		return $number_of_items;
	}

	/**
	 * Jump menu function.
	 *
	 * @param unknown_type $cat_id to handle parent cat_id
	 * @param unknown_type $depth related to function to generate tree
	 * @param unknown_type $default the cat you wanted to be selected
	 * @param unknown_type $for_file TRUE high category ids will be -1
	 * @param unknown_type $check_upload if true permission for upload will be checked
	 * @return unknown
	 */
	function generate_jumpbox( $cat_id = 0, $depth = 0, $default = '', $for_file = false, $check_upload = true, $auth = 'auth_view' )
	{
		global $page_id;
		//static $cat_rowset = false;

		if ( !is_array( $cat_rowset ) )
		{
			if ( $check_upload )
			{
				if ( !empty( $this->cat_rowset ) )
				{
					foreach( $this->cat_rowset as $row )
					{
						if ( $this->auth_user[$row['category_id']][$auth] )
						{
							$cat_rowset[$row['category_id']] = $row;
						}
					}
				}
			}
			else
			{
				$cat_rowset = $this->cat_rowset;
			}
		}

		$cat_list .= '';

		$pre = str_repeat( '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $depth );

		$temp_cat_rowset = $cat_rowset;

		if ( !empty( $temp_cat_rowset ) )
		{
			foreach ( $temp_cat_rowset as $temp_cat_id => $cat )
			{
				if ( $cat['parent'] == $cat_id )
				{
					if ( is_array( $default ) )
					{
						if ( isset( $default[$cat['category_id']] ) )
						{
							$sel = ' selected="selected"';
						}
						else
						{
							$sel = '';
						}
					}

					$cat_pre = ( !$cat['cat_allow_file'] ) ? '+ ' : '- ';
					$sub_cat_id = ( $for_file ) ? ( ( !$cat['cat_allow_file'] ) ? -1 : $cat['category_id'] ) : $cat['category_id'];
					$cat_class = ( !$cat['cat_allow_file'] ) ? 'class="greyed"' : '';
					$cat_list .= '<option value="' . $sub_cat_id . '"' . $sel . ' ' . $cat_class . ' />' . $pre . $cat_pre . $cat['category_name'] . '</option>';
					$cat_list .= $this->generate_jumpbox( $cat['category_id'], $depth + 1, $default, $for_file, $check_upload );
				}
			}
			$this->jumpbox = $cat_list;
			return $cat_list;
		}
		else
		{
			return;
		}
	}

	/**
	 * Jump menu function.
	 *
	 * @param unknown_type $cat_id to handle parent cat_id
	 * @param unknown_type $depth related to function to generate tree
	 * @param unknown_type $default the cat you wanted to be selected
	 * @param unknown_type $for_file TRUE high category ids will be -1
	 * @param unknown_type $check_upload if true permission for upload will be checked
	 * @return unknown
	 */
	function generate_app_tree( $current_article_id = '', $cat_id = 0, $pre = '', $default = '', $for_file = false, $check_upload = true, $auth = 'auth_view' )
	{
		global $article_path, $page_id;
		//static $cat_rowset = false;

		if ( !is_array( $cat_rowset ) )
		{
			if ( $check_upload )
			{
				if ( !empty( $this->cat_rowset ) )
				{
					foreach( $this->cat_rowset as $row )
					{
						if ( $this->auth_user[$row['category_id']][$auth] )
						{
							$cat_rowset[$row['category_id']] = $row;
						}
					}
				}
			}
			else
			{
				$cat_rowset = $this->cat_rowset;
			}
		}

		$cat_list .= '';

		$temp_cat_rowset = $cat_rowset;

		if ( !empty( $temp_cat_rowset ) )
		{
			foreach ( $temp_cat_rowset as $temp_cat_id => $cat )
			{
				if ( $cat['parent'] == $cat_id )
				{
					if ( is_array( $default ) )
					{
						if ( isset( $default[$cat['category_id']] ) )
						{
							$sel = ' selected="selected"';
						}
						else
						{
							$sel = '';
						}
					}

					$sub_cat_id = ( $for_file ) ? ( ( !$cat['cat_allow_file'] ) ? -1 : $cat['category_id'] ) : $cat['category_id'];
					$cat_list .= sprintf('<div onselectstart="return false" class="folder" id="tree-%s">%s', $pre . $sub_cat_id, $cat['category_name']);
	           		$cat_list .= $this->generate_app_tree( $current_article_id, $cat['category_id'], $pre . $cat['category_id'] . '/', $default, $for_file, $check_upload );

	            	//
	            	// Load items
	            	//
	            	 $cat_list .= $this->list_items($sub_cat_id, $pre, $current_article_id);

	            	 $cat_list .= '</div>';
				}
			}
			//$this->jumpbox = $cat_list;
			return $cat_list;
		}
		else
		{
			return;
		}
	}

	/**
	 * display items.
	 *
	 * @param unknown_type $start
	 * @param unknown_type $cat_id
	 * @param unknown_type $block_name
	 * @param unknown_type $approve
	 */
	function list_items( $cat_id = false, $pre = '', $current_article_id = '', $sql_xtra = '' )
	{
		global $db, $kb_config, $template, $board_config;
		global $images, $lang, $theme, $phpEx, $mx_kb_functions;
		global $phpbb_root_path, $mx_root_path, $module_root_path, $is_block, $phpEx;
		global $article_path, $phpBB2;

		$filelist = false;

		$file_rowset = array();
		$total_file = 0;

		//
		// Category SQL
		//
		if (!$cat_id)
		{
			$cat_where = "AND t.article_category_id IN (" . $this->gen_cat_ids( '0' ) . ")";
		}
		else
		{
			$cat_where = "AND t.article_category_id = $cat_id";
		}

		switch ( SQL_LAYER )
		{
			case 'oracle':
				$sql = "SELECT t.*, t.article_id, r.votes_article, AVG(r.rate_point) AS rating, COUNT(r.votes_article) AS total_votes, u.user_id, u.username, typ.type
					FROM " . KB_ARTICLES_TABLE . " AS t, " . KB_VOTES_TABLE . " AS r, " . USERS_TABLE . " AS u, " . KB_CATEGORIES_TABLE . " AS cat, " . KB_TYPES_TABLE . " AS typ
					WHERE t.article_id = r.votes_article(+)
					AND t.article_type = typ.id(+)
					AND t.article_author_id = u.user_id(+)
					AND t.approved = '1'
					AND t.article_category_id = cat.category_id
					$cat_where
					$sql_xtra
					GROUP BY t.article_id
					ORDER BY " . $this->sort_method_extra . $this->sort_method . " " . $this->sort_order;
				break;

			default:
				$sql = "SELECT t.*, t.article_id, r.votes_article, IF(COUNT(r.rate_point) > 0, AVG(r.rate_point), 0) AS rating, COUNT(r.votes_article) AS total_votes, u.user_id, u.username, typ.type
					FROM " . KB_ARTICLES_TABLE . " AS t
						LEFT JOIN " . KB_VOTES_TABLE . " AS r ON t.article_id = r.votes_article
						LEFT JOIN " . KB_TYPES_TABLE . " AS typ ON t.article_type = typ.id
						LEFT JOIN " . USERS_TABLE . " AS u ON t.article_author_id = u.user_id
						LEFT JOIN " . KB_CATEGORIES_TABLE . " AS cat ON t.article_category_id = cat.category_id
					WHERE t.approved = '1'
					$cat_where
					$sql_xtra
					GROUP BY t.article_id
					ORDER BY " . $this->sort_method_extra . $this->sort_method . " " . $this->sort_order;
				break;
		}

		//if ( !( $result = $mx_kb_functions->sql_query_limit( $sql, $kb_config['pagination'], $start ) ) )
		if (!( $result = $db->sql_query_limit($sql, $kb_config['pagination'], $start)))
		{
			mx_message_die( GENERAL_ERROR, 'Couldn\'t get article info for this category', '', __LINE__, __FILE__, $sql );
		}

		$file_rowset = array();

		while ( $row = $db->sql_fetchrow( $result ) )
		{
			if ( $this->auth_user[$row['article_category_id']]['auth_view'] )
			{
				$file_rowset[] = $row;
			}
		}

		$db->sql_freeresult( $result );

		$filelist = false;
		if (count( $file_rowset ) > 0)
		{
			$filelist = true;
		}

		$item_list = '';
		for ( $i = 0; $i < count( $file_rowset ); $i++ )
		{
			// ===================================================
			// Format the date for the given file
			// ===================================================
			//$article_date = $phpBB2->create_date( $board_config['default_dateformat'], $file_rowset[$i]['article_date'], $board_config['board_timezone'] );

			//
			// If the file is new then put a new image in front of it
			//
			$is_new = FALSE;
			if (time() - ($kb_config['settings_newdays'] * 24 * 60 * 60) < $file_rowset[$i]['article_date'])
			{
				$is_new = TRUE;
			}

			//$cat_name = ( empty( $cat_id ) ) ? $this->cat_rowset[$file_rowset[$i]['file_catid']]['category_name'] : '';
			//$cat_url = mx_append_sid( $this->this_mxurl( 'action=category&cat_id=' . $file_rowset[$i]['article_category_id'] ) );

			$article_description = $file_rowset[$i]['article_description'] ;
			//$article_cat_id = $file_rowset[$i]['article_category_id'];
			$article_approved = $file_rowset[$i]['approved'];

			//
			// type
			//
			$article_type = isset($lang['KB_type_' . $file_rowset[$i]['type']]) ? $lang['KB_type_' . $file_rowset[$i]['type']] : $file_rowset[$i]['type'];

			//
			// author information
			//
			$author = ( $file_rowset[$i]['user_id'] != ANONYMOUS ) ? '<a href="' . mx_append_sid( $phpbb_root_path . ((PORTAL_BACKEND == 'internal') && (PORTAL_BACKEND == 'phpbb2') ? 'profile.' : 'ucp.') . $phpEx . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $file_rowset[$i]['user_id'] ) . '" target=_blank>' : '';
			$author .= ( $file_rowset[$i]['user_id'] != ANONYMOUS ) ? $file_rowset[$i]['username'] : $file_rowset[$i]['post_username'] . '(' . $lang['Guest'] . ')';
			$author .= ( $file_rowset[$i]['user_id'] != ANONYMOUS ) ? '</a>' : '';

			$article_id = $file_rowset[$i]['article_id'];
			$views = $file_rowset[$i]['views'];

			$article_title = $file_rowset[$i]['article_title'];
			$article_url = mx_append_sid( $this->this_mxurl( "mode=article&amp;k=$article_id" ) );

			if ( $is_new )
			{
				//$template->assign_block_vars( "ARTICLELIST.articlerow.is_new_file", array() );
			}

			$item_list .= sprintf('<div class="doc" id="tree-%s">%s</div>', $pre . $cat_id . '/' . $article_id, $article_title);

			// Tweak to define the ajax history url
			if ($article_id == $current_article_id)
			{
				$article_path = $pre . $cat_id . '/' . $article_id;
			}
		}

		if ( $filelist )
		{
			//
		}
		else
		{
			if ($this->cat_rowset[$cat_id]['cat_allow_file'])
			{
				//$template->assign_block_vars( 'no_articles', array(
				//	'L_NO_ARTICLES' => $lang['No_articles'],
				//	'L_NO_ARTICLES_CAT' => $lang['No_Articles']
				//) );
			}
		}

		return $item_list;
	}

	/**
	 * get_sub_cat.
	 *
	 * get all sub category in side certain category
	 * - used when listing files/articles/links etc
	 *
	 * @param unknown_type $cat_id
	 * @return unknown
	 */
	function get_sub_cat( $cat_id )
	{
		global $mx_root_path, $module_root_path, $is_block, $phpEx;

		$cat_sub = '';
		if ( !empty( $this->subcat_rowset[$cat_id] ) )
		{
			$class = "gensmall";
			$init_link_max = ( count( $this->subcat_rowset[$cat_id] ) > 3 ) ? 3 : count( $this->subcat_rowset[$cat_id] );
			$truncate = false;
			$i = 0;
			foreach( $this->subcat_rowset[$cat_id] as $cat_id => $cat_row )
			{
				if ( $this->auth_user[$cat_row['category_id']]['auth_view'] && ( $cat_row['cat_allow_file'] || !empty( $this->subcat_rowset[$cat_row['category_id']] ) ) )
				{
					$i++;
					if ($i > $init_link_max)
					{
						$truncate = true;
						break;
					}
					$cat_sub .= (!empty($cat_sub) ? '<span class=' . $class . '>, </span>' : '') . '<a href="' . mx_append_sid( $this->this_mxurl( 'mode=cat&cat=' . $cat_row['category_id'] ) ) . '" class=' . $class . '>' . $cat_row['category_name'] . '</a>';
				}
				/*
				else
				{
					if ( !empty( $this->subcat_rowset[$cat_row['cat_id']] ) )
					{
						foreach( $this->subcat_rowset[$cat_row['cat_id']] as $sub_cat_id => $sub_cat_row )
						{
							if ( $sub_cat_row['cat_allow_file'] )
							{
								$i++;
								if ($i > $init_link_max)
								{
									$truncate = true;
									break;
								}
								$cat_sub .= (!empty($cat_sub) ? '<span class=' . $class . '>, </span>' : '') . '<a href="' . mx_append_sid( $this->this_mxurl( 'action=category&cat_id=' . $sub_cat_row['cat_id'] ) ) . '" class=' . $class . '>' . $sub_cat_row['cat_name'] . '</a> ';
							}
						}
					}
				}
				*/
			}

			if ($truncate)
			{
				$cat_sub .= '<span class=' . $class . '>, ...</span>';
			}
		}
		return $cat_sub;
	}

	/**
	 * generate_navigation.
	 *
	 * @param unknown_type $cat_id
	 */
	function generate_navigation( $cat_id )
	{
		global $template, $db;

		if ( $this->cat_rowset[$cat_id]['parents_data'] == '' )
		{
			$cat_nav = array();
			$this->category_nav( $this->cat_rowset[$cat_id]['parent'], $cat_nav = array() );

			$sql = 'UPDATE ' . KB_CATEGORIES_TABLE . "
				SET parents_data = '" . addslashes( serialize( $cat_nav ) ) . "'
				WHERE parent = " . $this->cat_rowset[$cat_id]['parent'];

			if ( !( $db->sql_query( $sql ) ) )
			{
				mx_message_die( GENERAL_ERROR, 'Couldnt Query categories info', '', __LINE__, __FILE__, $sql );
			}
		}
		else
		{
			$cat_nav = unserialize( stripslashes( $this->cat_rowset[$cat_id]['parents_data'] ) );
		}

		if ( !empty( $cat_nav ) )
		{
			foreach ( $cat_nav as $parent_cat_id => $parent_name )
			{
				$template->assign_block_vars( 'navlinks', array(
					'CAT_NAME' => $parent_name,
					'U_VIEW_CAT' => mx_append_sid( $this->this_mxurl( 'mode=cat&cat=' . $parent_cat_id ) ) )
				);
			}
		}

		$template->assign_block_vars( 'navlinks', array(
			'CAT_NAME' => $this->cat_rowset[$cat_id]['category_name'],
			'U_VIEW_CAT' => mx_append_sid( $this->this_mxurl( 'mode=cat&cat=' . $this->cat_rowset[$cat_id]['category_id'] ) ) )
		);
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $cat_id
	 * @return unknown
	 */
	function new_item_in_cat( $cat_id )
	{
		global $kb_config, $board_config, $db;

		$cat_array = explode(', ', $this->gen_cat_ids( $cat_id ));

		$files_new = 0;
		$time = time() - ( $kb_config['settings_newdays'] * 24 * 60 * 60 );

		foreach ( $cat_array as $key => $cat_id )
		{
			if ( $this->auth_user[$cat_id]['auth_view'] && $this->cat_rowset[$cat_id]['cat_last_article_time'] > $time)
			{
				$files_new++;
			}
		}

		return $files_new;
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $cat_id
	 * @param unknown_type $file_info
	 */
	function last_item_in_cat($cat_id, $file_info = array())
	{
		if ( ( empty( $this->cat_rowset[$cat_id]['cat_last_article_id'] ) && empty( $this->cat_rowset[$cat_id]['cat_last_article_name'] ) && empty( $this->cat_rowset[$cat_id]['cat_last_article_time'] ) ) || $this->modified )
		{
			global $db;

			$sql = 'SELECT article_date, article_id, article_title, article_category_id
				FROM ' . KB_ARTICLES_TABLE . "
				WHERE approved = '1'
				AND article_category_id IN (" . $this->gen_cat_ids($cat_id) . ")
				ORDER BY article_date DESC";

			if ( !( $result = $db->sql_query($sql) ) )
			{
				mx_message_die( GENERAL_ERROR, 'Couldnt Query links info', '', __LINE__, __FILE__, $sql );
			}

			while ( $row = $db->sql_fetchrow( $result ) )
			{
				$temp_cat[] = $row;
			}

			$file_info = $temp_cat[0];
			if ( !empty( $file_info ) )
			{
				$sql = 'UPDATE ' . KB_CATEGORIES_TABLE . "
					SET cat_last_article_id = " . intval( $file_info['article_id'] ) . ",
					cat_last_article_name = '" . addslashes( $file_info['article_title'] ) . "',
					cat_last_article_time = " . intval( $file_info['article_date'] ) . "
					WHERE category_id = $cat_id";

				if ( !( $db->sql_query( $sql ) ) )
				{
					mx_message_die( GENERAL_ERROR, 'Couldnt Query links info', '', __LINE__, __FILE__, $sql );
				}
			}
		}
		else
		{
			$file_info['article_id'] = $this->cat_rowset[$cat_id]['cat_last_article_id'];
			$file_info['article_title'] = $this->cat_rowset[$cat_id]['cat_last_article_name'];
			$file_info['article_date'] = $this->cat_rowset[$cat_id]['cat_last_article_time'];
		}
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $cat_id
	 * @param unknown_type $cat_ids
	 * @return unknown
	 */
	function gen_cat_ids( $cat_id, $cat_ids = '' )
	{
		$this->debug('mx_kb->gen_cat_ids', basename( __FILE__ ));

		if ( !empty( $this->subcat_rowset[$cat_id] ) )
		{
			foreach( $this->subcat_rowset[$cat_id] as $subcat_id => $cat_row )
			{
				$cat_ids = $this->gen_cat_ids( $subcat_id, $cat_ids );
			}
		}

		if ( !empty( $this->cat_rowset[$cat_id] ) )
		{
			$cat_ids .= ( ( $cat_ids != '' ) ? ', ' : '' ) . $cat_id;
		}
		return $cat_ids;
	}

	/**
	 * display_categories.
	 *
	 * @param unknown_type $parent
	 */
	function display_categories( $parent = 0 )
	{
		global $db, $template, $board_config, $lang, $phpBB2, $phpbb_root_path, $mx_root_path, $module_root_path, $phpEx, $is_block, $page_id, $kb_config, $userdata, $images, $kb_quick_nav;

		$this->debug('mx_kb->display_categories', basename( __FILE__ ));

		if ( $this->cat_empty() )
		{
			mx_message_die( GENERAL_ERROR, 'Either you are not allowed to view any category, or there is no category in the database' );
		}

		//
		// Introduce a style switch for simple/standard category navigation
		//
		$cat_style = $kb_config['use_simple_navigation'] ? 'CAT_NAV_SIMPLE' : 'CAT_NAV_STANDARD';

		$template->assign_block_vars( $cat_style, array() );
		$template->assign_vars( array(
			'L_SUB_CAT' => $lang['Sub_category'],
			'L_CATEGORY' => $lang['Category'],
			'L_CATEGORIES' => $lang['Categories'],
			'L_LAST_ARTICLE' => $lang['Last_article'],
			'L_FILES' => $lang['Files'] )
		);

		//
		// Output the categories
		//
		$num_of_cats = 0;
		if ( isset( $this->subcat_rowset[$parent] ) )
		{
			//
			// Variables needed for the simple nav
			//
			$catnum = count($this->subcat_rowset[$parent]);
			$catcol = $kb_config['cat_col'] > 0 ? $kb_config['cat_col'] : 1;
			$num_of_rows = intval( $catnum / $catcol );

			if ( $catnum % $catcol )
			{
				$num_of_rows++;
			}

			$template->assign_vars( array( 'WIDTH' => 100 / $catcol ) );
			$i = 0;

			foreach( $this->subcat_rowset[$parent] as $category_id => $category )
			{
				//
				// Auth
				//
				if ( $this->auth_user[$category_id]['auth_view'])
				{
					if ( $i == 0 || $i ==  $catcol)
					{
						$template->assign_block_vars( $cat_style.'.catrow', array() );
						$i = 0;
					}
					$i++;

					$category_articles = $this->items_in_cat($category_id);
					$category_details = $category['category_details'];

					$category_name = $category['category_name'];
					$category_url = mx_append_sid( $this->this_mxurl( "mode=cat&cat=$category_id" ) );

					$num_of_cats++;

					$last_article_info = array();
					$this->last_item_in_cat( $category_id, $last_file_info ); // Needed to update cat article stats

					if ( !empty( $last_file_info['article_id'] ) && $this->auth_user[$category_id]['auth_view'] )
					{
						$last_file_time = $phpBB2->create_date( $board_config['default_dateformat'], $last_file_info['article_date'], $board_config['board_timezone'] );
						$last_file = $last_file_time . '<br />';
						$last_file_name = ( strlen( stripslashes( $last_file_info['article_title'] ) ) > 20 ) ? substr( stripslashes( $last_file_info['article_title'] ), 0, 20 ) . '...' : stripslashes( $last_file_info['article_title'] );
						$last_file .= '<a href="' . mx_append_sid( $this->this_mxurl( 'mode=article&k=' . $last_file_info['article_id'] ) ) . '" alt="' . stripslashes( $last_file_info['article_title'] ) . '" title="' . stripslashes( $last_file_info['article_title'] ) . '">' . $last_file_name . '</a> ';
						$last_file .= '<a href="' . mx_append_sid( $this->this_mxurl( 'mode=article&k=' . $last_file_info['article_id'] ) ) . '"><img src="' . $images['kb_icon_latest_reply'] . '" border="0" alt="' . $lang['View_latest_file'] . '" title="' . $lang['View_latest_file'] . '" /></a>';
					}
					else
					{
						$last_file = $lang['No_articles'];
					}

					$is_new = false;
					if ( $this->new_item_in_cat( $category_id ) )
					{
						$is_new = true;
					}

					$sub_cat = $this->get_sub_cat( $category_id );
					$template->assign_block_vars( $cat_style.'.catrow.catcol', array(
						'CATEGORY' => $category_name,
						'U_CATEGORY' => $category_url,
						'CAT_DESCRIPTION' => $category_details,
						'CAT_ARTICLES' => $category_articles,

						'CAT_IMAGE' => $is_new ? $images['kb_category_new'] : $images['kb_category'],
						'SUB_CAT' => ( !empty( $sub_cat ) ) ? "&nbsp;&nbsp;$sub_cat" : "",
						'L_SUB_CAT' => $lang['Sub_categories'],

						'LAST_ARTICLE' => $last_file,
					));

					if (!empty( $sub_cat ))
					{
						$template->assign_block_vars( $cat_style.'.catrow.catcol.show_subs', array());
					}
				}
			}
		}

		if ( $num_of_cats == 0 )
		{
			$template->assign_block_vars( $cat_style.'.no_cats', array(
				'COMMENT' => 'Either you are not allowed to view any category, or you haven\'t selected any KB categories to use in this block. Admin should validate the blockCP settings. ',
			));
		}
	}

	/**
	 * display items.
	 *
	 * @param unknown_type $start
	 * @param unknown_type $cat_id
	 * @param unknown_type $block_name
	 * @param unknown_type $approve
	 */
	function display_items( $start, $cat_id = false, $sort_options_list = false, $sql_xtra = '', $target_page_id = false )
	{
		global $db, $kb_config, $template, $board_config, $mx_block;
		global $images, $lang, $theme, $phpEx, $mx_kb_functions, $phpBB2;
		global $phpbb_root_path, $mx_root_path, $module_root_path, $is_block, $phpEx;

		$filelist = false;

		$file_rowset = array();
		$total_file = 0;

		//
		// Category SQL
		//
		if (!$cat_id)
		{
			$cat_where = "AND t.article_category_id IN (" . $this->gen_cat_ids( '0' ) . ")";
		}
		else if (is_array($cat_id))
		{
			$cat_where = "AND t.article_category_id IN (" . $this->gen_cat_ids( $cat_id['parent'] ) . ")";
			$cat_id = false;
		}
		else
		{
			$cat_where = "AND t.article_category_id = $cat_id";
		}

		switch ( SQL_LAYER )
		{
			case 'oracle':
				$sql = "SELECT t.*, t.article_id, r.votes_article, AVG(r.rate_point) AS rating, COUNT(r.votes_article) AS total_votes, u.user_id, u.username, typ.type
					FROM " . KB_ARTICLES_TABLE . " AS t, " . KB_VOTES_TABLE . " AS r, " . USERS_TABLE . " AS u, " . KB_CATEGORIES_TABLE . " AS cat, " . KB_TYPES_TABLE . " AS typ
					WHERE t.article_id = r.votes_article(+)
					AND t.article_type = typ.id(+)
					AND t.article_author_id = u.user_id(+)
					AND t.approved = '1'
					AND t.article_category_id = cat.category_id
					$cat_where
					$sql_xtra
					GROUP BY t.article_id
					ORDER BY " . $this->sort_method_extra . $this->sort_method . " " . $this->sort_order;
			break;

			default:
				$sql = "SELECT t.*, t.article_id, r.votes_article, IF(COUNT(r.rate_point)>0,AVG(r.rate_point),0) AS rating, COUNT(r.votes_article) AS total_votes, u.user_id, u.username, typ.type
					FROM " . KB_ARTICLES_TABLE . " AS t
						LEFT JOIN " . KB_VOTES_TABLE . " AS r ON t.article_id = r.votes_article
						LEFT JOIN " . KB_TYPES_TABLE . " AS typ ON t.article_type = typ.id
						LEFT JOIN " . USERS_TABLE . " AS u ON t.article_author_id = u.user_id
						LEFT JOIN " . KB_CATEGORIES_TABLE . " AS cat ON t.article_category_id = cat.category_id
					WHERE t.approved = '1'
					$cat_where
					$sql_xtra
					GROUP BY t.article_id
					ORDER BY " . $this->sort_method_extra . $this->sort_method . " " . $this->sort_order;
			break;
		}
		
		//if ( !( $result = $mx_kb_functions->sql_query_limit( $sql, $kb_config['pagination'], $start ) ) )
		if (!($result = $db->sql_query_limit($sql, $kb_config['pagination'], $start)))
		{
			mx_message_die(GENERAL_ERROR, "Couldn't get article info for this category. Were sort_method=" . $this->sort_method . ". Were sort_order=" . $this->sort_order . ".", "", __LINE__, __FILE__, $sql );
		}

		$file_rowset = array();
		$total_file = 0;

		while ( $row = $db->sql_fetchrow( $result ) )
		{
			if ( $this->auth_user[$row['article_category_id']]['auth_view'] )
			{
				$file_rowset[] = $row;
			}
		}

		$db->sql_freeresult( $result );

		$sql = "SELECT COUNT(t.article_id) as total_articles
			FROM " . KB_ARTICLES_TABLE . " AS t
			WHERE t.approved='1'
			$cat_where
			$sql_xtra";

		if ( !( $result = $db->sql_query( $sql ) ) )
		{
			mx_message_die( GENERAL_ERROR, 'Couldn\'t get number of article', '', __LINE__, __FILE__, $sql );
		}

		$row = $db->sql_fetchrow( $result );
		$db->sql_freeresult( $result );

		$total_file = $row['total_articles'];
		unset( $row );

		if (count( $file_rowset ) > 0)
		{
			$template->assign_block_vars( 'ARTICLELIST', array() );

			$filelist = true;
		}

		//
		// Ratings
		//
		$kb_use_ratings = false;
		for ( $i = 0; $i < count( $file_rowset ); $i++ )
		{
			if ( $this->ratings[$file_rowset[$i]['article_category_id']]['activated'] )
			{
				$kb_use_ratings = true;
				break;
			}
		}

		for ( $i = 0; $i < count( $file_rowset ); $i++ )
		{
			// ===================================================
			// Format the date for the given file
			// ===================================================
			$article_date = $phpBB2->create_date( $board_config['default_dateformat'], $file_rowset[$i]['article_date'], $board_config['board_timezone'] );
			// ===================================================
			// Get rating for the file and format it
			// ===================================================
			$rating = ( $file_rowset[$i]['rating'] != 0 ) ? round( $file_rowset[$i]['rating'], 2 ) . '/10' : $lang['Not_rated'];
			//
			// If the file is new then put a new image in front of it
			//
			$is_new = FALSE;
			if (time() - ($kb_config['settings_newdays'] * 24 * 60 * 60) < $file_rowset[$i]['article_date'])
			{
				$is_new = TRUE;
			}

			$cat_name = ( empty( $cat_id ) ) ? $this->cat_rowset[$file_rowset[$i]['file_catid']]['category_name'] : '';
			$cat_url = mx_append_sid( $this->this_mxurl( 'action=category&cat_id=' . $file_rowset[$i]['article_category_id'] ) );

			$article_description = $file_rowset[$i]['article_description'] ;
			$article_cat_id = $file_rowset[$i]['article_category_id'];
			$article_approved = $file_rowset[$i]['approved'];

			//
			// type
			//
			$article_type = isset($lang['KB_type_' . $file_rowset[$i]['type']]) ? $lang['KB_type_' . $file_rowset[$i]['type']] : $file_rowset[$i]['type'];

			//
			// author information
			//
			$author = ( $file_rowset[$i]['user_id'] != ANONYMOUS ) ? '<a href="' . mx_append_sid($phpbb_root_path . ((PORTAL_BACKEND == 'internal') && (PORTAL_BACKEND == 'phpbb2') ? 'profile.' : 'ucp.') . $phpEx . '?mode=viewprofile&' . POST_USERS_URL . '=' . $file_rowset[$i]['user_id'] ) . '" target=_blank>' : '';
			$author .= ( $file_rowset[$i]['user_id'] != ANONYMOUS ) ? $file_rowset[$i]['username'] : $file_rowset[$i]['post_username'] . '(' . $lang['Guest'] . ')';
			$author .= ( $file_rowset[$i]['user_id'] != ANONYMOUS ) ? '</a>' : '';

			$article_id = $file_rowset[$i]['article_id'];
			$views = $file_rowset[$i]['views'];

			$article_title = $file_rowset[$i]['article_title'];

			//
			// Article Url - Standard or App
			//
			$article_url = mx_append_sid( $this->this_mxurl( "mode=article&k=$article_id", false, false, $target_page_id ) );

			// ===================================================
			// Assign Vars
			// ===================================================

			$template->assign_block_vars( "ARTICLELIST.articlerow", array(
				'ARTICLE' => $article_title,
				'ARTICLE_DESCRIPTION' => $article_description,
				'ARTICLE_TYPE' => $article_type,
				'ARTICLE_DATE' => $article_date,
				'ARTICLE_AUTHOR' => $author,
				'CATEGORY' => $cat_name,
				'ART_VIEWS' => $views,

				'ARTICLE_VOTES' => $file_rowset[$i]['total_votes'],
				'L_RATING' => $lang['Votes_label'],
				'DO_RATE' => $this->auth_user[$cat_id]['auth_rate'] ? '<a href="' . mx_append_sid( $this->this_mxurl( 'mode=rate&k=' . $file_rowset[$i]['article_id'] ) ) . '">' . $lang['ADD_RATING'] . '</a>' : '',
				'RATING' => $rating,

				'U_ARTICLE' => $article_url,
				'U_CAT' => $cat_url,

				//'U_APPROVE' => $approve,
				'U_ARTICLE' => $article_url,

				'ARTICLE_IMAGE' => $is_new ? $images['kb_article_new'] : $images['kb_article'],
				'COLOR' => ( ( $i % 2 ) ? "row2" : "row1" ),
				'POSTER' => $file_poster,

				//'U_DELETE' => $delete
			));

			if ( $kb_use_ratings )
			{
				$template->assign_block_vars( "ARTICLELIST.articlerow.show_ratings", array() );
			}

			if ( $is_new )
			{
				$template->assign_block_vars( "ARTICLELIST.articlerow.is_new_file", array() );
			}

			//
			// Options (only used for the toplist block)
			//
			if ($sort_options_list)
			{
				foreach ($sort_options_list as $sort_option => $options_value)
				{
					switch ($sort_option)
					{
						case 'date':
							$template->assign_block_vars( "ARTICLELIST.articlerow.display_date", array());
						break;
						case 'username':
							$template->assign_block_vars( "ARTICLELIST.articlerow.display_username", array());
						break;
						case 'counter':
							$template->assign_block_vars( "ARTICLELIST.articlerow.display_counter", array());
						break;
						case 'rate':
							$template->assign_block_vars( "ARTICLELIST.articlerow.display_rate", array());
						break;
					}
				}
			}

		}

		if ( $filelist )
		{
			$action = ( empty( $cat_id ) ) ? 'stats' : 'cat&amp;cat=' . $cat_id;

			$sort_method = isset($_REQUEST['sort_method']) ? $_REQUEST['sort_method'] : $kb_config['sort_method'];
			$sort_order = isset($_REQUEST['sort_order']) ? $_REQUEST['sort_order'] : $kb_config['sort_order'];

			$template->assign_vars( array(
				'L_CATEGORY' => $lang['Category'],
				'L_CATEGORY_NAME' => $category_name,
				'L_ARTICLE' => $lang['Article'],
				'L_ARTICLES' => $lang['Articles'],
				'L_ARTICLE_TYPE' => $lang['Article_type'],
				'L_ARTICLE_CATEGORY' => $lang['Category'],
				'L_ARTICLE_DATE' => $lang['Date'],
				'L_ARTICLE_AUTHOR' => $lang['Author'],
				'L_VIEWS' => $lang['Views'],
				'L_VOTES' => $lang['Votes'],

				'L_LINK_SITE_DESC' => $lang['Siteld'],
				'L_DOWNLOADS' => $lang['Hits'],
				'L_DATE' => $lang['Date'],
				'L_NAME' => $lang['Sitename'],
				'L_FILE' => $lang['Link'],
				'L_SUBMITED_BY' => $lang['Submiter'],
				'L_SELECT_SORT_METHOD' => $lang['Select_sort_method'],

				'L_ORDER' => $lang['Order'],
				'L_SORT' => $lang['Sort'],

				'L_ID' => $lang['Top_id'],
				'L_LATEST' => $lang['Top_latest'],
				'L_TOPRATED' => $lang['Top_toprated'],
				'L_MOST_POPULAR' => $lang['Top_most_popular'],
				'L_USERRANK' => $lang['Top_userrank'],
				'L_ALPHABETIC' => $lang['Top_alphabetic'],

				'L_ASC' => $lang['Sort_Ascending'],
				'L_DESC' => $lang['Sort_Descending'],

				'SORT_ID' => ( $sort_method == 'Id' ) ? 'selected="selected"' : '',
				'SORT_LATEST' => ( $sort_method == 'Latest' ) ? 'selected="selected"' : '',
				'SORT_TOPRATED' => ( $sort_method == 'Toprated' ) ? 'selected="selected"' : '',
				'SORT_MOST_POPULAR' => ( $sort_method == 'Most_popular' ) ? 'selected="selected"' : '',
				'SORT_USERRANK' => ( $sort_method == 'Userrank' ) ? 'selected="selected"' : '',
				'SORT_ALPHABETIC' => ( $sort_method == 'Alphabetic' ) ? 'selected="selected"' : '',

				'SORT_ASC' => ( $sort_order == 'ASC' ) ? 'selected="selected"' : '',
				'SORT_DESC' => ( $sort_order == 'DESC' ) ? 'selected="selected"' : '',

				'PAGINATION' => mx_generate_pagination( mx_append_sid( $this->this_mxurl( "&mode=$action&amp;sort_method=$sort_method&sort_order=$sort_order" ) ), $total_file, $kb_config['pagination'], $start),
				'PAGE_NUMBER' => sprintf( $lang['Page_of'], ( floor( $start / $kb_config['pagination'] ) + 1 ), ceil( $total_file / $kb_config['pagination'] ) ),
				'ID' => $cat_id,
				'START' => $start,

				'S_ACTION_SORT' => mx_append_sid( $this->this_mxurl( "&mode=$action" ) ) )
			);
		}
		else
		{
			if ($this->cat_rowset[$cat_id]['cat_allow_file'])
			{
				$template->assign_block_vars( 'no_articles', array(
					'L_NO_ARTICLES' => $lang['No_articles'],
					'L_NO_ARTICLES_CAT' => $lang['No_Articles']
				) );
			}
		}

		return $total_file;
	}

	/**
	 * auth_can.
	 *
	 * @param unknown_type $category_id
	 */
	function auth_can($category_id)
	{
		global $lang;

		$this->debug('mx_kb->auth_can', basename( __FILE__ ));

		//
		// User authorisation levels output
		//
		$this->auth_can_list = '<br />' . ( ( $this->auth_user[$category_id]['auth_post'] ) ? $lang['KB_Rules_post_can'] : $lang['KB_Rules_post_cannot'] ) . '<br />';
		$this->auth_can_list .= ( ( $this->auth_user[$category_id]['auth_edit'] ) ? $lang['KB_Rules_edit_can'] : $lang['KB_Rules_edit_cannot'] ) . '<br />';
		$this->auth_can_list .= ( ( $this->auth_user[$category_id]['auth_delete'] ) ? $lang['KB_Rules_delete_can'] : $lang['KB_Rules_delete_cannot'] ) . '<br />';
		$this->auth_can_list .= ( ( $this->comments[$category_id]['activated'] ? (( $this->auth_user[$category_id]['auth_post_comment'] ? $lang['KB_Rules_comment_can'] : $lang['KB_Rules_comment_cannot'] ) . '<br />') : '' ));
		$this->auth_can_list .= ( ( $this->ratings[$category_id]['activated'] ? (( $this->auth_user[$category_id]['auth_rate'] ? $lang['KB_Rules_rate_can'] : $lang['KB_Rules_rate_cannot'] ) . '<br />') : '' ));
		$this->auth_can_list .= ( ( $this->auth_user[$category_id]['auth_approval'] ) ? $lang['KB_Rules_approval_can'] : $lang['KB_Rules_approval_cannot'] ) . '<br />';
		$this->auth_can_list .= ( ( $this->auth_user[$category_id]['auth_approval_edit'] ) ? $lang['KB_Rules_approval_edit_can'] : $lang['KB_Rules_approval_edit_cannot'] ) . '<br />';

		if ( $this->auth_user[$category_id]['auth_mod'] )
		{
			$this->auth_can_list .= $lang['KB_Rules_moderate_can'];
		}
	}

	/**
	 * MX URL Fix
	 * Temporary solution to handle addons urls 
	 *
	 * @param unknown_type $args
	 * @param unknown_type $force_standalone_mode
	 * @param unknown_type $non_html_amp
	 * @return unknown
	 */
	function this_mxurl($args = '', $force_standalone_mode = false, $non_html_amp = false, $is_block = true)
	{
		global $mx_root_path, $module_root_path, $page_id, $phpEx, $is_block;
		
		$pageId 		= $this->request->variable('page_id', $page_id);
		$dynamicId 		= isset($_GET['dynamic_block']) ? ( $non_html_amp ? '&dynamic_block=' : '&amp;dynamic_block=' ) . $this->request->variable('dynamic_block') : '';
		
		$args = str_replace('&amp;', '&', $args);
		
		$actions_args 	= isset($args) ? @explode('&', str_replace('?', '&', $args)) : '';
		
		$action_arg1st 	= isset($actions_args[0]) ? @explode('=', $actions_args[0]) : '';
		$action_arg2nd 	= isset($actions_args[1]) ? @explode('=', $actions_args[1]) : '';
		$action_arg3rd 	= isset($actions_args[2]) ? @explode('=', $actions_args[2]) : '';
		
		$mode 		= ($action_arg1st[0] == 'mode') ? $action_arg1st[1] : $this->request->variable('mode', '');
		$action 		= ($action_arg1st[0] == 'action') ? $action_arg1st[1] : $this->request->variable('action', $mode);
		
		$do 			= ($action_arg1st[0] == 'do') ? $action_arg1st[1] : $this->request->variable('do', '');	
		$do 			= ($action_arg2nd[0] == 'do') ? $action_arg2nd[1] : $this->request->variable('do', $do);
		$do 			= ($action_arg3rd[0] == 'do') ? $action_arg3rd[1] : $this->request->variable('do', $do);
			
		$article_id 		= ($action_arg2nd[0] == 'article_id') ? $action_arg2nd[1] : $this->request->variable('article_id', '');
		$article_id 		= ($action_arg3rd[0] == 'article_id') ? $action_arg3rd[1] : $article_id;		
		
		$cat_id 		= ($action_arg2nd[0] == 'cat_id') ? $action_arg2nd[1] : $this->request->variable('cat_id', '');		
		$cat_id 		= ($action_arg3rd[0] == 'cat_id') ? $action_arg3rd[1] : $cat_id;				
		
	
		if ( ($cat_id == 0) && ($article_id !== 0) )
		{
			$sql = "SELECT article_id
				FROM " . KB_ARTICLES_TABLE . "
				WHERE article_id = '" . $article_id . "'";
			if ( !( $result = $this->db->sql_query( $sql ) ) )
			{
				$this->message_die( GENERAL_ERROR, 'Couldnt query download file category', '', __LINE__, __FILE__, $sql );
			}
			$article_data = $this->db->sql_fetchrow($result);
			$this->db->sql_freeresult($result);
			
			$cat_id = $article_data['article_category_id'];
		}
		
		
		$use_action = isset($action) ? '_' . str_replace('category', 'cat', $action) : '';
		
		$use_cat_or_file_array = ((isset($cat_id) && !isset($article_id)) || (!isset($cat_id) && isset($article_id))) ? true : false;					
		$use_cat_and_file_array = isset($cat_id) && isset($article_id) ? true : false;
		
		$use_cat_array = ($use_cat_file_array == false) && isset($cat_id) ? true : false;	
		$use_action_do_array = isset($action) && isset($do) ? true : false;
		
		$route_cat_or_file_array = (($use_cat_or_file_array == true) && ($use_cat_array == true)) ? array('cat_id' => $cat_id) : ( (($use_cat_or_file_array == true) && ($use_file_array == true )) ? array('file_id' => $file_id) : array('mode' => $mode) );
		$route_cat_and_file_array = ($use_cat_and_file_array == true) ? array('cat_id' => $cat_id, 'article_id' => $article_id) : $route_cat_or_file_array;	
		
		$route_action_do_file_array = (($use_action_do_array == true) && ($use_file_array == true)) ? array('action' => $action, 'do' => $do, 'file_id' => $file_id) : ((($use_action_do_array == true) && ($use_cat_array == true)) ? array('mode' => $mode, 'do' => $do, 'file_id' => $file_id) : $route_cat_and_file_array);
		
		$route_array = ($use_action_do_array == true && is_array($route_action_do_file_array)) ? $route_action_do_file_array : $route_cat_and_file_array;		
		
		
		$this->backend = PORTAL_BACKEND;
		
		$dynamicId = !empty($_GET['dynamic_block']) ? ( $non_html_amp ? '&dynamic_block=' : '&amp;dynamic_block=' ) . $_GET['dynamic_block'] : '';

		$args .= ($args == '' ? '' : '&' ) . 'modrewrite=no';
		
		
		if ( !MXBB_MODULE )
		{
			$mxurl = PORTAL_URL . $module_root_path . 'kb.' . $phpEx . ( $args == '' ? '' : '?' . $args );
			return $mxurl;
		}

		if ( $force_standalone_mode || !$is_block )
		{
			$mxurl = PORTAL_URL . $module_root_path . 'kb.' . $phpEx . ( $args == '' ? '' : '?' . $args );
		}
		else
		{
			$mxurl = PORTAL_URL . 'index.' . $phpEx;
			
			if ( is_numeric( $pageId ) )
			{
				$mxurl .= '?page=' . $pageId . $dynamicId . ( $args == '' ? ( $non_html_amp ? '&' : '&amp;' ) . 'mode=' . $mode : ( $non_html_amp ? '&' : '&amp;' ) . $args );
			}
			else
			{
				$mxurl .= ( $args == '' ? ( $non_html_amp ? '&' : '&amp;' ) . 'mode=' . $mode : ( $non_html_amp ? '&' : '&amp;' ) . $args );
			}
		}
		return $mxurl;
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $args
	 * @param unknown_type $force_standalone_mode
	 * @param unknown_type $non_html_amp
	 * @return unknown
	 */
	function this_mxurl_search( $args = '', $force_standalone_mode = false, $non_html_amp = false )
	{
		global $mx_root_path, $module_root_path, $page_id, $phpEx, $is_block;

		$dynamicId = !empty($_GET['dynamic_block']) ? ( $non_html_amp ? '&dynamic_block=' : '&amp;dynamic_block=' ) . $_GET['dynamic_block'] : '';

		if ( !MXBB_MODULE )
		{
			$mxurl = $module_root_path . 'kb_search.' . $phpEx . ( $args == '' ? '' : '?' . $args );
			return $mxurl;
		}

		if ( $force_standalone_mode || !$is_block )
		{
			$mxurl = $mx_root_path . 'modules/mx_kb/kb_search.' . $phpEx . ( $args == '' ? '' : '?' . $args );
		}
		else
		{
			$mxurl = $mx_root_path . 'index.' . $phpEx;
			if ( is_numeric( $page_id ) )
			{
				$mxurl .= '?page=' . $page_id . $dynamicId . ( $args == '' ? '' : ( $non_html_amp ? '&' : '&amp;' ) . $args );
			}
			else
			{
				$mxurl .= ( $args == '' ? '' : '?' . $args );
			}
		}
		return $mxurl;
	}

	// =============================================
	// Admin and mod functions
	// =============================================

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $article_id
	 * @param unknown_type $rating
	 */
	function update_voter_info( $article_id, $rating )
	{
		global $db, $userdata, $lang, $kb_config;

		$ipaddy = getenv ( "REMOTE_ADDR" );

		if ($kb_config['votes_check_ip'] && $kb_config['votes_check_userid'])
		{
			$where_sql = ( $userdata['user_id'] != ANONYMOUS ) ? "(user_id = '" . $userdata['user_id'] . "' OR votes_ip = '" . $ipaddy . "')": "votes_ip = '" . $ipaddy . "'";
		}
		else if($kb_config['votes_check_ip'])
		{
			$where_sql = ( $kb_config['votes_check_ip'] ) ? "votes_ip = '" . $ipaddy . "'" : '';
		}
		else if($kb_config['votes_check_userid'])
		{
			$where_sql = ( $userdata['user_id'] != ANONYMOUS ) ? "user_id = '" . $userdata['user_id'] . "'" : '';
		}
		else
		{
			$where_sql = "user_id = '-99'";
		}
		$where_sql .= !empty($where_sql) ? " AND votes_article = '" . $article_id . "'" : "votes_article = '" . $article_id . "'";

		$sql = "SELECT user_id, votes_ip
			FROM " . KB_VOTES_TABLE . "
			WHERE $where_sql
			LIMIT 1";

		if ( !( $result = $db->sql_query( $sql ) ) )
		{
			mx_message_die( GENERAL_ERROR, 'Couldnt Query User id', '', __LINE__, __FILE__, $sql );
		}

		//
		// Has already voted. Should we care?
		//
		if ( !$db->sql_numrows( $result ) )
		{
			$sql = "INSERT INTO " . KB_VOTES_TABLE . " (user_id, votes_ip, votes_article, rate_point)
						VALUES('" . $userdata['user_id'] . "', '" . $ipaddy . "', '" . $article_id . "','" . $rating . "')";

			if ( !( $db->sql_query( $sql ) ) )
			{
				mx_message_die( GENERAL_ERROR, 'Couldnt Update Votes Table Info', '', __LINE__, __FILE__, $sql );
			}
		}
		else
		{
			$message = $lang['Rerror'] . "<br /><br />" . sprintf( $lang['Click_return'], "<a href=\"" . mx_append_sid( $this->this_mxurl( "mode=article&amp;k=$article_id" ) ) . "\">", "</a>" );
			mx_message_die( GENERAL_MESSAGE, $message );
		}

		$db->sql_freeresult( $result );
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $article_data
	 * @param unknown_type $item_id
	 * @param unknown_type $cid
	 * @param unknown_type $subject
	 * @param unknown_type $message
	 * @param unknown_type $html_on
	 * @param unknown_type $bbcode_on
	 * @param unknown_type $smilies_on
	 */
	function update_add_comment($article_data = '', $item_id, $cid, $subject = '', $message = '', $html_on = false, $bbcode_on = true, $smilies_on = false, $allow_wysiwyg = false)
	{
		global $template, $mx_kb_functions, $lang, $board_config, $phpEx, $kb_config, $db, $images, $userdata;
		global $html_entities_match, $html_entities_replace, $unhtml_specialchars_match, $unhtml_specialchars_replace;
		global $mx_root_path, $module_root_path, $phpbb_root_path, $is_block, $phpEx, $mx_request_vars;

		//
		// Ensure we have article_data defined
		//
		if (!is_array($article_data) && !empty($item_id) && $item_id > 0)
		{
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
		}

		//
		// vars (can both be POSTed or send through the function)
		//
		$update_comment = $cid > 0 ? true : false;
		$subject = !empty($subject) ? $subject : $_POST['subject'];
		$message = !empty($message) ? $message : $_POST['message'];

		$length = strlen( $message );

		//
		// Instantiate the mx_text class
		//
		$mx_text = new mx_text();
		$mx_text->init($html_on, $bbcode_on, $smilies_on);
		$mx_text->allow_all_html_tags = $allow_wysiwyg;

		//
		// Encode for db storage
		//
		$title = $mx_text->encode_simple($subject);
		$comments_text = $mx_text->encode($message);
		$comment_bbcode_uid = $mx_text->bbcode_uid;

		if ( $length > $kb_config['max_comment_chars'] )
		{
			mx_message_die( GENERAL_ERROR, 'Your comment is too long!<br/>The maximum length allowed in characters is ' . $kb_config['max_comment_chars'] . '' );
		}

		if ( $update_comment )
		{
			if ( $this->comments[$article_data['article_category_id']]['internal_comments'] )
			{
				$sql = "UPDATE " . KB_COMMENTS_TABLE . "
					SET comments_text = '" . str_replace( "\'", "''", $comments_text ) . "',
				          comments_title = '" . str_replace( "\'", "''", $title ) . "',
				          comment_bbcode_uid = '" . $comment_bbcode_uid . "'
				    WHERE comments_id = " . $cid . "
						AND article_id = ". $item_id;

				if ( !( $db->sql_query( $sql ) ) )
				{
					mx_message_die( GENERAL_ERROR, 'Couldnt update comments', '', __LINE__, __FILE__, $sql );
				}
			}
			else
			{
				include( $module_root_path . 'kb/includes/functions_comment.' . $phpEx );
				$mx_kb_comments = new mx_kb_comments();
				$mx_kb_comments->init( $item_id );

				$return_data = $mx_kb_comments->post( 'update', $cid, $title, $comments_text, $userdata['user_id'], $userdata['username'], 0, '', '', $comment_bbcode_uid);
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

				if ( !( $db->sql_query( $sql ) ) )
				{
					mx_message_die( GENERAL_ERROR, 'Couldnt insert comments', '', __LINE__, __FILE__, $sql );
				}
			}
			else
			{
				include( $module_root_path . 'kb/includes/functions_comment.' . $phpEx );
				$mx_kb_comments = new mx_kb_comments();
				$mx_kb_comments->init( $item_id );

				$return_data = $mx_kb_comments->post( 'insert', '', $title, $comments_text, $userdata['user_id'], $userdata['username'], 0, '', '', $comment_bbcode_uid);
			}
		}

		if ( !$this->comments[$article_data['article_category_id']]['internal_comments'] )
		{

			//
			// Update the item data itself
			//
			if ($article_data['topic_id'] == 0 )
			{
				//
				// Update item with new topic_id
				//
				$sql = "UPDATE " . KB_ARTICLES_TABLE . "
					SET topic_id = '" . $return_data['topic_id'] . "'
				    WHERE article_id = ". $item_id;

				if ( !( $result = $db->sql_query( $sql ) ) )
				{
					mx_message_die( GENERAL_ERROR, 'Couldnt update item', '', __LINE__, __FILE__, $sql );
				}

				$db->sql_freeresult( $result );
			}
		}
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $file_id
	 * @return unknown
	 */
	function update_add_item( $file_id = false )
	{
		mx_message_die(GENERAL_ERROR, "Use mode=add or mode=edit to post or edit articles.");
	}


	/**
	 * Enter description here...
	 *
	 * @param unknown_type $article_id
	 * @param unknown_type $cat_id
	 * @param unknown_type $mode_notification
	 */
	function update_add_item_notify( $article_id = false, $mode_notification = 'edit' )
	{
		global $db;

		if ( in_array( $mode_notification, array( 'add', 'edit', 'approve', 'unapprove', 'delete' ) ) )
		{
			if (!$article_id)
			{
				die('bad update_add_item_notify arg');
			}

			if (is_array( $article_id ) && !empty( $article_id ))
			{
				$articleIdsArray = $article_id;
			}
			else
			{
				$articleIdsArray[] = $article_id;
			}

			foreach($articleIdsArray as $articleId)
			{
				$sql = "SELECT article_category_id
					FROM " . KB_ARTICLES_TABLE . "
					WHERE article_id = '" . $articleId . "'";

				if ( !$result = $db->sql_query( $sql ) )
				{
					mx_message_die( GENERAL_ERROR, 'Couldn\'t get article info', '', __LINE__, __FILE__, $sql );
				}

				$row = $db->sql_fetchrow( $result );
				$catId = $row['article_category_id'];

				//
				// Notification
				//
				if ( $this->notification[$catId]['activated'] > 0 ) // -1, 0, 1, 2
				{
					//
					// Instatiate notification
					//
					$mx_kb_notification = new mx_kb_notification();
					$mx_kb_notification->init( $articleId );

					//
					// Now send notification
					//
					$mx_notification_mode = $this->notification[$catId]['activated'] == 1 ? MX_PM_MODE : MX_MAIL_MODE;

					switch ( $mode_notification )
					{
						case 'add':
							$mx_notification_action = MX_NEW_NOTIFICATION;
						break;
						case 'edit':
							$mx_notification_action = MX_EDITED_NOTIFICATION;
						break;
						case 'approve':
							$mx_notification_action = MX_APPROVED_NOTIFICATION;
						break;
						case 'unapprove':
							$mx_notification_action = MX_UNAPPROVED_NOTIFICATION;
						break;
						case 'delete':
							$mx_notification_action = MX_DELETED_NOTIFICATION;
						break;
					}

					$html_entities_match = array('#&(?!(\#[0-9]+;))#', '#<#', '#>#', '#"#');
					$html_entities_replace = array('&amp;', '&lt;', '&gt;', '&quot;');

					$mx_kb_notification->notify( $mx_notification_mode, $mx_notification_action );

					if ( $this->notification[$catId]['notify_group'] > 0 )
					{
						$mx_kb_notification->notify( $mx_notification_mode, $mx_notification_action, - intval($this->notification[$catId]['notify_group']) );
					}
				}
			}
		}
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $id
	 * @param unknown_type $mode
	 */
	function delete_items( $id, $mode = 'article' )
	{
		global $db, $phpbb_root_path, $mx_kb_functions;

		if ( $mode == 'category' )
		{
			$file_ids = array();
			$files_data = array();
			$sql = 'SELECT article_id
				FROM ' . KB_ARTICLES_TABLE . "
				WHERE article_category_id = $id";

			if ( !( $result = $db->sql_query( $sql ) ) )
			{
				mx_message_die( GENERAL_ERROR, 'Couldnt select articles', '', __LINE__, __FILE__, $sql );
			}

			while ( $row = $db->sql_fetchrow( $result ) )
			{
				$file_ids[] = $row['article_id'];
				$files_data[] = $row;
			}

			$where_sql = "WHERE article_category_id = $id";
		}
		else
		{
			$sql = 'SELECT article_id
				FROM ' . KB_ARTICLES_TABLE . "
				WHERE article_id = $id";

			if ( !( $result = $db->sql_query( $sql ) ) )
			{
				mx_message_die( GENERAL_ERROR, 'Couldnt select articles', '', __LINE__, __FILE__, $sql );
			}

			$file_data = $db->sql_fetchrow( $result );

			$where_sql = "WHERE article_id = $id";
		}

		$sql = 'DELETE FROM ' . KB_ARTICLES_TABLE . "
			$where_sql";

		unset( $where_sql );

		if ( !( $db->sql_query( $sql ) ) )
		{
			mx_message_die( GENERAL_ERROR, 'Couldnt delete articles', '', __LINE__, __FILE__, $sql );
		}

		$where_sql = ( $mode != 'article' && !empty( $file_ids ) ) ? ' IN (' . implode( ', ', $file_ids ) . ') ' : " = $id";

		$sql = 'DELETE FROM ' . KB_CUSTOM_DATA_TABLE . "
			WHERE customdata_file$where_sql";

		if ( !( $db->sql_query( $sql ) ) )
		{
			mx_message_die( GENERAL_ERROR, 'Couldnt delete custom data', '', __LINE__, __FILE__, $sql );
		}

		if ( $mode == 'article' )
		{
			$this->modified( true );
		}

		return;
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $mode
	 * @param unknown_type $link_id
	 */
	function approve_item( $mode = 'do_approve', $article_id )
	{
		global $db;

		$article_approved = ( $mode == 'do_approve' ) ? 1 : 0;

		$sql = 'UPDATE ' . KB_ARTICLES_TABLE . "
			SET approved = $article_approved
			WHERE article_id = $article_id";

		if ( !( $db->sql_query( $sql ) ) )
		{
			mx_message_die( GENERAL_ERROR, 'Couldnt Add the article information to the database', '', __LINE__, __FILE__, $sql );
		}
		$this->modified( true );
	}

	/**
	 * generate bottom category jumpbox.
	 *
	 * @param unknown_type $auth_type
	 * @param unknown_type $id
	 * @param unknown_type $select
	 * @param unknown_type $selected
	 * @param unknown_type $is_admin
	 * @return unknown
	 */
	/**
	function generate_jumpbox( $auth_type, $id = 0, $select = 1, $selected = false, $is_admin = false )
	{
		global $db, $userdata;

		$this->debug('mx_kb->generate_jumpbox', basename( __FILE__ ));

		$cat_rowset = array();
		$idfield = 'category_id';
		$namefield = 'category_name';

		//
		// Do we really have to query cat data?
		//
		if ( empty( $this->subcat_rowset[0] ) )
		{
			$sql = "SELECT *
		       	FROM " . KB_CATEGORIES_TABLE . "
				WHERE parent = 0 ";

			if ( $select == 0 )
			{
				$sql .= " AND $idfield <> $id";
			}

			$sql .= " ORDER BY cat_order ASC";

			if ( !( $result = $db->sql_query( $sql ) ) )
			{
				mx_message_die( GENERAL_ERROR, "Could not obtain category information", '', __LINE__, __FILE__, $sql );
			}

			while ( $category = $db->sql_fetchrow( $result ) )
			{
				$cat_rowset[$category['category_id']] = $category;
			}
		}
		else
		{
			foreach ( $this->subcat_rowset[0] as $temp_cat_id => $temp_category )
			{
				if ( !($select == 0 && $temp_cat_id == $id) )
				{
					$cat_rowset[$temp_cat_id] = $temp_category;
				}
			}
		}

		$catlist = "";

		foreach ( $cat_rowset as $temp_cat_id => $category )
		{
			if ( $select == $category[$idfield] && $selected)
			{
				$status = 'selected';
			}
			else
			{
				$status = '';
			}

			if ( ( $this->ns_auth_cat( $category[$idfield] ) && $this->auth_user[$category[$idfield]][$auth_type] ) || $is_admin)
			{
				$catlist .= "<option value=\"$category[$idfield]\" $status>" . $category[$namefield] . "</option>\n";

				$catlist .= $this->generate_jumpbox_sub( $auth_type, $category[$idfield], $select, $selected, $is_admin, '&nbsp;&nbsp;' );
			}
		}

		$this->jumpbox = $catlist;
		return $catlist ;
	}
	*/

	/**
	 * generate_navigation.
	 *
	 * @param unknown_type $parent
	 * @param unknown_type $path_kb_array
	 */
	/*
	function generate_navigation( $parent, $path_kb_array = array() )
	{
		global $template, $db, $phpbb_root_path, $mx_root_path, $module_root_path, $phpEx;
		global $is_block, $page_id;

		$this->debug('mx_kb->generate_navigation', basename( __FILE__ ));

		$sql = "SELECT * FROM " . KB_CATEGORIES_TABLE . "
			       WHERE category_id = $parent";

		if ( !( $result = $db->sql_query( $sql ) ) )
		{
			mx_message_die( GENERAL_ERROR, "Could not obtain category data", '', __LINE__, __FILE__, $sql );
		}
		$row = $db->sql_fetchrow( $result );

		$temp_url = mx_append_sid( $this->this_mxurl( 'mode=cat&amp;cat=' . $row['category_id'] ) );
		$path_kb_array[] .= '&nbsp;&raquo;&nbsp; <a href="' . $temp_url . '" class="nav">' . $row['category_name'] . '</a> ';

		if ( $row['parent'] != '0' )
		{
			$this->generate_navigation( $row['parent'], $path_kb_array );
			return;
		}

		$path_kb_array2 = array_reverse( $path_kb_array );

		$i = 0;
		while ( $i <= count( $path_kb_array2 ) )
		{
			$path_kb .= $path_kb_array2[$i];
			$i++;
		}

		$template->assign_vars( array(
			'PATH' => $path_kb,
		));

	}
	*/

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $auth_type
	 * @param unknown_type $parent
	 * @param unknown_type $select
	 * @param unknown_type $selected
	 * @param unknown_type $is_admin
	 * @param unknown_type $indent
	 * @return unknown
	 */
	/*
	function generate_jumpbox_sub( $auth_type, $parent, $select = 1, $selected = false, $is_admin = false, $indent )
	{
		global $db;

		$this->debug('mx_kb->generate_jumpbox_sub', basename( __FILE__ ));

		$subcat_rowset = array();
		$idfield = 'category_id';
		$namefield = 'category_name';

		//
		// Do we really have to query cat data?
		//
		if ( empty( $this->subcat_rowset[$parent] ) )
		{
			$sql = "SELECT *
		       		FROM " . KB_CATEGORIES_TABLE . "
					WHERE parent = " . $parent;

			if ( $select == 0 )
			{
				$sql .= " AND $idfield <> $parent";
			}

			$sql .= " ORDER BY cat_order ASC";

			if ( !( $result = $db->sql_query( $sql ) ) )
			{
				mx_message_die( GENERAL_ERROR, "Could not obtain sub-category data", '', __LINE__, __FILE__, $sql );
			}

			while ( $category2 = $db->sql_fetchrow( $result ) )
			{
				$subcat_rowset[$category2['category_id']] = $category2;
			}
		}
		else
		{
			foreach ( $this->subcat_rowset[$parent] as $temp_cat_id => $temp_category )
			{
				if ( !($select == 0 && $temp_cat_id == $parent) )
				{
					$subcat_rowset[$temp_cat_id] = $temp_category;
				}
			}
		}

		$catlist = "";

		foreach ( $subcat_rowset as $temp_cat_id => $category2 )
		{
			if ( $select == $category2[$idfield] && $selected )
			{
				$status = 'selected';
			}
			else
			{
				$status = '';
			}

			if ( ( $this->ns_auth_cat( $category2[$idfield] ) && $this->auth_user[$category2[$idfield]][$auth_type] ) || $is_admin)
			{
				$catlist .= "<option value=\"$category2[$idfield]\" $status>" . $indent . '--&raquo;'. $category2[$namefield] . "</option>\n";
				$temp = $indent . '&nbsp;&nbsp;';
				$catlist .= $this->generate_jumpbox_sub( $auth_type, $category2[$idfield], $select, $selected, $is_admin, $temp );
			}
		}

		return $catlist;
	}
	*/
}

/**
 * Public mx_kb class.
 *
 */
class mx_kb_public extends mx_kb
{
	var $modules = array();
	var $module_name = '';

	/**
	 * load module.
	 *
	 * @param unknown_type $module_name send module name to load it
	 */
	function module($module_name)
	{
		if (!class_exists('mx_kb_' . $module_name))
		{
			global $module_root_path, $phpEx;

			$this->module_name = $module_name;

			require_once( $module_root_path . 'kb/modules/kb_' . $module_name . '.' . $phpEx);
			@eval('$this->modules[' . $module_name . '] = new mx_kb_' . $module_name . '();' );

			if (method_exists($this->modules[$module_name], 'init'))
			{
				$this->modules[$module_name]->init();
			}
		}
	}

	/**
	 * this will be replaced by the loaded module.
	 *
	 * @param unknown_type $module_id
	 * @return unknown
	 */
	function main($module_id = false)
	{
		return false;
	}

	/**
	 * go ahead and output the page
	 * - not used in mx_kb
	 *
	 * @param unknown_type $page_title send page title
	 * @param unknown_type $tpl_name template file name
	 */
	function display( $page_title1, $tpl_name )
	{
		global $page_title, $kb_tpl_name;

		$page_title = $page_title1;
		$kb_tpl_name = $tpl_name;
	}
}

if ( !function_exists( 'html_entity_decode' ) )
{
	/**
	 * For old php versions.
	 *
	 * @param unknown_type $string
	 * @param unknown_type $opt
	 * @return unknown
	 */
	/*
	function html_entity_decode ( $string, $opt = ENT_COMPAT )
	{
		$trans_tbl = get_html_translation_table ( HTML_ENTITIES );
		$trans_tbl = array_flip ( $trans_tbl );

		if ( $opt &1 )
		{
			// Translating single quotes
			// Add single quote to translation table;
			// doesn't appear to be there by default
			$trans_tbl["&apos;"] = "'";
		}

		if ( !( $opt &2 ) )
		{
			// Not translating double quotes
			// Remove double quote from translation table
			unset( $trans_tbl["&quot;"] );
		}

		return strtr ( $string, $trans_tbl );
	}
	*/
}

//
// Just to be safe ;o)
//
/*
if ( !defined( "ENT_COMPAT" ) ) define( "ENT_COMPAT", 2 );
if ( !defined( "ENT_NOQUOTES" ) ) define( "ENT_NOQUOTES", 0 );
if ( !defined( "ENT_QUOTES" ) ) define( "ENT_QUOTES", 3 );
*/
?>