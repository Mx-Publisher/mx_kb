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
 *    $Id: lang_main.php,v 1.7 2005/12/16 03:28:10 mennonitehobbit Exp $
 */

/**
 * This program is free software; you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation; either version 2 of the License, or
 *    (at your option) any later version.
 */

$lang['KB_title'] = 'Knowledge Base';
$lang['Article'] = 'Article';
$lang['Category'] = 'Category';
$lang['Sub_categories'] = 'Subcategories';
$lang['Article_description'] = 'Description';
$lang['Article_type'] = 'Type';
$lang['Article_keywords'] = 'Keywords';
$lang['Articles'] = 'Articles';
$lang['Add_article'] = 'Add Article';
$lang['Click_cat_to_add'] = 'Click on Category to add Article';
$lang['KB_Home'] = 'KB Home';
$lang['No_articles'] = 'No Articles';
$lang['Article_title'] = 'Article Name';
$lang['Article_text'] = 'Article text';
$lang['Add_article'] = 'Submit Article';
$lang['Read_article'] = 'Reading Article';
$lang['Article_not_exsist'] = 'Article doesn\'t exist';
$lang['Category_not_exsist'] = 'Category doesn\'t exist';

$lang['Edit'] = 'Edit';

$lang['Standalone_Not_Supported'] = 'This module does not support standalone usage. In the AdminCP, add the KB block to a portal page.';

$lang['Article_submitted_Approve'] = 'Article Submitted Successfully.<br />An Administrator will review your article and decide whether to let users view it or not.';
$lang['Article_submitted'] = 'Article Submitted Successfully.';
$lang['Click_return_kb'] = 'Click %sHere%s to return to the ' . $lang['KB_title'];
$lang['Click_return_article'] = 'Click %sHere%s to return to the ' . $lang['Article'];

$lang['Article_Edited_Approve'] = 'Article Edited Successfully.<br />It needs to be approved again before users can view it.';
$lang['Article_Edited'] = 'Article Edited Successfully.';
$lang['Edit_article'] = 'Edit Article';

$lang['KB_title'] = 'Knowledge Base';

$lang['KB_notify_subject_new'] = 'New Article!';
$lang['KB_notify_subject_edited'] = 'Edited Article!';
$lang['KB_notify_subject_approved'] = 'Approved Article!';
$lang['KB_notify_subject_unapproved'] = 'Unapproved Article!';
$lang['KB_notify_body'] = 'An article has been submitted or modified:'; 

$lang['KB_Rules_post_can'] = 'You <b>can</b> post new articles in this category';
$lang['KB_Rules_post_cannot'] = 'You <b>cannot</b> post new articles in this category';
$lang['KB_Rules_comment_can'] = 'You <b>can</b> comment articles in this category';
$lang['KB_Rules_comment_cannot'] = 'You <b>cannot</b> comment articles in this category';
$lang['KB_Rules_edit_can'] = 'You <b>can</b> edit your articles in this category';
$lang['KB_Rules_edit_cannot'] = 'You <b>cannot</b> edit your articles in this category';
$lang['KB_Rules_delete_can'] = 'You <b>can</b> delete your articles in this category';
$lang['KB_Rules_delete_cannot'] = 'You <b>cannot</b> delete eyour articles in this category';
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

$lang['Read_full_article'] = '>> Read Full Article';
$lang['Comments'] = 'Comments';

$lang['No_add'] = 'You can\'t add a new article';
$lang['No_edit'] = 'You can\'t edit this article!';
$lang['Post_comments'] = 'Post your comments';

$lang['Category_sub'] = 'Sub-Categories';
$lang['Quick_stats'] = 'Quick Stats';

// added

$lang['Edited_Article_info'] = 'Article updated by ';
$lang['No_Articles'] = 'There are no articles in this cateogry!';
$lang['Not_authorized'] = 'Sorry, but you are not authorized!';
$lang['TOC'] = 'Contents';

// Rate
$lang['Votes_label'] = 'Rating ';
$lang['Votes'] = 'Vote(s)';
$lang['No_votes'] = 'No votes';
$lang['Rate'] = 'Rate Article';
$lang['ADD_RATING'] = '[Rate Article]';
$lang['Rerror'] = 'Sorry, you have already rated this article.';
$lang['Rateinfo'] = 'You are about to rate the article <i>{filename}</i>.<br />Please select a rating. 1 is the worst, 10 is the best.';
$lang['Rconf'] = 'You have given <i>{filename}</i> a rating of {rate}.<br />This makes the files new rating {newrating}/10.';
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

// Print version
$lang['Print_version'] = '[Printable version]';

// Stats
$lang['Top_toprated'] = 'Toprated Articles';
$lang['Top_most_popular'] = 'Most Popular';
$lang['Top_latest'] = 'Latest Articles';

// 
// General strings from the news admin panel
// 

$lang['News_settings'] = "KB Block Settings";
$lang['News_settings_short_explain'] = "Configure some options for the front-page news.";
$lang['News_settings_explain'] = "Here you can edit the configuration for the KB Block. This panel lets you extract what categories the block will display, thus you'll create subinstances of the module.";

// 
// Update result messages
// 

$lang['News_updated_return_settings'] = "KB block configuration updated successfully.<br /><br />Click %shere%s to return to main page."; // %s's for URI params - DO NOT REMOVE
$lang['News_update_error'] = "Couldn't update KB block configuration.<br /><br />This mod is designed for MySQL, so please contact the author if you have troubles. If you can offer a translation of the SQL into other database formats, please send them to:<br />";



// added
$lang['Cat_all'] = "All";

$lang['L_Pages'] = "Pages"; 
$lang['L_Pages_explain'] = "Use the '[pages]' command to split the article into pages"; 
$lang['L_Toc'] = "Table of contents (TOC)"; 
$lang['L_Toc_explain'] = "Use the '[toc]' command to add entry in the TOC"; 
$lang['L_Abstract'] = "Abstract"; 
$lang['L_Abstract_explain'] = "Use the '[abstract]...[/abstract]' environment to insert an abstract"; 

$lang['L_Title_Format'] = "Title"; 
$lang['L_Title_Format_explain'] = "Use the '[title]...[/title]' environment to insert a main title"; 

$lang['L_Subtitle_Format'] = "Subtitle"; 
$lang['L_Subtitle_Format_explain'] = "Use the '[subtitle]...[/subtitle]' environment to insert a subtitle"; 

$lang['L_Subsubtitle_Format'] = "Sub-subtitle"; 
$lang['L_Subsubtitle_Format'] = "Use the '[subsubtitle]...[/subsubtitle]' environment to insert a small header"; 

$lang['L_Options'] = "Options:"; 
$lang['L_Formatting'] = "Formatting:"; 

$lang['Default_article_id'] = "Set default article, for the article viewer";

// Added for v. 2.0

$lang['Addtional_field'] = 'More information (optional)';

$lang['No_cat_comments_forum_id'] = 'Comments are enabled but you have not specified the target phpBB forum category in the KB adminCP - Categories';

// Quick Nav
$lang['Quick_nav'] = 'Quick Navigation';
$lang['Quick_jump'] = 'Select Category';
$lang['Quick_go'] = 'Go';

// Search
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

// Comments
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
$lang['No_comments'] = 'No Comments have been posted yet.';
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

?>