<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html dir="{S_CONTENT_DIRECTION}">
<head>
<meta http-equiv="Content-Type" content="text/html; charset={S_CONTENT_ENCODING}">
<meta http-equiv="Content-Style-Type" content="text/css">
{META}
{NAV_LINKS}
<title>{ARTICLE_TITLE}</title>
<link rel="stylesheet" href="modules/mx_kb/templates/print_version.css" type="text/css" >

<style type="text/css">
<!--
.articleDetails {
    width: 200px;
    display: block;
    border: 1px solid {T_TH_COLOR1};
    float:right;
}
-->
</style>

</head>

<body bgcolor="{T_BODY_BGCOLOR}" text="{T_BODY_TEXT}" link="{T_BODY_LINK}" vlink="{T_BODY_VLINK}" />
<a name="top"></a>

<table align="center" width="70%" cellpadding="20" cellspacing="1" border="0" class="forumline">
  <tr>
  	   <td class="row1" wrap="wrap">

  	   	<div class="articleDetails" align="right">
		<table width="100%" cellpadding="4" cellspacing="1" border="0">
		  <tr>
		  	  <td class="row2">
		  	  <span class="gensmall"><b>{L_ARTICLE_AUTHOR}</b></span> <span class="gensmall">{ARTICLE_AUTHOR}</span><br />
		  	  <span class="gensmall"><b>{L_ARTICLE_DATE}</b></span> <span class="gensmall">{ARTICLE_DATE}</span><br />
		  	  <span class="gensmall">{VIEWS}</span><br />
		  	  <span class="gensmall"><b>{L_ARTICLE_CATEGORY}</b></span> <span class="gensmall">{ARTICLE_CATEGORY}</span><br />
		  	  <span class="gensmall"><b>{L_ARTICLE_TYPE}</b></span> <span class="gensmall">{ARTICLE_TYPE}</span><br />

		    	<!-- BEGIN custom_field -->
					<span class="gensmall"><b>{custom_field.CUSTOM_NAME}</b> </span> <span class="gensmall">{custom_field.DATA} </span><br />
				<!-- END custom_field -->
			  	<!-- BEGIN switch_ratings -->
			  		<hr>
			  	  	<span class="gensmall"><b>{L_RATINGS}</b></span> <span class="gensmall">{switch_ratings.RATING} ({switch_ratings.VOTES} {switch_ratings.L_VOTES})</span>
			  	<!-- END switch_ratings -->
			  </td>
		  </tr>
		</table>
		</div>

	   	<span class="maintitle"style="font-size: 9pt;">{ARTICLE_TITLE}</span>
	   	<p><span class="gensmall"><b>{ARTICLE_DESCRIPTION}</b></span>

  		<!-- BEGIN switch_toc -->
       		<br />
       		<span class="maintitle">{L_TOC}</span><br /><br />
		   	<span class="nav">
		   	<!-- BEGIN pages -->
		   	{switch_toc.pages.TOC_ITEM}
		   	<!-- END pages -->
		   	</span>
		<!-- END switch_toc -->

		<p><span class="postbody">{ARTICLE_TEXT}</span>

  	   </td>
  </tr>
  <!-- BEGIN switch_pages -->
  <tr>
       <td class="row1" align="center"><span class="nav">{L_GOTO_PAGE}
	   <!-- BEGIN pages -->
	   {switch_pages.pages.PAGE_LINK}
	   <!-- END pages -->
	   </span></td>
  </tr>
<!-- END switch_pages -->
</table>


  <!-- BEGIN switch_comments_show -->
  <br />
	<table align ="center" width="80%" cellpadding="4" cellspacing="1" border="0" class="forumline">
 	 <tr>
  		  <td class="cattitle">{L_COMMENTS}&nbsp;</td>
 	 </tr>
  <!-- END switch_comments_show -->
	<!-- BEGIN postrow -->
	<tr>
		<td class="row1" width="100%" height="28" valign="top">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="100%"><span class="genmed"><b>{postrow.POSTER_NAME}</b></span><span class="postdetails"> {L_POSTED}: {postrow.POST_DATE}<span class="gen">&nbsp;</span>&nbsp; &nbsp;{L_POST_SUBJECT} {postrow.POST_SUBJECT}</span></td>
			</tr>
			<tr>
				<td ><hr /></td>
			</tr>
			<tr>
				<td ><span class="postbody">{postrow.MESSAGE}</span></td>
			</tr>
		</table></td>
	</tr>
	<tr>
		<td class="spaceRow" colspan="2" height="1"><img src="{SPACER_IMG}" alt="" width="1" height="1" /></td>
	</tr>
	<!-- END postrow -->
  <!-- BEGIN switch_comments_show -->
	</table>
  <!-- END switch_comments_show -->
</body>
</html>