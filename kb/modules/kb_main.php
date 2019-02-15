<?php
/**
*
* @package MX-Publisher Module - mx_kb
* @version $Id: kb_main.php,v 1.5 2008/06/03 20:10:33 jonohlsson Exp $
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
class mx_kb_main extends mx_kb_public
{
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $action
	 */
	function main( $action )
	{
		global $template, $lang, $db, $board_config, $phpEx, $kb_config, $debug;

		$page_title = $category_name;

		$template->set_filenames( array( 'body' => 'kb_index_body.tpl' ));

		//
		// Assign vars
		//
		$template->assign_vars( array(
			'L_CATEGORY' => $lang['Category'],
			'L_ARTICLES' => $lang['Articles'] )
		);

		// ===================================================
		// Show the Category for the download database index
		// ===================================================
		$this->display_categories();

		//
		// Get footer quick dropdown jumpbox
		//
		$this->generate_jumpbox( 0, 0, array( $_GET['cat'] => 1 ));
	}
}
?>