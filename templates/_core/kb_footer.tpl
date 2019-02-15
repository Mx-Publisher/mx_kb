	<tr>
	  <td>
	  	<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td align="left" valign="top">
					  <!-- BEGIN jumpbox -->
					  	<form method="get" name="jumpbox" action="{QUICK_JUMP_ACTION}" onSubmit="if(document.jumpbox.cat.value == -1){return false;}">
					    	<table cellspacing="0" cellpadding="0" border="0">
								<tr>
									<td align="left" valign="middle" height="35" width="50%"><span class="gensmall">
					  					<select name="cat" onchange="if(this.options[this.selectedIndex].value != 0){ forms['jumpbox'].submit() }">
					    					<option value="0">{L_QUICK_JUMP}</otpion>
											{QUICK_NAV}
										</select>
										{S_HIDDEN_VARS}
										<input type="submit" value="{L_QUICK_GO}" class="liteoption" /></span>
									</td>
									</tr>
							</table>
						</form>
						<!-- END jumpbox -->
				</td>
				<td align="right">
					  <!-- BEGIN auth_can_list -->
					  <span class="gensmall">{S_AUTH_LIST}</span>
					  <!-- END auth_can_list -->
				</td>
			</tr>
		</table>
	 </td>
	</tr>
</table>
{DEBUG}
<!-- BEGIN copy_footer -->
<div align="center"><span class="copyright"><br />
Powered by {L_MODULE_VERSION}, {L_MODULE_ORIG_AUTHOR} & <a href="http://www.mx-publisher.com/" target="_phpbb" class="copyright">{L_MODULE_AUTHOR}</a> © 2002-2005 <br /><a href="http://www.phpbb.com/phpBB/viewtopic.php?t=200195" target="_phpbb" class="copyright">PHPBB.com MOD</a>
</span></div>
<!-- END copy_footer -->
