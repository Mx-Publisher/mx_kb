<?php
/**
*
* @package MX-Publisher Module - mx_kb
* @version $Id: lang_admin.php,v 1.3 2013/06/17 15:47:48 orynider Exp $
* @copyright (c) 2002-2006 [Jon Ohlsson, Mohd Basri, wGEric, PHP Arena, pafileDB, CRLin] MX-Publisher Project Team
* @license http://opensource.org/licenses/gpl-license.php GNU General Public License v2
*
*/

//
// adminCP index
//
$lang['KB_title'] = 'Knowledge Base';
$lang['1_Configuration'] = 'General Settings';
$lang['2_Cat_man'] = 'Category Manager';
$lang['3_Art_man'] = 'Article Manager';
$lang['4_Permissions'] = 'Permissions';
$lang['5_Types_man'] = 'Types Manager';
$lang['6_Custom_Field'] = 'Custom Fields';
$lang['7_Optimize_tables'] = 'Optimize Search Table';

//
// Parameter Types
//
$lang['ParType_kb_type_select'] = 'Advanced phpBB Source Forum Selection';
$lang['ParType_kb_type_select_info'] = '';
$lang['ParType_default_article_id'] = 'Default Article';
$lang['ParType_default_article_id_info'] = '- Article Reader';

$lang['ParType_kb_quick_cat'] = 'KB default category';
$lang['ParType_kb_quick_cat_info'] = '';

//
// Parameter Names
//
$lang['kb_type_select'] = 'KB Source:';
$lang['kb_type_select_explain'] = 'Select Source KB categories and article types';

$lang['default_article_id'] = 'Default Article:';
$lang['default_article_id_explain'] = '- This article is default (first) displayed if noone else is chosen';

//
// Admin Panels - Configuration
//
$lang['Panel_config_title'] = 'Knowledge Base Configuration';
$lang['Panel_config_explain'] = 'Change the configuration of your Knowledge Base';

//
// General
//
$lang['General_title'] = 'General';

$lang['Module_name'] = 'Module name';
$lang['Module_name_explain'] = '';

$lang['Enable_module'] = 'Enable this module';
$lang['Enable_module_explain'] = 'Let users view and post new articles on in your Knowledge Base.';

$lang['Wysiwyg_path'] = 'Path to WYSIWYG software';
$lang['Wysiwyg_path_explain'] = 'This is the path (from MX-Publisher/phpBB root) to the WYSIWYG software folder, eg \'modules/mx_shared/\' if you have uploaded, for example, TinyMCE in modules/mx_shared/tinymce.';

$lang['Allow_file'] = 'Allow Adding article';
$lang['Allow_file_info'] = 'If you are not allowed to add articles in this category it will be a higher level category.';

//
// Article
//
$lang['Article_title'] = 'Article';

//
// Appearance
//
$lang['Appearance_title'] = 'Appearance';

$lang['Article_pag'] = 'Article pagination';
$lang['Article_pag_explain'] = 'The number of articles to show in a (stats) category before pagination.';

$lang['Sort_method'] = 'Sorting method';
$lang['Sort_method_explain'] = 'Define how articles are sorted within its category.';

$lang['Sort_order'] = 'ASC or DESC sorting';
$lang['Sort_order_explain'] = '';

$lang['Stats_list'] = 'Show KB Stats links';
$lang['Stats_list_explain'] = 'Show KB stats links in the header.';

$lang['Header_banner'] = 'Show Top Logo';
$lang['Header_banner_explain'] = 'Show KB logo in the header.';

$lang['Use_simple_navigation'] = 'Simple Category Navigation';
$lang['Use_simple_navigation_explain'] = 'If you prefer, this will generate more simple categories and other navigation';

$lang['Cat_col'] = 'How many column of categories are to be listed (only used for \'Simple Category Navigation\')';

$lang['Nfdays'] = 'New Article Days';
$lang['Nfdaysinfo'] = 'How many days a new article is to be listed with a \'New Article\' icon. If this is set to 5, then all articles added within the past 5 days will have the \'New Article\' icon';

//
// Comments
//
$lang['Comments_title'] = 'Comments';
$lang['Comments_title_explain'] = 'Some comments settings are default settings, and can be overridden per category';

$lang['Use_comments'] = 'Comments';
$lang['Use_comments_explain'] = 'Enable comments for articles, to be inserted in the forum';

$lang['Internal_comments'] = 'Internal or phpBB Comments';
$lang['Internal_comments_explain'] = 'Use internal comments, or phpBB comments';

$lang['Internal_comments_phpBB'] = 'phpBB Comments';
$lang['Internal_comments_internal'] = 'Internal Comments';

$lang['Select_topic_id'] = 'Select phpBB Comments Topic!';

$lang['Forum_id'] = 'phpBB Forum ID';
$lang['Forum_id_explain'] = 'If phpBB comments are used, this is the forum where the comments will be kept';

$lang['Autogenerate_comments'] = 'Autogenerate comments when articles are managed';
$lang['Autogenerate_comments_explain'] = 'When editing/adding an article, a notifying reply is posted in the article topic.';

$lang['Del_topic'] = 'Delete Topic';
$lang['Del_topic_explain'] = 'When you delete an article, do you want its comments topic to be deleted also?';

$lang['Comments_pag'] = 'Comments pagination';
$lang['Comments_pag_explain'] = 'The number of comments to show for the article before pagination.';

$lang['Allow_Wysiwyg'] = 'Use WYSIWYG editor';
$lang['Allow_Wysiwyg_explain'] = 'If enabled, the standard BBCode/HTML/Smilies input dialog is replaced by a WYSIWYG editor.';

$lang['Allow_links'] = 'Allow Links';
$lang['Allow_links_message'] = 'Default \'No Links\' Message';
$lang['Allow_links_explain'] = 'If links are not allowed this text will be displayed instead';

$lang['Allow_images'] = 'Allow Images';
$lang['Allow_images_message'] = 'Default \'No Images\' Message';
$lang['Allow_images_explain'] = 'If images are not allowed this text will be displayed instead';

$lang['Max_subject_char'] = 'Maximum Number of charcters in subject';
$lang['Max_subject_char_explain'] = 'If to big, you get an error message (Limit the subject).';

$lang['Max_desc_char'] = 'Maximum Number of charcters in description';
$lang['Max_desc_char_explain'] = 'If to big, you get an error message (Limit the subject).';

$lang['Max_char'] = 'Maximum Number of charcters in text';
$lang['Max_char_explain'] = 'If to big, you get an error message (Limit the comment).';

$lang['Format_wordwrap'] = 'Word wrapping';
$lang['Format_wordwrap_explain'] = 'Text control filter';

$lang['Format_truncate_links'] = 'Truncate Links';
$lang['Format_truncate_links_explain'] = 'Links are shortened, eg t ex \'www.mxp-portal...\'';

$lang['Format_image_resize'] = 'Image resize';
$lang['Format_image_resize_explain'] = 'Resize images to this width (pixels)';

//
// Ratings
//
$lang['Ratings_title'] = 'Ratings';
$lang['Ratings_title_explain'] = 'Some ratings settings are default settings, and can be overridden per category';

$lang['Use_ratings'] = 'Ratings';
$lang['Use_ratings_explain'] = 'Enable ratings';

$lang['Votes_check_ip'] = 'Validate ratings - IP';
$lang['Votes_check_ip_explain'] = 'Only one vote per IP address is permitted.';

$lang['Votes_check_userid'] = 'Validate ratings - User';
$lang['Votes_check_userid_explain'] = 'Users may only vote once.';

//
// Instructions
//
$lang['Instructions_title'] = 'User Instructions';

$lang['Pre_text_name'] = 'Article Submission Instructions';
$lang['Pre_text_explain'] = 'Activate Submission Instructions displayed to users at the top of the submission forum.';

$lang['Pre_text_header'] = 'Article Submission Instructions Header';
$lang['Pre_text_body'] = 'Article Submission Instructions Body';

$lang['Show'] = 'Show';
$lang['Hide'] = 'Hide';

//
// Notifications
//
$lang['Notifications_title'] = 'Notification';

$lang['Notify'] = 'Notify admin by';
$lang['Notify_explain'] = 'Choose which way to receive notices that new articles have been posted';
$lang['PM'] = 'PM';
$lang['Notify_group'] = 'and groupmembers ';
$lang['Notify_group_explain'] = 'Also send notification to members in this group';

$lang['Click_return_kb_config'] = 'Click %sHere%s to return to Knowledge Base Configuration';
$lang['KB_config_updated'] = 'Knowledge Base Configuration Updated Successfully.';


$lang['KB_config'] = 'KB Configuration';
$lang['Art_types'] = 'Article Types';




$lang['Mod_group'] = 'KB Moderator Group';
$lang['Mod_group_explain'] = '- with KB Admin permissions!';

//
// General
//
$lang['Article'] = 'Article';
$lang['Articles'] = 'Articles';
$lang['Article_description'] = 'Description';

$lang['Article_category'] = 'Category';

$lang['Category'] = 'Category';
$lang['Category_desc'] = 'Category description';

$lang['Article_type'] = 'Type';
$lang['Art_action'] = 'Action';

//
// Admin Panels - Article
//
$lang['Panel_art_title'] = 'Article administration';
$lang['Panel_art_explain'] = 'Here you can approve articles so users can view them, or you can delete articles.';

//approve
$lang['Art_edit'] = 'Edited Articles';
$lang['Art_not_approved'] = 'Not Approved';
$lang['Art_approved'] = 'Approved';
$lang['Approve'] = 'Approve';
$lang['Un_approve'] = 'Un-Approve';
$lang['Article_approved'] = 'Article is now Approved.';
$lang['Article_unapproved'] = 'Article is now Unapproved.';

//delete
$lang['Delete'] = 'Delete';
$lang['Confirm_art_delete'] = 'Are you sure you want to delete this article?';
$lang['Confirm_art_delete_yes'] = '%sYes, I want to delete this article%s';
$lang['Confirm_art_delete_no'] = '%sNo, I don\'t want to delete this article%s';
$lang['Article_deleted'] = 'Article Deleted Successfully.';

$lang['Click_return_article_manager'] = 'Click %sHere%s to return to the Article Manager';

//
// Admin Panels - Category
//
$lang['Panel_cat_title'] = 'Category administration';
$lang['Panel_cat_explain'] = 'Here you can add, edit, or delete categories in the Knowledge Base';

$lang['Use_default'] = 'Use default setting';

$lang['Create_cat'] = 'Create New Category:';
$lang['Create'] = 'Create';
$lang['Cat_settings'] = 'Category Settings';
$lang['Create_description'] = 'Here you can change the name of the category and add a description to the new category.';
$lang['Cat_created'] = 'Category Created Successfully.';
$lang['Click_return_cat_manager'] = 'Click %sHere%s to return to the ' . $lang['2_Cat_man'];
$lang['Edit_description'] = 'Here you can edit the settings of your category';
$lang['Edit_cat'] = 'Edit Category';
$lang['Cat_edited'] = 'Category Edited Successfully.';
$lang['Parent'] = 'Parent';

$lang['Cat_delete_title'] = 'Delete Category';
$lang['Cat_delete_desc'] = 'Here you can delete a category and move all of the articles in it to a new category';
$lang['Cat_deleted'] = 'Category Deleted Successfully.';
$lang['Delete_all_articles'] = 'Delete Articles';

//
// Admin Panels - Permissions
//
$lang['KB_Auth_Title'] = 'KB Permissions';
$lang['KB_Auth_Explain'] = 'Here you can choose which usergroup(s) can be the moderators for each KB category, or just has the private access';
$lang['Select_a_Category'] = 'Select a Category';
$lang['Look_up_Category'] = 'Look up Category';
$lang['KB_Auth_successfully'] = 'Auth has been updated successfully';
$lang['Click_return_KB_auth'] = 'Click %sHere%s to return to the KB Permissions';

$lang['Upload'] = 'Upload';
$lang['Rate'] = 'Rate';
$lang['Comment'] = 'Comment';
$lang['Approval'] = 'Approval';
$lang['Approval_edit'] = 'Approval Edit';

$lang['Allow_rating'] = 'Allow ratings';
$lang['Allow_rating_explain'] = 'Users are allowed to rate articles.';

$lang['Allow_anonymos_rating'] = 'Allow anonymous ratings';
$lang['Allow_anonymos_rating_explain'] = 'If ratings are activated, allow anonymous users to add ratings to your articles';

$lang['Category_Permissions'] = 'Category Permissions';
$lang['Category_Title'] = 'Category Title';
$lang['Category_Desc'] = 'Category Description';
$lang['View_level'] = 'View Level';
$lang['Upload_level'] = 'Upload Level';
$lang['Rate_level'] = 'Rate Level';
$lang['View_Comment_level'] = 'View Comment';
$lang['Post_Comment_level'] = 'Post Comment';
$lang['Edit_Comment_level'] = 'Edit Comment';
$lang['Delete_Comment_level'] = 'Delete Comment';
$lang['Edit_level'] = ' Edit Level';
$lang['Delete_level'] = 'Delete Level';
$lang['Approval_level'] = 'Approval Level';
$lang['Approval_edit_level'] = 'Approval Edit Level';

//
// Admin Panels - Types
//
$lang['Types_man'] = 'Types Manager';
$lang['KB_types_description'] = 'Here you can add, delete, and/or edit the different article types';
$lang['Create_type'] = 'Create new Article Type:';
$lang['Type_created'] = 'Article Type Created Successfully.';
$lang['Click_return_type_manager'] = 'Click %sHere%s to return to the Types Manager';

$lang['Edit_type'] = 'Edit Type';
$lang['Edit_type_description'] = 'Here you can edit the name of the type';
$lang['Type_edited'] = 'Article Type Edited Successfully.';

$lang['Type_delete_title'] = 'Delete Article Type';
$lang['Type_delete_desc'] = 'Here you can change what the article type is of the articles that have the type you are deleting.';
$lang['Change_type'] = 'Change article\'s type to';
$lang['Change_and_Delete'] = 'Change and Delete';
$lang['Type_deleted'] = 'Article Type Deleted Successfully.';

//
// Admin Panels - Custom Field
//
$lang['Fieldselecttitle'] = 'Select what to do';
$lang['Afield'] = 'Custom Field: Add';
$lang['Efield'] = 'Custom Field: Edit';
$lang['Dfield'] = 'Custom Field: Delete';
$lang['Mfieldtitle'] = 'Custom Fields';
$lang['Afieldtitle'] = 'Add Field';
$lang['Efieldtitle'] = 'Edit Field';
$lang['Dfieldtitle'] = 'Delete Field';
$lang['Fieldexplain'] = 'You can use the custom fields management section to add, edit, and delete custom fields. You can use custom fields to add more information about an article.';
$lang['Fieldname'] = 'Field Name';
$lang['Fieldnameinfo'] = 'This is the name of the field, for example \'File Size\'';
$lang['Fielddesc'] = 'Field Description';
$lang['Fielddescinfo'] = 'This is a description of the field, for example \'File Size in Megabytes\'';
$lang['Fieldadded'] = 'The custom field has been successfully added';
$lang['Fieldedited'] = 'The custom field you selected has been successfully edited';
$lang['Dfielderror'] = 'You didn\'t select any fields to delete';
$lang['Fieldsdel'] = 'The custom fields you selected have been successfully deleted';

$lang['Field_data'] = 'Options';
$lang['Field_data_info'] = 'Enter the options that the user can choose from. Separate each option with a newline (carriage return).';
$lang['Field_regex'] = 'Regular Expression';
$lang['Field_regex_info'] = 'You may require the input field to match a regular expression %s(PCRE)%s.';
$lang['Field_order'] = 'Display Order';

$lang['Click_return'] = 'Click %sHere%s to return to the previous page';

// These are displayed in the drop down boxes for advanced
// mode auth, try and keep them short!
$lang['Cat_NONE'] = 'NONE';
$lang['Cat_ALL'] = 'ALL';
$lang['Cat_REG'] = 'REG';
$lang['Cat_PRIVATE'] = 'PRIVATE';
$lang['Cat_MOD'] = 'MOD';
$lang['Cat_ADMIN'] = 'ADMIN';

//
// Admin Panels - Field Types
//
$lang['Field_Input'] = 'Single-Line Text Box';
$lang['Field_Textarea'] = 'Multiple-Line Text Box';
$lang['Field_Radio'] = 'Single-Selection Radio Buttons';
$lang['Field_Select'] = 'Single-Selection Menu';
$lang['Field_Select_multiple'] = 'Multiple-Selection Menu';
$lang['Field_Checkbox'] = 'Multiple-Selection Checkbox';

//
// Admin Panels - Toplists
//
$lang['toplist_sort_method']		= 'Toplist type';
$lang['toplist_display_options']	= 'Display options';
$lang['toplist_use_pagination']		= 'Use Pagination (Previous/Next \'Number of rows\')';
$lang['toplist_pagination']			= 'Number of rows';
$lang['toplist_filter_date']			= "Filter by time";
$lang['toplist_filter_date_explain']	= "- Show posts from last week, month, year...";
$lang['toplist_cat_id']				= 'Limit to category';
$lang['target_block']				= 'Associated (target) KB Block';

//
// Admin Panels - Mini
//
$lang['mini_display_options']		= 'Display options';
$lang['mini_pagination']			= 'Number of rows';
$lang['mini_default_cat_id']			= 'Limit to category';

?>