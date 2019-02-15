<table width="100%" cellspacing="1" cellpadding="0" border="0" class="forumline" style="border-top:none;">
	<tr>
		<td>
			<table width="100%" cellspacing="0" cellpadding="2" border="0">
				<tr>
				   {L_KB_TITLE}
			  	</tr>

			  	<tr>
					<td align="center" class="row1">
						<span class="gen">&nbsp;<a href="{U_SEARCH}" class="gen">{L_SEARCH}</a>&nbsp;
						<!-- IF U_ADD_ARTICLE -->
							&bull;&nbsp;<a href="{U_ADD_ARTICLE}" class="gen"><b>{L_ADD_ARTICLE}</b></a>&nbsp;
						<!-- ENDIF -->
						<!-- BEGIN MCP -->
						&bull;&nbsp;<a href="{U_MCP}" class="gen"><b>{L_MCP}</b></a>&nbsp;
						<!-- END MCP -->
						</span>
						<br />
						<span class="genmed">
							&nbsp;<a href="{U_MOST_POPULAR}" class="gensmall">{L_MOST_POPULAR}</a>&nbsp;
							<!-- BEGIN switch_toprated -->
							&bull;&nbsp;<a href="{U_TOPRATED}" class="gensmall">{L_TOPRATED}</a>&nbsp;
							<!-- END switch_toprated -->
							&bull;&nbsp;<a href="{U_LATEST}" class="gensmall">{L_LATEST}</a>&nbsp;
						</span>
						<br /><hr>
					</td>
			  	</tr>

				<!-- BEGIN switch_quick_stats -->
				<tr>
					<td class="cat" align="center">
						<span class="nav">
					  		&nbsp;{switch_quick_stats.L_QUICK_STATS}&nbsp;
					  	</span>
					</td>
				</tr>
				<tr>
					<td class="row1"  width = "100%" align="center">
						<span class="gensmall">
							{switch_quick_stats.STATS}
						</span>
					</td>
				</tr>
				<!-- END switch_quick_stats -->

			</table>
		</td>
	</tr>
</table>
<br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
  	<tr valign="top">
	<td>
