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
 *    $Id: kb_defs.php,v 1.1 2005/12/08 15:06:46 jonohlsson Exp $
 */

/**
 * This program is free software; you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation; either version 2 of the License, or
 *    (at your option) any later version.
 */

define( 'NEWS_CAT_TABLE', $mx_table_prefix . 'kb_categories' );
define( 'KB_ARTICLES_TABLE', $mx_table_prefix . 'kb_articles' );
define( 'KB_CATEGORIES_TABLE', $mx_table_prefix . 'kb_categories' );
define( 'KB_TYPES_TABLE', $mx_table_prefix . 'kb_types' );

$sql = "SELECT *  
       	FROM " . KB_TYPES_TABLE;

if ( !( $type_result = $db->sql_query( $sql ) ) )
{
	mx_message_die( GENERAL_ERROR, "Could not obtain types information", '', __LINE__, __FILE__, $sql );
}

$item_types_array = array();
$item_types_id_array = array();
$item_types_name_array = array();

while ( $type = $db->sql_fetchrow( $type_result ) )
{
	$item_types_array[] = 'type_' . $type['id'];
	$item_types_id_array[] = $type['id'];
	$item_types_name_array[] = $type['type'];
}

$cat_extract_order = 'parent, cat_order';
$cool_array_category_id = 'category_id';

$cat_table_category_id = 'f.category_id';
$item_table_category_id = 't.article_category_id';

$item_table_item_id = 't.article_id';
$item_table_item_type = 't.article_type';
$item_table_item_time = 't.article_date';
$item_table_item_last_time = 'tt.topic_last_post_id';
$item_table_item_title = 't.article_title';

$item_id = 'article_id';
$item_type = 'article_type';
$item_cat_id = 'article_category_id';
$item_text = 'article_body';
$item_bbcode_uid = 'bbcode_uid';
$item_time = 'article_date';
$item_views = 'views';
$item_title = 'article_title';

$catt_id = 'category_id';
$catt_name = 'category_name';
$catt_desc = 'category_details';

$item_types_all = 'forum_news';

?>