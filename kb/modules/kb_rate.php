<?php
/**
*
* @package MX-Publisher Module - mx_kb
* @version $Id: kb_rate.php,v 1.11 2008/06/03 20:10:35 jonohlsson Exp $
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
class mx_kb_rate extends mx_kb_public
{
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $action
	 */
	function main($action = false)
	{
		global $template, $lang, $board_config, $phpEx, $kb_config, $db, $userdata;
		global $phpbb_root_path, $mx_kb_user, $mx_kb_functions;
		global $mx_root_path, $module_root_path, $is_block, $mx_request_vars;

		// =======================================================
		// Request vars
		// =======================================================
		$article_id = $mx_request_vars->request('k', MX_TYPE_INT, '');

		if ( empty( $article_id ) )
		{
			mx_message_die( GENERAL_MESSAGE, $lang['Link_not_exist'] );
		}

		$rating = ( isset( $_POST['rating'] ) ) ? intval( $_POST['rating'] ) : '';

		$sql = 'SELECT article_title, article_category_id
			FROM ' . KB_ARTICLES_TABLE . "
			WHERE article_id = $article_id";

		if ( !( $result = $db->sql_query( $sql ) ) )
		{
			mx_message_die( GENERAL_ERROR, 'Couldnt Query article info', '', __LINE__, __FILE__, $sql );
		}

		if ( !$article_data = $db->sql_fetchrow( $result ) )
		{
			mx_message_die( GENERAL_MESSAGE, $lang['Article_not_exsist'] );
		}

		$db->sql_freeresult( $result );

		if ( !$this->auth_user[$article_data['article_category_id']]['auth_rate'] )
		{
			if ( !$userdata['session_logged_in'] )
			{
				mx_message_die( GENERAL_MESSAGE, 'not logged in' );
			}
			mx_message_die( GENERAL_MESSAGE, $lang['Not_authorized'] );
		}

		$template->assign_vars( array(
			'L_INDEX' => "<<",
			'L_RATE' => $lang['Rate'],

			'U_INDEX' => mx_append_sid( $mx_root_path . 'index.' . $phpEx ),
			'U_DOWNLOAD_HOME' => mx_append_sid( $this->this_mxurl() ),
			'U_FILE_NAME' => mx_append_sid( $this->this_mxurl( 'mode=article&k=' . $article_id ) ),

			'FILE_NAME' => $article_data['file_name'],
			'DOWNLOAD' => $kb_config['module_name']
		));

		if ( isset( $_POST['submit'] ) )
		{
			$result_msg = str_replace( "{filename}", $article_data['article_title'], $lang['Rconf'] );

			$result_msg = str_replace( "{rate}", $rating, $result_msg );

			if ( ( $rating <= 0 ) or ( $rating > 10 ) )
			{
				mx_message_die( GENERAL_ERROR, 'Bad submited value' );
			}

			$this->update_voter_info( $article_id, $rating, $article_data['article_category_id'] );

			$rate_info = $mx_kb_functions->get_rating( $article_id );

			$result_msg = str_replace( "{newrating}", $rate_info, $result_msg );

			$message = $result_msg . '<br /><br />' . sprintf( $lang['Click_return'], "<a href=\"" . mx_append_sid( $this->this_mxurl( "mode=article&amp;k=$article_id" ) ) . "\">", "</a>" );
			mx_message_die( GENERAL_MESSAGE, $message );
		}
		else
		{
			$rate_info = str_replace( "{filename}", $article_data['article_title'], $lang['Rateinfo'] );

			$template->assign_vars( array(
				'S_RATE_ACTION' => mx_append_sid( $this->this_mxurl( 'mode=rate&k=' . $article_id ) ),
				'L_RATE' => $lang['Rate'],
				'L_RERROR' => $lang['Rerror'],
				'L_R1' => $lang['R1'],
				'L_R2' => $lang['R2'],
				'L_R3' => $lang['R3'],
				'L_R4' => $lang['R4'],
				'L_R5' => $lang['R5'],
				'L_R6' => $lang['R6'],
				'L_R7' => $lang['R7'],
				'L_R8' => $lang['R8'],
				'L_R9' => $lang['R9'],
				'L_R10' => $lang['R10'],
				'RATEINFO' => $rate_info,
				'ID' => $article_id )
			);
		}

		// ===================================================
		// assign var for navigation
		// ===================================================
		$this->generate_navigation( $article_data['article_category_id'] );
		$template->set_filenames( array( 'body' => 'kb_rate_body.tpl' ) );
	}
}

?>