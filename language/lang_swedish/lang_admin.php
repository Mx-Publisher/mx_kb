<?php
/**
*
* @package MX-Publisher Module - mx_kb
* @version $Id: lang_admin.php,v 1.4 2013/06/17 15:47:49 orynider Exp $
* @copyright (c) 2002-2006 [Jon Ohlsson, Mohd Basri, wGEric, PHP Arena, pafileDB, CRLin] MX-Publisher Project Team
* @license http://opensource.org/licenses/gpl-license.php GNU General Public License v2
*
*/

//
// adminCP index
//
$lang['KB_title'] = 'Kunskapsbank (KB)';
$lang['1_Configuration'] = 'Inst�llningar';
$lang['2_Cat_man'] = 'Kategorihantering';
$lang['3_Art_man'] = 'Artikelhantering';
$lang['4_Permissions'] = 'R�ttigheter';
$lang['5_Types_man'] = 'Artikeltyper';
$lang['6_Custom_Field'] = 'Extra f�lt';
$lang['7_Optimize_tables'] = 'Optimera s�ktabell';

//
// Parameter Types
//
$lang['ParType_kb_type_select'] = 'Avancerat k�llval';
$lang['ParType_kb_type_select_info'] = '';
$lang['ParType_default_article_id'] = 'StandardID';
$lang['ParType_default_article_id_info'] = '- Article Reader';

$lang['ParType_kb_quick_cat'] = 'KB grundkategori';
$lang['ParType_kb_quick_cat_info'] = '';

//
// Parameter Names
//
$lang['kb_type_select'] = 'KB k�lla:';
$lang['kb_type_select_explain'] = 'V�lj k�llkategori och artikeltyp';

$lang['default_article_id'] = 'Standard ArticleID:';
$lang['default_article_id_explain'] = '- Denna artikel visas (f�rst) om ingen annan �r vald';

//
// Admin Panels - Configuration
//
$lang['Panel_config_title'] = 'KB inst�llningar';
$lang['Panel_config_explain'] = '�ndra inst�llningar i din KB';

//
// General
//
$lang['General_title'] = 'Allm�nt';

$lang['Module_name'] = 'Modulnamn';
$lang['Module_name_explain'] = '';

$lang['Enable_module'] = 'Aktivera modulen';
$lang['Enable_module_explain'] = 'N�r modulen �r inaktiverad f�r anv�ndare har fortfarande administrat�ren tilltr�de.';

$lang['Wysiwyg_path'] = 'Var finns wysiwyg mjukvaran?';
$lang['Wysiwyg_path_explain'] = 'S�v�gen till (fr�n MX-Publisher roten) mappen d�r wysiwyg mjukvaran �r uppladdad, t ex \'modules/mx_shared/\' om tinymce finns i mappen modules/mx_shared/tinymce.';

$lang['Allow_file'] = 'Till�t l�gga till artiklar';
$lang['Allow_file_info'] = 'Om du inte till�ter att artiklar l�ggs till denna kategori, s� blir kategorin en toppniv�kategori - en plats med subkategorier.';

//
// Article
//
$lang['Article_title'] = 'Artiklar';

//
// Appearance
//
$lang['Appearance_title'] = 'Utseende';

$lang['Article_pag'] = 'Artiklar och sidbrytning';
$lang['Article_pag_explain'] = 'Antal artiklar att visa innan sidbrytning.';

$lang['Sort_method'] = 'Sorteringss�tt';
$lang['Sort_method_explain'] = 'Best�m hur artiklar sorteras inom sin kategori.';

$lang['Sort_order'] = 'ASC eller DESC sortering';
$lang['Sort_order_explain'] = '';

$lang['Stats_list'] = 'Visa KB statistikl�nkar';
$lang['Stats_list_explain'] = 'Visa KB statistikl�nkar �verst p� sidan.';

$lang['Header_banner'] = 'Visa KB logo';
$lang['Header_banner_explain'] = 'Visa KB logo �verst p� sidan.';

$lang['Use_simple_navigation'] = 'Enkel kategorinavigering';
$lang['Use_simple_navigation_explain'] = 'Enklare navigering...';

$lang['Cat_col'] = 'Antal kategorikolumner (anv�nds bara vid \'Enkel kategorinavigering\')';

$lang['Nfdays'] = 'Antal dagar nya';
$lang['Nfdaysinfo'] = 'Antal dagar en artikel visas som ny, med en \'Ny artikel\' ikon. Om v�rdet s�tt till 5, kommer alla artiklar uppladdade de senaste 5 dagarna visas med en \'Ny artikel\' ikon';


//
// Comments
//
$lang['Comments_title'] = 'Kommentarer';
$lang['Comments_title_explain'] = 'Vissa kommentarinst�llningar �r defaultv�rden, och kan �ndras per kategori';

$lang['Use_comments'] = 'Kommentarer';
$lang['Use_comments_explain'] = 'Aktivera kommentarer.';

$lang['Internal_comments'] = 'Interna eller phpBB kommentarer';
$lang['Internal_comments_explain'] = 'Anv�nd interna eller phpBB kommentarer';

$lang['Internal_comments_phpBB'] = 'phpBB kommentarer';
$lang['Internal_comments_internal'] = 'Interna kommentarer';

$lang['Select_topic_id'] = 'V�lj phpBB kommentarforum!';

$lang['Forum_id'] = 'phpBB Forum ID';
$lang['Forum_id_explain'] = 'Om phpBB kommentarer anv�nds �r detta det forum d�r kommentarerna samlas';

$lang['Autogenerate_comments'] = 'Skapa autokommentarer';
$lang['Autogenerate_comments_explain'] = 'N�r en artikel �ndras/skrivs, g�rs ett inl�gg i kommentarforumet automatiskt.';

$lang['Del_topic'] = 'Ta bort forumkommentarer';
$lang['Del_topic_explain'] = 'N�r en artikel tas bort, skall �ven associerade forumkommentarer tas bort?';

$lang['Comments_pag'] = 'Kommentarer och sidbrytning';
$lang['Comments_pag_explain'] = 'Antal kommentarer att visa innan sidbrytning.';

$lang['Allow_Wysiwyg'] = 'Anv�nd wysiwyg editor';
$lang['Allow_Wysiwyg_explain'] = 'Om aktiverad, ers�tts den vanliga bbcode/html/smilies redigeraren med en wysiwyg editor.';

$lang['Allow_links'] = 'Till�t l�nkar';
$lang['Allow_links_message'] = 'Default \'inga l�nkar\' meddelande';
$lang['Allow_links_explain'] = 'Om l�nkar ej �r till�tna visas detta meddelande ist�llet';

$lang['Allow_images'] = 'Till�t bilder';
$lang['Allow_images_message'] = 'Default \'No Images\' meddelande';
$lang['Allow_images_explain'] = 'Om bilder ej �r till�tna visas detta meddelande ist�llet';

$lang['Max_subject_char'] = 'Max antal tecken (i titel)';
$lang['Max_subject_char_explain'] = 'Om man skriven en titel med fler tecken visas ett felmeddelande.';

$lang['Max_desc_char'] = 'Max antal tecken (i beskrivning)';
$lang['Max_desc_char_explain'] = 'Om man skriven en titel med fler tecken visas ett felmeddelande.';

$lang['Max_char'] = 'Max antal tecken';
$lang['Max_char_explain'] = 'Om man skriven en kommentar med fler tecken visas ett felmeddelande.';

$lang['Format_wordwrap'] = 'Avstavning';
$lang['Format_wordwrap_explain'] = '';

$lang['Format_truncate_links'] = 'F�rkorta l�nkar';
$lang['Format_truncate_links_explain'] = 'L�nkar skrivs om, t ex \'www.mxp-portal...\'';

$lang['Format_image_resize'] = 'Skala om bilder';
$lang['Format_image_resize_explain'] = 'Bilder omskalas till denna bredd (pixlar)';

//
// Ratings
//
$lang['Ratings_title'] = 'Betygs�ttning';
$lang['Ratings_title_explain'] = 'Vissa inst�llning �r grundinst�llningar och kan �ndras per kategori';

$lang['Use_ratings'] = 'Betygs�ttning (r�sta)';
$lang['Use_ratings_explain'] = 'Aktivera betygs�ttning';

$lang['Votes_check_ip'] = 'Godk�nn r�stningar - IP';
$lang['Votes_check_ip_explain'] = 'Endast en r�st per IP-adress godk�nns.';

$lang['Votes_check_userid'] = 'Godk�nn r�stningar - anv�ndare';
$lang['Votes_check_userid_explain'] = 'Anv�ndare f�r endast r�sta en g�ng.';

//
// Instructions
//
$lang['Instructions_title'] = 'Anv�ndarinstruktioner';

$lang['Pre_text_name'] = 'Instruktionstext';
$lang['Pre_text_explain'] = 'Aktivera instruktionstext som visas f�r anv�ndare d� de skall skicka en artikel.';

$lang['Pre_text_header'] = 'Instruktionstext - rubrik';
$lang['Pre_text_body'] = 'Instruktionstext - text';

$lang['Show'] = 'Visa';
$lang['Hide'] = 'D�lj';

//
// Notifications
//
$lang['Notifications_title'] = 'P�minnelser';

$lang['Notify'] = 'Informera admin via: ';
$lang['Notify_explain'] = 'Best�m p� vilket s�tt admin skall bli informerad om nya/redigerade artiklar';
$lang['PM'] = 'PM';

$lang['Notify_group'] = 'och till grupp: ';
$lang['Notify_group_explain'] = 'Informera dessutom medlemmarna i denna grupp.';

$lang['KB_config'] = 'KB konfiguration';
$lang['Art_types'] = 'Artikeltyp';

$lang['Click_return_kb_config'] = 'Klicka %sh�r%s f�r att �terg� till KB konfiguration';
$lang['KB_config_updated'] = 'KB konfigurationen uppdaterades...';

$lang['Mod_group'] = 'KB moderatorgrupp';
$lang['Mod_group_explain'] = '- med KB adminr�ttigheter!';

//
// General
//
$lang['Article'] = 'Artikel';
$lang['Articles'] = 'Artiklar';
$lang['Article_description'] = 'Beskrivning';

$lang['Article_category'] = 'Kategori';

$lang['Category'] = 'Kategori';
$lang['Category_desc'] = 'Kategoribeskrivning';

$lang['Article_type'] = 'Typ';
$lang['Art_action'] = 'Val';

//
// Admin Panels - Article
//
$lang['Panel_art_title'] = 'Artikelhantering';
$lang['Panel_art_explain'] = 'H�r kan du granska artiklar, och godk�nna/underk�nna dem.';

//approve
$lang['Art_edit'] = '�ndrade/redigerade artiklar';
$lang['Art_not_approved'] = 'Inte godk�nda';
$lang['Art_approved'] = 'Godk�nda';
$lang['Approve'] = 'Godk�nn';
$lang['Un_approve'] = 'Underk�nn';
$lang['Article_approved'] = 'Artikeln �r nu godk�nd.';
$lang['Article_unapproved'] = 'Artikeln �r nu underk�nd.';

//delete
$lang['Delete'] = 'Ta bort';
$lang['Confirm_art_delete'] = 'Vill du verkligen ta bort artikeln?';
$lang['Confirm_art_delete_yes'] = '%sJa, jag vill ta bort artikeln%s';
$lang['Confirm_art_delete_no'] = '%sNej, jag vill inte ta bort artikeln%s';
$lang['Article_deleted'] = 'Artikeln togs bort...';

//
// Admin Panels - Category
//
$lang['Panel_cat_title'] = 'Kategoriadministration';
$lang['Panel_cat_explain'] = 'H�r kan du l�gga till, �ndra och ta bort kategorier.';

$lang['Use_default'] = 'Andv�nd grundinst�llning';

$lang['Create_cat'] = 'Ny kategori:';
$lang['Create'] = 'Skapa';
$lang['Cat_settings'] = 'Kategoriinst�llningar';
$lang['Create_description'] = 'H�r kan du �ndra kategorins namn och beskrivning.';
$lang['Cat_created'] = 'Kategorin skapades...';
$lang['Click_return_cat_manager'] = 'Klicka %sh�r%s f�r att �terg� till ' . $lang['2_Cat_man'];
$lang['Edit_description'] = 'H�r kan du �ndra dina kategoriinst�llningar.';
$lang['Edit_cat'] = '�ndra kategori';
$lang['Cat_edited'] = 'Kategorin �ndrades.';
$lang['Parent'] = 'Parent';

$lang['Cat_delete_title'] = 'Ta bort kategori';
$lang['Cat_delete_desc'] = 'H�r kan du ta bort en kategori och flytta alla dess artiklar till en annan kategori';
$lang['Cat_deleted'] = 'Kategorin togs bort...';
$lang['Delete_all_articles'] = 'Ta bort artiklar';

//
// Admin Panels - Permissions
//
$lang['KB_Auth_Title'] = 'KB-r�ttigheter';
$lang['KB_Auth_Explain'] = 'H�r best�ms de r�ttigheter olika grupper har per kategori';
$lang['Select_a_Category'] = 'V�lj kategori';
$lang['Look_up_Category'] = 'V�lj kategori';
$lang['KB_Auth_successfully'] = 'R�ttigheterna uppdaterades';
$lang['Click_return_KB_auth'] = 'Klicka %sh�r%s f�r att �terg� till KB-r�ttigeterna';

$lang['Upload'] = 'Skicka';
$lang['Rate'] = 'R�sta';
$lang['Comment'] = 'Kommentera';
$lang['Approval'] = 'Godk�nna';
$lang['Approval_edit'] = 'Godk�nna �ndringar';

$lang['Allow_rating'] = 'Till�t r�stningar';
$lang['Allow_rating_explain'] = 'Till�t anv�ndare att r�sta p� artiklar.';

$lang['Allow_anonymos_rating'] = 'Till�t g�str�stning';
$lang['Allow_anonymos_rating_explain'] = 'Om r�stingar �r aktiverade, till�t icke-registrerade anv�ndare att r�sta.';

$lang['Category_Permissions'] = 'Kategorir�ttigheter';
$lang['Category_Title'] = 'Kategorititel';
$lang['Category_Desc'] = 'Kategoribeskrivning';
$lang['View_level'] = 'L�sa';
$lang['Upload_level'] = 'Skicka (skriva)';
$lang['Rate_level'] = 'R�sta';
$lang['View_Comment_level'] = 'Se kommentarer';
$lang['Post_Comment_level'] = 'Skriva kommentarer';
$lang['Edit_Comment_level'] = '�ndra kommentarer';
$lang['Delete_Comment_level'] = 'Ta bort kommentarer';
$lang['Edit_level'] = '�ndra';
$lang['Delete_level'] = 'Ta bort';
$lang['Approval_level'] = 'Godk�nna';
$lang['Approval_edit_level'] = 'Godk�nna �ndrade';

//
// Admin Panels - Types
//
$lang['Types_man'] = 'Artikeltyphantering';
$lang['KB_types_description'] = 'H�r kan du �ndra och hantera artikeltyper.';
$lang['Create_type'] = 'Skapa ny artikeltyp:';
$lang['Type_created'] = 'Artikeltypen skapades...';
$lang['Click_return_type_manager'] = 'Klicka %sh�r%s f�r att �terg� till artikeltyphanteringen';

$lang['Edit_type'] = '�ndra artikeltyp';
$lang['Edit_type_description'] = 'H�r kan du �ndra artikeltypens namn';
$lang['Type_edited'] = 'Artikeltyp togs bort.';

$lang['Type_delete_title'] = 'Ta bort artikeltyp';
$lang['Type_delete_desc'] = 'Ange den artikeltyp artiklar skall f� som har den artikeltyp du nu tar bort.';
$lang['Change_type'] = '�ndra artikeltyp till ';
$lang['Change_and_Delete'] = '�ndra och ta bort';
$lang['Type_deleted'] = 'Artikeltyp togs bort...';

//
// Admin Panels - Custom Field
//
$lang['Fieldselecttitle'] = 'V�lj ett alternativ';
$lang['Afield'] = 'Extra f�lt: l�gg till';
$lang['Efield'] = 'Extra f�lt: �ndra';
$lang['Dfield'] = 'Extra f�lt: ta bort';
$lang['Mfieldtitle'] = 'Extra f�lt';
$lang['Afieldtitle'] = 'L�gg till f�lt';
$lang['Efieldtitle'] = '�ndra f�lt';
$lang['Dfieldtitle'] = 'Ta bort f�lt';
$lang['Fieldexplain'] = 'H�r kan du l�gga till extra f�lt. Om du exempelvis vill ha ett f�lt med artikelns publikationsdatum, skapar du ett s�dant.';
$lang['Fieldname'] = 'F�ltnamn';
$lang['Fieldnameinfo'] = 'Detta �r f�ltnamnet, t ex \'Filstorlek\'';
$lang['Fielddesc'] = 'F�ltbeskrivning';
$lang['Fielddescinfo'] = 'Detta �r en f�ltbeskrivning, t ex \'Storlek p� filen\'';
$lang['Fieldadded'] = 'F�ltet lades till';
$lang['Fieldedited'] = 'F�ltet �ndrades...';
$lang['Dfielderror'] = 'Du valde inga f�lt att ta bort';
$lang['Fieldsdel'] = 'F�ltet togs bort...';

$lang['Field_data'] = 'Options';
$lang['Field_data_info'] = 'Enter the options that the user can choose from. Separate each option with a new-line (carriage return).';
$lang['Field_regex'] = 'Regular Expression';
$lang['Field_regex_info'] = 'You may require the input field to match a regular expression %s(PCRE)%s.';
$lang['Field_order'] = 'Display Order';

$lang['Click_return'] = 'Klicka %sh�r%s f�r att �terg� till f�reg�ende sida';

// These are displayed in the drop down boxes for advanced
// mode auth, try and keep them short!
$lang['Cat_NONE'] = "INGEN";
$lang['Cat_ALL'] = "ALLA";
$lang['Cat_REG'] = "REG";
$lang['Cat_PRIVATE'] = "PRIVAT";
$lang['Cat_MOD'] = "MOD";
$lang['Cat_ADMIN'] = "ADMIN";

//
// Admin Panels - Field Types
//
$lang['Field_Input'] = 'Single-Line Text Box';
$lang['Field_Textarea'] = 'Multiple-Line Text Box';
$lang['Field_Radio'] = 'Single-Selection Radio Buttons';
$lang['Field_Select'] = 'Single-Selection Menu';
$lang['Field_Select_multiple'] = 'Multiple-Selection Menu';
$lang['Field_Checkbox'] = 'Multiple-Selection Checkbox';

//
// Toplists
//
$lang['toplist_sort_method']     	= 'Typ av toplista';
$lang['toplist_display_options']    = 'Visningsalternativ';
$lang['toplist_use_pagination']     = 'Bl�ddra \'antal rader\' i taget';
$lang['toplist_pagination']         = 'Antal rader';
$lang['toplist_filter_date'] 			= 'Datumfilter';
$lang['toplist_filter_date_explain'] 	= '- Visa inl�gg fr�n senaste dagen, veckan, m�ndaden, �ret...';
$lang['toplist_cat_id']       		= 'Begr�nsa till kategori';
$lang['target_block']       		= 'Associerat KB block';

//
// Mini
//
$lang['mini_display_options']    = 'Visningsalternativ';
$lang['mini_pagination']         = 'Antal rader';
$lang['mini_default_cat_id']     = 'Begr�nsa till kategori';

?>