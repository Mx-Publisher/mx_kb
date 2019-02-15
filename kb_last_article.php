<?php
/**
*
* @package MX-Publisher Module - mx_kb
* @version $Id: kb_last_article.php,v 1.9 2008/07/13 19:34:16 jonohlsson Exp $
* @copyright (c) 2002-2006 [Jon Ohlsson, Mohd Basri, wGEric, PHP Arena, pafileDB, CRLin] MX-Publisher Project Team
* @license http://opensource.org/licenses/gpl-license.php GNU General Public License v2
*
*/

if( !defined('IN_PORTAL') || !is_object($mx_block))
{
	die("Hacking attempt");
}

define( 'MXBB_27x', file_exists( $mx_root_path . 'mx_login.' . $phpEx ) );
define( 'MXBB_MODULE', true );

// ===================================================
// Include the constants file
// ===================================================
include_once( $module_root_path . 'kb/includes/kb_constants.' . $phpEx );

//
// Read Block Settings
//
$title = $mx_block->block_info['block_title'];

$template->set_filenames(array(
	'body_last_msg' => 'kb_last_article.tpl')
);

//
// Read block Configuration
//
$PostNumber = $mx_block->get_parameters( 'Last_Article_Number_Title' );
$display_date = $mx_block->get_parameters( 'Last_Article_Display_Date' );
$nb_characteres = $mx_block->get_parameters( 'Last_Article_Title_Length' );
$target = $mx_block->get_parameters( 'Last_Article_Target' );
$align = $mx_block->get_parameters( 'Last_Article_Align' );
$display_forum = $mx_block->get_parameters( 'Last_Article_Display_Cat' );

//
// Temp rewrite for getting KB categories
//
$kb_last_article_cat_var = $mx_block->get_parameters( 'Last_Article_Cat' );
$kb_type_select_data = ( !empty( $kb_last_article_cat_var ) ) ? unserialize( $kb_last_article_cat_var ) : array();
$forum_lst_msg = $kb_type_select_data ? implode(',', $kb_type_select_data) : '';

if( empty($PostNumber) ) $PostNumber = 5;

$display_author = $mx_block->get_parameters( 'Last_Article_Display_Author' );
$display_last_author = $mx_block->get_parameters( 'Last_Article_Display_Last_Author' );
$display_icon_view = $mx_block->get_parameters( 'Last_Article_Display_Icon_View' );

$kb_article_mode = $mx_block->get_parameters( 'Last_Article_Mode' );
$kb_block_target = $kb_article_mode == 'KB_Reader' ? 'kb_article_reader.' : 'kb.';

//
// no limit, last day, 2 days, 3 days, week, 2 weeks, 3 weeks, month, 2 months, 3 months, 6 months, i year,
//
$msg_filter_time = $mx_block->get_parameters( 'article_filter_date' );

//
// Authorization SQL
//
$auth_data_sql_msg = $mx_kb->get_auth_forum('kb');

if ( empty($forum_lst_msg) )
{
	$forum_lst_msg = $auth_data_sql_msg;
}
if ( empty($forum_lst_msg) )
{
	$forum_lst_msg = -1;
}

$msg_start = ( isset( $_GET['kb_lmsg_start'] ) ) ? intval( $_GET['kb_lmsg_start'] ) : 0;

$start_prev = ( $msg_start == 0 ) ? 0 : $msg_start - $PostNumber;
$start_next = $msg_start + $PostNumber;

$url_next = mx_url('kb_lmsg_start' , $start_next);
$url_prev = mx_url('kb_lmsg_start' , $start_prev);

$msg_today = date('mdY');
switch( $msg_filter_time )
{
	case '0':
		$msg_time_filter_lo = 'no';
		break;
	case '1':
		$msg_time_filter_lo = mktime(0, 0, 0 , intval(substr($msg_today, 0, 2)), intval(substr($msg_today, 2, 2) - 1), intval(substr($msg_today, 4, 4)));
		break;
	case '2':
		$msg_time_filter_lo = mktime(0, 0, 0 , intval(substr($msg_today, 0, 2)), intval(substr($msg_today, 2, 2) - 1), intval(substr($msg_today, 4, 4)));
		break;
	case '3':
		$msg_time_filter_lo = mktime(0, 0, 0 , intval(substr($msg_today, 0, 2)), intval(substr($msg_today, 2, 2) - 1), intval(substr($msg_today, 4, 4)));
		break;
	case '4':
		$msg_time_filter_lo = mktime(0, 0, 0 , intval(substr($msg_today, 0, 2)), intval(substr($msg_today, 2, 2) - 7), intval(substr($msg_today, 4, 4)));
		break;
	case '5':
		$msg_time_filter_lo = mktime(0, 0, 0 , intval(substr($msg_today, 0, 2)), intval(substr($msg_today, 2, 2) - 14), intval(substr($msg_today, 4, 4)));
		break;
	case '6':
		$msg_time_filter_lo = mktime(0, 0, 0 , intval(substr($msg_today, 0, 2)), intval(substr($msg_today, 2, 2) - 21), intval(substr($msg_today, 4, 4)));
		break;
	case '7':
		$msg_time_filter_lo = mktime(0, 0, 0 , intval(substr($msg_today, 0, 2) - 1), intval(substr($msg_today, 2, 2)), intval(substr($msg_today, 4, 4)));
		break;
	case '8':
		$msg_time_filter_lo = mktime(0, 0, 0 , intval(substr($msg_today, 0, 2) - 2), intval(substr($msg_today, 2, 2)), intval(substr($msg_today, 4, 4)));
		break;
	case '9':
		$msg_time_filter_lo = mktime(0, 0, 0 , intval(substr($msg_today, 0, 2) - 3), intval(substr($msg_today, 2, 2)), intval(substr($msg_today, 4, 4)));
		break;
	case '10':
		$msg_time_filter_lo = mktime(0, 0, 0 , intval(substr($msg_today, 0, 2) - 6), intval(substr($msg_today, 2, 2)), intval(substr($msg_today, 4, 4)));
		break;
	case '11':
		$msg_time_filter_lo = mktime(0, 0, 0 , intval(substr($msg_today, 0, 2)), intval(substr($msg_today, 2, 2)), intval(substr($msg_today, 4, 4) - 1));
		break;
	default:
		$msg_time_filter_lo = 'no';
		break;
}

$sql = "SELECT COUNT(*) AS article_num
	FROM " . KB_CATEGORIES_TABLE . " c, " . KB_ARTICLES_TABLE . " a
	WHERE a.article_category_id = c.category_id
		AND c.category_id in ( $forum_lst_msg )
		AND c.category_id in ( $auth_data_sql_msg )
		AND a.approved = 1";

if ( $msg_time_filter_lo != 'no' && !empty($msg_time_filter_lo) )
{
	$sql .= " AND a.article_date > " . $msg_time_filter_lo;
}

if ( !($result = $db->sql_query($sql)) )
{
	mx_message_die(GENERAL_ERROR, 'Could not obtain limited topics count information', '', __LINE__, __FILE__, $sql);
}

$row = $db->sql_fetchrow($result);
$db->sql_freeresult($result);
$msg_total_match_count = $row['article_num'];

$sql = "SELECT *
	FROM " . KB_CATEGORIES_TABLE . " c, " . KB_ARTICLES_TABLE . " a, " . USERS_TABLE . " u
	WHERE a.article_category_id = c.category_id
		AND a.article_author_id = u.user_id
		AND c.category_id in ( $forum_lst_msg )
		AND c.category_id in ( $auth_data_sql_msg )
		AND a.approved = 1";

if ( $msg_time_filter_lo != 'no' && !empty($msg_time_filter_lo) )
{
	$sql .= " AND a.article_date > " . $msg_time_filter_lo;
}

$sql .= " ORDER BY a.article_id DESC
	LIMIT $msg_start, $PostNumber";

if ( !($result = $db->sql_query($sql)) )
{
	mx_message_die(GENERAL_ERROR, 'Could not query topics list', '', __LINE__, __FILE__, $sql);
}

$postrow = $db->sql_fetchrowset($result);
$db->sql_freeresult($result);

$base_url = mx_this_url();

$template->assign_vars(array(
	'L_TITLE' => ( !empty($title) ? $title : 'Last Message' ),
	'U_TARGET' => $target,
	'U_ALIGN' => $align,
	'BLOCK_SIZE' => ( !empty($block_size) ? $block_size : '100%' ),
	'U_URL' => mx_append_sid(PORTAL_URL . 'index.php' . '?block_id=' . $block_id),
	'U_URL_NEXT' => $url_next,
	'U_URL_PREV' => $url_prev,
	'U_PHPBB_ROOT_PATH' => PHPBB_URL,
	'U_PORTAL_ROOT_PATH' => PORTAL_URL,
	'TEMPLATE_ROOT_PATH' => TEMPLATE_ROOT_PATH,
	'L_MSG_PREV' => $lang['Previous'],
	'L_MSG_NEXT' => $lang['Next'],
	'PAGINATION' => mx_generate_pagination($base_url, $msg_total_match_count, $PostNumber, $msg_start, true, true, true, false, 'kb_lmsg_start'),
	'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor($msg_start / $PostNumber) + 1 ), ceil($msg_total_match_count / $PostNumber))
));

if ( $msg_total_match_count == 0 || $msg_total_match_count == '' )
{
	$template->assign_block_vars("no_row", array(
		'L_NO_ITEMS' => $lang['No_items_found']
	));
}

for( $row_count = 0; $row_count < count($postrow); $row_count++ )
{
	$row_color = ( !( $row_count % 2 ) ) ? $theme['td_color1'] : $theme['td_color2'];
	$row_class = ( !( $row_count % 2 ) ) ? $theme['td_class1'] : $theme['td_class2'];

	$message = $postrow[$row_count]['article_title'];
	$url = mx_append_sid($mx_root_path . $module_root_path . $kb_block_target . $phpEx . "?mode=article&k=" . $postrow[$row_count]['article_id']);

	$folder = $images['kb_last_article_folder'];
	$folder_image = $folder;

	if ( $display_author == "TRUE" )
	{
		$topic_author = ( $postrow[$row_count]['user_id'] != ANONYMOUS ) ? '<a href="' . mx_append_sid(PHPBB_URL . "profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . '=' . $postrow[$row_count]['user_id']) . '" class="gensmall">' : '';
		$topic_author .= ( $postrow[$row_count]['user_id'] != ANONYMOUS ) ? $postrow[$row_count]['username'] : ( ( $postrow[$row_count]['post_username'] != '' ) ? $postrow[$row_count]['post_username'] : $lang['Guest'] );
		$topic_author .= ( $postrow[$row_count]['user_id'] != ANONYMOUS ) ? '</a>' : '';
	}
	else
	{
		$topic_author = '';
	}

	if ( $display_last_author == "TRUE" )
	{
		$last_post_author = ( $postrow[$row_count]['id2'] == ANONYMOUS ) ? ( ( $postrow[$row_count]['post_username'] != '' ) ? $postrow[$row_count]['post_username'] . ' ' : $lang['Guest'] . ' ' ) : '<a href="' . mx_append_sid(PHPBB_URL . "profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . '=' . $postrow[$row_count]['id2']) . '" class="gensmall">' . $postrow[$row_count]['user2'] . '</a>';
	}
	else
	{
		$last_post_author = '';
	}

	$message_alt = $message;
	if ( strlen($message) > $nb_characteres )
	{
		$message = substr($message, 0, $nb_characteres);
		$position_espace = strrpos($message, ' ');

		$position_espace = empty($position_espace) ? $nb_characteres : $position_espace;
		$message = substr($message, 0, $position_espace);
		$message .= '...';
	}

	if ( $display_date == "TRUE" )
	{
		$message_date = phpBB2::create_date($board_config['default_dateformat'], $postrow[$row_count]['article_date'], $board_config['board_timezone']);
	}
	else
	{
		$message_date = '';
	}

	if ( $display_forum == "TRUE" )
	{
		$forum_name = $postrow[$row_count]['category_name'];

		$forum_name_alt = $forum_name;
		if ( strlen($forum_name) > $nb_characteres )
		{
			$forum_name = substr($forum_name, 0, $nb_characteres);
			$position_espace = strrpos($forum_name, ' ');

			$position_espace = empty($position_espace) ? $nb_characteres : $position_espace;
			$forum_name = substr($forum_name, 0, $position_espace);
			$forum_name .= '...';
		}

		$forum_name = $kb_article_mode != 'KB_Reader' ? '<a href="' . mx_append_sid($mx_root_path . $module_root_path . $kb_block_target . $phpEx . "?mode=cat&cat=" . $postrow[$row_count]['article_category_id']) . '" target="'.$target.'" class="gensmall">'.$forum_name.'</a>' : $forum_name;

	}
	else
	{
		$forum_name = '';
	}

	if ( $display_icon_view == "TRUE" )
	{
		$last_post_url = '<a href="' . mx_append_sid($mx_root_path . $module_root_path . $kb_block_target . $phpEx . "?mode=article&k=" . $postrow[$row_count]['article_id']) . '"><img src="' . $images['kb_icon_latest_reply'] . '" alt="' . $lang['View_latest_post'] . '" title="' . $lang['View_latest_post'] . '" border="0" /></a>';
	}
	else
	{
		$last_post_url = '';
	}

	$template->assign_block_vars('msg_row', array(
		'ROW_COLOR' => "#" . $row_color,
		'ROW_CLASS' => $row_class,
		'LAST_MSG' => $message,
		'LAST_MSG_ALT' => $message_alt,
		'LAST_MSG_DATE' => $message_date,
		'FORUM_NAME' => $forum_name,
		'FORUM_NAME_ALT' => $forum_name_alt,
		'U_LAST_MSG' => $url,
		'LAST_POST_IMG' => $last_post_url,
		'FOLDER_IMG' => $folder_image,
		'TOPIC_AUTHOR' => $topic_author,
		'LAST_POST_AUTHOR' => $last_post_author,
		'L_TOPIC_FOLDER_ALT' => $folder_alt
	));
}

$template->pparse('body_last_msg');
?>