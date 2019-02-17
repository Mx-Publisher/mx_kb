<!-- BEGIN ARTICLELIST -->
<table width="100%" cellpadding="0" cellspacing="0" border="0" class="forumline" style="border-top:none;"><tr><td>

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
	<!-- BEGIN toplist_pagination -->
	<tr valign="middle">
		<td align="right" colspan="2" valign="top" height="28" class="cat"><span class="gensmall">{BLOCK_PAGINATION}</span></td>
	</tr>
	<!-- END toplist_pagination -->
</table>

</td></tr></table>
<!-- END ARTICLELIST -->

<!-- BEGIN no_articles -->
<table class="forumline" width="100%" cellspacing="1" cellpadding="3">
	<tr>
		<th class="thHead">{no_articles.L_NO_ARTICLES}</th>
	</tr>
	<tr>
		<td class="row1" align="center" height="30"><span class="genmed">{no_articles.L_NO_ARTICLES_CAT}</span></td>
	</tr>
</table>
<!-- END no_articles -->