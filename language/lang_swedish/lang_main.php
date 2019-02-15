<?php
/**
*
* @package MX-Publisher Module - mx_kb
* @version $Id: lang_main.php,v 1.2 2008/06/03 20:11:34 jonohlsson Exp $
* @copyright (c) 2002-2006 [Jon Ohlsson, Mohd Basri, wGEric, PHP Arena, pafileDB, CRLin] MX-Publisher Project Team
* @license http://opensource.org/licenses/gpl-license.php GNU General Public License v2
*
*/

//
// General
//
$lang['kb_disable'] = 'Kunskapsbanken �r inaktiverad.';

$lang['KB_title'] = 'Kunskapsbank (KB)';
$lang['Article'] = 'Artikel';
$lang['Articles'] = 'Artiklar';
$lang['Category'] = 'Kategori';
$lang['Sub_categories'] = 'Underkategorier';
$lang['Article_description'] = 'Beskrivning';
$lang['Article_type'] = 'Typ';
$lang['Article_keywords'] = 'Nyckelord';
$lang['Add_article'] = 'L�gg till artiklen';
$lang['KB_Home'] = 'KB Hem';
$lang['No_articles'] = 'Inga artiklar';
$lang['Article_title'] = 'Artikelnamn';
$lang['Article_text'] = 'Artikeltext';
$lang['Add_article'] = 'L�gg till artikel';
$lang['Read_article'] = 'L�s artikel';
$lang['Article_not_exsist'] = 'Artikeln finns inte';
$lang['Category_not_exsist'] = 'Kategorin finns inte';
$lang['Last_article'] = 'Senaste artikel';
$lang['Quick_jump'] = 'V�lj kategori';

$lang['Edit'] = '�ndra';
$lang['Click_cat_to_add'] = 'Klicka p� \'Kategori\' f�r att l�gga till en artikel';
$lang['Standalone_Not_Supported'] = 'Denna module till�ter inte att anv�ndas utanf�r MX-Publisher. Skapa ett MX-Publisher block i MXPs kontrollpanel.';

$lang['Article_submitted_Approve'] = 'Artikeln skickades...<br />En administrat�r kommer att granska artikeln innan den blir tillg�nglig f�r andra.';
$lang['Article_submitted'] = 'Artikeln skickades...';

$lang['Article_Edited_Approve'] = 'Artikeln �ndrades...<br />En administrat�r kommer att granska artikeln innan den blir tillg�nglig f�r andra.';
$lang['Article_Edited'] = 'Artikeln �ndrades...';
$lang['Edit_article'] = '�ndra artikel';

$lang['Article_Deleted'] = 'Artikeln togs bort...';

//
// Notification
//
$lang['KB_prefix'] = '[ KB ]';
$lang['KB_notify_subject_new'] = 'Ny artikel!';
$lang['KB_notify_subject_edited'] = '�ndrad artikel!';
$lang['KB_notify_subject_approved'] = 'Godk�nd artikel!';
$lang['KB_notify_subject_unapproved'] = 'Underk�nd artikel!';
$lang['KB_notify_subject_deleted'] = 'Borttagen artikel!';

$lang['KB_notify_new_body'] = 'En ny artikel �r skriven.';
$lang['KB_notify_edited_body'] = 'En artikel har �ndrats.';
$lang['KB_notify_approved_body'] = 'En artikel godk�ndes.';
$lang['KB_notify_unapproved_body'] = 'En artikel underk�ndes.';
$lang['KB_notify_deleted_body'] = 'En artikel togs bort.';
$lang['Edited_Article_info'] = 'Artikeln uppdaterades av ';

$lang['Read_full_article'] = '>>L�s hela artikeln';

//
// Auth Can
//
$lang['KB_Rules_post_can'] = 'Du <b>kan</b> skriva nya artiklar i denna kategori';
$lang['KB_Rules_post_cannot'] = 'Du <b>kan inte</b> skriva nya artiklar i denna kategori';
$lang['KB_Rules_comment_can'] = 'Du <b>kan</b> kommentera artiklar i denna kategori';
$lang['KB_Rules_comment_cannot'] = 'Du <b>kan inte</b> kommentera artiklar i denna kategori';
$lang['KB_Rules_edit_can'] = 'Du <b>kan</b> �ndra dina artiklar i denna kategori';
$lang['KB_Rules_edit_cannot'] = 'Du <b>kan inte</b> �ndra dina artiklar i denna kategori';
$lang['KB_Rules_delete_can'] = 'Du <b>kan</b> ta bort dina artiklar i denna kategori';
$lang['KB_Rules_delete_cannot'] = 'Du <b>kan inte</b> ta bort dina artiklar i denna kategori';
$lang['KB_Rules_rate_can'] = 'Du <b>kan</b> betygs�tta artiklar i denna kategori';
$lang['KB_Rules_rate_cannot'] = 'Du <b>kan inte</b> betygs�tta artiklar i denna kategori';
$lang['KB_Rules_approval_can'] = 'Artiklar <b>m�ste inte</b> godk�nnas i denna kategori';
$lang['KB_Rules_approval_cannot'] = 'Artiklar <b>m�ste</b> godk�nnas i denna kategori';
$lang['KB_Rules_approval_edit_can'] = 'Artikel�ndringar <b>m�ste inte</b> godk�nnas i denna kategori';
$lang['KB_Rules_approval_edit_cannot'] = 'Artikel�ndringar <b>m�ste</b> godk�nnas i denna kategori';
$lang['KB_Rules_moderate'] = 'Du <b>kan</b> %sadministrera denna kategori%s'; // %s replaced by a href links, do not remove!
$lang['KB_Rules_moderate_can'] = 'Du <b>kan</b> administrera denna kategori'; // %s replaced by a href links, do not remove!

$lang['Empty_fields'] ='V�nligen, fyll i alla f�lt.';
$lang['Empty_fields_return'] ='Klicka %sh�r%s f�r att �terg� till formul�ret.';
$lang['Empty_category'] ='V�lj en kategori';
$lang['Empty_type']='Ange artikeltyp';
$lang['Empty_article_name'] = 'Fyll i artikelns namn';
$lang['Empty_article_desc'] = 'Fyll i artikelns beskrivning';

$lang['Comments'] = 'Kommentarer/�sikter';

$lang['Post_comments'] = 'L�gg till en kommentar';

$lang['Category_sub'] = 'Underkategori';
$lang['Quick_stats'] = 'Statistik';

$lang['No_Articles'] = 'Det finns inga artiklar i denna kategori!';
$lang['Not_authorized'] = 'Du saknar r�ttighet att utf�ra �tg�rden...';
$lang['TOC'] = 'Inneh�ll';

//
// Print version
//
$lang['Print_version'] = '[Utskrivbar version]';

//
// Stats
//
$lang['Top_toprated'] = 'Topprankade';
$lang['Top_most_popular'] = 'Popul�rast';
$lang['Top_latest'] = 'Senaste artiklar';
$lang['Top_id'] = 'Artikelnummer';
$lang['Top_creation'] = 'Datum';
$lang['Top_alphabetic'] = 'Alfabetiskt';
$lang['Top_userrank'] = 'F�rfattarniv�';

//
// Update result messages
//
$lang['Click_return'] = 'Klicka %sh�r%s f�r att �terv�nda till f�reg�ende sida';
$lang['Click_return_kb'] = 'Klicka %sh�r%s f�r att �terv�nda till ' . $lang['KB_title'];
$lang['Click_return_article'] = 'Klicka %sh�r%s f�r att �terv�nda till din ' . $lang['Article'];

//
// Article formatting
//
$lang['Cat_all'] = 'Alla';

$lang['L_Pages'] = 'Sidor';
$lang['L_Pages_explain'] = 'Anv�nd kommandot \'[pages]\' f�r att dela upp artikeln i flera sidor';
$lang['L_Toc'] = 'Inneh�llsf�rteckning';
$lang['L_Toc_explain'] = 'Anv�nd kommandot \'[toc]\' f�r att l�gga till en post i inneh�llsf�rteckningen';
$lang['L_Abstract'] = 'Abstrakt/Ingress';
$lang['L_Abstract_explain'] = 'Anv�nd \'[abstract]...[/abstract]\' f�r att skapa en ingress';

$lang['L_Title_Format'] = 'Rubrik';
$lang['L_Title_Format_explain'] = 'Anv�nd \'[title]...[/title]\' f�r att skapa en rubrik';

$lang['L_Subtitle_Format'] = 'Underrubrik';
$lang['L_Subtitle_Format_explain'] = 'Anv�nd \'[subtitle]...[/subtitle]\' f�r att skapa en underrubrik';

$lang['L_Subsubtitle_Format'] = 'Liten rubrik';
$lang['L_Subsubtitle_Format_explain'] = 'Anv�nd \'[subsubtitle]...[/subsubtitle]\' f�r att skapa en liten rubrik';

$lang['L_Options'] = 'Alternativ:';
$lang['L_Formatting'] = 'Formatering:';

$lang['Default_article_id'] = 'Vilken artikel skall visas som standard?';

//
// MCP
//
$lang['MCP_title'] = 'Moderator Kontrollpanel';
$lang['MCP_title_explain'] = 'H�r kan admin (eller moderatorer) godk�nna artiklar';

$lang['View'] = 'Visa';

$lang['Approve_selected'] = 'Godk�nn valda';
$lang['Unapprove_selected'] = 'Underk�nn valda';
$lang['Delete_selected'] = 'Ta bort valda';
$lang['No_item'] = 'Det finns inga artiklar';

$lang['All_items'] = 'Alla artiklar';
$lang['Approved_items'] = 'Godk�nda artiklar';
$lang['Unapproved_items'] = 'Underk�nda artiklar';
$lang['Broken_items'] = 'Skadade artiklar';
$lang['Item_cat'] = 'Artikel i kategori';
$lang['Approve'] = 'Godk�nn';
$lang['Unapprove'] = 'Underk�nn';

$lang['Sorry_auth_delete'] = 'Tyv�rr, du kan inte ta bort artiklar i denna kategori.';
$lang['Sorry_auth_mcp'] = 'Tyv�rr, du kan inte hantera artiklar i denna kategori.';
$lang['Sorry_auth_approve'] = 'Tyv�rr, du kan inte godk�nna artiklar i denna kategori.';
$lang['Sorry_auth_post'] = 'Tyv�rr, du kan inte skriva artiklar i denna kategori.';
$lang['Sorry_auth_edit'] = 'Tyv�rr, du kan inte �ndra artiklar i denna kategori.';

$lang['Edit_article'] = '�ndra';
$lang['Delete_article'] = 'Ta bort';

//
// Added for v. 2.0
//
$lang['Addtional_field'] = 'Mer information (ej krav)';
$lang['No_cat_comments_forum_id'] = 'Kommentarer �r aktiverade men du har inte angett en phpBB forumkategori f�r dessa inl�gg i KB adminCP - kategorier.';

//
// Quick Nav
//
$lang['Quick_nav'] = 'Snabbnavigering';
$lang['Quick_jump'] = 'V�lj kategori';
$lang['Quick_go'] = 'G�';

//
// Search
//
$lang['Search'] = 'S�k';
$lang['Search_results'] = 'S�kresultat';
$lang['Search_for'] = 'S�k efter';
$lang['Results'] = 'Resultaten f�r';
$lang['No_matches'] = 'Tyv�rr, inga tr�ffar';
$lang['Matches'] = 'tr�ffar hittades f�r';
$lang['All'] = 'Alla kategorier';
$lang['Choose_cat'] = 'V�lj kategori:';
$lang['Include_comments'] = 'Inkludera kommentarer';
$lang['Submiter'] = 'S�nd av';

//
// Comments
//
$lang['KB_comment_prefix'] = '[ KB ] ';
$lang['Comments'] = 'Kommentarer';
$lang['Comments_title'] = 'Kommentartitel';
$lang['Comment_subject'] = 'Kommentar�mne';
$lang['Comment'] = 'Kommentar';
$lang['Comment_explain'] = 'Anv�nd textrutan och skriv din kommentar!';
$lang['Comment_add'] = 'L�gg till kommentar';
$lang['Comment_edit'] = '�ndra';
$lang['Comment_delete'] = 'Ta bort';
$lang['Comment_posted'] = 'Kommentaren skickades...';
$lang['Comment_deleted'] = 'Kommentaren togs bort...';
$lang['Comment_desc'] = 'Titel';
$lang['No_comments'] = 'Inte kommenterad';
$lang['Links_are_ON'] = 'L�nkar �r <u>P�</u>';
$lang['Links_are_OFF'] = 'L�nkar �r <u>AV</u>';
$lang['Images_are_ON'] = 'Bilder �r <u>P�</u>';
$lang['Images_are_OFF'] = 'Bilder �r <u>AV</u>';
$lang['Check_message_length'] = 'Kontrollera meddelandel�ngd';
$lang['Msg_length_1'] = 'Ditt meddelande �r ';
$lang['Msg_length_2'] = ' bokst�ver l�ngt.';
$lang['Msg_length_3'] = 'Du har ';
$lang['Msg_length_4'] = ' bokst�ver tillhanda.';;
$lang['Msg_length_5'] = 'Det finns ';
$lang['Msg_length_6'] = ' bokst�ver kvar att anv�nda.';

//
// Rate
//
$lang['Votes_label'] = 'Betyg';
$lang['Votes'] = 'betyg';
$lang['No_votes'] = 'Inte betygsatt';
$lang['Rate'] = 'Betygs�tt artikeln';
$lang['ADD_RATING'] = '[Betygs�tt artikeln]';
$lang['Rerror'] = 'Tyv�rr, du har redan r�stat.';
$lang['Rateinfo'] = 'Du t�nker betygs�tta artikeln <i>{filename}</i>.<br />V�lj ett betyg. 1 �r s�mst, 10 �r b�st.';
$lang['Rconf'] = 'Du har gett <i>{filename}</i> betyget {rate}.<br />Artikelns nya betyg blir d� {newrating}.';
$lang['R1'] = '1';
$lang['R2'] = '2';
$lang['R3'] = '3';
$lang['R4'] = '4';
$lang['R5'] = '5';
$lang['R6'] = '6';
$lang['R7'] = '7';
$lang['R8'] = '8';
$lang['R9'] = '9';
$lang['R10'] = '10';
$lang['Click_return_rate'] = 'Klicka %sh�r%s f�r att �terg� till artiklen';

//
// App
//

//
// Menu
//
$lang['sd_Project'] = 'Projekt';
$lang['sd_Management'] = 'Hantera';
$lang['sd_Doc_view'] = 'Visa dokument';
$lang['sd_Options'] = 'Alternativ';
$lang['sd_Help'] = 'Hj�lp';
$lang['sd_Contents'] = 'Inneh�ll *';
$lang['sd_About'] = 'Om *';

//
// Tree
//
$lang['sd_Tree_View'] = 'Tr�dvy';
$lang['sd_Toc'] = 'Inneh�ll';
$lang['sd_Where'] = 'Var';
$lang['sd_Before'] = 'F�re';
$lang['sd_After'] = 'Efter';
$lang['sd_Type'] = 'Typ';
$lang['sd_Name'] = 'Namn';
$lang['sd_Document'] = 'Dokument';
$lang['sd_Folder'] = 'Mapp';

//
// Index
//
$lang['sd_Doc_info'] = 'Dokumentinformation';
$lang['sd_Doc_preview'] = 'F�rhandsgranska';
$lang['sd_Edit_content'] = 'Redigera';
$lang['sd_Default_edit'] = '�ppna \'redigera\' som f�rval';
$lang['sd_Loading'] = 'Laddar data ..';
$lang['sd_Saving'] = 'Sparar data ..';

//
// Generic Type strings
// - Types are matched against these lang keys...where 'NAME' is the db defined type name
//
$lang['KB_type_NAME'] = 'Example Type';
?>