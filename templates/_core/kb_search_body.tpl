<form action="{S_SEARCH_ACTION}" method="post">
<table width="100%" cellpadding="2" cellspacing="2">
  <tr>
	<td valign="bottom">
		<span class="nav"><a href="{U_KB}" class="nav">{L_KB}</a>&nbsp;&raquo;&nbsp;{L_SEARCH}</span>
	</td>
  </tr>
</table>

<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
  <tr> 
	<th class="thHead" colspan="2">&nbsp;{L_SEARCH}</th>
  </tr>
	<tr> 
		<td class="row1" width="50%"><span class="gen">{L_SEARCH_KEYWORDS}:</span><br /><span class="gensmall">{L_SEARCH_KEYWORDS_EXPLAIN}</span></td>
		<td class="row2" valign="top"><span class="genmed"><input type="text" style="width: 300px" class="post" name="search_keywords" size="30" /><br /><input type="radio" name="search_terms" value="any" checked="checked" /> {L_SEARCH_ANY_TERMS}<br /><input type="radio" name="search_terms" value="all" /> {L_SEARCH_ALL_TERMS}</span></td>
	</tr>
	<tr> 
		<td class="row1"><span class="gen">{L_SEARCH_AUTHOR}:</span><br /><span class="gensmall">{L_SEARCH_AUTHOR_EXPLAIN}</span></td>
		<td class="row2" valign="middle"><span class="genmed"><input type="text" style="width: 300px" class="post" name="search_author" size="30" /></span></td>
	</tr>
	<tr> 
		<th class="thHead" colspan="2" height="25">&nbsp;{L_SEARCH_OPTIONS}</th>
	</tr>
  <tr> 
	<td class="row1" width="50%"><span class="genmed">{L_CHOOSE_CAT}&nbsp; </span></td>
	<td class="row2"><select name="cat_id" class="forminput"><option value="0" selected>{L_ALL}</option>{S_CAT_MENU}</select></td>
  </tr>
  <tr> 
	<td class="row1" width="50%"><span class="genmed">{L_INCLUDE_COMMENTS}:&nbsp; </span></td>
	<td class="row2"><span class="genmed"><input type="radio" name="comments_search" value="YES" checked="checked" /> {L_YES} <input type="radio" name="comments_search" value="NO" /> {L_NO}</span></td>
  </tr>
  <tr>
	<td class="row1"><span class="genmed">{L_SORT_BY}:&nbsp;</span></td>
	<td class="row2" valign="middle"><span class="genmed">
	<select class="post" name="sort_method">
		<option value='article_title'>{L_NAME}</option>
		<option selected="selected" value='article_date'>{L_DATE}</option>
		<option value='article_rating'>{L_RATING}</option>
		<option value='views'>{L_VIEWS}</option>
		<!--<option value='file_update_time'>{L_UPDATE_TIME}</option>-->
	</select></span>&nbsp;</td>
  </tr>
  <tr>
	<td class="row1"><span class="genmed">{L_SORT_DIR}:&nbsp;</span></td>
	<td class="row2" valign="middle"><span class="genmed"><input type="radio" name="sort_order" value="ASC" /> {L_SORT_ASCENDING} <input type="radio" name="sort_order" value="DESC" checked /> {L_SORT_DESCENDING}</span>&nbsp;</td>
  </tr>  
  <tr>   
	<td class="cat" align="center" colspan="2"><input type="hidden" name="mode" value="search"><input class="liteoption" type="submit" name="submit" value="{L_SEARCH}"></td>
  </tr>
</form>
</table>