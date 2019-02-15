<script language="JavaScript" type="text/javascript">
<!--
	var error_msg = "";
	function checkAddForm()
	{
		error_msg = "";

		if(document.form.catname.value == "")
		{
			error_msg += "Empty Category name!!";
		}

		if(error_msg != "")
		{
			alert(error_msg);
			error_msg = "";
			return false;
		}
		else
		{
			return true;
		}
	}
// -->
</script>

<h1>{L_EDIT_TITLE}</h1>

<p>{L_EDIT_DESCRIPTION}</p>

<form action="{S_ACTION}" method="post" name="form" onsubmit="return checkAddForm();">
<table width="100%" cellpadding="4" cellspacing="1" border="0" class="forumline" align="center">
	<tr>
	  <th class="thHead" colspan="2">{L_CAT_SETTINGS}</th>
	</tr>
	<tr>
	  <td class="row1">{L_CATEGORY}</td>
	  <td class="row2"><input class="post" type="text" size="25" name="catname" value="{CAT_NAME}" class="post" /></td>
	</tr>
	<!-- BEGIN switch_cat -->
	<tr>
	  <td class="row1">{L_DESCRIPTION}</td>
	  <td class="row2"><textarea rows="5" cols="45" wrap="virtual" name="catdesc" class="post">{CAT_DESCRIPTION}</textarea></td>
	</tr>
	<tr>
	  <td class="row1">{L_PARENT}</td>
	  <td class="row2">
	    <select name="parent">
	    <option value="0">{L_NONE}</otpion>
		{PARENT_LIST}
		</select>
	</tr>
  	<tr>
		<td class="row1">{L_CAT_ALLOWFILE}<br><span class="gensmall">{L_CAT_ALLOWFILE_INFO}</span></td>
		<td class="row2">
		<input type="radio" name="cat_allow_file" value="1" {CHECKED_YES}>{L_YES}&nbsp;
		<input type="radio" name="cat_allow_file" value="0" {CHECKED_NO}>{L_NO}&nbsp;
		</td>
  	</tr>
	<!-- BEGIN switch_edit_category -->
	<tr>
	  <td class="row1">{L_NUMBER_ITEMS}</td>
	  <td class="row2"><input class="post" type="text" size="4" maxlength="3" name="number_articles" value="{NUMBER_ITEMS}" class="post" /></td>
	</tr>
	<!-- END switch_edit_category -->
	<!-- END switch_cat -->

	<tr>
		<!-- TITLE ------------------------------------------------------------------------------------------- -->
	  	<th class="thHead" colspan="2">&nbsp;{L_COMMENTS_TITLE}</th>
	</tr>
	<tr>
		<td class="row1" width="50%">{L_USE_COMMENTS}<br /><span class="gensmall">{L_USE_COMMENTS_EXPLAIN}</span></td>
		<td class="row2" width="50%"><input type="radio" name="cat_allow_comments" value="-1" {S_USE_COMMENTS_DEFAULT} /> {L_DEFAULT}&nbsp;&nbsp;<input type="radio" name="cat_allow_comments" value="1" {S_USE_COMMENTS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="cat_allow_comments" value="0" {S_USE_COMMENTS_NO} /> {L_NO}</td>
	</tr>
	<tr>
		<td class="row1" width="50%">{L_INTERNAL_COMMENTS}<br /><span class="gensmall">{L_INTERNAL_COMMENTS_EXPLAIN}</span></td>
		<td class="row2" width="50%"><input type="radio" name="internal_comments" value="-1" {S_INTERNAL_COMMENTS_DEFAULT} /> {L_DEFAULT}&nbsp;&nbsp;<input type="radio" name="internal_comments" value="1" {S_INTERNAL_COMMENTS_INTERNAL} /> {L_INTERNAL_COMMENTS_INTERNAL}&nbsp;&nbsp;<input type="radio" name="internal_comments" value="0" {S_INTERNAL_COMMENTS_PHPBB} /> {L_INTERNAL_COMMENTS_PHPBB}</td>
	</tr>
    <tr>
		<td class="row1" width="50%">{L_FORUM_ID}<br /><span class="gensmall">{L_FORUM_ID_EXPLAIN}</span></td>
		<td class="row2" width="50%">{FORUM_LIST}</td>
	</tr>
	<tr>
        <td class="row1" width="50%">{L_AUTOGENERATE_COMMENTS}<br /><span class="gensmall">{L_AUTOGENERATE_COMMENTS_EXPLAIN}</span></td>
        <td class="row2" width="50%"><input type="radio" name="autogenerate_comments" value="-1" {S_AUTOGENERATE_COMMENTS_DEFAULT} /> {L_DEFAULT}&nbsp;&nbsp;<input type="radio" name="autogenerate_comments" value="1" {S_AUTOGENERATE_COMMENTS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="autogenerate_comments" value="0" {S_AUTOGENERATE_COMMENTS_NO} /> {L_NO}</td>
    </tr>
	<tr>
		<!-- TITLE ------------------------------------------------------------------------------------------- -->
	  	<th class="thHead" colspan="2">&nbsp;{L_INSTRUCTIONS_TITLE}</th>
	</tr>
	<tr>
		<td class="row1" width="50%">{L_PRE_TEXT_NAME}<br /><span class="gensmall">{L_PRE_TEXT_EXPLAIN}</span></td>
		<td class="row2" width="50%"><input type="radio" name="show_pretext" value="-1" {S_DEFAULT_PRETEXT} /> {L_DEFAULT}&nbsp;&nbsp;<input type="radio" name="show_pretext" value="1" {S_SHOW_PRETEXT} /> {L_SHOW}&nbsp;&nbsp;<input type="radio" name="show_pretext" value="0" {S_HIDE_PRETEXT} /> {L_HIDE}</td>
	</tr>
	<tr>
		<!-- TITLE ------------------------------------------------------------------------------------------- -->
	  	<th class="thHead" colspan="2">&nbsp;{L_NOTIFICATIONS_TITLE}</th>
	</tr>
	<tr>
		<td class="row1" width="50%">{L_NOTIFY}<br /><span class="gensmall">{L_NOTIFY_EXPLAIN}</span></td>
		<td class="row2" width="50%"><input type="radio" name="notify" value="-1" {S_NOTIFY_DEFAULT} />{L_DEFAULT}&nbsp;&nbsp;<input type="radio" name="notify" value="0" {S_NOTIFY_NONE} />{L_NONE}&nbsp;&nbsp;<input type="radio" name="notify" value="2" {S_NOTIFY_EMAIL} />{L_EMAIL}&nbsp; &nbsp;<input type="radio" name="notify" value="1" {S_NOTIFY_PM} />{L_PM}</td>
	</tr>
	<tr>
		<td class="row1" width="50%">{L_NOTIFY_GROUP}<br /><span class="gensmall">{L_NOTIFY_GROUP_EXPLAIN}</span></td>
		<td class="row2" width="50%">{NOTIFY_GROUP}</td>
	</tr>

	<tr>
		<!-- TITLE ------------------------------------------------------------------------------------------- -->
		<th class="thHead" height="25" nowrap="nowrap" colspan="2">{L_CAT_PERMISSIONS}</th>
	</tr>
  	<tr>
		<td class="row1" width="50%">{L_VIEW_LEVEL}<br><span class="gensmall">{L_VIEW_LEVEL_INFO}</span></td>
		<td class="row2" width="50%">{S_VIEW_LEVEL}</td>
  	</tr>
  	<tr>
		<td class="row1" width="50%">{L_EDIT_LEVEL}<br><span class="gensmall">{L_EDIT_LEVEL_INFO}</span></td>
		<td class="row2" width="50%">{S_EDIT_LEVEL}</td>
  	</tr>
  	<tr>
		<td class="row1" width="50%">{L_DELETE_LEVEL}<br><span class="gensmall">{L_DELETE_LEVEL_INFO}</span></td>
		<td class="row2" width="50%">{S_DELETE_LEVEL}</td>
  	</tr>
  	<tr>
		<td class="row1" width="50%">{L_UPLOAD_LEVEL}<br><span class="gensmall">{L_UPLOAD_LEVEL_INFO}</span></td>
		<td class="row2" width="50%">{S_UPLOAD_LEVEL}</td>
  	</tr>
  	<tr>
		<td class="row1" width="50%">{L_APPROVAL_LEVEL}<br><span class="gensmall">{L_APPROVAL_LEVEL_INFO}</span></td>
		<td class="row2" width="50%">{S_APPROVAL_LEVEL}</td>
  	</tr>
  	<tr>
		<td class="row1" width="50%">{L_APPROVAL_EDIT_LEVEL}<br><span class="gensmall">{L_APPROVAL_EDIT_LEVEL_INFO}</span></td>
		<td class="row2" width="50%">{S_APPROVAL_EDIT_LEVEL}</td>
  	</tr>
  	<tr>
		<td class="row1" width="50%">{L_RATE_LEVEL}<br><span class="gensmall">{L_RATE_LEVEL_INFO}</span></td>
		<td class="row2" width="50%">{S_RATE_LEVEL}</td>
  	</tr>
  	<tr>
		<td class="row1" width="50%">{L_VIEW_COMMENT_LEVEL}<br><span class="gensmall">{L_VIEW_COMMENT_LEVEL_INFO}</span></td>
		<td class="row2" width="50%">{S_VIEW_COMMENT_LEVEL}</td>
  	</tr>
  	<tr>
		<td class="row1" width="50%">{L_POST_COMMENT_LEVEL}<br><span class="gensmall">{L_POST_COMMENT_LEVEL_INFO}</span></td>
		<td class="row2" width="50%">{S_POST_COMMENT_LEVEL}</td>
  	</tr>
  	<tr>
		<td class="row1" width="50%">{L_EDIT_COMMENT_LEVEL}<br><span class="gensmall">{L_EDIT_COMMENT_LEVEL_INFO}</span></td>
		<td class="row2" width="50%">{S_EDIT_COMMENT_LEVEL}</td>
  	</tr>
  	<tr>
		<td class="row1" width="50%">{L_DELETE_COMMENT_LEVEL}<br><span class="gensmall">{L_DELETE_COMMENT_LEVEL_INFO}</span></td>
		<td class="row2" width="50%">{S_DELETE_COMMENT_LEVEL}</td>
  	</tr>
	<tr>
	  <td class="cat" colspan="2" align="center">{S_HIDDEN}<input type="submit" name="submit" value="{L_CREATE}" class="mainoption" /></td>
	</tr>
  </table>
</form>

<br clear="all" />