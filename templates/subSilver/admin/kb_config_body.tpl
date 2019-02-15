<h1>{L_CONFIGURATION_TITLE}</h1>

<p>{L_CONFIGURATION_EXPLAIN}</p>

<form action="{S_ACTION}" method="post">
<table width="100%" cellpadding="3" cellspacing="1" border="0" align="center" class="forumline">
	<tr>
		<!-- TITLE ------------------------------------------------------------------------------------------- -->
	  	<th class="thHead" colspan="2">&nbsp;{L_GENERAL_TITLE}</th>
	</tr>
	<tr>
		<td class="row1" width="50%">{L_MODULE_NAME}<br /><span class="gensmall">{L_MODULE_NAME_EXPLAIN}</span></td>
		<td class="row2" width="50%"><input text="text" name="module_name" value="{MODULE_NAME}" size="20" maxlength="50" /></td>
	</tr>
	<tr>
		<td class="row1" width="50%">{L_ENABLE_MODULE}<br /><span class="gensmall">{L_ENABLE_MODULE_EXPLAIN}</span></td>
		<td class="row2" width="50%"><input type="radio" name="enable_module" value="1" {S_ENABLE_MODULE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="enable_module" value="0" {S_ENABLE_MODULE_NO} /> {L_NO}</td>
	</tr>
	<tr>
		<td class="row1" width="50%">{L_WYSIWYG_PATH}<br /><span class="gensmall">{L_WYSIWYG_PATH_EXPLAIN}</span></td>
		<td class="row2" width="50%"><input text="text" name="wysiwyg_path" value="{WYSIWYG_PATH}" size="20" maxlength="50" /></td>
	</tr>
	<tr>
		<!-- TITLE ------------------------------------------------------------------------------------------- -->
	  	<th class="thHead" colspan="2">&nbsp;{L_ARTICLE_TITLE}</th>
	</tr>	
	<tr>
		<td class="row1" width="50%">{L_ALLOW_WYSIWYG}<br /><span class="gensmall">{L_ALLOW_WYSIWYG_EXPLAIN}</span></td>
		<td class="row2" width="50%"><input type="radio" name="allow_wysiwyg" value="1" {S_ALLOW_WYSIWYG_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_wysiwyg" value="0" {S_ALLOW_WYSIWYG_NO} /> {L_NO}</td>
	</tr>
	<tr>
		<td class="row1" width="50%">{L_ALLOW_HTML}<br /><span class="gensmall">{L_ALLOW_HTML_EXPLAIN}</span></td>
		<td class="row2" width="50%"><input class="radio" type="radio" name="allow_html" value="1" {S_ALLOW_HTML_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_html" value="0" {S_ALLOW_HTML_NO} /> {L_NO}</td>
	</tr>
	<tr>
		<td class="row1" width="50%">{L_ALLOWED_HTML_TAGS}<br /><span class="gensmall">{L_ALLOWED_HTML_TAGS_EXPLAIN}</span></td>
		<td class="row2" width="50%"><input text="text" name="allowed_html_tags" value="{ALLOWED_HTML_TAGS}" size="15" maxlength="50" /></td>
	</tr>	
	<tr>
		<td class="row1" width="50%">{L_ALLOW_BBCODE}<br /><span class="gensmall">{L_ALLOW_BBCODE_EXPLAIN}</span></td>
		<td class="row2" width="50%"><input type="radio" name="allow_bbcode" value="1" {S_ALLOW_BBCODE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_bbcode" value="0" {S_ALLOW_BBCODE_NO} /> {L_NO}</td>
	</tr>	
	<tr>
		<td class="row1" width="50%">{L_ALLOW_SMILIES}<br /><span class="gensmall">{L_ALLOW_SMILIES_EXPLAIN}</span></td>
		<td class="row2" width="50%"><input type="radio" name="allow_smilies" value="1" {S_ALLOW_SMILIES_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_smilies" value="0" {S_ALLOW_SMILIES_NO} /> {L_NO}</td>
	</tr>	
	<tr>
		<td class="row1" width="50%">{L_ALLOW_IMAGES}<br /><span class="gensmall">{L_ALLOW_IMAGES_EXPLAIN}</span></td>
		<td class="row2" width="50%"><input type="radio" name="allow_images" value="1" {S_ALLOW_IMAGES_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_images" value="0" {S_ALLOW_IMAGES_NO} /> {L_NO}</td>
	</tr>
  	<tr>
		<td class="row1">{L_IMAGES_MESSAGE}<br><span class="gensmall">{L_IMAGES_MESSAGE_EXPLAIN}</span></td>
		<td class="row2"><input type="text" class="post" size="50" name="no_image_message" value="{MESSAGE_IMAGE}" /></td>
  	</tr>	
	<tr>
		<td class="row1" width="50%">{L_ALLOW_LINKS}<br /><span class="gensmall">{L_ALLOW_LINKS_EXPLAIN}</span></td>
		<td class="row2" width="50%"><input type="radio" name="allow_links" value="1" {S_ALLOW_LINKS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_links" value="0" {S_ALLOW_LINKS_NO} /> {L_NO}</td>
	</tr>	
  	<tr>
		<td class="row1">{L_LINKS_MESSAGE}<br><span class="gensmall">{L_LINKS_MESSAGE_EXPLAIN}</span></td>
		<td class="row2"><input type="text" class="post" size="50" name="no_link_message" value="{MESSAGE_LINK}" /></td>
  	</tr>
	<tr>
		<td class="row1" width="50%">{L_FORMAT_WORDWRAP}<br /><span class="gensmall">{L_FORMAT_WORDWRAP_EXPLAIN}</span></td>
		<td class="row2" width="50%"><input type="radio" name="formatting_wordwrap" value="1" {S_FORMAT_WORDWRAP_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="formatting_wordwrap" value="0" {S_FORMAT_WORDWRAP_NO} /> {L_NO}</td>
	</tr> 
  	<tr>
		<td class="row1">{L_FORMAT_IMAGE_RESIZE}<br><span class="gensmall">{L_FORMAT_IMAGE_RESIZE_EXPLAIN}</span></td>
		<td class="row2"><input type="text" class="post" size="50" name="formatting_image_resize" value="{FORMAT_IMAGE_RESIZE}" /></td>
  	</tr>
	<tr>
		<td class="row1" width="50%">{L_FORMAT_TRUNCATE_LINKS}<br /><span class="gensmall">{L_FORMAT_TRUNCATE_LINKS_EXPLAIN}</span></td>
		<td class="row2" width="50%"><input type="radio" name="formatting_truncate_links" value="1" {S_FORMAT_TRUNCATE_LINKS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="formatting_truncate_links" value="0" {S_FORMAT_TRUNCATE_LINKS_NO} /> {L_NO}</td>
	</tr>
  	<tr>
		<td class="row1">{L_MAX_SUBJECT_CHAR}<br><span class="gensmall">{L_MAX_SUBJECT_CHAR_EXPLAIN}</span></td>
		<td class="row2"><input type="text" class="post" size="50" name="max_subject_chars" value="{MAX_SUBJECT_CHAR}" /></td>
  	</tr>	
  	<tr>
		<td class="row1">{L_MAX_DESC_CHAR}<br><span class="gensmall">{L_MAX_DESC_CHAR_EXPLAIN}</span></td>
		<td class="row2"><input type="text" class="post" size="50" name="max_desc_chars" value="{MAX_DESC_CHAR}" /></td>
  	</tr>  	
  	<tr>
		<td class="row1">{L_MAX_CHAR}<br><span class="gensmall">{L_MAX_CHAR_EXPLAIN}</span></td>
		<td class="row2"><input type="text" class="post" size="50" name="max_chars" value="{MAX_CHAR}" /></td>
  	</tr>  		
	<tr>
		<!-- TITLE ------------------------------------------------------------------------------------------- -->
	  	<th class="thHead" colspan="2">&nbsp;{L_APPEARANCE_TITLE}</th>
	</tr>
	<tr>
		<td class="row1" width="50%">{L_STATS_LIST}<br /><span class="gensmall">{L_STATS_LIST_EXPLAIN}</span></td>
		<td class="row2" width="50%"><input type="radio" name="stats_list" value="1" {S_STATS_LIST_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="stats_list" value="0" {S_STATS_LIST_NO} /> {L_NO}</td>
	</tr>	
	<tr>
		<td class="row1" width="50%">{L_HEADER_BANNER}<br /><span class="gensmall">{L_HEADER_BANNER_EXPLAIN}</span></td>
		<td class="row2" width="50%"><input type="radio" name="header_banner" value="1" {S_HEADER_BANNER_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="header_banner" value="0" {S_HEADER_BANNER_NO} /> {L_NO}</td>
	</tr>			
	<tr>
		<td class="row1" width="50%">{L_PAGINATION}<br /><span class="gensmall">{L_PAGINATION_EXPLAIN}</span></td>
		<td class="row2" width="50%"><input class="post" type="text" name="pagination" value="{PAGINATION}" size="5" maxlength="4" /></td>
	</tr>	
	<tr>
		<td class="row1" width="50%">{L_SORT_METHOD}<br /><span class="gensmall">{L_SORT_METHOD_EXPLAIN}</span></td>
		<td class="row2" width="50%">{SORT_METHOD} </td>
	</tr>
	<tr>
		<td class="row1" width="50%">{L_SORT_ORDER}<br /><span class="gensmall">{L_SORT_ORDER_EXPLAIN}</span></td>
		<td class="row2" width="50%">{SORT_ORDER} </td>
	</tr>	
	<tr>
		<!-- TITLE ------------------------------------------------------------------------------------------- -->
	  	<th class="thHead" colspan="2">&nbsp;{L_COMMENTS_TITLE}<br /><span class="gensmall">{L_COMMENTS_TITLE_EXPLAIN}</span></th>
	</tr>
	<tr>
		<td class="row1" width="50%">{L_USE_COMMENTS}<br /><span class="gensmall">{L_USE_COMMENTS_EXPLAIN}</span></td>
		<td class="row2" width="50%"><input type="radio" name="use_comments" value="1" {S_USE_COMMENTS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="use_comments" value="0" {S_USE_COMMENTS_NO} /> {L_NO}</td>
	</tr>	
	<tr>
		<td class="row1" width="50%">{L_INTERNAL_COMMENTS}<br /><span class="gensmall">{L_INTERNAL_COMMENTS_EXPLAIN}</span></td>
		<td class="row2" width="50%"><input type="radio" name="internal_comments" value="1" {S_INTERNAL_COMMENTS_INTERNAL} /> {L_INTERNAL_COMMENTS_INTERNAL}&nbsp;&nbsp;<input type="radio" name="internal_comments" value="0" {S_INTERNAL_COMMENTS_PHPBB} /> {L_INTERNAL_COMMENTS_PHPBB}</td>
	</tr>	
	<tr>
		<td class="row1" width="50%">{L_COMMENTS_PAG}<br /><span class="gensmall">{L_COMMENTS_PAG_EXPLAIN}</span></td>
		<td class="row2" width="50%"><input class="post" type="text" name="comments_pagination" value="{COMMENTS_PAG}" size="5" maxlength="4" /></td>
	</tr>	
	<tr> 
        <td class="row1" width="50%">{L_AUTOGENERATE_COMMENTS}<br /><span class="gensmall">{L_AUTOGENERATE_COMMENTS_EXPLAIN}</span></td> 
        <td class="row2" width="50%"><input type="radio" name="autogenerate_comments" value="1" {S_AUTOGENERATE_COMMENTS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="autogenerate_comments" value="0" {S_AUTOGENERATE_COMMENTS_NO} /> {L_NO}</td> 
    </tr>
	<tr>
		<td class="row1" width="50%">{L_DEL_TOPIC}<br /><span class="gensmall">{L_DEL_TOPIC_EXPLAIN}</span></td>
		<td class="row2" width="50%"><input type="radio" name="del_topic" value="1" {S_DEL_TOPIC_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="del_topic" value="0" {S_DEL_TOPIC_NO} /> {L_NO}</td>
	</tr>	
	<tr>
		<td class="row1" width="50%">{L_ALLOW_COMMENT_WYSIWYG}<br /><span class="gensmall">{L_ALLOW_COMMENT_WYSIWYG_EXPLAIN}</span></td>
		<td class="row2" width="50%"><input type="radio" name="allow_comment_wysiwyg" value="1" {S_ALLOW_COMMENT_WYSIWYG_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_comment_wysiwyg" value="0" {S_ALLOW_COMMENT_WYSIWYG_NO} /> {L_NO}</td>
	</tr>
	<tr>
		<td class="row1" width="50%">{L_ALLOW_COMMENT_HTML}<br /><span class="gensmall">{L_ALLOW_COMMENT_HTML_EXPLAIN}</span></td>
		<td class="row2" width="50%"><input class="radio" type="radio" name="allow_comment_html" value="1" {S_ALLOW_COMMENT_HTML_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_comment_html" value="0" {S_ALLOW_COMMENT_HTML_NO} /> {L_NO}</td>
	</tr>
	<tr>
		<td class="row1" width="50%">{L_ALLOWED_COMMENT_HTML_TAGS}<br /><span class="gensmall">{L_ALLOWED_HTML_TAGS_EXPLAIN}</span></td>
		<td class="row2" width="50%"><input text="text" name="allowed_comment_html_tags" value="{ALLOWED_COMMENT_HTML_TAGS}" size="15" maxlength="50" /></td>
	</tr>	
	<tr>
		<td class="row1" width="50%">{L_ALLOW_COMMENT_BBCODE}<br /><span class="gensmall">{L_ALLOW_BBCODE_EXPLAIN}</span></td>
		<td class="row2" width="50%"><input type="radio" name="allow_comment_bbcode" value="1" {S_ALLOW_COMMENT_BBCODE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_comment_bbcode" value="0" {S_ALLOW_COMMENT_BBCODE_NO} /> {L_NO}</td>
	</tr>	
	<tr>
		<td class="row1" width="50%">{L_ALLOW_COMMENT_SMILIES}<br /><span class="gensmall">{L_ALLOW_SMILIES_EXPLAIN}</span></td>
		<td class="row2" width="50%"><input type="radio" name="allow_comment_smilies" value="1" {S_ALLOW_COMMENT_SMILIES_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_comment_smilies" value="0" {S_ALLOW_COMMENT_SMILIES_NO} /> {L_NO}</td>
	</tr>	
	<tr>
		<td class="row1" width="50%">{L_ALLOW_COMMENT_IMAGES}<br /><span class="gensmall">{L_ALLOW_IMAGES_EXPLAIN}</span></td>
		<td class="row2" width="50%"><input type="radio" name="allow_comment_images" value="1" {S_ALLOW_COMMENT_IMAGES_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_comment_images" value="0" {S_ALLOW_COMMENT_IMAGES_NO} /> {L_NO}</td>
	</tr>
  	<tr>
		<td class="row1">{L_COMMENT_IMAGES_MESSAGE}<br><span class="gensmall">{L_COMMENT_IMAGES_MESSAGE_EXPLAIN}</span></td>
		<td class="row2"><input type="text" class="post" size="50" name="no_comment_image_message" value="{COMMENT_MESSAGE_IMAGE}" /></td>
  	</tr>	
	<tr>
		<td class="row1" width="50%">{L_ALLOW_COMMENT_LINKS}<br /><span class="gensmall">{L_ALLOW_COMMENT_LINKS_EXPLAIN}</span></td>
		<td class="row2" width="50%"><input type="radio" name="allow_comment_links" value="1" {S_ALLOW_COMMENT_LINKS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_comment_links" value="0" {S_ALLOW_COMMENT_LINKS_NO} /> {L_NO}</td>
	</tr>	
  	<tr>
		<td class="row1">{L_COMMENT_LINKS_MESSAGE}<br><span class="gensmall">{L_COMMENT_LINKS_MESSAGE_EXPLAIN}</span></td>
		<td class="row2"><input type="text" class="post" size="50" name="no_comment_link_message" value="{COMMENT_MESSAGE_LINK}" /></td>
  	</tr>
	<tr>
		<td class="row1" width="50%">{L_COMMENT_FORMAT_WORDWRAP}<br /><span class="gensmall">{L_COMMENT_FORMAT_WORDWRAP_EXPLAIN}</span></td>
		<td class="row2" width="50%"><input type="radio" name="formatting_comment_wordwrap" value="1" {S_COMMENT_FORMAT_WORDWRAP_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="formatting_comment_wordwrap" value="0" {S_COMMENT_FORMAT_WORDWRAP_NO} /> {L_NO}</td>
	</tr> 
  	<tr>
		<td class="row1">{L_COMMENT_FORMAT_IMAGE_RESIZE}<br><span class="gensmall">{L_COMMENT_FORMAT_IMAGE_RESIZE_EXPLAIN}</span></td>
		<td class="row2"><input type="text" class="post" size="50" name="formatting_comment_image_resize" value="{COMMENT_FORMAT_IMAGE_RESIZE}" /></td>
  	</tr>
	<tr>
		<td class="row1" width="50%">{L_COMMENT_FORMAT_TRUNCATE_LINKS}<br /><span class="gensmall">{L_COMMENT_FORMAT_TRUNCATE_LINKS_EXPLAIN}</span></td>
		<td class="row2" width="50%"><input type="radio" name="formatting_comment_truncate_links" value="1" {S_COMMENT_FORMAT_TRUNCATE_LINKS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="formatting_comment_truncate_links" value="0" {S_COMMENT_FORMAT_TRUNCATE_LINKS_NO} /> {L_NO}</td>
	</tr>
  	<tr>
		<td class="row1">{L_COMMENT_MAX_SUBJECT_CHAR}<br><span class="gensmall">{L_COMMENT_MAX_SUBJECT_CHAR_EXPLAIN}</span></td>
		<td class="row2"><input type="text" class="post" size="50" name="max_comment_subject_chars" value="{COMMENT_MAX_SUBJECT_CHAR}" /></td>
  	</tr>	
  	<tr>
		<td class="row1">{L_COMMENT_MAX_CHAR}<br><span class="gensmall">{L_COMMENT_MAX_CHAR_EXPLAIN}</span></td>
		<td class="row2"><input type="text" class="post" size="50" name="max_comment_chars" value="{COMMENT_MAX_CHAR}" /></td>
  	</tr>			
	<tr>
		<!-- TITLE ------------------------------------------------------------------------------------------- -->
	  	<th class="thHead" colspan="2">&nbsp;{L_RATINGS_TITLE}<br /><span class="gensmall">{L_RATINGS_TITLE_EXPLAIN}</span></th>
	</tr>
	<tr>
		<td class="row1" width="50%">{L_USE_RATINGS}<br /><span class="gensmall">{L_USE_RATINGS_EXPLAIN}</span></td>
		<td class="row2" width="50%"><input type="radio" name="use_ratings" value="1" {S_USE_RATINGS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="use_ratings" value="0" {S_USE_RATINGS_NO} /> {L_NO}</td>
	</tr>
	<tr>
		<td class="row1" width="50%">{L_VOTES_CHECK_IP}<br /><span class="gensmall">{L_VOTES_CHECK_IP_EXPLAIN}</span></td>
		<td class="row2" width="50%"><input type="radio" name="votes_check_ip" value="1" {S_VOTES_CHECK_IP_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="votes_check_ip" value="0" {S_VOTES_CHECK_IP_NO} /> {L_NO}</td>
	</tr>
	<tr>
		<td class="row1" width="50%">{L_VOTES_CHECK_USERID}<br /><span class="gensmall">{L_VOTES_CHECK_USERID_EXPLAIN}</span></td>
		<td class="row2" width="50%"><input type="radio" name="votes_check_userid" value="1" {S_VOTES_CHECK_USERID_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="votes_check_userid" value="0" {S_VOTES_CHECK_USERID_NO} /> {L_NO}</td>
	</tr>		
	<tr>
		<!-- TITLE ------------------------------------------------------------------------------------------- -->
	  	<th class="thHead" colspan="2">&nbsp;{L_INSTRUCTIONS_TITLE}</th>
	</tr>
	<tr>
		<td class="row1" width="50%">{L_PRE_TEXT_NAME}<br /><span class="gensmall">{L_PRE_TEXT_EXPLAIN}</span></td>
		<td class="row2" width="50%"><input type="radio" name="show_pretext" value="1" {S_SHOW_PRETEXT} /> {L_SHOW}&nbsp;&nbsp;<input type="radio" name="show_pretext" value="0" {S_HIDE_PRETEXT} /> {L_HIDE}</td>
	</tr>
	<tr>
		<td class="row1" width="50%">{L_PRE_TEXT_HEADER}</td>
		<td class="row2" width="50%"><input text="text" name="pt_header" value="{L_PT_HEADER}" size="40" maxlength="100" /></td>
	</tr>
	<tr>
		<td class="row1" width="50%">{L_PRE_TEXT_BODY}</td>
		<td class="row2" width="50%"><textarea name="pt_body" cols="40" rows="5">{L_PT_BODY}</textarea></td>
	</tr>	
	<tr>
		<!-- TITLE ------------------------------------------------------------------------------------------- -->
	  	<th class="thHead" colspan="2">&nbsp;{L_NOTIFICATIONS_TITLE}</th>
	</tr>
	<tr>
		<td class="row1" width="50%">{L_NOTIFY}<br /><span class="gensmall">{L_NOTIFY_EXPLAIN}</span></td>
		<td class="row2" width="50%"><input type="radio" name="notify" value="0" {S_NOTIFY_NONE} />{L_NONE}&nbsp; &nbsp;<input type="radio" name="notify" value="2" {S_NOTIFY_EMAIL} />{L_EMAIL}&nbsp; &nbsp;<input type="radio" name="notify" value="1" {S_NOTIFY_PM} />{L_PM}</td>
	</tr>
	<tr>
		<td class="row1" width="50%">{L_NOTIFY_GROUP}<br /><span class="gensmall">{L_NOTIFY_GROUP_EXPLAIN}</span></td>
		<td class="row2" width="50%">{NOTIFY_GROUP}</td>
	</tr>
	<tr>
		<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
	</tr>
</table>
</form>