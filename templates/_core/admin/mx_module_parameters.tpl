				<script type="text/javascript" src="{MX_ROOT_PATH}/modules/mx_shared/lib/rollout_toggle.js"></script>

				<tr>
					<td width="100%" colspan="2">
						<!-- BEGIN switch_forums_phpbb -->
						<table border="0" cellpadding="4" cellspacing="1" width="100%" class="forumline">
							<tr name="{PARAMETER_TYPE} :: {PARAMETER_TYPE_EXPLAIN}">
								<td class="row2" colspan="2" align="left">
									<span class="cattitle">
										{PARAMETER_TITLE}<hr>
									</span>
								</td>
							</tr>
						<!-- END switch_forums_phpbb -->
							<!-- BEGIN catrow -->
							<tr>
								<td class="cat" colspan="2"><span class="cattitle">{catrow.CAT_NAME}</span></td>
							</tr>
								<!-- BEGIN forumrow_phpbb -->
								<tr>
									<td class="row1" align="center" valign="top"><input type="checkbox" name="{SELSCT_NAME}[{catrow.forumrow_phpbb.FORUM_ID}]" value="1" {catrow.forumrow_phpbb.CHECKED} /></td>
									<td class="row1" align="left" valign="top"><span class="forumlink">{catrow.forumrow_phpbb.FORUM_NAME}</span><br /><span class="gensmall">{catrow.forumrow_phpbb.FORUM_DESC}</span></td>
								</tr>
								<!-- END forumrow_phpbb -->
							<!-- END catrow -->
						<!-- BEGIN switch_forums_phpbb -->
						</table>
						<!-- END switch_forums_phpbb -->
					</td>
				</tr>

				<!-- BEGIN select -->
				<tr>
					<td width="100%" colspan="2">
						<table cellpadding="2" cellspacing="0" width="100%">
							<tr title="{select.FIELD_DESCRIPTION}">
								<td class="row1" width="50%" align="right">
									<span class="topictitle">{select.PARAMETER_TITLE}:</span>
									<span class="gensmall">{select.PARAMETER_TITLE_EXPLAIN}</span>
								</td>
								<td class="row1">
									<select name="{select.FIELD_ID}" class="post">
										<option value="0">{select.L_NONE}</option>
										{select.SELECT_LIST}
									</select>
								</td>
				  			</tr>
						</table>
					</td>
				</tr>
				<!-- END select -->


