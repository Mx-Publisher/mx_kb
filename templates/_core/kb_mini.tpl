<table width="100%" cellpadding="0" cellspacing="0" border="0" class="forumline" style="border-top:none;"><tr><td>
	<tr>
		<td>
			<table width="100%" cellpadding="4" cellspacing="1">
			<tr><td align="center" class="row2">
				<form method="get" name="jumpbox" action="{S_JUMPBOX_ACTION}" onSubmit="if(document.jumpbox.cat_id.value == -1){return false;}">
				<input type="hidden" name="mode" value="cat" />
				<input type="hidden" name="page" value="{MX_PAGE}" />
				<select name="cat" onchange="if(this.options[this.selectedIndex].value != -1){ forms['jumpbox'].submit() }">
				<option value="-1">{L_QUICK_JUMP}</option>
				{BLOCK_JUMPMENU}
				</select>
				<!--<input type="submit" value="{L_QUICK_GO}" class="liteoption" />-->
				</span>
				</form>
			</td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td align="center">

<!-- BEGIN CAT_NAV_SIMPLE -->
<table width="100%" cellpadding="2" cellspacing="1" border="0" >
  	<tr><td class="row1"><table border="0" cellpadding="2" cellspacing="0" width="100%">
	<!-- BEGIN catrow -->
		<!-- BEGIN catcol -->
		<tr>
			<td><a href="{CAT_NAV_SIMPLE.catrow.catcol.U_CATEGORY}"><img src="{CAT_NAV_SIMPLE.catrow.catcol.CAT_IMAGE}" alt="{CAT_NAV_SIMPLE.catrow.catcol.CAT_DESCRIPTION}" align="absmiddle" border="0" /></a></td>
			<td width="100%" valign="middle" nowrap="nowrap"><a href="{CAT_NAV_SIMPLE.catrow.catcol.U_CATEGORY}"  class="topictitle">{CAT_NAV_SIMPLE.catrow.catcol.CATEGORY}</a>&nbsp;<span class="gensmall">({CAT_NAV_SIMPLE.catrow.catcol.CAT_ARTICLES})</span>
			</td>
      	</tr>
		<!-- END catcol -->
	<!-- END catrow -->
	</table></td></tr>
	<tr valign="middle">
		<td class="cat"><span class="gensmall">&nbsp;</span></td>
	</tr>
</table>
<!-- END CAT_NAV_SIMPLE -->

<!-- BEGIN ARTICLELIST -->
<table width="100%" cellpadding="2" cellspacing="0">
	<!-- BEGIN articlerow -->
	<tr>
		<td rowspan="2" class="{ARTICLELIST.articlerow.COLOR}" valign="middle">&nbsp;<img src="{ARTICLELIST.articlerow.ARTICLE_IMAGE}" border="0" class="mx_icon"></td>
		<td width="100%" class="{ARTICLELIST.articlerow.COLOR}">
		<a href="{ARTICLELIST.articlerow.U_ARTICLE}" class="topictitle">{ARTICLELIST.articlerow.ARTICLE}</a>&nbsp;
		<br><span class="genmed">{ARTICLELIST.articlerow.ARTICLE_DESCRIPTION}</span>
		</td>
	</tr>
	<tr>
		<td valign="top" align="left" class="{ARTICLELIST.articlerow.COLOR}">
		<span class="gensmall">
		<!-- BEGIN display_username -->
		&bull;&nbsp;{L_ARTICLE_AUTHOR}&nbsp;{ARTICLELIST.articlerow.ARTICLE_AUTHOR}<br />
		<!-- END display_username -->
		<!-- BEGIN display_date -->
		&bull;&nbsp;{L_ARTICLE_DATE}: {ARTICLELIST.articlerow.ARTICLE_DATE}<br />
		<!-- END display_date -->
		<!-- BEGIN display_counter -->
		&bull;&nbsp;{L_VIEWS}: {ARTICLELIST.articlerow.ART_VIEWS}<br />
		<!-- END display_counter -->
		<!-- BEGIN display_rate -->
		&bull;&nbsp;{ARTICLELIST.articlerow.L_RATING}: {ARTICLELIST.articlerow.RATING} ({ARTICLELIST.articlerow.ARTICLE_VOTES} {L_VOTES}) {ARTICLELIST.articlerow.DO_RATE}<br />
		<!-- END display_rate -->
		</span>
		</td>
	</tr>
	<!-- END articlerow -->
	<tr valign="middle">
		<td align="right" colspan="2" valign="top" nowrap="nowrap" height="28" class="cat"><span class="gensmall">{BLOCK_PAGINATION}</span></td>
	</tr>
</table>
<!-- END ARTICLELIST -->

<!-- BEGIN no_articles -->
<table width="100%" cellspacing="1" cellpadding="3">
	<tr>
		<td class="row1" align="center" height="30"><span class="genmed">{no_articles.L_NO_ARTICLES_CAT}</span></td>
	</tr>
</table>
<!-- END no_articles -->

</td></tr></table>