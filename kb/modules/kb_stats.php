<?php
/**
*
* @package MX-Publisher Module - mx_kb
* @version $Id: kb_stats.php,v 1.10 2008/06/03 20:10:36 jonohlsson Exp $
* @copyright (c) 2002-2006 [wGEric, Jon Ohlsson] MX-Publisher Project Team
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
class mx_kb_stats extends mx_kb_public
{
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $action
	 */
	function main( $action )
	{
		global $template, $lang, $db, $phpEx, $kb_config, $mx_request_vars, $userdata;
		global $mx_root_path, $module_root_path, $is_block;

		//
		// Request vars
		//
		$start = $mx_request_vars->get('start', MX_TYPE_INT, 0);

		//
		// Sorting of items
		//
		if ( isset( $_REQUEST['sort_method'] ) )
		{
			switch ( $_REQUEST['sort_method'] )
			{
				case 'Id':
				$this->sort_method = 't.article_id';
				$this->sort_method_extra = 't.article_type' . " DESC, " ;
				$path_kb = '&nbsp;&raquo;&nbsp;' . $lang['Top_id'];
					break;
				case 'Latest':
				$this->sort_method = 't.article_date';
				$this->sort_method_extra = 't.article_type' . " DESC, " ;
				$path_kb = '&nbsp;&raquo;&nbsp;' . $lang['Top_latest'];
					break;
				case 'Toprated':
				$this->sort_method = 'rating';
				$this->sort_method_extra = 't.article_type' . " DESC, " ;
				$path_kb = '&nbsp;&raquo;&nbsp;' . $lang['Top_toprated'];
					break;
				case 'Most_popular':
				$this->sort_method = 't.views';
				$this->sort_method_extra = 't.article_type' . " DESC, " ;
				$path_kb = '&nbsp;&raquo;&nbsp;' . $lang['Top_most_popular'];
					break;
				case 'Userrank':
				$this->sort_method = 'u.user_rank';
				$this->sort_method_extra = 't.article_type' . " DESC, " ;
				$path_kb = '&nbsp;&raquo;&nbsp;' . $lang['Top_userrank'];
					break;
				case 'Alphabetic':
				$this->sort_method = 't.article_title';
				$this->sort_method_extra = 't.article_type' . " DESC, " ;
				$path_kb = '&nbsp;&raquo;&nbsp;' . $lang['Top_alphabetic'];
					break;
			}
		}

		if ( isset( $_REQUEST['sort_order'] ) )
		{
			switch ( $_REQUEST['sort_order'] )
			{
				case 'ASC':
					$this->sort_order = 'ASC';
					break;
				case 'DESC':
					$this->sort_order = 'DESC';
					break;
			}
		}

		$template->set_filenames( array( 'body' => 'kb_stats_body.tpl' ) );

		$template->assign_vars( array(
			'L_CATEGORY_NAME' => $category_name,
			'L_ARTICLE' => $lang['Article'],
			'L_CAT' => $lang['Category'],
			'L_ARTICLE_TYPE' => $lang['Article_type'],
			'L_ARTICLE_CATEGORY' => $lang['Category'],
			'L_ARTICLE_DATE' => $lang['Date'],
			'L_ARTICLE_AUTHOR' => $lang['Author'],
			'L_VIEWS' => $lang['Views'],
			'L_VOTES' => $lang['Votes'],
			'L_CATEGORY' => $lang['Category_sub'],
			'L_ARTICLES' => $lang['Articles'],
			'PATH' => $path_kb,
			'U_CAT' => mx_append_sid( $this->this_mxurl( 'mode=cat&cat=' . $category_id ) )
		));

		$this->display_items( $start );

		//
		// Get footer quick dropdown jumpbox
		//
		$this->generate_jumpbox( 0, 0, array( $_GET['cat'] => 1 ));
	}
}
?>