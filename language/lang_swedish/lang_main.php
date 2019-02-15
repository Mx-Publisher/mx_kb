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
$lang['kb_disable'] = 'Kunskapsbanken är inaktiverad.';

$lang['KB_title'] = 'Kunskapsbank (KB)';
$lang['Article'] = 'Artikel';
$lang['Articles'] = 'Artiklar';
$lang['Category'] = 'Kategori';
$lang['Sub_categories'] = 'Underkategorier';
$lang['Article_description'] = 'Beskrivning';
$lang['Article_type'] = 'Typ';
$lang['Article_keywords'] = 'Nyckelord';
$lang['Add_article'] = 'Lägg till artiklen';
$lang['KB_Home'] = 'KB Hem';
$lang['No_articles'] = 'Inga artiklar';
$lang['Article_title'] = 'Artikelnamn';
$lang['Article_text'] = 'Artikeltext';
$lang['Add_article'] = 'Lägg till artikel';
$lang['Read_article'] = 'Läs artikel';
$lang['Article_not_exsist'] = 'Artikeln finns inte';
$lang['Category_not_exsist'] = 'Kategorin finns inte';
$lang['Last_article'] = 'Senaste artikel';
$lang['Quick_jump'] = 'Välj kategori';

$lang['Edit'] = 'Ändra';
$lang['Click_cat_to_add'] = 'Klicka på \'Kategori\' för att lägga till en artikel';
$lang['Standalone_Not_Supported'] = 'Denna module tillåter inte att användas utanför MX-Publisher. Skapa ett MX-Publisher block i MXPs kontrollpanel.';

$lang['Article_submitted_Approve'] = 'Artikeln skickades...<br />En administratör kommer att granska artikeln innan den blir tillgänglig för andra.';
$lang['Article_submitted'] = 'Artikeln skickades...';

$lang['Article_Edited_Approve'] = 'Artikeln ändrades...<br />En administratör kommer att granska artikeln innan den blir tillgänglig för andra.';
$lang['Article_Edited'] = 'Artikeln ändrades...';
$lang['Edit_article'] = 'Ändra artikel';

$lang['Article_Deleted'] = 'Artikeln togs bort...';

//
// Notification
//
$lang['KB_prefix'] = '[ KB ]';
$lang['KB_notify_subject_new'] = 'Ny artikel!';
$lang['KB_notify_subject_edited'] = 'Ändrad artikel!';
$lang['KB_notify_subject_approved'] = 'Godkänd artikel!';
$lang['KB_notify_subject_unapproved'] = 'Underkänd artikel!';
$lang['KB_notify_subject_deleted'] = 'Borttagen artikel!';

$lang['KB_notify_new_body'] = 'En ny artikel är skriven.';
$lang['KB_notify_edited_body'] = 'En artikel har ändrats.';
$lang['KB_notify_approved_body'] = 'En artikel godkändes.';
$lang['KB_notify_unapproved_body'] = 'En artikel underkändes.';
$lang['KB_notify_deleted_body'] = 'En artikel togs bort.';
$lang['Edited_Article_info'] = 'Artikeln uppdaterades av ';

$lang['Read_full_article'] = '>>Läs hela artikeln';

//
// Auth Can
//
$lang['KB_Rules_post_can'] = 'Du <b>kan</b> skriva nya artiklar i denna kategori';
$lang['KB_Rules_post_cannot'] = 'Du <b>kan inte</b> skriva nya artiklar i denna kategori';
$lang['KB_Rules_comment_can'] = 'Du <b>kan</b> kommentera artiklar i denna kategori';
$lang['KB_Rules_comment_cannot'] = 'Du <b>kan inte</b> kommentera artiklar i denna kategori';
$lang['KB_Rules_edit_can'] = 'Du <b>kan</b> ändra dina artiklar i denna kategori';
$lang['KB_Rules_edit_cannot'] = 'Du <b>kan inte</b> ändra dina artiklar i denna kategori';
$lang['KB_Rules_delete_can'] = 'Du <b>kan</b> ta bort dina artiklar i denna kategori';
$lang['KB_Rules_delete_cannot'] = 'Du <b>kan inte</b> ta bort dina artiklar i denna kategori';
$lang['KB_Rules_rate_can'] = 'Du <b>kan</b> betygsätta artiklar i denna kategori';
$lang['KB_Rules_rate_cannot'] = 'Du <b>kan inte</b> betygsätta artiklar i denna kategori';
$lang['KB_Rules_approval_can'] = 'Artiklar <b>måste inte</b> godkännas i denna kategori';
$lang['KB_Rules_approval_cannot'] = 'Artiklar <b>måste</b> godkännas i denna kategori';
$lang['KB_Rules_approval_edit_can'] = 'Artikeländringar <b>måste inte</b> godkännas i denna kategori';
$lang['KB_Rules_approval_edit_cannot'] = 'Artikeländringar <b>måste</b> godkännas i denna kategori';
$lang['KB_Rules_moderate'] = 'Du <b>kan</b> %sadministrera denna kategori%s'; // %s replaced by a href links, do not remove!
$lang['KB_Rules_moderate_can'] = 'Du <b>kan</b> administrera denna kategori'; // %s replaced by a href links, do not remove!

$lang['Empty_fields'] ='Vänligen, fyll i alla fält.';
$lang['Empty_fields_return'] ='Klicka %shär%s för att återgå till formuläret.';
$lang['Empty_category'] ='Välj en kategori';
$lang['Empty_type']='Ange artikeltyp';
$lang['Empty_article_name'] = 'Fyll i artikelns namn';
$lang['Empty_article_desc'] = 'Fyll i artikelns beskrivning';

$lang['Comments'] = 'Kommentarer/åsikter';

$lang['Post_comments'] = 'Lägg till en kommentar';

$lang['Category_sub'] = 'Underkategori';
$lang['Quick_stats'] = 'Statistik';

$lang['No_Articles'] = 'Det finns inga artiklar i denna kategori!';
$lang['Not_authorized'] = 'Du saknar rättighet att utföra åtgärden...';
$lang['TOC'] = 'Innehåll';

//
// Print version
//
$lang['Print_version'] = '[Utskrivbar version]';

//
// Stats
//
$lang['Top_toprated'] = 'Topprankade';
$lang['Top_most_popular'] = 'Populärast';
$lang['Top_latest'] = 'Senaste artiklar';
$lang['Top_id'] = 'Artikelnummer';
$lang['Top_creation'] = 'Datum';
$lang['Top_alphabetic'] = 'Alfabetiskt';
$lang['Top_userrank'] = 'Författarnivå';

//
// Update result messages
//
$lang['Click_return'] = 'Klicka %shär%s för att återvända till föregående sida';
$lang['Click_return_kb'] = 'Klicka %shär%s för att återvända till ' . $lang['KB_title'];
$lang['Click_return_article'] = 'Klicka %shär%s för att återvända till din ' . $lang['Article'];

//
// Article formatting
//
$lang['Cat_all'] = 'Alla';

$lang['L_Pages'] = 'Sidor';
$lang['L_Pages_explain'] = 'Använd kommandot \'[pages]\' för att dela upp artikeln i flera sidor';
$lang['L_Toc'] = 'Innehållsförteckning';
$lang['L_Toc_explain'] = 'Använd kommandot \'[toc]\' för att lägga till en post i innehållsförteckningen';
$lang['L_Abstract'] = 'Abstrakt/Ingress';
$lang['L_Abstract_explain'] = 'Använd \'[abstract]...[/abstract]\' för att skapa en ingress';

$lang['L_Title_Format'] = 'Rubrik';
$lang['L_Title_Format_explain'] = 'Använd \'[title]...[/title]\' för att skapa en rubrik';

$lang['L_Subtitle_Format'] = 'Underrubrik';
$lang['L_Subtitle_Format_explain'] = 'Använd \'[subtitle]...[/subtitle]\' för att skapa en underrubrik';

$lang['L_Subsubtitle_Format'] = 'Liten rubrik';
$lang['L_Subsubtitle_Format_explain'] = 'Använd \'[subsubtitle]...[/subsubtitle]\' för att skapa en liten rubrik';

$lang['L_Options'] = 'Alternativ:';
$lang['L_Formatting'] = 'Formatering:';

$lang['Default_article_id'] = 'Vilken artikel skall visas som standard?';

//
// MCP
//
$lang['MCP_title'] = 'Moderator Kontrollpanel';
$lang['MCP_title_explain'] = 'Här kan admin (eller moderatorer) godkänna artiklar';

$lang['View'] = 'Visa';

$lang['Approve_selected'] = 'Godkänn valda';
$lang['Unapprove_selected'] = 'Underkänn valda';
$lang['Delete_selected'] = 'Ta bort valda';
$lang['No_item'] = 'Det finns inga artiklar';

$lang['All_items'] = 'Alla artiklar';
$lang['Approved_items'] = 'Godkända artiklar';
$lang['Unapproved_items'] = 'Underkända artiklar';
$lang['Broken_items'] = 'Skadade artiklar';
$lang['Item_cat'] = 'Artikel i kategori';
$lang['Approve'] = 'Godkänn';
$lang['Unapprove'] = 'Underkänn';

$lang['Sorry_auth_delete'] = 'Tyvärr, du kan inte ta bort artiklar i denna kategori.';
$lang['Sorry_auth_mcp'] = 'Tyvärr, du kan inte hantera artiklar i denna kategori.';
$lang['Sorry_auth_approve'] = 'Tyvärr, du kan inte godkänna artiklar i denna kategori.';
$lang['Sorry_auth_post'] = 'Tyvärr, du kan inte skriva artiklar i denna kategori.';
$lang['Sorry_auth_edit'] = 'Tyvärr, du kan inte ändra artiklar i denna kategori.';

$lang['Edit_article'] = 'Ändra';
$lang['Delete_article'] = 'Ta bort';

//
// Added for v. 2.0
//
$lang['Addtional_field'] = 'Mer information (ej krav)';
$lang['No_cat_comments_forum_id'] = 'Kommentarer är aktiverade men du har inte angett en phpBB forumkategori för dessa inlägg i KB adminCP - kategorier.';

//
// Quick Nav
//
$lang['Quick_nav'] = 'Snabbnavigering';
$lang['Quick_jump'] = 'Välj kategori';
$lang['Quick_go'] = 'Gå';

//
// Search
//
$lang['Search'] = 'Sök';
$lang['Search_results'] = 'Sökresultat';
$lang['Search_for'] = 'Sök efter';
$lang['Results'] = 'Resultaten för';
$lang['No_matches'] = 'Tyvärr, inga träffar';
$lang['Matches'] = 'träffar hittades för';
$lang['All'] = 'Alla kategorier';
$lang['Choose_cat'] = 'Välj kategori:';
$lang['Include_comments'] = 'Inkludera kommentarer';
$lang['Submiter'] = 'Sänd av';

//
// Comments
//
$lang['KB_comment_prefix'] = '[ KB ] ';
$lang['Comments'] = 'Kommentarer';
$lang['Comments_title'] = 'Kommentartitel';
$lang['Comment_subject'] = 'Kommentarämne';
$lang['Comment'] = 'Kommentar';
$lang['Comment_explain'] = 'Använd textrutan och skriv din kommentar!';
$lang['Comment_add'] = 'Lägg till kommentar';
$lang['Comment_edit'] = 'Ändra';
$lang['Comment_delete'] = 'Ta bort';
$lang['Comment_posted'] = 'Kommentaren skickades...';
$lang['Comment_deleted'] = 'Kommentaren togs bort...';
$lang['Comment_desc'] = 'Titel';
$lang['No_comments'] = 'Inte kommenterad';
$lang['Links_are_ON'] = 'Länkar är <u>PÅ</u>';
$lang['Links_are_OFF'] = 'Länkar är <u>AV</u>';
$lang['Images_are_ON'] = 'Bilder är <u>PÅ</u>';
$lang['Images_are_OFF'] = 'Bilder är <u>AV</u>';
$lang['Check_message_length'] = 'Kontrollera meddelandelängd';
$lang['Msg_length_1'] = 'Ditt meddelande är ';
$lang['Msg_length_2'] = ' bokstäver långt.';
$lang['Msg_length_3'] = 'Du har ';
$lang['Msg_length_4'] = ' bokstäver tillhanda.';;
$lang['Msg_length_5'] = 'Det finns ';
$lang['Msg_length_6'] = ' bokstäver kvar att använda.';

//
// Rate
//
$lang['Votes_label'] = 'Betyg';
$lang['Votes'] = 'betyg';
$lang['No_votes'] = 'Inte betygsatt';
$lang['Rate'] = 'Betygsätt artikeln';
$lang['ADD_RATING'] = '[Betygsätt artikeln]';
$lang['Rerror'] = 'Tyvärr, du har redan röstat.';
$lang['Rateinfo'] = 'Du tänker betygsätta artikeln <i>{filename}</i>.<br />Välj ett betyg. 1 är sämst, 10 är bäst.';
$lang['Rconf'] = 'Du har gett <i>{filename}</i> betyget {rate}.<br />Artikelns nya betyg blir då {newrating}.';
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
$lang['Click_return_rate'] = 'Klicka %shär%s för att återgå till artiklen';

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
$lang['sd_Help'] = 'Hjälp';
$lang['sd_Contents'] = 'Innehåll *';
$lang['sd_About'] = 'Om *';

//
// Tree
//
$lang['sd_Tree_View'] = 'Trädvy';
$lang['sd_Toc'] = 'Innehåll';
$lang['sd_Where'] = 'Var';
$lang['sd_Before'] = 'Före';
$lang['sd_After'] = 'Efter';
$lang['sd_Type'] = 'Typ';
$lang['sd_Name'] = 'Namn';
$lang['sd_Document'] = 'Dokument';
$lang['sd_Folder'] = 'Mapp';

//
// Index
//
$lang['sd_Doc_info'] = 'Dokumentinformation';
$lang['sd_Doc_preview'] = 'Förhandsgranska';
$lang['sd_Edit_content'] = 'Redigera';
$lang['sd_Default_edit'] = 'Öppna \'redigera\' som förval';
$lang['sd_Loading'] = 'Laddar data ..';
$lang['sd_Saving'] = 'Sparar data ..';

//
// Generic Type strings
// - Types are matched against these lang keys...where 'NAME' is the db defined type name
//
$lang['KB_type_NAME'] = 'Example Type';
?>