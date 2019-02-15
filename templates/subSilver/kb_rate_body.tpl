<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
	<tr>
		<td align="left" class="nav">
		  <a href="{U_KB}" class="nav">{L_KB}</a> {PATH}
		</td>
	</tr>
</table>

<!-- BEGIN rate -->
<form action="{S_RATE_ACTION}" method="POST">
<table width="100%" cellpadding="4" cellspacing="0" class="forumline">
  	<tr> 
		<th colspan="2" class="thHead">&nbsp;{L_RATE}</th>
	</tr>
	<tr> 
		<td class="row1" width="90%"><span class="genmed">{RATEINFO}</span></td>
		<td class="row2">
				<select size="1" name="rating" class="forminput">
				<option value="1">{L_R1}</option>
				<option value="2">{L_R2}</option>
				<option value="3">{L_R3}</option>
				<option value="4">{L_R4}</option>
				<option value="5" selected>{L_R5}</option>
				<option value="6">{L_R6}</option>
				<option value="7">{L_R7}</option>
				<option value="8">{L_R8}</option>
				<option value="9">{L_R9}</option>
				<option value="10">{L_R10}</option>
				</select>
				<input type="hidden" name="mode" value="rate">
				<input type="hidden" name="k" value="{ID}">
				<input type="hidden" name="rate" value="dorate">
		</td>
	</tr>
	<tr> 
		<td colspan="2" class="catBottom" align="center"><input class="liteoption" type="submit" value="{L_RATE}">
			&nbsp;
		</td>
	</tr>
</table>
</form>
<!-- END rate -->
