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
 *    $Id: kb_main.php,v 1.1 2005/12/08 15:06:46 jonohlsson Exp $
 */

/**
 * This program is free software; you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation; either version 2 of the License, or
 *    (at your option) any later version.
 */
 
class mx_kb_main extends mx_kb_public
{
	function main( $action )
	{
		global $template, $lang, $db, $board_config, $phpEx, $kb_config, $debug; 

		$page_title = $category_name;
		
		$template->set_filenames( array( 'body' => 'kb_index_body.tpl' ));	
			
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
		$this->generate_jumpbox( 'auth_view', 0, 0, true );		
	}
}
?>