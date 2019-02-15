<?php
/**
*
* @package MX-Publisher Module - mx_kb
* @version $Id: functions.php,v 1.25 2008/07/15 22:05:43 jonohlsson Exp $
* @copyright (c) 2002-2006 [wGEric, Jon Ohlsson] MX-Publisher Project Team
* @license http://opensource.org/licenses/gpl-license.php GNU General Public License v2
*
*/

if ( !defined( 'IN_PORTAL' ) )
{
	die( "Hacking attempt" );
}

/**
 * mx_kb_functions.
 *
 * This class is used for general kb handling
 *
 * @access public
 * @author Jon Ohlsson
 *
 */
class mx_kb_functions
{
	/**
	 * This class is used for general kb handling
	 *
	 * @param unknown_type $config_name
	 * @param unknown_type $config_value
	 */
	function set_config( $config_name, $config_value )
	{
		global $mx_kb_cache, $kb_config, $db, $mx_kb;

		$mx_kb->debug('functions->set_config', basename( __FILE__ ));

		$sql = "UPDATE " . KB_CONFIG_TABLE . " SET
			config_value = '" . str_replace( "\'", "''", $config_value ) . "'
			WHERE config_name = '$config_name'";

		if ( !$db->sql_query( $sql ) )
		{
			mx_message_die( GENERAL_ERROR, "Failed to update kb configuration for $config_name", "", __LINE__, __FILE__, $sql );
		}

		if ( !$db->sql_affectedrows() && !isset( $kb_config[$config_name] ) )
		{
			$sql = 'INSERT INTO ' . KB_CONFIG_TABLE . " (config_name, config_value)
				VALUES ('$config_name', '" . str_replace( "\'", "''", $config_value ) . "')";

			if ( !$db->sql_query( $sql ) )
			{
				mx_message_die( GENERAL_ERROR, "Failed to update kb configuration for $config_name", "", __LINE__, __FILE__, $sql );
			}
		}

		$kb_config[$config_name] = $config_value;
		$mx_kb_cache->put( 'config', $kb_config );
	}

	/**
	 * Enter description here...
	 *
	 * @return unknown
	 */
	function kb_config()
	{
		global $db;

		$sql = "SELECT *
			FROM " . KB_CONFIG_TABLE;

		if ( !( $result = $db->sql_query( $sql ) ) )
		{
			mx_message_die( GENERAL_ERROR, 'Couldnt query kb configuration', '', __LINE__, __FILE__, $sql );
		}

		while ( $row = $db->sql_fetchrow( $result ) )
		{
			$kb_config[$row['config_name']] = trim( $row['config_value'] );
		}

		$db->sql_freeresult( $result );

		return ( $kb_config );
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $mode
	 * @param unknown_type $page_id
	 */
	/*
	function generate_smilies( $mode, $page_id )
	{
		global $db, $board_config, $template, $lang, $images, $theme, $phpEx, $phpbb_root_path;
		global $user_ip, $session_length, $starttime;
		global $userdata, $mx_user, $mx_kb;
		global $mx_root_path, $module_root_path, $is_block, $phpEx;

		$mx_kb->debug('functions->generate smilies', basename( __FILE__ ));

		$inline_columns = 4;
		$inline_rows = 5;
		$window_columns = 8;

		if ( $mode == 'window' )
		{
			if ( !MXBB_MODULE )
			{
				$userdata = session_pagestart( $user_ip, $page_id );
				init_userprefs( $userdata );
			}
			else
			{
				$mx_user->init($user_ip, PAGE_INDEX);
			}

			$gen_simple_header = true;

			$page_title = $lang['Review_topic'] . " - $topic_title";

			include( $mx_root_path . 'includes/page_header.' . $phpEx );

			$template->set_filenames( array( 'smiliesbody' => 'posting_smilies.tpl' ) );
		}

		$sql = "SELECT emoticon, code, smile_url
			FROM " . SMILIES_TABLE . "
			ORDER BY smilies_id";
		if ( $result = $db->sql_query( $sql ) )
		{
			$num_smilies = 0;
			$rowset = array();
			while ( $row = $db->sql_fetchrow( $result ) )
			{
				if ( empty( $rowset[$row['smile_url']] ) )
				{
					$rowset[$row['smile_url']]['code'] = str_replace( "'", "\\'", str_replace( '\\', '\\\\', $row['code'] ) );
					$rowset[$row['smile_url']]['emoticon'] = $row['emoticon'];
					$num_smilies++;
				}
			}

			if ( $num_smilies )
			{
				$smilies_count = ( $mode == 'inline' ) ? min( 19, $num_smilies ) : $num_smilies;
				$smilies_split_row = ( $mode == 'inline' ) ? $inline_columns - 1 : $window_columns - 1;

				$s_colspan = 0;
				$row = 0;
				$col = 0;

				while ( list( $smile_url, $data ) = @each( $rowset ) )
				{
					if ( !$col )
					{
						$template->assign_block_vars( 'smilies_row', array() );
					}

					$template->assign_block_vars( 'smilies_row.smilies_col', array(
						'SMILEY_CODE' => $data['code'],
						'SMILEY_IMG' => $phpbb_root_path . $board_config['smilies_path'] . '/' . $smile_url,
						'SMILEY_DESC' => $data['emoticon'] )
					);

					$s_colspan = max( $s_colspan, $col + 1 );

					if ( $col == $smilies_split_row )
					{
						if ( $mode == 'inline' && $row == $inline_rows - 1 )
						{
							break;
						}
						$col = 0;
						$row++;
					}
					else
					{
						$col++;
					}
				}

				if ( $mode == 'inline' && $num_smilies > $inline_rows * $inline_columns )
				{
					$template->assign_block_vars( 'switch_smilies_extra', array() );

					$template->assign_vars( array(
						'L_MORE_SMILIES' => $lang['More_emoticons'],
						'U_MORE_SMILIES' => mx_append_sid( $phpbb_root_path . "posting.$phpEx?mode=smilies" ) )
					);
				}

				$template->assign_vars( array(
					'L_EMOTICONS' => $lang['Emoticons'],
					'L_CLOSE_WINDOW' => $lang['Close_window'],
					'S_SMILIES_COLSPAN' => $s_colspan )
				);
			}
		}

		if ( $mode == 'window' )
		{
			$template->pparse( 'smiliesbody' );
			include( $mx_root_path . 'includes/page_tail.' . $phpEx );
		}
	}
	*/

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $query
	 * @param unknown_type $total
	 * @param unknown_type $offset
	 * @return unknown
	 */
	function sql_query_limit( $query, $total, $offset = 0, $sql_cache = false )
	{
		global $db;

		$query .= ' LIMIT ' . ( ( !empty( $offset ) ) ? $offset . ', ' . $total : $total );
		return $sql_cache ? $db->sql_query( $query, $sql_cache ) : $db->sql_query( $query );
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $link_id
	 * @param unknown_type $link_rating
	 * @return unknown
	 */
	function get_rating( $article_id, $article_rating = '' )
	{
		global $db, $lang;

		$sql = "SELECT AVG(rate_point) AS rating
			FROM " . KB_VOTES_TABLE . "
			WHERE votes_article = '" . $article_id . "'";

		if ( !( $result = $db->sql_query( $sql ) ) )
		{
			mx_message_die( GENERAL_ERROR, 'Couldnt rating info for the giving article', '', __LINE__, __FILE__, $sql );
		}

		$row = $db->sql_fetchrow( $result );
		$db->sql_freeresult( $result );
		$link_rating = $row['rating'];

		return ( $link_rating != 0 ) ? round( $link_rating, 2 ) . ' / 10' : $lang['Not_rated'];
	}

	/**
	 * page header.
	 *
	 */
	function page_header( )
	{
		global $kb_config, $lang, $userdata, $images, $mode;
		global $mx_kb;
		global $template, $db, $theme, $gen_simple_header, $starttime, $phpEx, $board_config, $user_ip, $phpbb_root_path;
		global $admin_level, $level_prior, $tree, $do_gzip_compress;
		global $mx_root_path, $module_root_path, $is_block, $phpEx, $title;

		$mx_kb->debug('kb_page_header', basename( __FILE__ ));

		$template->set_filenames( array( 'kb_header' => 'kb_header.tpl' ) );

		if ( $mode == 'cat' )
		{
			if ( $mx_kb->modules[$mx_kb->module_name]->auth_user[$_REQUEST['cat']]['auth_post'] || $mx_kb->modules[$mx_kb->module_name]->auth_user[$_REQUEST['cat']]['auth_mod']  )
			{
				$add_article_url = mx_append_sid( $mx_kb->this_mxurl( "mode=add&cat=" . $_REQUEST['cat'] ) );
				$template->assign_block_vars( 'switch_add_article', array() );
			}
			$mcp_url = mx_append_sid( $mx_kb->this_mxurl( "mode=mcp&cat_id={$_REQUEST['cat']}" ) );
			if ( $mx_kb->modules[$mx_kb->module_name]->auth_user[$_REQUEST['cat']]['auth_mod'] )
			{
				$template->assign_block_vars( 'MCP', array() );
			}
		}

		$search_url = mx_append_sid( $mx_kb->this_mxurl( "mode=search" ) );

		if ( $kb_config['header_banner'] == 1 )
		{
			$temp_url = mx_append_sid( $mx_kb->this_mxurl() );
			$block_title = '<td align="center" class="row1"><a href="' . $temp_url . '"><img src="' . $images['kb_title'] . '" width="285" height="45" border="0" alt="' . $title . '"></a></td>';
		}
		else
		{
			$block_title = MXBB_MODULE ? '' : '<td align="center"><b>' . $lang['KB_title'] . '</b></td>';
		}

		$template->assign_vars( array(
			'L_KB_TITLE' => $block_title,
			'L_ADD_ARTICLE' => $lang['Add_article'],
			'L_MCP' => $lang['MCP_title'],
			'L_SEARCH' => $lang['Search'],
			'SPACER_IMG' => $images['mx_spacer'],

			'U_ADD_ARTICLE' => $add_article_url,
			'U_MCP' => $mcp_url,
			'U_SEARCH' => $search_url,
			'U_TOPRATED' => mx_append_sid( $mx_kb->this_mxurl( "mode=stats&amp;sort_method=Toprated&amp;sort_order=DESC" ) ),
			'L_TOPRATED' => $lang['Top_toprated'],
			'U_MOST_POPULAR' => mx_append_sid( $mx_kb->this_mxurl( "mode=stats&amp;sort_method=Most_popular&amp;sort_order=DESC" ) ),
			'L_MOST_POPULAR' => $lang['Top_most_popular'],
			'U_LATEST' => mx_append_sid( $mx_kb->this_mxurl( "mode=stats&amp;sort_method=Latest&amp;sort_order=DESC" ) ),
			'L_LATEST' => $lang['Top_latest']
		));

		//
		// Ratings enabled for any category ?
		//
		if ( !empty( $mx_kb->modules[$mx_kb->module_name]->cat_rowset ) )
		{
			foreach( $mx_kb->modules[$mx_kb->module_name]->cat_rowset as $cat_id => $cat_row )
			{
				if ( $mx_kb->modules[$mx_kb->module_name]->ratings[$cat_id]['activated'] )
				{
					$template->assign_block_vars( 'switch_toprated', array() );
					break;
				}
			}
		}

		if ( $kb_config['stats_list'] == 1 )
		{
			$this->get_quick_stats( $_REQUEST['cat'] );
		}

		$template->pparse( 'kb_header' );
	}

	/**
	 * page footer.
	 *
	 */
	function page_footer()
	{
		global $lang, $board_config, $userdata, $phpbb_root_path, $mx_root_path, $module_root_path, $is_block, $phpEx, $page_id;
		global $mx_kb_cache, $mx_kb;
		global $phpEx, $template, $do_gzip_compress, $debug, $db, $starttime;
		global $kb_module_version, $kb_module_orig_author, $kb_module_author;

		$mx_kb->debug('kb_page_footer', basename( __FILE__ ));

		$template->set_filenames( array( 'kb_footer' => 'kb_footer.tpl' ) );

		if ( !empty($mx_kb->modules[$mx_kb->module_name]->auth_can_list) )
		{
			$template->assign_block_vars( 'auth_can_list', array() );
		}

		if ( !empty($mx_kb->modules[$mx_kb->module_name]->jumpbox) )
		{
			$template->assign_block_vars( 'jumpbox', array() );
		}

		if ( !MXBB_MODULE || MXBB_27x )
		{
			$template->assign_block_vars( 'copy_footer', array() );
		}

		$s_hidden_vars = '<input type="hidden" name="mode" value="cat"><input type="hidden" name="page" value="' . $page_id . '">';

		//
		// Generate debug message
		//
		$debug_message_top = $mx_kb->display_debug();
		$debug_message_module = $mx_kb->modules[$mx_kb->module_name]->display_debug();
		$debug_message = $debug_message_top . '<br><br>' . $debug_message_module;

		$template->assign_vars( array(
			'L_QUICK_GO' => $lang['Quick_go'],
			'L_QUICK_NAV' => $lang['Quick_nav'],
			'L_QUICK_JUMP' => $lang['Quick_jump'],
			'QUICK_NAV' => $mx_kb->modules[$mx_kb->module_name]->jumpbox,
			'QUICK_JUMP_ACTION' => $mx_kb->this_mxurl(),

			'S_HIDDEN_VARS' => $s_hidden_vars,

			'S_AUTH_LIST' => $mx_kb->modules[$mx_kb->module_name]->auth_can_list,

			'L_MODULE_VERSION' => $kb_module_version,
			'L_MODULE_ORIG_AUTHOR' => $kb_module_orig_author,
			'L_MODULE_AUTHOR' => $kb_module_author,
			'DEBUG'			=> !empty($debug_message) && $mx_kb->debug ? '<div style="overflow:auto; height:100px;"><span class="gensmall">' . $debug_message  . '<br/> -::-</span></div>': '',
		));

		$template->pparse( 'kb_footer' );

		$mx_kb->modules[$mx_kb->module_name]->_kb();

		$mx_kb_cache->unload();
	}

	/**
	 * Quick stats.
	 *
	 * @param unknown_type $category_id
	 */
	function get_quick_stats( $category_id = '' )
	{
		global $db, $template, $lang, $kb_config;

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
			$type_name = isset($lang['KB_type_' . $type['type']]) ? $lang['KB_type_' . $type['type']] : $type['type'];

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
				$stats_list .= empty($stats_list) ? $type_name . '(' . $number_count . ')&nbsp' : '&bull;&nbsp;' . $type_name . '(' . $number_count . ')&nbsp' ;
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

	/**
	 * get type list for adding and editing articles.
	 *
	 * @param unknown_type $sel_id
	 */
	function get_kb_type_list( $sel_id )
	{
		global $db, $template;

		$sql = "SELECT *
	       	FROM " . KB_TYPES_TABLE;

		if ( !( $type_result = $db->sql_query( $sql ) ) )
		{
			mx_message_die( GENERAL_ERROR, "Could not obtain category information", '', __LINE__, __FILE__, $sql );
		}

		while ( $type = $db->sql_fetchrow( $type_result ) )
		{
			$type_name = isset($lang['KB_type_' . $type['type']]) ? $lang['KB_type_' . $type['type']] : $type['type'];
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

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $article
	 * @return unknown
	 */
	function article_formatting( $article )
	{
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
}

/**
 * mx_user_info
 *
 * This class is used to determin Browser and operating system info of the user
 *
 * @access public
 * @author http://www.chipchapin.com
 * @copyright (c) 2002 Chip Chapin <cchapin@chipchapin.com>
 */
class mx_user_info
{
	var $agent = 'unknown';
	var $ver = 0;
	var $majorver = 0;
	var $minorver = 0;
	var $platform = 'unknown';

	/**
	 * Constructor.
	 *
	 * Determine client browser type, version and platform using heuristic examination of user agent string.
	 *
	 * @param unknown_type $user_agent allows override of user agent string for testing.
	 */
	function mx_user_info( $user_agent = '' )
	{
		global $_SERVER, $HTTP_USER_AGENT, $HTTP_SERVER_VARS;

		if ( !empty( $_SERVER['HTTP_USER_AGENT'] ) )
		{
			$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
		}
		else if ( !empty( $HTTP_SERVER_VARS['HTTP_USER_AGENT'] ) )
		{
			$HTTP_USER_AGENT = $HTTP_SERVER_VARS['HTTP_USER_AGENT'];
		}
		else if ( !isset( $HTTP_USER_AGENT ) )
		{
			$HTTP_USER_AGENT = '';
		}

		if ( empty( $user_agent ) )
		{
			$user_agent = $HTTP_USER_AGENT;
		}

		$user_agent = strtolower( $user_agent );
		// Determine browser and version
		// The order in which we test the agents patterns is important
		// Intentionally ignore Konquerer.  It should show up as Mozilla.
		// post-Netscape Mozilla versions using Gecko show up as Mozilla 5.0
		// known browsers, list will be updated routinely, check back now and then
		if ( preg_match( '/(opera |opera\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(msie )([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(mozilla\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(phoenix\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(firebird\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(omniweb |omni\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(konqueror |konq\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(safari |saf\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		// covers Netscape 6-7, K-Meleon, Most linux versions, uses moz array below
		elseif ( preg_match( '/(gecko |moz\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(netpositive |netp\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(lynx |lynx\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(elinks |elinks\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(links |links\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(w3m |w3m\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(webtv |webtv\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(amaya |amaya\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(dillo |dillo\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(ibrowsevibrowse |ibrowsevibrowse\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(icab |icab\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(crazy browser |ie\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(sonyericssonp800 |sonyericssonp800\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(aol )([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(camino )([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		// search engine spider bots:
		elseif ( preg_match( '/(googlebot |google\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(mediapartners-google |adsense\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(yahoo-verticalcrawler |yahoo\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(yahoo! slurp |yahoo\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(yahoo-mm |yahoomm\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(inktomi |inktomi\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(slurp |inktomi\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(fast-webcrawler |fast\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(msnbot |msn\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(ask jeeves |ask\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(teoma |ask\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(scooter |scooter\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(openbot |openbot\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(ia_archiver |ia_archiver\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(zyborg |looksmart\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(almaden |ibm\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(baiduspider |baidu\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(psbot |psbot\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(gigabot |gigabot\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(naverbot |naverbot\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(surveybot |surveybot\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(boitho.com-dc |boitho\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(objectssearch |objectsearch\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(answerbus |answerbus\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(sohu-search |sohu\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(iltrovatore-setaccio |il-set\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		// various http utility libaries
		elseif ( preg_match( '/(w3c_validator |w3c\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(wdg_validator |wdg\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(libwww-perl |libwww-perl\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(jakarta commons-httpclient |jakarta\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(python-urllib |python-urllib\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		// download apps
		elseif ( preg_match( '/(getright |getright\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		elseif ( preg_match( '/(wget |wget\/)([0-9]*).([0-9]{1,2})/', $user_agent, $matches ) ) ;
		else
		{
			$matches[1] = 'unknown';
			$matches[2] = 0;
			$matches[3] = 0;
		}

		$this->majorver = $matches[2];
		$this->minorver = $matches[3];
		$this->ver = $matches[2] . '.' . $matches[3];

		switch ( $matches[1] )
		{
			case 'opera/':
			case 'opera ':
				$this->agent = 'OPERA';
				break;

			case 'msie ':
				$this->agent = 'IE';
				break;

			case 'mozilla/':
				$this->agent = 'NETSCAPE';
				if ( $this->majorver >= 5 )
				{
					$this->agent = 'MOZILLA';
				}
				break;

 			case 'phoenix ':
 			case 'firebird ':
				$this->agent = 'MOZILLA';
				break;

			case 'konqueror ':
			case 'konq ':
				$this->agent = 'KONQUEROR';
				break;

			case 'lynx/':
			case 'lynx ':
				$this->agent = 'LYNX';
				break;

			case 'safari ':
			case 'saf ':
				$this->agent = 'SAFARI';
				break;

			case 'aol/':
			case 'aol ':
				$this->agent = 'AOL';
				break;

			case 'omniweb':
			case 'omni ':
				$this->agent = 'OTHER';
				break;

			case 'gecko ':
 			case 'moz ':
				$this->agent = 'OTHER';
				break;

			case 'netpositive ':
			case 'netp ':
				$this->agent = 'OTHER';
				break;

			case 'elinks/':
			case 'elinks ':
				$this->agent = 'OTHER';
				break;

			case 'links/':
			case 'links ':
				$this->agent = 'OTHER';
				break;

			case 'w3m/':
			case 'w3m ':
				$this->agent = 'OTHER';
				break;

			case 'webtv/':
			case 'webtv ':
				$this->agent = 'OTHER';
				break;

			case 'amaya/':
			case 'amaya ':
				$this->agent = 'OTHER';
				break;

			case 'dillo/':
			case 'dillo ':
				$this->agent = 'OTHER';
				break;

			case 'ibrowsevibrowse/':
			case 'ibrowsevibrowse ':
				$this->agent = 'OTHER';
				break;

			case 'icab/':
			case 'icab ':
				$this->agent = 'OTHER';
				break;

			case 'crazy browser ':
			case 'ie ':
				$this->agent = 'OTHER';
				break;

			case 'camino/ ':
			case 'camino ':
				$this->agent = 'OTHER';
				break;

			case 'sonyericssonp800/':
			case 'sonyericssonp800 ':
				$this->agent = 'OTHER';
				break;

			case 'googlebot ':
			case 'google ':
			case 'mediapartners-google ':
			case 'adsense ':
			case 'yahoo-verticalcrawler ':
			case 'yahoo ':
			case 'yahoo! slurp ':
			case 'yahoo-mm ':
			case 'yahoomm ':
			case 'inktomi ':
			case 'slurp ':
			case 'fast-webcrawler ':
			case 'msnbot ':
			case 'msn ':
			case 'ask jeeves ':
			case 'ask ':
			case 'teoma ':
			case 'scooter ':
			case 'openbot ':
			case 'ia_archiver ':
			case 'zyborg ':
			case 'looksmart ':
			case 'almaden ':
			case 'baiduspider ':
			case 'baidu ':
			case 'psbot ':
			case 'gigabot ':
			case 'naverbot ':
			case 'surveybot ':
			case 'boitho.com-dc ':
			case 'boitho ':
			case 'objectssearch ':
			case 'answerbus ':
			case 'sohu-search ':
			case 'sohu ':
			case 'iltrovatore-setaccio ':
			case 'il-set ':
				$this->agent = 'BOT';
				break;

			case 'unknown':
				$this->agent = 'OTHER';
				break;

			default:
				$this->agent = 'Oops!';
		}

		// Determine platform
		// This is very incomplete for platforms other than Win/Mac
		if ( preg_match( '/(win|mac|linux|unix|x11|freebsd|beos|os2|irix|sunos|aix)/', $user_agent, $matches ) );
		else $matches[1] = 'unknown';

		switch ( $matches[1] )
		{
			case 'win':
				$this->platform = 'Win';
				break;

			case 'mac':
				$this->platform = 'Mac';
				break;

			case 'linux':
				$this->platform = 'Linux';
				break;

			case 'unix':
			case 'x11':
				$this->platform = 'Unix';
				break;

			case 'freebsd':
				$this->platform = 'FreeBSD';
				break;

			case 'beos':
				$this->platform = 'BeOS';
				break;

			case 'os2':
				$this->platform = 'OS2';
				break;

            case 'irix':
				$this->platform = 'IRIX';
				break;

            case 'sunos':
				$this->platform = 'SunOS';
				break;

            case 'aix':
				$this->platform = 'Aix';
				break;

            case 'palm':
				$this->platform = 'PalmOS';
				break;

			case 'unknown':
				$this->platform = 'Other';
				break;

			default:
				$this->platform = 'Oops!';
		}
	}

	/**
	 * update_info.
	 *
	 * - Not implemented in this module.
	 *
	 * @param unknown_type $id
	 */
	function update_info( $id )
	{
		global $user_ip, $db, $userdata;

		$where_sql = ( $userdata['user_id'] != ANONYMOUS ) ? "user_id = '" . $userdata['user_id'] . "'" : "downloader_ip = '" . $user_ip . "'";

		$sql = "SELECT user_id, downloader_ip
			FROM " . PA_DOWNLOAD_INFO_TABLE . "
			WHERE $where_sql";

		if ( !( $result = $db->sql_query( $sql ) ) )
		{
			mx_message_die( GENERAL_ERROR, 'Couldnt Query User id', '', __LINE__, __FILE__, $sql );
		}

		if ( !$db->sql_numrows( $result ) )
		{
			$sql = "INSERT INTO " . PA_DOWNLOAD_INFO_TABLE . " (file_id, user_id, downloader_ip, downloader_os, downloader_browser, browser_version)
						VALUES('" . $id . "', '" . $userdata['user_id'] . "', '" . $user_ip . "', '" . $this->platform . "', '" . $this->agent . "', '" . $this->ver . "')";
			if ( !( $db->sql_query( $sql ) ) )
			{
				mx_message_die( GENERAL_ERROR, 'Couldnt Update Downloader Table Info', '', __LINE__, __FILE__, $sql );
			}
		}

		$db->sql_freeresult( $result );
	}
}

/**
 * mx_kb_notification.
 *
 * This class extends general mx_notification class
 *
 * // MODE: MX_PM_MODE/MX_MAIL_MODE, $id: get all file/article data for this id
 * $mx_notification->init($mode, $id); // MODE: MX_PM_MODE/MX_MAIL_MODE
 *
 * // MODE: MX_PM_MODE/MX_MAIL_MODE, ACTION: MX_NEW_NOTIFICATION/MX_EDITED_NOTIFICATION/MX_APPROVED_NOTIFICATION/MX_UNAPPROVED_NOTIFICATION
 * $mx_notification->notify( $mode = MX_PM_MODE, $action = MX_NEW_NOTIFICATION, $to_id, $from_id, $subject, $message, $html_on, $bbcode_on, $smilies_on )
 *
 * @access public
 * @author Jon Ohlsson
 */
class mx_kb_notification extends mx_notification
{
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $item_id
	 */
	function init( $item_id = 0, $allow_comment_wysiwyg = 0)
	{
		global $db, $lang, $module_root_path, $phpbb_root_path, $mx_root_path, $phpEx, $userdata, $mx_kb;

			// =======================================================
			// item id is not set, give him/her a nice error message
			// =======================================================
			if (empty($item_id))
			{
				mx_message_die(GENERAL_ERROR, 'Bad Init pars');
			}

			unset($this->langs);

			//
			// Build up generic lang keys
			//
			$this->langs['item_not_exist'] = $lang['File_not_exist'];
			$this->langs['module_title'] = $lang['KB_prefix'];

			$this->langs['notify_subject_new'] = $lang['KB_notify_subject_new'];
			$this->langs['notify_subject_edited'] = $lang['KB_notify_subject_edited'];
			$this->langs['notify_subject_approved'] = $lang['KB_notify_subject_approved'];
			$this->langs['notify_subject_unapproved'] = $lang['KB_notify_subject_unapproved'];
			$this->langs['notify_subject_deleted'] = $lang['KB_notify_subject_deleted'];

			$this->langs['notify_new_body'] = $lang['KB_notify_new_body'];
			$this->langs['notify_edited_body'] = $lang['KB_notify_edited_body'];
			$this->langs['notify_approved_body'] = $lang['KB_notify_approved_body'];
			$this->langs['notify_unapproved_body'] = $lang['KB_notify_unapproved_body'];
			$this->langs['notify_deleted_body'] = $lang['KB_notify_deleted_body'];

			$this->langs['item_title'] = $lang['Article_title'];
			$this->langs['author'] = $lang['Author'];
			$this->langs['item_description'] = $lang['Article_description'];
			$this->langs['item_type'] = $lang['Article_type'];
			$this->langs['category'] = $lang['Category'];
			$this->langs['read_full_item'] = $lang['Read_full_article'];
			$this->langs['edited_item_info'] = $lang['Edited_Article_info'];

			switch ( SQL_LAYER )
			{
				case 'oracle':
					$sql = "SELECT a.*, AVG(r.rate_point) AS rating, COUNT(r.votes_article) AS total_votes, u.user_id, u.username, c.category_id, c.category_name
						FROM " . KB_ARTICLES_TABLE . " AS a, " . KB_VOTES_TABLE . " AS r, " . USERS_TABLE . " AS u, " . KB_CATEGORIES_TABLE . " AS c
						WHERE a.article_id = r.votes_article(+)
							AND a.article_author_id = u.user_id(+)
							AND c.category_id = a.article_category_id
							AND a.article_id = '" . $item_id . "'
						GROUP BY a.article_id";
					break;

				default:
            		$sql = "SELECT a.*, AVG(r.rate_point) AS rating, COUNT(r.votes_article) AS total_votes, u.user_id, u.username, c.category_id, c.category_name
                  		FROM " . KB_ARTICLES_TABLE . " AS a
                     		LEFT JOIN " . KB_CATEGORIES_TABLE . " AS c ON a.article_category_id = c.category_id
                     		LEFT JOIN " . KB_VOTES_TABLE . " AS r ON a.article_id = r.votes_article
                     		LEFT JOIN " . USERS_TABLE . " AS u ON a.article_author_id = u.user_id
                  		WHERE a.article_id = '" . $item_id . "'
                  		GROUP BY a.article_id ";
					break;
			}

			if ( !( $result = $db->sql_query( $sql ) ) )
			{
				mx_message_die( GENERAL_ERROR, 'Couldnt Query article info', '', __LINE__, __FILE__, $sql );
			}

			// ===================================================
			// file doesn't exist'
			// ===================================================
			if ( !$item_data = $db->sql_fetchrow( $result ) )
			{
				mx_message_die( GENERAL_MESSAGE, $this->langs['Item_not_exist'] );
			}

			$db->sql_freeresult( $result );

			unset($this->data);

			//
			// File data
			//
			$this->data['item_id'] = $item_id;
			$this->data['item_title'] = $item_data['article_title'];
			$this->data['item_desc'] = $item_data['article_description'];

			//
			// Category data
			//
			$this->data['item_category_id'] = $item_data['category_id'];
			$this->data['item_category_name'] = $item_data['category_name'];

			//
			// File author
			//
			$this->data['item_author_id'] = $item_data['user_id'];
			$this->data['item_author'] = ( $item_data['user_id'] != ANONYMOUS ) ? $item_data['username'] : $lang['Guest'];

			//
			// File editor
			//
			$this->data['item_editor_id'] = $userdata['user_id'];
			$this->data['item_editor'] = ( $userdata['user_id'] != '-1' ) ? $userdata['username'] : $lang['Guest'];

			$mx_root_path_tmp = $mx_root_path; // Stupid workaround, since phpbb posts need full paths.
			$mx_root_path = '';
			$this->temp_url = PORTAL_URL . $mx_kb->this_mxurl("mode=" . "article&k=" . $this->data['item_id'], false, true);
			$mx_root_path = $mx_root_path_tmp;

			//
			// Toggles
			//
			$this->allow_comment_wysiwyg = $allow_comment_wysiwyg;
	}
}
?>