<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
	<tr>
		<td align="left" class="nav"><a href="{U_KB}" class="nav">{L_KB}</a></td>
	</tr>
</table>

<!-- BEGIN CAT_NAV_STANDARD -->
<table width="100%" cellpadding="4" cellspacing="1" border="0" class="forumline" >
  	<tr>
		<th class="thCornerL" width="6%">&nbsp;</th>
  	   	<th class="thTop">&nbsp;{L_CATEGORY}&nbsp;</th>
		<th class="thCornerR" width="10%">&nbsp;{L_LAST_ARTICLE}&nbsp;</th>
	   	<th class="thCornerR" width="50" >&nbsp;{L_ARTICLES}&nbsp;</th>
  	</tr>
  	<!-- BEGIN catrow -->
  	<!-- BEGIN catcol -->
 	<tr>
		<td class="row1" align="center" valign="middle"><img src="{CAT_NAV_STANDARD.catrow.catcol.CAT_IMAGE}" border="0" alt="{CAT_NAV_STANDARD.catrow.catcol.CAT_DESCRIPTION}"></td>
  	   	<td class="row1" onmouseout="this.className='row1';" onmouseover="this.className='row2';" ><a href="{CAT_NAV_STANDARD.catrow.catcol.U_CATEGORY}" class="cattitle">{CAT_NAV_STANDARD.catrow.catcol.CATEGORY}</a><br /><span class="genmed">{CAT_NAV_STANDARD.catrow.catcol.CAT_DESCRIPTION}</span></td>
		<td class="row2" align="center" valign="middle"><span class="genmed">{CAT_NAV_STANDARD.catrow.catcol.LAST_ARTICLE}</span></td>
	   	<td class="row2" align="center" valign="middle"><span class="genmed">{CAT_NAV_STANDARD.catrow.catcol.CAT_ARTICLES}</span></td>
  	</tr>
  	<!-- BEGIN show_subs -->
  	<tr>
		<td class="row2" width="6%">&nbsp;</td>
  	   	<td class="row2" colspan="2"><span class="genmed"><b>{CAT_NAV_STANDARD.catrow.catcol.L_SUB_CAT}:</b> {CAT_NAV_STANDARD.catrow.catcol.SUB_CAT}</span></td>
	   	<td class="row2" align="center" valign="middle">&nbsp;</td>
  	</tr>
  	<!-- END show_subs -->
  	<!-- END catcol -->
  	<!-- END catrow -->

  <!-- BEGIN no_cats -->
  <tr>
  	   <td class="row1" align="center" colspan="4" height="50"><span class="genmed">{CAT_NAV_STANDARD.no_cats.COMMENT}</span></td>
  </tr>
  <!-- END no_cats -->
  <tr>
  	  <td class="cat" colspan="4">&nbsp;</td>
  </tr>
</table>
<br>
<!-- END CAT_NAV_STANDARD -->

<!-- BEGIN CAT_NAV_SIMPLE -->
<table width="100%" cellpadding="2" cellspacing="1" border="0" class="forumline">
  <tr>
	<th class="thHead">{L_CATEGORIES}</th>
  </tr>
  <tr><td class="row1">
	<table border="0" cellpadding="5" cellspacing="1" width="100%">
	<!-- BEGIN catrow -->
		<tr>
		<!-- BEGIN catcol -->
		<td width="{WIDTH}%">
		<table border="0" cellpadding="2" cellspacing="2" width="100%">
		<tr>
			<td><a href="{CAT_NAV_SIMPLE.catrow.catcol.U_CATEGORY}"><img src="{CAT_NAV_SIMPLE.catrow.catcol.CAT_IMAGE}" alt="{CAT_NAV_SIMPLE.catrow.catcol.CAT_DESCRIPTION}" align="absmiddle" border="0" /></a></td>
			<td width="100%" valign="middle"><a href="{CAT_NAV_SIMPLE.catrow.catcol.U_CATEGORY}"  class="cattitle">{CAT_NAV_SIMPLE.catrow.catcol.CATEGORY}</a>&nbsp;<span class="gensmall">({CAT_NAV_SIMPLE.catrow.catcol.CAT_ARTICLES})</span><br>
			{CAT_NAV_SIMPLE.catrow.catcol.SUB_CAT}
			</td>
		</tr></table>
		</td>
		<!-- END catcol -->
      		</tr>
	<!-- END catrow -->
	</table>
  </td></tr>
</table>
<br />
<!-- END CAT_NAV_SIMPLE -->
