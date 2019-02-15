<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
	<tr>
		<td align="left" class="nav">
		  <a href="{U_KB}" class="nav">{L_KB}</a> {PATH}
		</td>
	</tr>
</table>

<!-- BEGIN show_subs -->
<table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
  	<tr> 
		<th class="thCornerL" width="6%">&nbsp;</th>
  	   	<th class="thTop" nowrap="nowrap">&nbsp;{L_CATEGORY}&nbsp;</th>
	   	<th class="thCornerR" width="50"  nowrap="nowrap">&nbsp;{L_ARTICLES}&nbsp;</th>
  	</tr>
<!-- END show_subs -->  
  	<!-- BEGIN catrow -->
 	<tr> 
		<td class="row1" align="center" valign="middle"><img src="{catrow.CAT_IMAGE}" border="0" alt="{catrow.CAT_DESCRIPTION}"></td>
  	   	<td class="row1"><span class="forumlink">{catrow.CATEGORY}</span><br /><span class="genmed">{catrow.CAT_DESCRIPTION}</span></td>
	   	<td class="row2" align="center" valign="middle"><span class="genmed">{catrow.CAT_ARTICLES}</span></td>
  	</tr>
  <!-- BEGIN sub -->
  	<tr> 
		<th class="row1" width="6%">&nbsp;</th>
  	   	<td class="row1" ><span class="genmed"><b>{catrow.L_SUB_CAT}:</b> {catrow.sub.SUB_CAT_LIST}</span></td>
	   	<td class="row2" align="center" valign="middle">&nbsp;</td>
  	</tr>  
  	<!-- END sub -->
  	<!-- END catrow -->
<!-- BEGIN show_subs -->  
  	<tr>
  	  	<td class="catBottom" colspan="3">&nbsp;</td>
  	</tr>
</table>
<!-- END show_subs -->

<table width="100%" cellpadding="3" cellspacing="0" border="0" class="forumline">
  <tr> 
  	   <th class="thCornerL" nowrap="nowrap">&nbsp;{L_ARTICLE}&nbsp;</th>
  	   <th class="thTop" nowrap="nowrap">&nbsp;{L_ARTICLE_TYPE}&nbsp;</th>
  	   <th class="thTop" nowrap="nowrap">&nbsp;{L_ARTICLE_AUTHOR}&nbsp;</th>
  	   <th class="thTop" nowrap="nowrap">&nbsp;{L_ARTICLE_DATE}&nbsp;</th>
	   <th class="thCornerR" nowrap="nowrap">&nbsp;{L_VIEWS}&nbsp;</th>
  </tr>
  <!-- BEGIN articlerow -->
  <tr> 
  	   <td class="row2" align="left" valign="middle">{articlerow.ARTICLE}</td>
	   <td class="row2" align="center" valign="middle">&nbsp;<span class="genmed">{articlerow.ARTICLE_TYPE}</span>&nbsp;</td>
	   <td class="row2" align="center" valign="middle"><span class="genmed">{articlerow.ARTICLE_AUTHOR}</span></td>
	   <td class="row2" align="center" valign="middle" nowrap="nowrap"><span class="gensmall">{articlerow.ARTICLE_DATE}</span></td>
	   <td class="row2" align="center" valign="middle"><span class="genmed">{articlerow.ART_VIEWS}</span></td>
  </tr>
  <tr>
 		<td class="row1" align="left"  colspan="3"><span class="genmed">{articlerow.ARTICLE_DESCRIPTION}</span></td> 
 		<td class="row1" align="right" colspan="2" >{articlerow.U_APPROVE} {articlerow.U_DELETE}</td> 
  </tr>
  <!-- END articlerow -->
  <!-- BEGIN no_articles -->
  <tr> 
  	   <td class="row1" align = "center" colspan = "5" height="50"><span class="genmed">{no_articles.COMMENT}</span></td>
  </tr>
  <!-- END no_articles -->
  <tr>
  	  <td class="catBottom" colspan="5">&nbsp;</td>
  </tr>
</table>

<!-- BEGIN pagination -->
<table width="100%" cellspacing="2" cellpadding="0" border="0">
  <tr>
	<td valign="top" align="left" ><span class="nav">{PAGE_NUMBER}</span></td>
	<td valign="top" align="right" ><span class="nav">{PAGINATION}</span></td>
  </tr>
</table>
<!-- END pagination -->
