<h1>{L_FIELD_TITLE}</h1>

<p>{L_FIELD_EXPLAIN}</p>

<form action="{S_FIELD_ACTION}" method="post">
<!-- BEGIN error -->
<table width="100%" cellpadding="3" cellspacing="1" class="forumline">
  <tr>
	<td class="row2" align="center">{ERROR}</td>
  </tr>
</table>
<br />
<!-- END error -->

<table width="100%" cellpadding="3" cellspacing="1" class="forumline">
  <tr>
	<th colspan="2" class="thHead">{L_FIELD_TITLE}</th>
  </tr>
  <tr>	
	<td width="50%" class="row1">{L_FIELD_NAME}<br><span class="gensmall">{L_FIELD_NAME_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="50" name="field_name" value="{FIELD_NAME}" /></td>
  </tr>
  <tr>
	<td class="row1">{L_FIELD_DESC}<br><span class="gensmall">{L_FIELD_DESC_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="50" name="field_desc" value="{FIELD_DESC}" /></td>
  </tr>
<!-- BEGIN data -->
  <tr>
	<td class="row1">{L_FIELD_DATA}<br><span class="gensmall">{L_FIELD_DATA_INFO}</span></td>
	<td class="row2"><textarea rows="6" name="data" cols="32">{FIELD_DATA}</textarea></td>
  </tr>
<!-- END data -->

<!-- BEGIN regex -->
  <tr>
	<td class="row1">{L_FIELD_REGEX}<br><span class="gensmall">{L_FIELD_REGEX_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="50" name="regex" value="{FIELD_REGEX}" /></td>
  </tr>
<!-- END regex -->

<!-- BEGIN order -->
  <tr>	
	<td width="50%" class="row1">{L_FIELD_ORDER}</td>
	<td class="row2"><input type="text" class="post" size="6" name="field_order" value="{FIELD_ORDER}" /></td>
  </tr>
<!-- END order -->
  <tr>
	<td align="center" class="cat" colspan="2">
	{S_HIDDEN_FIELDS}<input class="liteoption" type="submit" value="{L_FIELD_TITLE}" name="submit">
	</td>
  </tr>
</table>
</form>
