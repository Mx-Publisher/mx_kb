<?php
/**
*
* @package MX-Publisher Module - mx_kb
* @version $Id: lang_main.php,v 1.2 2008/06/03 20:11:33 jonohlsson Exp $
* @copyright (c) 2002-2006 [Jon Ohlsson, Mohd Basri, wGEric, PHP Arena, pafileDB, CRLin] MX-Publisher Project Team
* @license http://opensource.org/licenses/gpl-license.php GNU General Public License v2
*
*/

//
// General
//
$lang['kb_disable'] = 'Knowledge base is disabled.';

$lang['KB_title'] = 'Catalogul de articole';
$lang['Article'] = 'Articol';
$lang['Articles'] = 'Articole';
$lang['Category'] = 'Categorie';
$lang['Sub_categories'] = 'Subcategorie';
$lang['Article_description'] = 'Descriere';
$lang['Article_type'] = 'Tip';
$lang['Article_keywords'] = 'Cuvinte cheie';
$lang['Articles'] = 'Articole';
$lang['Add_article'] = 'Adaugã articol';
$lang['Click_cat_to_add'] = 'Apasã pe categorie pentru a adãuga articol';
$lang['KB_Home'] = 'Pagina de start - Catalog articole';
$lang['No_articles'] = 'Nici un articol';
$lang['Article_title'] = 'Nume articol';
$lang['Article_text'] = 'Text articol';
$lang['Add_article'] = 'Trimite articol';
$lang['Read_article'] = 'Citeºte articol';
$lang['Article_not_exsist'] = 'Articolul nu existã';
$lang['Category_not_exsist'] = 'Categoria nu existã';
$lang['Last_article'] = 'Ultimul Articol';
$lang['Quick_jump'] = 'Selecteazã Categoria';

$lang['Edit'] = 'Modificã';
$lang['Click_cat_to_add'] = 'Click on Category to add Article';
$lang['Standalone_Not_Supported'] = 'This module does not support standalone usage. In the AdminCP, add the KB block to a portal page.';

$lang['Article_submitted_Approve'] = 'Articol transmis cu succes.<br />Un administrator va revizui articolul ºi va decide dacã va fi lãsat la dispoziþia utilizatorilor.';
$lang['Article_submitted'] = 'Articol transmis cu succes.';
$lang['Click_return_kb'] = 'Apasã %saici%s pentru a reveni la ' . $lang['KB_title'];
$lang['Click_return_article'] = 'Apasã %saici%s pentru a reveni la ' . $lang['Article'];

$lang['Article_Edited_Approve'] = 'Articolul a fost modificat cu succes.<br />Va fi necesar ca sã fie aprobat din nou înainte ca utilizatorii sã-l poatã vedea.';
$lang['Article_Edited'] = 'Articolul a fost modificat cu succes.';
$lang['Edit_article'] = 'Modificã articol';


$lang['Article_Deleted'] = 'Articolul a fost ºters cu succes.';

//
// Notification
//
$lang['KB_prefix'] = '[ CA ]';
$lang['KB_notify_subject_new'] = 'Articol Nou!';
$lang['KB_notify_subject_edited'] = 'Articol Editat!';
$lang['KB_notify_subject_approved'] = 'Articol Aprobat!';
$lang['KB_notify_subject_unapproved'] = 'Articol Dez-Aprobat!';
$lang['KB_notify_subject_deleted'] = 'Articol ªters!';

$lang['KB_notify_new_body'] = 'Un nou articol a fost adãugat!';
$lang['KB_notify_edited_body'] = 'Un articol a fost modificat!';
$lang['KB_notify_approved_body'] = 'Un articol a fost aprobat!';
$lang['KB_notify_unapproved_body'] = 'Un articol a fost dez-aprobat.';
$lang['KB_notify_deleted_body'] = 'Un articol a fost ºters.';
$lang['Edited_Article_info'] = 'Articol actualizat de ';

$lang['Read_full_article'] = '>> Citeºte articolul întreg';

//
// Auth Can
//
$lang['KB_Rules_post_can'] = 'You <b>can</b> post new articles in this category';
$lang['KB_Rules_post_cannot'] = 'You <b>cannot</b> post new articles in this category';
$lang['KB_Rules_comment_can'] = 'You <b>can</b> comment articles in this category';
$lang['KB_Rules_comment_cannot'] = 'You <b>cannot</b> comment articles in this category';
$lang['KB_Rules_edit_can'] = 'You <b>can</b> edit your articles in this category';
$lang['KB_Rules_edit_cannot'] = 'You <b>cannot</b> edit your articles in this category';
$lang['KB_Rules_delete_can'] = 'You <b>can</b> delete your articles in this category';
$lang['KB_Rules_delete_cannot'] = 'You <b>cannot</b> delete your articles in this category';
$lang['KB_Rules_rate_can'] = 'You <b>can</b> rate articles in this category';
$lang['KB_Rules_rate_cannot'] = 'You <b>cannot</b> rate articles in this category';
$lang['KB_Rules_approval_can'] = 'Articles <b>need no</b> approval in this category';
$lang['KB_Rules_approval_cannot'] = 'Articles <b>need</b> approval in this category';
$lang['KB_Rules_approval_edit_can'] = 'Article edits <b>need no</b> approval in this category';
$lang['KB_Rules_approval_edit_cannot'] = 'Article edits <b>need</b> approval in this category';
$lang['KB_Rules_moderate'] = 'You <b>can</b> %smoderate this category%s'; // %s replaced by a href links, do not remove!
$lang['KB_Rules_moderate_can'] = 'You <b>can</b> moderate this category'; // %s replaced by a href links, do not remove!

$lang['Empty_fields'] ='Please fill out all parts of the form.';
$lang['Empty_fields_return'] ='Click %sHere%s to return to the form.';
$lang['Empty_category'] ='You must choose a category';
$lang['Empty_type']='You must choose a type';
$lang['Empty_article_name'] = 'You must fill out the article name';
$lang['Empty_article_desc'] = 'You must fill out the article description';

$lang['Comments'] = 'Comments';

$lang['Post_comments'] = 'Post your comments';

$lang['Category_sub'] = 'Sub-Categories';
$lang['Quick_stats'] = 'Quick Stats';

$lang['No_Articles'] = 'There are no articles in this category!';
$lang['Not_authorized'] = 'Sorry, but you are not authorized!';
$lang['TOC'] = 'Contents';

//
// Print version
//
$lang['Print_version'] = '[Printable version]';

//
// Stats
//
$lang['Top_toprated'] = 'Toprated Articles';
$lang['Top_most_popular'] = 'Most Popular';
$lang['Top_latest'] = 'Latest Articles';
$lang['Top_id'] = 'Article Id';
$lang['Top_creation'] = 'Article date';
$lang['Top_alphabetic'] = 'Alphabetic';
$lang['Top_userrank'] = 'Author userrank';

//
// Update result messages
//
$lang['Click_return'] = 'Click %sHere%s to return to previous page';
$lang['Click_return_kb'] = 'Click %sHere%s to return to the ' . $lang['KB_title'];
$lang['Click_return_article'] = 'Click %sHere%s to return to the ' . $lang['Article'];

//
// Article formattting
//
$lang['Cat_all'] = 'All';

$lang['L_Pages'] = 'Pages';
$lang['L_Pages_explain'] = 'Use the \'[pages]\' command to split the article into pages';
$lang['L_Toc'] = 'Table of contents (TOC)';
$lang['L_Toc_explain'] = 'Use the \'[toc]\' command to add entry in the TOC';
$lang['L_Abstract'] = 'Abstract';
$lang['L_Abstract_explain'] = 'Use the \'[abstract]...[/abstract]\' environment to insert an abstract';

$lang['L_Title_Format'] = 'Title';
$lang['L_Title_Format_explain'] = 'Use the \'[title]...[/title]\' environment to insert a main title';

$lang['L_Subtitle_Format'] = 'Subtitle';
$lang['L_Subtitle_Format_explain'] = 'Use the \'[subtitle]...[/subtitle]\' environment to insert a subtitle';

$lang['L_Subsubtitle_Format'] = 'Sub-subtitle';
$lang['L_Subsubtitle_Format'] = 'Use the \'[subsubtitle]...[/subsubtitle]\' environment to insert a small header';

$lang['L_Options'] = 'Options:';
$lang['L_Formatting'] = 'Formatting:';

$lang['Default_article_id'] = 'Set default article, for the article viewer';

//
// MCP
//
$lang['MCP_title'] = 'Moderator Control Panel';
$lang['MCP_title_explain'] = 'Here moderators can approve and manage articles';

$lang['View'] = 'View';

$lang['Approve_selected'] = 'Approve Selected';
$lang['Unapprove_selected'] = 'Unapprove Selected';
$lang['Delete_selected'] = 'Delete Selected';
$lang['No_item'] = 'There is no articles';

$lang['All_items'] = 'All articles';
$lang['Approved_items'] = 'Approved articles';
$lang['Unapproved_items'] = 'Unapproved articles';
$lang['Broken_items'] = 'Broken articles';
$lang['Item_cat'] = 'Articles in Category';
$lang['Approve'] = 'Approve';
$lang['Unapprove'] = 'Unapprove';

$lang['Sorry_auth_delete'] = 'Sorry, but you cannot delete articles in this category.';
$lang['Sorry_auth_mcp'] = 'Sorry, but you cannot moderate this category.';
$lang['Sorry_auth_approve'] = 'Sorry, but you cannot approve articles in this category.';
$lang['Sorry_auth_post'] = 'Sorry, but you cannot post articles in this category.';
$lang['Sorry_auth_edit'] = 'Sorry, but you cannot edit articles in this category.';

$lang['Edit_article'] = 'Edit';
$lang['Delete_article'] = 'Delete';

//
// Added for v. 2.0
//
$lang['Addtional_field'] = 'More information (optional)';
$lang['No_cat_comments_forum_id'] = 'Comments are enabled but you have not specified the target phpBB forum category in the KB adminCP - Categories';

//
// Quick Nav
//
$lang['Quick_nav'] = 'Quick Navigation';
$lang['Quick_jump'] = 'Select Category';
$lang['Quick_go'] = 'Go';

//
// Search
//
$lang['Search'] = 'Search';
$lang['Search_results'] = 'Search Results';
$lang['Search_for'] = 'Search for';
$lang['Results'] = 'Results for';
$lang['No_matches'] = 'Sorry, no matches were found for';
$lang['Matches'] = 'matches were found for';
$lang['All'] = 'All Categories';
$lang['Choose_cat'] = 'Choose Category:';
$lang['Include_comments'] = 'Include Comments';
$lang['Submiter'] = 'Submitted by';

//
// Comments
//
$lang['KB_comment_prefix'] = '[ KB ] ';
$lang['Comments'] = 'Comments';
$lang['Comments_title'] = 'Comments Title';
$lang['Comment_subject'] = 'Comment Subject';
$lang['Comment'] = 'Comment';
$lang['Comment_explain'] = 'Use the textbox above to give your opinion on this file!';
$lang['Comment_add'] = 'Add Comment';
$lang['Comment_delete'] = 'Delete';
$lang['Comment_posted'] = 'Your comment has been entered successfully';
$lang['Comment_deleted'] = 'The comment you selected has been deleted successfully';
$lang['Comment_desc'] = 'Title';
$lang['No_comments'] = 'Not commented';
$lang['Links_are_ON'] = 'Links are <u>ENABLED</u>';
$lang['Links_are_OFF'] = 'Links are <u>DISABLED</u>';
$lang['Images_are_ON'] = 'Images are <u>ENABLED</u>';
$lang['Images_are_OFF'] = 'Images are <u>DISABLED</u>';
$lang['Check_message_length'] = 'Check Message Length';
$lang['Msg_length_1'] = 'Your message is ';
$lang['Msg_length_2'] = ' characters long.';
$lang['Msg_length_3'] = 'You have ';
$lang['Msg_length_4'] = ' characters available.';;
$lang['Msg_length_5'] = 'There are ';
$lang['Msg_length_6'] = ' characters remaining.';

//
// Rate
//
$lang['Votes_label'] = 'Rating';
$lang['Votes'] = 'Votes';
$lang['No_votes'] = 'No votes';
$lang['Rate'] = 'Rate Article';
$lang['ADD_RATING'] = '[Rate Article]';
$lang['Rerror'] = 'Sorry, you have already rated this article.';
$lang['Rateinfo'] = 'You are about to rate the article <i>{filename}</i>.<br />Please select a rating. 1 is the worst, 10 is the best.';
$lang['Rconf'] = 'You have given <i>{filename}</i> a rating of {rate}.<br />This makes the files new rating {newrating}.';
$lang['R1'] = '1';
$lang['R2'] = '2';
$lang['R3'] = '3';
$lang['R4'] = '4';
$lang['R5'] = '5';
$lang['R6'] = '6';
$lang['R7'] = '7';
$lang['R8'] = '8';
$lang['R9'] = '9';
$lang['R10'] = '10';
$lang['Click_return_rate'] = 'Click %sHere%s to return to article';

//
// App
//

//
// Menu
//
$lang['sd_Project'] = 'Project';
$lang['sd_Management'] = 'Management';
$lang['sd_Doc_view'] = 'View Document';
$lang['sd_Options'] = 'Options';
$lang['sd_Help'] = 'Help';
$lang['sd_Contents'] = 'Contents *';
$lang['sd_About'] = 'About *';

//
// Tree
//
$lang['sd_Tree_View'] = 'Tree View';
$lang['sd_Toc'] = 'Table of Contents';
$lang['sd_Where'] = 'Where';
$lang['sd_Before'] = 'Before';
$lang['sd_After'] = 'After';
$lang['sd_Type'] = 'Type';
$lang['sd_Name'] = 'Name';
$lang['sd_Document'] = 'Document';
$lang['sd_Folder'] = 'Folder';

//
// Index
//
$lang['sd_Doc_info'] = 'Document Info';
$lang['sd_Doc_preview'] = 'Preview Document';
$lang['sd_Edit_content'] = 'Edit content';
$lang['sd_Default_edit'] = 'open Edit Content by default';
$lang['sd_Loading'] = 'Loading data ..';
$lang['sd_Saving'] = 'Saving data ..';

//
// Generic Type strings
// - Types are matched against these lang keys...where 'NAME' is the db defined type name
//
$lang['KB_type_NAME'] = 'Example Type';
?>