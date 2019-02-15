<h1>{L_EDIT_TITLE}</h1>

<p>{L_EDIT_DESCRIPTION}</p>

  <table width="100%" cellpadding="4" cellspacing="1" border="0" class="forumline" align="center">
<form action="{S_ACTION}" method="post">
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
	<!-- BEGIN switch_edit_category -->
	<tr> 
	  <td class="row1">{L_NUMBER_ARTICLES}</td>
	  <td class="row2"><input class="post" type="text" size="4" maxlength="3" name="number_articles" value="{NUMBER_ARTICLES}" class="post" /></td>
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
	  	<th class="thHead" colspan="2">&nbsp;{L_RATINGS_TITLE}</th>
	</tr>
	<tr>
		<td class="row1" width="50%">{L_USE_RATINGS}<br /><span class="gensmall">{L_USE_RATINGS_EXPLAIN}</span></td>
		<td class="row2" width="50%"><input type="radio" name="cat_allow_ratings" value="-1" {S_USE_RATINGS_DEFAULT} /> {L_DEFAULT}&nbsp;&nbsp;<input type="radio" name="cat_allow_ratings" value="1" {S_USE_RATINGS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="cat_allow_ratings" value="0" {S_USE_RATINGS_NO} /> {L_NO}</td>
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
		<th class="thHead" height="25" nowrap="nowrap" colspan="2">{L_CAT_PERMISSIONS}</th>
	</tr>
	<tr>
		<td class="row1"><span class="gen">{L_VIEW_LEVEL}:</span></td>
		<td class="row2"><select name="auth_view"><option {VIEW_GUEST} value="{S_GUEST}">{L_GUEST}</option><option {VIEW_REG} value="{S_USER}">{L_REG}</option><option {VIEW_PRIVATE} value="{S_PRIVATE}">{L_PRIVATE}</option><option {VIEW_MOD} value="{S_MOD}">{L_MOD}</option><option {VIEW_ADMIN} value="{S_ADMIN}">{L_ADMIN}</option></select></td>
	</tr>
	<tr>
		<td class="row1"><span class="gen">{L_UPLOAD_LEVEL}:</span></td>
		<td class="row2"><select name="auth_post"><option {UPLOAD_GUEST} value="{S_GUEST}">{L_GUEST}</option><option {UPLOAD_REG} value="{S_USER}">{L_REG}</option><option {UPLOAD_PRIVATE} value="{S_PRIVATE}">{L_PRIVATE}</option><option {UPLOAD_MOD} value="{S_MOD}">{L_MOD}</option><option {UPLOAD_ADMIN} value="{S_ADMIN}">{L_ADMIN}</option></select></td>
	</tr>
	<tr>
		<td class="row1"><span class="gen">{L_RATE_LEVEL}:</span></td>
		<td class="row2"><select name="auth_rate"><option {RATE_GUEST} value="{S_GUEST}">{L_GUEST}</option><option {RATE_REG} value="{S_USER}">{L_REG}</option><option {RATE_PRIVATE} value="{S_PRIVATE}">{L_PRIVATE}</option><option {RATE_MOD} value="{S_MOD}">{L_MOD}</option><option {RATE_ADMIN} value="{S_ADMIN}">{L_ADMIN}</option></select></td>
	</tr>
	<tr>
		<td class="row1"><span class="gen">{L_COMMENT_LEVEL}:</span></td>
		<td class="row2"><span class="gen"><select name="auth_comment"><option {COMMENT_GUEST} value="{S_GUEST}">{L_GUEST}</option><option {COMMENT_REG} value="{S_USER}">{L_REG}</option><option {COMMENT_PRIVATE} value="{S_PRIVATE}">{L_PRIVATE}</option><option {COMMENT_MOD} value="{S_MOD}">{L_MOD}</option><option {COMMENT_ADMIN} value="{S_ADMIN}">{L_ADMIN}</option></select></span></td>
	</tr>
	<tr>
		<td class="row1"><span class="gen">{L_EDIT_LEVEL}:</span></td>
		<td class="row2"><select name="auth_edit"><option {EDIT_GUEST} value="{S_GUEST}">{L_GUEST}</option><option {EDIT_REG} value="{S_USER}">{L_REG}</option><option {EDIT_PRIVATE} value="{S_PRIVATE}">{L_PRIVATE}</option><option {EDIT_MOD} value="{S_MOD}">{L_MOD}</option><option {EDIT_ADMIN} value="{S_ADMIN}">{L_ADMIN}</option></select></td>
	</tr>
	<tr>
		<td class="row1"><span class="gen">{L_DELETE_LEVEL}:</span></td>
		<td class="row2"><select name="auth_delete"><option {DELETE_REG} value="{S_USER}">{L_REG}</option><option {DELETE_PRIVATE} value="{S_PRIVATE}">{L_PRIVATE}</option><option {DELETE_MOD} value="{S_MOD}">{L_MOD}</option><option {DELETE_ADMIN} value="{S_ADMIN}">{L_ADMIN}</option></select></td>
	</tr>
	<tr>
		<td class="row1"><span class="gen">{L_APPROVAL_LEVEL}:</span></td>
		<td class="row2"><select name="auth_approval"><option {APPROVAL_DISABLED} value="{S_GUEST}">{L_DISABLED}</option><option {APPROVAL_MOD} value="{S_MOD}">{L_MOD}</option><option {APPROVAL_ADMIN} value="{S_ADMIN}">{L_ADMIN}</option></select></td>
	</tr>
	<tr>
		<td class="row1"><span class="gen">{L_APPROVAL_EDIT_LEVEL}:</span></td>
		<td class="row2"><select name="auth_approval_edit"><option {APPROVAL_DISABLED} value="{S_GUEST}">{L_DISABLED}</option><option {APPROVAL_MOD} value="{S_MOD}">{L_MOD}</option><option {APPROVAL_ADMIN} value="{S_ADMIN}">{L_ADMIN}</option></select></td>
	</tr>	
	<tr> 
	  <td class="catBottom" colspan="2" align="center">{S_HIDDEN}<input type="submit" name="submit" value="{L_CREATE}" class="mainoption" /></td>
	</tr>
  </table>
</form>
	
<br clear="all" />