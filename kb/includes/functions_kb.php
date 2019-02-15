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
 *    $Id: functions_kb.php,v 1.1 2005/12/08 15:06:46 jonohlsson Exp $
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
 
// ===================================================
// public mx_kb class
// ===================================================
class mx_kb_public extends mx_kb
{
	var $modules = array();
	var $module_name = ''; 
	
	// ===================================================
	// load module
	// $module name : send module name to load it
	// ===================================================
	function module( $module_name )
	{
		if ( !class_exists( 'mx_kb_' . $module_name ) )
		{
			global $phpbb_root_path, $phpEx; 
			global $mx_root_path, $module_root_path, $is_block, $phpEx;

			$this->module_name = $module_name;

			require_once( $module_root_path . 'kb/modules/kb_' . $module_name . '.' . $phpEx );
			eval( '$this->modules[' . $module_name . '] = new mx_kb_' . $module_name . '();' );

			if ( method_exists( $this->modules[$module_name], 'init' ) )
			{
				$this->modules[$module_name]->init();
			}
		}
	} 
	
	// ===================================================
	// this will be replaced by the loaded module
	// ===================================================
	function main( $module_id = false )
	{
		return false;
	} 
	
}

// ===================================================
// mx_kb class
// ===================================================
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
	
	var $sort_method = '';
	var $sort_method_extra = '';
	var $sort_order = '';
	
	var $page_title = '';
	var $jumpbox = '';
	var $auth_can_list = '';
	var $navigation = '';
	
	var $debug = true;
	var $debug_msg = array();
	
	// ===================================================
	// Prepare data
	// ===================================================
	function init()
	{
		global $db, $userdata, $debug, $kb_config;

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

		$this->auth( AUTH_ALL, AUTH_LIST_ALL, $userdata, $cat_rowset );

		for( $i = 0; $i < count( $cat_rowset ); $i++ )
		{		
			if ( $this->auth_user[$cat_rowset[$i]['category_id']]['auth_view'] )
			{
				$this->cat_rowset[$cat_rowset[$i]['category_id']] = $cat_rowset[$i];
				$this->subcat_rowset[$cat_rowset[$i]['parent']][$cat_rowset[$i]['category_id']] = $cat_rowset[$i];
				$this->total_cat++;
			}
			
			//
			// Comments
			// Note: some settings are category dependent, but may use default config settings
			//
			$this->comments[$cat_rowset[$i]['category_id']]['activated'] = $cat_rowset[$i]['cat_allow_comments'] == -1 ? ($kb_config['use_comments'] == 1 ? true : false ) : ( $cat_rowset[$i]['cat_allow_comments'] == 1 ? true : false );
			$this->comments[$cat_rowset[$i]['category_id']]['internal_comments'] = $cat_rowset[$i]['internal_comments'] == -1 ? ($kb_config['internal_comments'] == 1 ? true : false ) : ( $cat_rowset[$i]['internal_comments'] == 1 ? true : false ); // phpBB or internal comments
			$this->comments[$cat_rowset[$i]['category_id']]['autogenerate_comments'] = $cat_rowset[$i]['autogenerate_comments'] == -1 ? ($kb_config['autogenerate_comments'] == 1 ? true : false ) : ( $cat_rowset[$i]['autogenerate_comments'] == 1 ? true : false ); // autocreate comments when updated
			$this->comments[$cat_rowset[$i]['category_id']]['comments_forum_id'] = $cat_rowset[$i]['comments_forum_id'] == -1 ? ($kb_config['comments_forum_id'] == 1 ? true : false ) : ( $cat_rowset[$i]['comments_forum_id'] == 1 ? true : false ); // phpBB target forum (only used for phpBB comments)
			
			if (!$this->comments[$cat_rowset[$i]['category_id']]['internal_comments'] && intval($this->comments[$cat_rowset[$i]['category_id']]['comments_forum_id']) < 1)
			{
				// mx_message_die(GENERAL_ERROR, 'Init Failure, phpBB comments with no target forum_id :(');
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
			$this->notification[$cat_rowset[$i]['category_id']]['activated'] = $cat_rowset[$i]['notify'] == -1 ? (intval($kb_config['nofity'])) : ( intval($cat_rowset[$i]['nofity']) ); // -1, 0, 1, 2
			$this->notification[$cat_rowset[$i]['category_id']]['nofity_group'] = $cat_rowset[$i]['nofity_group'] == -1 ? (intval($kb_config['nofity_group'])) : ( intval($cat_rowset[$i]['nofity_group']) ); // Group_id
		
		}
					
		$this->sort_order = $kb_config['sort_order'];
		
		switch ( $kb_config['sort_method'] )
		{
			case 'Id':
				$this->sort_method = 't.article_id';
				$this->sort_method_extra = 't.article_type' . " DESC, " ;
				break;
			case 'Creation':
				$this->sort_method = 't.article_date';
				$this->sort_method_extra = 't.article_type' . " DESC, " ;
				break;
			case 'Latest':
				$this->sort_method = 't.article_date';
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
	} 

	// ===================================================
	// Clean up
	// ===================================================	
	function _kb()
	{
		$this->debug('mx_kb->_kb', basename( __FILE__ ));
		
		if ( $this->modified )
		{
			$this->sync_all();
		}
	}

	// ===================================================
	// Add debug message
	// ===================================================	
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
		

	// ===================================================
	// Display debug message
	// ===================================================	
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
			
	// ===================================================
	// Sync All
	// ===================================================	
	function sync_all()
	{
		$this->debug('mx_kb->sync_all', basename( __FILE__ ));
		
		foreach( $this->cat_rowset as $cat_id => $void )
		{
			$this->sync( $cat_id, false );
		}
		$this->init();
	}

	function sync( $cat_id, $init = true )
	{
		global $db;

		$this->debug('mx_kb->sync', basename( __FILE__ ));
				
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
		
	// ===================================================
	// if there is no cat
	// ===================================================
	function cat_empty()
	{
		$this->debug('mx_kb->cat_empty', basename( __FILE__ ));

		return ( $this->total_cat == 0 ) ? true : false;
	}

	function modified( $true_false = false )
	{
		$this->debug('mx_kb->modified', basename( __FILE__ ));
		
		$this->modified = $true_false;
	} 

	function article_in_cat( $cat_id )
	{
		$this->debug('mx_kb->article_in_cat', basename( __FILE__ ));
		
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

			$article_no = 0;
			if ( $row = $db->sql_fetchrow( $result ) )
			{
				$article_no = $row['total'];
			}

			$sql = 'UPDATE ' . KB_CATEGORIES_TABLE . "
					SET number_articles = $article_no
					WHERE category_id = $cat_id";

			if ( !( $result = $db->sql_query( $sql ) ) )
			{
				mx_message_die( GENERAL_ERROR, 'Couldnt Query Files info', '', __LINE__, __FILE__, $sql );
			}
		}
		else
		{
			$article_no = $this->cat_rowset[$cat_id]['number_articles'];
		}

		return $article_no;
	}
	
	// ===================================================
	// generate bottom category jumpbox
	// ===================================================	
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

	// ===================================================
	// 
	// ===================================================		
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
		
	// ===================================================
	// Quick stats
	// ===================================================
	function get_quick_stats( $category_id = '' )
	{
		global $db, $template, $lang, $kb_config;

		$this->debug('mx_kb->get_quick_stats', basename( __FILE__ ));
		
		$stats_list = '';
		
		$sql_stat = "SELECT * 
				FROM " . KB_TYPES_TABLE; 
	
		$sql_stat .= " ORDER BY type";
	
		if ( !( $result = $db->sql_query( $sql_stat ) ) )
		{
			mx_message_die( GENERAL_ERROR, "Error getting quick stats", '', __LINE__, __FILE__, $sql );
		}

		$ii = 0;
		while ( $type = $db->sql_fetchrow( $result ) )
		{
			$ii++;
			$type_id = $type['id'];
			$type_name = $type['type'];
	
			$sql = "SELECT article_id FROM " . KB_ARTICLES_TABLE . " 
				WHERE article_type = $type_id "; 
	
			if ( !empty( $category_id ) )
			{
				$sql .= " AND article_category_id = '$category_id'";
			}
	
			if ( !( $count = $db->sql_query( $sql ) ) )
			{
				mx_message_die( GENERAL_ERROR, "error getting quick stats", '', __LINE__, __FILE__, $sql );
			}
	
			$number_count = 0;
			$number_count = $db->sql_numrows( $count );
	
			if ( !empty( $category_id ) && $number_count > 0 )
			{
				$stats_list .= empty($stats_list) ? ':: ' . $type_name . '(' . $number_count . ') :: ' : $type_name . '(' . $number_count . ') ::' ;
			}
		}
		
		if (!empty($stats_list))
		{
			$template->assign_block_vars( 'switch_quick_stats', array( 
				'L_QUICK_STATS' => $lang['Quick_stats'], 
				'STATS' => $stats_list 
			));	
		}		
	} 
	
	// ===================================================
	// Get article type
	// ===================================================
	function get_kb_type( $id )
	{
		global $db;

		$this->debug('mx_kb->get_kb_type', basename( __FILE__ ));
		
		$sql = "SELECT type  
	       		FROM " . KB_TYPES_TABLE . " 
	      		WHERE id = '$id'";
	
		if ( !( $result = $db->sql_query( $sql ) ) )
		{
			mx_message_die( GENERAL_ERROR, "Could not obtain article type data", '', __LINE__, __FILE__, $sql );
		}
	
		if ( $row = $db->sql_fetchrow( $result ) )
		{
			$type = $row['type'];
		}
	
		return $type;
	}
	
	// ===================================================
	// generate_navigation($cat_id)
	// gets parents for category
	// ===================================================
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
	
		$temp_url = append_sid( this_kb_mxurl( 'mode=cat&amp;cat=' . $row['category_id'] ) );
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
	
	// ===================================================
	// Get all category articles
	// ===================================================
	function display_articles( $id = false, $approve, $block_name, $start = -1, $articles_in_cat = 0 )
	{
		global $db, $template, $images, $phpEx, $module_root_path, $phpbb_root_path, $mx_root_path, $board_config, $lang, $is_block, $page_id, $is_admin, $userdata;
		global $kb_config; 
		
		$this->debug('mx_kb->display_articles', basename( __FILE__ ));
		
		$sql = "SELECT t.*, u.user_id, u.user_rank, u.user_sig, u.user_sig_bbcode_uid, u.user_allowsmile
				FROM " . KB_ARTICLES_TABLE . " t, " . USERS_TABLE . " u 
				WHERE ";
	
		if ( $id )
		{
			$sql .= " t.article_category_id = " . $id . " AND";
		}
		$sql .= " u.user_id = t.article_author_id";
	
		if ( !$this->auth_user[$id]['auth_mod'] )
		{
			$sql .= " AND t.approved = " . $approve;
		} 
	
		if ( defined( 'IN_ADMIN' ) )
		{
			$sql .= " ORDER BY t.article_id";
		}
		else
		{
			$sql .= " ORDER BY " . $this->sort_method_extra . $this->sort_method . " " . $this->sort_order;
		}
		if ( $start > -1 && $articles_in_cat > 0 )
		{
			$sql .= " LIMIT $start, $articles_in_cat";
		}
	
		if ( !( $article_result = $db->sql_query( $sql ) ) )
		{
			mx_message_die( GENERAL_ERROR, "Could not obtain article data", '', __LINE__, __FILE__, $sql );
		}
	
		$i = 0;
	
		while ( $article = $db->sql_fetchrow( $article_result ) )
		{
			$i++;
			$article_description = $article['article_description'] ;
			$article_cat = $article['article_category_id'];
			$article_approved = $article['approved']; 
			// type
			$type_id = $article['article_type'];
			$article_type = $this->get_kb_type( $type_id );
	
			$article_date = create_date( $board_config['default_dateformat'], $article['article_date'], $board_config['board_timezone'] ); 
			// author information
			$author_id = $article['article_author_id'];
			if ( $author_id == -1 )
			{
				$author = ( $article['username'] == '' ) ? $lang['Guest'] : $article['username'];
			}
			else
			{
				$author_name = $this->get_kb_author( $author_id );
	
				$temp_url = append_sid( $phpbb_root_path . "profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$author_id" );
				$author = '<a href="' . $temp_url . '" class="gen">' . $author_name . '</a>';
			}
	
			$article_id = $article['article_id'];
			$views = $article['views'];
	
			$article_title = $article['article_title'];
			$temp_url = append_sid( this_kb_mxurl( "mode=article&amp;k=$article_id" ) );
			$article = '<a href="' . $temp_url . '" class="gen">' . $article_title . '</a>';
	
			$approve = '';
			$delete = '';
			$category_name = '';
			$category = $this->cat_rowset[$article_cat];
			$category_name = $category['category_name'];
				
			if ( defined( 'IN_ADMIN' ) )
			{
				if ( $article_approved == 2 || $article_approved == 0 )
				{ 
					// approve
					$temp_url = append_sid( $module_root_path . "admin/admin_kb_art.$phpEx?mode=approve&amp;a=$article_id&cat=$article_cat" . "&amp;start=" . $start);
					$approve = '<a href="' . $temp_url . '"><img src="' . $images['icon_approve'] . '" border="0" alt="' . $lang['Approve'] . '"></a>';
				}
				elseif ( $article_approved == 1 )
				{ 
					// unapprove
					$temp_url = append_sid( $module_root_path . "admin/admin_kb_art.$phpEx?mode=unapprove&amp;a=$article_id&cat=$article_cat" . "&amp;start=" . $start);
					$approve = '<a href="' . $temp_url . '"><img src="' . $images['icon_unapprove'] . '" border="0" alt="' . $lang['Un_approve'] . '"></a>';
				} 
				// delete
				$temp_url = append_sid( $module_root_path . "admin/admin_kb_art.$phpEx?mode=delete&amp;a=$article_id&cat=$article_cat" . "&amp;start=" . $start);
				$delete = '<a href="' . $temp_url . '"><img src="' . $phpbb_root_path . $images['icon_delpost'] . '" border="0" alt="' . $lang['Delete'] . '"></a>';
			}
			else
			{
				if ( $this->auth_user[$id]['auth_mod'] )
				{
					if ( $article_approved == 2 || $article_approved == 0 )
					{ 
						// approve
						$temp_url = append_sid( this_kb_mxurl( "mode=moderate&action=approve&amp;a=$article_id&cat=$article_cat&page=$page_id" . "&start=" . $start) );
						$approve = '<a href="' . $temp_url . '"><img src="' . $images['icon_approve'] . '" border="0" alt="' . $lang['Approve'] . '"></a>';
					}
					elseif ( $article_approved == 1 )
					{ 
						// unapprove
						$temp_url = append_sid( this_kb_mxurl( "mode=moderate&action=unapprove&amp;a=$article_id&cat=$article_cat&page=$page_id" . "&start=" . $start) );
						$approve = '<a href="' . $temp_url . '"><img src="' . $images['icon_unapprove'] . '" border="0" alt="' . $lang['Un_approve'] . '"></a>';
					} 
				}
				if ( $this->auth_user[$id]['auth_delete'] || $this->auth_user[$id]['auth_mod'])
				{
					// delete
					$temp_url = append_sid( this_kb_mxurl( "mode=moderate&action=delete&amp;a=$article_id&cat=$article_cat&page=$page_id" . "&start=" . $start) );
					$delete = '<a href="' . $temp_url . '"><img src="' . $phpbb_root_path . $images['icon_delpost'] . '" border="0" alt="' . $lang['Delete'] . '"></a>';
				}
			}
	
			if ( $article['article_rating'] == 0 || $article['article_totalvotes'] == 0 )
			{
				$rating = 0;
				$rating_votes = 0;
				$rating_message = '';
			}
			else
			{
				$rating = round( $postrow[$i]['link_rating'] / $postrow[$i]['link_totalvotes'], 2 );
				$rating_votes = $postrow[$i]['link_totalvotes'];
				$rating_message = '(' . $rating . '/10, </span><span class="gensmall">' . $rating_votes . ' votes)';
			} 
	
			$template->assign_block_vars( $block_name, array( 'ARTICLE' => $article ,
					'ARTICLE_DESCRIPTION' => $article_description,
					'ARTICLE_TYPE' => $article_type,
					'ARTICLE_DATE' => $article_date,
					'ARTICLE_AUTHOR' => $author,
					'CATEGORY' => $category_name,
					'ART_VIEWS' => $views,
					'ART_VOTES' => $rating_message,
					'U_APPROVE' => $approve,
					'U_DELETE' => $delete ) 
				);
		}  // end loop
		
		if ( $i == 0 )
		{
			$template->assign_block_vars( 'no_articles', array( 'COMMENT' => $lang['No_Articles'] ) );
		}
		
		return $i;
	}
	
	// ===================================================
	// Get Stats
	// ===================================================
	function display_stats( $type = false, $approve, $block_name, $start = -1, $articles_in_cat = 0 )
	{
		global $db, $template, $images, $phpEx, $module_root_path, $phpbb_root_path, $mx_root_path, $board_config, $lang, $is_block, $page_id, $is_admin, $userdata;

		$this->debug('mx_kb->display_stats', basename( __FILE__ ));
		
		$sql = "SELECT * FROM " . KB_ARTICLES_TABLE . " WHERE";
	
		$sql .= " approved = " . $approve;
	
		if ( $type )
		{
			if ( $type == 'toprated' )
			{
				$sql .= " AND article_totalvotes > 0";
				$sql .= " ORDER BY article_rating/article_totalvotes DESC";
			}elseif ( $type == 'latest' )
			{
				$sql .= " ORDER BY article_date DESC";
			}elseif ( $type == 'mostpopular' )
			{
				$sql .= " AND views > 0";
				$sql .= " ORDER BY views DESC";
			}
		}
	
		if ( $start > -1 && $articles_in_cat > 0 )
		{
			$sql .= " LIMIT $start, $articles_in_cat";
		}
			
		if ( !( $article_result = $db->sql_query( $sql ) ) )
		{
			mx_message_die( GENERAL_ERROR, "Could not obtain article data", '', __LINE__, __FILE__, $sql );
		}
	
		$i = 0;
	
		while ( $article = $db->sql_fetchrow( $article_result ) )
		{
			if ( $i == $articles_in_cat )
			{
				break;
			}
			
			$article_description = $article['article_description'];
			$article_cat = $article['article_category_id'];
			$article_approved = $article['approved']; 
			// type
			$type_id = $article['article_type'];
			$article_type = $this->get_kb_type( $type_id );
	
			$article_date = create_date( $board_config['default_dateformat'], $article['article_date'], $board_config['board_timezone'] ); 
			// author information
			$author_id = $article['article_author_id'];
			if ( $author_id == -1 )
			{
				$author = ( $article['username'] == '' ) ? $lang['Guest'] : $article['username'];
			}
			else
			{
				$author = $this->get_kb_author( $author_id );
	
				$temp_url = append_sid( $phpbb_root_path . "profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$author_id" );
				$author = '<a href="' . $temp_url . '" class="gen">' . $author . '</a>';
			}
	
			$article_id = $article['article_id'];
			$views = $article['views'];
	
			$article_title = $article['article_title'];
			$temp_url = append_sid( this_kb_mxurl( "mode=article&amp;k=$article_id" ) );
			$article = '<a href="' . $temp_url . '" class="gen">' . $article_title . '</a>';
	
			$approve = '';
			$delete = '';
			$category_name = '';
	
			//$category = $this->get_kb_cat( $article_cat );
			$category = $this->cat_rowset[$article_cat];
			$category_id = $category['category_id'];
			$category_name = $category['category_name'];
			$category_temp = append_sid( this_kb_mxurl( "mode=cat&amp;cat=$category_id" ) );
			$category_url = '<a href="' . $category_temp . '" class="genmed">' . $category_name . '</a>';
	
			if ( defined( 'IN_ADMIN' ) || $userdata['user_level'] == ADMIN )
			{
				//$category = $this->get_kb_cat( $article_cat );
				$category = $this->cat_rowset[$article_cat];
				$category_name = $category['category_name'];
	
				if ( $article_approved == 2 || $article_approved == 0 )
				{ 
					// approve
					$temp_url = append_sid( $module_root_path . "admin/admin_kb_art.$phpEx?mode=approve&amp;a=$article_id" );
					$approve = '<a href="' . $temp_url . '"><img src="' . $images['icon_approve'] . '" border="0" alt="' . $lang['Approve'] . '"></a>';
				}
				elseif ( $article_approved == 1 )
				{ 
					// unapprove
					$temp_url = append_sid( $module_root_path . "admin/admin_kb_art.$phpEx?mode=unapprove&amp;a=$article_id" );
					$approve = '<a href="' . $temp_url . '"><img src="' . $images['icon_unapprove'] . '" border="0" alt="' . $lang['Un_approve'] . '"></a>';
				} 
				// delete
				$temp_url = append_sid( $module_root_path . "admin/admin_kb_art.$phpEx?mode=delete&amp;a=$article_id" );
				$delete = '<a href="' . $temp_url . '"><img src="' . $phpbb_root_path . $images['icon_delpost'] . '" border="0" alt="' . $lang['Delete'] . '"></a>';
			}
	
			if ( $article['article_rating'] == 0 || $article['article_totalvotes'] == 0 )
			{
				$rating = 0;
				$rating_votes = 0;
				$rating_message = '';
			}
			else
			{
				$rating = round( $postrow[$i]['link_rating'] / $postrow[$i]['link_totalvotes'], 2 );
				$rating_votes = $postrow[$i]['link_totalvotes'];
				$rating_message = '(' . $rating . '/10, </span><span class="gensmall">' . $rating_votes . ' votes)';
			}
	
			if ( $this->ns_auth_cat( $article_cat ) && $this->auth_user[$article_cat]['auth_view'] )
			{
				$i++;
				$template->assign_block_vars( $block_name, array( 'ARTICLE' => $article ,
						'ARTICLE_DESCRIPTION' => $article_description,
						'ARTICLE_TYPE' => $article_type,
						'ARTICLE_DATE' => $article_date,
						'ARTICLE_AUTHOR' => $author,
						'CATEGORY' => $category_url,
						'ART_VIEWS' => $views,
						'ART_VOTES' => $rating_message,
	
						'U_APPROVE' => $approve,
						'U_DELETE' => $delete ) 
					);
			}
		}
		if ( $i == 0 )
		{
			$template->assign_block_vars( 'no_articles', array( 'COMMENT' => $lang['No_Articles'] ) );
		}
	
	}
	
	// ===================================================
	// Update category 'number of articles'
	// ===================================================
	function update_kb_number( $category_id, $change = 0 )
	{
		global $db, $kb_config; 

		$this->debug('mx_kb->update_kb_number', basename( __FILE__ ));
		
		//
		// Get number of articles in this category
		//
		$sql = "SELECT count(article_id) AS total
			FROM " . KB_ARTICLES_TABLE . "
			WHERE "; 
		
		$sql .= " article_category_id = '$category_id' AND approved = '1'";
	
		if ( !( $result = $db->sql_query( $sql ) ) )
		{
			mx_message_die( GENERAL_ERROR, 'Error getting total articles', '', __LINE__, __FILE__, $sql );
		}
	
		if ( $total = $db->sql_fetchrow( $result ) )
		{
			//
			// Define number of articles in this cateogry + subcats
			//
			$total_articles = $total['total'] + $change;
		}
	
		//
		// Get some category data
		//
		$sql = "SELECT * FROM " . KB_CATEGORIES_TABLE . " WHERE category_id = '" . $category_id . "'";
		
		if ( !( $results = $db->sql_query( $sql ) ) )
		{
			mx_message_die( GENERAL_ERROR, "Could not obtain article data", '', __LINE__, __FILE__, $sql );
		}
		
		if ( $kb_cat = $db->sql_fetchrow( $results ) )
		{
			$parent_id = $kb_cat['parent'];
		}
	
		//
		// update number of articles in category if article has been approve
		//
		$sql = "UPDATE " . KB_CATEGORIES_TABLE . " SET number_articles = " . $total_articles . " WHERE category_id = '" . $category_id . "'";
	
		if ( !( $result = $db->sql_query( $sql ) ) )
		{
			mx_message_die( GENERAL_ERROR, "Could not update category data", '', __LINE__, __FILE__, $sql );
		}
	
		if ( $parent_id != '0' )
		{
			$this->update_kb_number( $parent_id, $total_articles );
		}
	
		return;
	}
	
	// ===================================================
	// Get Index
	// ===================================================	
	function display_categories( $parent = 0)
	{
		global $db, $template, $lang, $phpbb_root_path, $mx_root_path, $module_root_path, $phpEx, $is_block, $page_id, $kb_config, $userdata, $images, $kb_quick_nav;

		$this->debug('mx_kb->display_categories', basename( __FILE__ ));
		
		foreach( $this->subcat_rowset[$parent] as $category_id => $category )
		{ 
			$category_articles = $this->article_in_cat($category_id);
			$category_details = $category['category_details'];
	
			$category_name = $category['category_name'];
			$temp_url = append_sid( this_kb_mxurl( "mode=cat&amp;cat=$category_id" ) );
			$category = '<a href="' . $temp_url . '" class="forumlink">' . $category_name . '</a>'; 
			
			$show_sub_categories = false;
			//
			// Auth
			//
			if ( $this->ns_auth_cat( $category_id ) && $this->auth_user[$category_id]['auth_view'])
			{
				$show_sub_categories = true;
				
				$template->assign_block_vars( 'catrow', array( 
					'CATEGORY' => $category,
					'CAT_DESCRIPTION' => $category_details,
					'CAT_ARTICLES' => $category_articles,
					'CAT_IMAGE' => $phpbb_root_path . $images['folder'],
					'SUB_CAT_LIST' => $sub_cat_list,
					'L_SUB_CAT' => $lang['Sub_categories']
				));
				
				//
				// Get subcats
				//
				$sub_cat_list = '';
				if ( isset( $this->subcat_rowset[$category_id] ) )
				{
					foreach( $this->subcat_rowset[$category_id] as $sub_cat_id => $sub_cat_row )
					{
						if ( $this->auth_user[$sub_cat_id]['auth_view'] )
						{
							$temp_url = append_sid( this_kb_mxurl( 'mode=cat&amp;cat=' . $sub_cat_id ) );
							$sub_cat_list .= ( empty($sub_cat_list) ? '&nbsp;' : ',&nbsp;') . '<a href="' . $temp_url . '" class="nav">' . $sub_cat_row['category_name'] . '</a>';
						}
					}
					
					if (!empty($sub_cat_list))
					{
						$template->assign_block_vars( 'catrow.sub', array( 
							'SUB_CAT_LIST' => $sub_cat_list,
						));	
					}
				}
			}
		}

		if ($show_sub_categories)
		{
			$template->assign_block_vars( 'show_subs', array());	
		}		
	}

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
		
	// ===================================================
	// Get article author
	// ===================================================
	function get_kb_author( $id, $get_all_userdata = false )
	{
		global $db;
	
		$this->debug('mx_kb->get_kb_author', basename( __FILE__ ));
		
		$sql = "SELECT *  
	       		FROM " . USERS_TABLE . " 
	      		WHERE user_id = $id";
	
		if ( !( $result = $db->sql_query( $sql ) ) )
		{
			mx_message_die( GENERAL_ERROR, "Could not obtain author data", '', __LINE__, __FILE__, $sql );
		}
	
		if ( $row = $db->sql_fetchrow( $result ) )
		{
			if ( $get_all_userdata )
			{
				$name = $row;
			}
			else 
			{ 
				$name = $row['username'];
			}
		}
		else
		{
			$name = '';
		}
	
		return $name;
	}	
		
	// ===================================================
	// get type list for adding and editing articles
	// ===================================================	
	function get_kb_type_list( $sel_id )
	{
		global $db, $template;
	
		$this->debug('mx_kb->get_kb_type_list', basename( __FILE__ ));
		
		$sql = "SELECT *  
	       	FROM " . KB_TYPES_TABLE;
	
		if ( !( $type_result = $db->sql_query( $sql ) ) )
		{
			mx_message_die( GENERAL_ERROR, "Could not obtain category information", '', __LINE__, __FILE__, $sql );
		}
	
		while ( $type = $db->sql_fetchrow( $type_result ) )
		{
			$type_name = $type['type'];
			$type_id = $type['id'];
	
			if ( $sel_id == $type_id )
			{
				$status = 'selected';
			}
			else
			{
				$status = '';
			}
	
			$type = '<option value="' . $type_id . '" ' . $status . '>' . $type_name . '</option>';
	
			$template->assign_block_vars( 'types', array( 
				'TYPE' => $type ) 
			);
		}
	}
	
	// ===================================================
	// 
	// ===================================================	
	function article_formatting( $article )
	{
		$this->debug('mx_kb->article_formatting', basename( __FILE__ ));
		
		// Prepare ingress/preword
		$search = array ();
		$replace = array ();
		
		$search = array ( "'\[title*?[^\[\]]*?\]'si",
			"'\[\/title*?[^\[\]]*?\]'si",
			"'\[subtitle*?[^\[\]]*?\]'si",
			"'\[\/subtitle*?[^\[\]]*?\]'si",
			"'\[subsubtitle*?[^\[\]]*?\]'si",
			"'\[\/subsubtitle*?[^\[\]]*?\]'si",
			"'\[quote*?[^\[\]]*?\]'si",
			"'\[\/quote*?[^\[\]]*?\]'si",
			"'\[abstract*?[^\[\]]*?\]'si",
			"'\[\/abstract*?[^\[\]]*?\]'si" );
			
		$replace = array ( "<span class=\"cattitle\">",
			"</span>",
			"<span class=\"topictitle\">",
			"</span>",
			"<span class=\"gensmall\"><b>",
			"</b></span>",
			"<div align=\"center\"><span class=\"gensmall\"><i>''",
			"''</i></span></div>",
			"<table cellpadding=\"20\" style=\"margin-bottom: -20px;\"><tr><td><span class=\"postbody\" style=\"font-weight: bold; font-size: 9pt;\">",
			"</span></td></td></tr></table>" );
			
		$article = preg_replace( $search, $replace, $article );
		
		return $article;
	}	
	
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
		$this->auth_can_list .= ( ( $this->comments[$category_id]['activated'] ? (( $this->auth_user[$category_id]['auth_comment'] ? $lang['KB_Rules_comment_can'] : $lang['KB_Rules_comment_cannot'] ) . '<br />') : '' ));
		$this->auth_can_list .= ( ( $this->ratings[$category_id]['activated'] ? (( $this->auth_user[$category_id]['auth_rate'] ? $lang['KB_Rules_rate_can'] : $lang['KB_Rules_rate_cannot'] ) . '<br />') : '' ));
		$this->auth_can_list .= ( ( $this->auth_user[$category_id]['auth_approval'] ) ? $lang['KB_Rules_approval_can'] : $lang['KB_Rules_approval_cannot'] ) . '<br />';
		$this->auth_can_list .= ( ( $this->auth_user[$category_id]['auth_approval_edit'] ) ? $lang['KB_Rules_approval_edit_can'] : $lang['KB_Rules_approval_edit_cannot'] ) . '<br />';
		
		if ( $this->auth_user[$category_id]['auth_mod'] )
		{
			$this->auth_can_list .= $lang['KB_Rules_moderate_can'];
		}		
	}
	
	function get_cat_id($article_id)
	{
		global $db;
		
		$this->debug('mx_kb->get_cat_id', basename( __FILE__ ));
		
		$sql = "SELECT article_category_id
			  FROM " . KB_ARTICLES_TABLE . "
			  WHERE article_id = $article_id";
				
		if ( !( $result = $db->sql_query( $sql ) ) )
		{
			mx_message_die( GENERAL_ERROR, "Could not obtain article data", '', __LINE__, __FILE__, $sql );
		}
		
		$kb_row = $db->sql_fetchrow( $result );
		$category_id = $kb_row['article_category_id'];	
		
		return $category_id;
	}
}

//
// For old php versions...
//
if ( !function_exists( 'html_entity_decode' ) )
{
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
}

//
// Just to be safe ;o)
//
if ( !defined( "ENT_COMPAT" ) ) define( "ENT_COMPAT", 2 );
if ( !defined( "ENT_NOQUOTES" ) ) define( "ENT_NOQUOTES", 0 );
if ( !defined( "ENT_QUOTES" ) ) define( "ENT_QUOTES", 3 );

?>