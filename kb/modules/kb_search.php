<?php
/**
*
* @package MX-Publisher Module - mx_kb
* @version $Id: kb_search.php,v 1.15 2008/07/15 22:05:43 jonohlsson Exp $
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
class mx_kb_search extends mx_kb_public
{
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $action
	 */
	function main($action = false)
	{
		global $template, $lang, $board_config, $phpEx, $kb_config, $db, $images;
		global $phpbb_root_path, $userdata, $mx_root_path, $module_root_path, $is_block;

		if ( isset( $_REQUEST['search_keywords'] ) )
		{
			$search_keywords = htmlspecialchars( $_REQUEST['search_keywords'] );
		}
		else
		{
			$search_keywords = '';
		}

		$search_author = ( isset( $_REQUEST['search_author'] ) ) ? htmlspecialchars( $_REQUEST['search_author'] ) : '';

		$search_id = ( isset( $_REQUEST['search_id'] ) ) ? intval( $_REQUEST['search_id'] ) : 0;

		if ( isset( $_REQUEST['search_terms'] ) )
		{
			$search_terms = ( $_REQUEST['search_terms'] == 'all' ) ? 1 : 0;
		}
		else
		{
			$search_terms = 0;
		}

		$cat_id = ( isset( $_REQUEST['cat_id'] ) ) ? intval( $_REQUEST['cat_id'] ) : 0;

		if ( isset( $_REQUEST['comments_search'] ) )
		{
			$comments_search = ( $_REQUEST['comments_search'] == 'YES' ) ? 1 : 0;
		}
		else
		{
			$comments_search = 0;
		}

		$start = ( isset( $_REQUEST['start'] ) ) ? intval( $_REQUEST['start'] ) : 0;

		if ( isset( $_REQUEST['sort_method'] ) )
		{
			switch ( $_REQUEST['sort_method'] )
			{
				case 'article_title':
					$sort_method = 'article_title';
					break;
				case 'article_date':
					$sort_method = 'article_date';
					break;
				case 'views':
					$sort_method = 'views';
					break;
				case 'article_rating':
					//$sort_method = 'article_rating';
					break;
				default:
					switch ( $kb_config['sort_method'] )
					{
						case 'Id':
						$sort_method = 'article_id';
							break;
						case 'Latest':
						$sort_method = 'article_date';
							break;
						case 'Toprated':
						$sort_method = 'rating';
							break;
						case 'Most_popular':
						$sort_method = 'views';
							break;
						case 'Userrank':
						$sort_method = 'user_rank';
							break;
						case 'Alphabetic':
						$sort_method = 'article_title';
							break;
					}
			}
		}
		else
		{
			switch ( $kb_config['sort_method'] )
			{
				case 'Id':
				$sort_method = 'article_id';
					break;
				case 'Latest':
				$sort_method = 'article_date';
					break;
				case 'Toprated':
				$sort_method = 'rating';
					break;
				case 'Most_popular':
				$sort_method = 'views';
					break;
				case 'Userrank':
				$sort_method = 'user_rank';
					break;
				case 'Alphabetic':
				$sort_method = 'article_title';
					break;
			}
		}

		if ( isset( $_REQUEST['sort_order'] ) )
		{
			switch ( $_REQUEST['sort_order'] )
			{
				case 'ASC':
					$sort_order = 'ASC';
					break;
				case 'DESC':
					$sort_order = 'DESC';
					break;
				default:
					$sort_order = $kb_config['sort_order'];
			}
		}
		else
		{
			$sort_order = $kb_config['sort_order'];
		}

		$limit_sql = ( $start == 0 ) ? $kb_config['pagination'] : $start . ',' . $kb_config['pagination'];

		//
		// encoding match for workaround
		//
		$multibyte_charset = 'utf-8, big5, shift_jis, euc-kr, gb2312';

		if ( isset( $_POST['submit'] ) || $search_author != '' || $search_keywords != '' || $search_id )
		{
			$store_vars = array( 'search_results', 'total_match_count', 'split_search', 'sort_method', 'sort_order' );

			if ( $search_author != '' || $search_keywords != '' )
			{
				if ( $search_author != '' && $search_keywords == '' )
				{
					$search_author = str_replace( '*', '%', trim( $search_author ) );

					$sql = "SELECT user_id
					FROM " . USERS_TABLE . "
					WHERE username LIKE '" . str_replace( "\'", "''", $search_author ) . "'";
					if ( !( $result = $db->sql_query( $sql ) ) )
					{
						mx_message_die( GENERAL_ERROR, "Couldn't obtain list of matching users (searching for: $search_author)", "", __LINE__, __FILE__, $sql );
					}

					$matching_userids = '';
					if ( $row = $db->sql_fetchrow( $result ) )
					{
						do
						{
							$matching_userids .= ( ( $matching_userids != '' ) ? ', ' : '' ) . $row['user_id'];
						}
						while ( $row = $db->sql_fetchrow( $result ) );
					}
					else
					{
						mx_message_die( GENERAL_MESSAGE, $lang['No_search_match'] );
					}

					$sql = "SELECT *
					FROM " . KB_ARTICLES_TABLE . "
					WHERE article_author_id IN ($matching_userids)";

					if ( !( $result = $db->sql_query( $sql ) ) )
					{
						mx_message_die( GENERAL_ERROR, 'Could not obtain matched article list', '', __LINE__, __FILE__, $sql );
					}

					$search_ids = array();
					while ( $row = $db->sql_fetchrow( $result ) )
					{
						if ( $this->auth_user[$row['article_category_id']]['auth_view'] )
						{
							$search_ids[] = $row['article_id'];
						}
					}
					$db->sql_freeresult( $result );

					$total_match_count = count( $search_ids );
				}
				else if ( $search_keywords != '' )
				{
					$stopword_array = @file( $phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/search_stopwords.txt' );
					$synonym_array = @file( $phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/search_synonyms.txt' );

					$split_search = array();
					$split_search = ( !strstr( $multibyte_charset, $lang['ENCODING'] ) ) ? split_words( clean_words( 'search', stripslashes( $search_keywords ), $stopword_array, $synonym_array ), 'search' ) : split( ' ', $search_keywords );

					$word_count = 0;
					$current_match_type = 'or';

					$word_match = array();
					$result_list = array();

					for( $i = 0; $i < count( $split_search ); $i++ )
					{
						switch ( $split_search[$i] )
						{
							case 'and':
								$current_match_type = 'and';
								break;

							case 'or':
								$current_match_type = 'or';
								break;

							case 'not':
								$current_match_type = 'not';
								break;

							default:
								if ( !empty( $search_terms ) )
								{
									$current_match_type = 'and';
								}
								$match_word = addslashes( '%' . str_replace( '*', '', $split_search[$i] ) . '%' );

								$sql = "SELECT article_id
								FROM " . KB_ARTICLES_TABLE . "
								WHERE (article_title LIKE '$match_word'
								OR username LIKE '$match_word'
								OR article_description LIKE '$match_word'
								OR article_body LIKE '$match_word')";

								if ( !( $result = $db->sql_query( $sql ) ) )
								{
									mx_message_die( GENERAL_ERROR, 'Could not obtain matched article list', '', __LINE__, __FILE__, $sql );
								}

								$row = array();
								while ( $temp_row = $db->sql_fetchrow( $result ) )
								{
									$row[$temp_row['article_id']] = 1;

									if ( !$word_count )
									{
										$result_list[$temp_row['article_id']] = 1;
									}
									else if ( $current_match_type == 'or' )
									{
										$result_list[$temp_row['article_id']] = 1;
									}
									else if ( $current_match_type == 'not' )
									{
										$result_list[$temp_row['article_id']] = 0;
									}
								}

								if ( $current_match_type == 'and' && $word_count )
								{
									@reset( $result_list );
									while ( list( $article_id, $match_count ) = @each( $result_list ) )
									{
										if ( !$row[$article_id] )
										{
											$result_list[$article_id] = 0;
										}
									}
								}

								if ( $comments_search )
								{
									$sql = "SELECT article_id
								FROM " . KB_COMMENTS_TABLE . "
								WHERE (comments_title LIKE '$match_word'
								OR comments_text LIKE '$match_word')";

									if ( !( $result = $db->sql_query( $sql ) ) )
									{
										mx_message_die( GENERAL_ERROR, 'Could not obtain matched articles list', '', __LINE__, __FILE__, $sql );
									}

									$row = array();
									while ( $temp_row = $db->sql_fetchrow( $result ) )
									{
										$row[$temp_row['article_id']] = 1;

										if ( !$word_count )
										{
											$result_list[$temp_row['article_id']] = 1;
										}
										else if ( $current_match_type == 'or' )
										{
											$result_list[$temp_row['article_id']] = 1;
										}
										else if ( $current_match_type == 'not' )
										{
											$result_list[$temp_row['article_id']] = 0;
										}
									}

									if ( $current_match_type == 'and' && $word_count )
									{
										@reset( $result_list );
										while ( list( $article_id, $match_count ) = @each( $result_list ) )
										{
											if ( !$row[$article_id] )
											{
												$result_list[$article_id] = 0;
											}
										}
									}
								}

								$word_count++;

								$db->sql_freeresult( $result );
						}
					}
					@reset( $result_list );

					$search_ids = array();
					while ( list( $article_id, $matches ) = each( $result_list ) )
					{
						if ( $matches )
						{
							$search_ids[] = $article_id;
						}
					}

					unset( $result_list );
					$total_match_count = count( $search_ids );
				}

				// Author name search

				if ( $search_author != '' )
				{
					$search_author = str_replace( '*', '%', trim( str_replace( "\'", "''", $search_author ) ) );
				}

				if ( $total_match_count )
				{
					$where_sql = ( $cat_id ) ? 'AND article_category_id IN (' . $this->gen_cat_ids( $cat_id, '' ) . ')' : '';

					if ( $search_author == '' )
					{
						$sql = "SELECT article_id, article_category_id
						FROM " . KB_ARTICLES_TABLE . "
						WHERE article_id IN (" . implode( ", ", $search_ids ) . ")
							$where_sql
						GROUP BY article_id";
					}
					else
					{
						$from_sql = KB_ARTICLES_TABLE . " f";
						if ( $search_author != '' )
						{
							$from_sql .= ", " . USERS_TABLE . " u";
							$where_sql .= " AND u.user_id = f.article_author_id AND u.username LIKE '$search_author' ";
						}

						$where_sql .= ( $cat_id ) ? 'AND article_category_id IN (' . $this->gen_cat_ids( $cat_id, '' ) . ')' : '';

						$sql = "SELECT f.article_id, f.article_category_id
						FROM $from_sql
						WHERE f.article_id IN (" . implode( ", ", $search_ids ) . ")
							$where_sql
						GROUP BY f.article_id";
					}

					if ( !( $result = $db->sql_query( $sql ) ) )
					{
						mx_message_die( GENERAL_ERROR, 'Could not obtain article ids', '', __LINE__, __FILE__, $sql );
					}

					$search_ids = array();
					while ( $row = $db->sql_fetchrow( $result ) )
					{
						if ( $this->auth_user[$row['article_category_id']]['auth_view'] )
						{
							$search_ids[] = $row['article_id'];
						}
					}
					$db->sql_freeresult( $result );
					$total_match_count = sizeof( $search_ids );
				}
				else
				{
					mx_message_die( GENERAL_MESSAGE, $lang['No_search_match'] );
				}

				// Finish building query (for all combinations)
				// and run it ...

				$expiry_time = $current_time - $board_config['session_length'];
				$sql = "SELECT session_id
					FROM " . SESSIONS_TABLE . "
					WHERE session_time > $expiry_time";

				if ( $result = $db->sql_query( $sql ) )
				{
					$delete_search_ids = array();
					while ( $row = $db->sql_fetchrow( $result ) )
					{
						$delete_search_ids[] = "'" . $row['session_id'] . "'";
					}

					if ( count( $delete_search_ids ) )
					{
						$sql = "DELETE FROM " . SEARCH_TABLE . "
							WHERE session_id NOT IN (" . implode( ", ", $delete_search_ids ) . ")";
						if ( !$result = $db->sql_query( $sql ) )
						{
							mx_message_die( GENERAL_ERROR, 'Could not delete old search id sessions', '', __LINE__, __FILE__, $sql );
						}
					}
				}

				// Store new result data

				$search_results = implode( ', ', $search_ids );

				$store_search_data = array();

				for( $i = 0; $i < count( $store_vars ); $i++ )
				{
					$store_search_data[$store_vars[$i]] = $$store_vars[$i];
				}

				$result_array = serialize( $store_search_data );
				unset( $store_search_data );

				mt_srand ( ( double ) microtime() * 1000000 );
				$search_id = mt_rand();

				$sql = "UPDATE " . SEARCH_TABLE . "
					SET search_id = $search_id, search_array = '" . str_replace( "\'", "''", $result_array ) . "'
					WHERE session_id = '" . $userdata['session_id'] . "'";
				if ( !( $result = $db->sql_query( $sql ) ) || !$db->sql_affectedrows() )
				{
					$sql = "INSERT INTO " . SEARCH_TABLE . " (search_id, session_id, search_array)
						VALUES($search_id, '" . $userdata['session_id'] . "', '" . str_replace( "\'", "''", $result_array ) . "')";
					if ( !( $result = $db->sql_query( $sql ) ) )
					{
						mx_message_die( GENERAL_ERROR, 'Could not insert search results', '', __LINE__, __FILE__, $sql );
					}
				}
			}
			else
			{
				$search_id = intval( $search_id );
				if ( $search_id )
				{
					$sql = "SELECT search_array
						FROM " . SEARCH_TABLE . "
						WHERE search_id = $search_id
						AND session_id = '" . $userdata['session_id'] . "'";
					if ( !( $result = $db->sql_query( $sql ) ) )
					{
						mx_message_die( GENERAL_ERROR, 'Could not obtain search results', '', __LINE__, __FILE__, $sql );
					}

					if ( $row = $db->sql_fetchrow( $result ) )
					{
						$search_data = unserialize( $row['search_array'] );
						for( $i = 0; $i < count( $store_vars ); $i++ )
						{
							$$store_vars[$i] = $search_data[$store_vars[$i]];
						}
					}
				}
			}

			if ( $search_results != '' )
			{
				switch ( SQL_LAYER )
				{
					case 'oracle':
						$sql = "SELECT a.*, AVG(r.rate_point) AS rating, COUNT(r.votes_article) AS total_votes, u.user_id, u.username, c.category_id, c.category_name, COUNT(cm.comments_id) AS total_comments
							FROM " . KB_ARTICLES_TABLE . " AS a, " . KB_VOTES_TABLE . " AS r, " . USERS_TABLE . " AS u, " . KB_CATEGORIES_TABLE . " AS c, " . KB_COMMENTS_TABLE . " AS cm
							WHERE a.article_id IN ($search_results)
							AND a.article_id = r.votes_article(+)
							AND a.article_author_id = u.user_id(+)
							AND a.article_id = cm.article_id(+)
							AND c.category_id = a.article_category_id
							AND a.approved = '1'
							GROUP BY a.article_id
							ORDER BY $sort_method $sort_order
							LIMIT $limit_sql";
						break;

					default:
						$sql = "SELECT a.*, AVG(r.rate_point) AS rating, COUNT(r.votes_article) AS total_votes, u.user_id, u.username, c.category_id, c.category_name, COUNT(cm.comments_id) AS total_comments
							FROM " . KB_CATEGORIES_TABLE . " AS c, " . KB_ARTICLES_TABLE . " AS a
								LEFT JOIN " . KB_VOTES_TABLE . " AS r ON a.article_id = r.votes_article
								LEFT JOIN " . USERS_TABLE . " AS u ON a.article_author_id = u.user_id
								LEFT JOIN " . KB_COMMENTS_TABLE . " AS cm ON a.article_id = cm.article_id
							WHERE a.article_id IN ($search_results)
							AND c.category_id = a.article_category_id
							AND a.approved = '1'
							GROUP BY a.article_id
							ORDER BY $sort_method $sort_order
							LIMIT $limit_sql";
						break;
				}

				if ( !$result = $db->sql_query( $sql ) )
				{
					mx_message_die( GENERAL_ERROR, 'Could not obtain search results', '', __LINE__, __FILE__, $sql );
				}

				$searchset = array();
				while ( $row = $db->sql_fetchrow( $result ) )
				{
					$searchset[] = $row;
				}

				$db->sql_freeresult( $result );

				$l_search_matches = ( $total_match_count == 1 ) ? sprintf( $lang['Found_search_match'], $total_match_count ) : sprintf( $lang['Found_search_matches'], $total_match_count );

				$template->set_filenames( array( 'body' => 'kb_search_result.tpl' ) );

				$template->assign_vars( array(
					'L_SEARCH_MATCHES' => $l_search_matches,
					'L_SEARCH_RESULTS' => $lang['Search_results']
				 ));

				for( $i = 0; $i < count( $searchset ); $i++ )
				{
					$cat_url = mx_append_sid( $this->this_mxurl( 'mode=cat&cat=' . $searchset[$i]['category_id'] ) );
					$file_url = mx_append_sid( $this->this_mxurl( 'mode=article&k=' . $searchset[$i]['article_id'] ) );
					// ===================================================
					// Format the date for the given article
					// ===================================================
					$date = phpBB2::create_date( $board_config['default_dateformat'], $searchset[$i]['article_date'], $board_config['board_timezone'] );
					// ===================================================
					// Get rating for the article and format it
					// ===================================================
					$rating = ( $searchset[$i]['rating'] != 0 ) ? round( $searchset[$i]['rating'], 2 ) . ' / 10' : $lang['Not_rated'];
					// ===================================================
					// If the article is new then put a new image in front of it
					// ===================================================

					$poster = ( $searchset[$i]['user_id'] != ANONYMOUS ) ? '<a href="' . mx_append_sid( $phpbb_root_path . 'profile.' . $phpEx . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $searchset[$i]['user_id'] ) . '">' : '';
					$poster .= ( $searchset[$i]['user_id'] != ANONYMOUS ) ? $searchset[$i]['username'] : $lang['Guest'];
					$poster .= ( $searchset[$i]['user_id'] != ANONYMOUS ) ? '</a>' : '';

					$template->assign_block_vars( 'searchresults', array(
						'CAT_NAME' => $searchset[$i]['category_name'],
						'PIN_IMAGE' => '<img src="' . $images['kb_icon_newest_reply'] . '" border="0" />',

						'FILE_NAME' => $searchset[$i]['article_title'],
						'FILE_DESC' => $searchset[$i]['article_description'],
						'FILE_SUBMITER' => $poster,
						'DATE' => $date,
						'RATING' => $rating,
						'DOWNLOADS' => $searchset[$i]['views'],
						'U_FILE' => $file_url,
						'U_CAT' => $cat_url )
					);
				}
				$base_url = mx_append_sid( $this->this_mxurl( "mode=search&amp;search_id=$search_id" ) );

				$template->assign_vars( array(
					'PAGINATION' => phpBB2::generate_pagination( $base_url, $total_match_count, $kb_config['pagination'], $start ),
					'PAGE_NUMBER' => sprintf( $lang['Page_of'], ( floor( $start / $kb_config['pagination'] ) + 1 ), ceil( $total_match_count / $kb_config['pagination'] ) ),
					'L_MODULE' => $kb_config['module_name'],

					'U_INDEX' => mx_append_sid( $mx_root_path . 'index.' . $phpEx ),
					'U_DOWNLOAD' => mx_append_sid( $this->this_mxurl() ),

					'L_INDEX' => "<<",
					'L_RATE' => $lang['Rating'],
					'L_VIEWS' => $lang['Views'],
					'L_DATE' => $lang['Date'],
					'L_NAME' => $lang['Name'],
					'L_ARTICLE' => $lang['Article'],
					'L_SUBMITER' => $lang['Submiter'],
					'L_CATEGORY' => $lang['Category'],
					'L_NEW_FILE' => $lang['New_file'] )
				);
			}
			else
			{
				mx_message_die( GENERAL_MESSAGE, $lang['No_search_match'] );
			}
		}

		if ( !isset( $_POST['submit'] ) || ( $search_author == '' && $search_keywords == '' && !$search_id ) )
		{
			//$dropmenu = $this->generate_jumpbox( 'auth_view', 0, 0, true );
			$dropmenu = $this->generate_jumpbox( 0, 0, array( $_GET['cat'] => 1 ));

			$template->set_filenames( array( 'body' => 'kb_search_body.tpl' ) );

			$template->assign_vars( array(
				'S_SEARCH_ACTION' => mx_append_sid( $this->this_mxurl() ),
				'S_CAT_MENU' => $dropmenu,

				'L_MODULE' => $kb_config['module_name'],

				'U_INDEX' => mx_append_sid( $mx_root_path . 'index.' . $phpEx ),
				'U_DOWNLOAD' => mx_append_sid( $this->this_mxurl() ),

				'L_YES' => $lang['Yes'],
				'L_NO' => $lang['No'],
				'L_SEARCH_OPTIONS' => $lang['Search_options'],
				'L_SEARCH_KEYWORDS' => $lang['Search_keywords'],
				'L_SEARCH_KEYWORDS_EXPLAIN' => $lang['Search_keywords_explain'],
				'L_SEARCH_AUTHOR' => $lang['Search_author'],
				'L_SEARCH_AUTHOR_EXPLAIN' => $lang['Search_author_explain'],
				'L_SEARCH_ANY_TERMS' => $lang['Search_for_any'],
				'L_SEARCH_ALL_TERMS' => $lang['Search_for_all'],
				'L_INCLUDE_COMMENTS' => $lang['Include_comments'],
				'L_SORT_BY' => $lang['Select_sort_method'],
				'L_SORT_DIR' => $lang['Order'],
				'L_SORT_ASCENDING' => $lang['Sort_Ascending'],
				'L_SORT_DESCENDING' => $lang['Sort_Descending'],

				'L_INDEX' => "<<",

				'L_RATING' => $lang['Top_toprated'],
				'L_VIEWS' => $lang['Top_most_popular'],
				'L_DATE' => $lang['Top_latest'],
				'L_NAME' => $lang['Article_title'],
				'L_UPDATE_TIME' => $lang['Update_time'],

				'L_SEARCH' => $lang['Search'],
				'L_SEARCH_FOR' => $lang['Search_for'],
				'L_ALL' => $lang['All'],
				'L_CHOOSE_CAT' => $lang['Choose_cat'] )
			);
		}

		//
		// Get footer quick dropdown jumpbox
		//
		//$this->generate_jumpbox( 'auth_view', 0, 0, true );
		$this->generate_jumpbox( 0, 0, array( $_GET['cat'] => 1 ));
	}
}

?>