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
$lang['1_Configuration'] = 'Inställningar';
$lang['2_Cat_man'] = 'Kategorihantering';
$lang['3_Art_man'] = 'Artikelhantering';
$lang['4_Permissions'] = 'Rättigheter';
$lang['5_Types_man'] = 'Artikeltyper';
$lang['6_Custom_Field'] = 'Extra fält';
$lang['7_Optimize_tables'] = 'Optimera söktabell';

//
// Parameter Types
//
$lang['ParType_kb_type_select'] = 'Avancerat källval';
$lang['ParType_kb_type_select_info'] = '';
$lang['ParType_default_article_id'] = 'StandardID';
$lang['ParType_default_article_id_info'] = '- Article Reader';

$lang['ParType_kb_quick_cat'] = 'KB grundkategori';
$lang['ParType_kb_quick_cat_info'] = '';

//
// Parameter Names
//
$lang['kb_type_select'] = 'KB källa:';
$lang['kb_type_select_explain'] = 'Välj källkategori och artikeltyp';

$lang['default_article_id'] = 'Standard ArticleID:';
$lang['default_article_id_explain'] = '- Denna artikel visas (först) om ingen annan är vald';

//
// Admin Panels - Configuration
//
$lang['Panel_config_title'] = 'KB inställningar';
$lang['Panel_config_explain'] = 'Ändra inställningar i din KB';

//
// General
//
$lang['General_title'] = 'Allmänt';

$lang['Module_name'] = 'Modulnamn';
$lang['Module_name_explain'] = '';

$lang['Enable_module'] = 'Aktivera modulen';
$lang['Enable_module_explain'] = 'När modulen är inaktiverad för användare har fortfarande administratören tillträde.';

$lang['Wysiwyg_path'] = 'Var finns wysiwyg mjukvaran?';
$lang['Wysiwyg_path_explain'] = 'Sövägen till (från MX-Publisher roten) mappen där wysiwyg mjukvaran är uppladdad, t ex \'modules/mx_shared/\' om tinymce finns i mappen modules/mx_shared/tinymce.';

$lang['Allow_file'] = 'Tillåt lägga till artiklar';
$lang['Allow_file_info'] = 'Om du inte tillåter att artiklar läggs till denna kategori, så blir kategorin en toppnivåkategori - en plats med subkategorier.';

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

$lang['Sort_method'] = 'Sorteringssätt';
$lang['Sort_method_explain'] = 'Bestäm hur artiklar sorteras inom sin kategori.';

$lang['Sort_order'] = 'ASC eller DESC sortering';
$lang['Sort_order_explain'] = '';

$lang['Stats_list'] = 'Visa KB statistiklänkar';
$lang['Stats_list_explain'] = 'Visa KB statistiklänkar överst på sidan.';

$lang['Header_banner'] = 'Visa KB logo';
$lang['Header_banner_explain'] = 'Visa KB logo överst på sidan.';

$lang['Use_simple_navigation'] = 'Enkel kategorinavigering';
$lang['Use_simple_navigation_explain'] = 'Enklare navigering...';

$lang['Cat_col'] = 'Antal kategorikolumner (används bara vid \'Enkel kategorinavigering\')';

$lang['Nfdays'] = 'Antal dagar nya';
$lang['Nfdaysinfo'] = 'Antal dagar en artikel visas som ny, med en \'Ny artikel\' ikon. Om värdet sätt till 5, kommer alla artiklar uppladdade de senaste 5 dagarna visas med en \'Ny artikel\' ikon';


//
// Comments
//
$lang['Comments_title'] = 'Kommentarer';
$lang['Comments_title_explain'] = 'Vissa kommentarinställningar är defaultvärden, och kan ändras per kategori';

$lang['Use_comments'] = 'Kommentarer';
$lang['Use_comments_explain'] = 'Aktivera kommentarer.';

$lang['Internal_comments'] = 'Interna eller phpBB kommentarer';
$lang['Internal_comments_explain'] = 'Använd interna eller phpBB kommentarer';

$lang['Internal_comments_phpBB'] = 'phpBB kommentarer';
$lang['Internal_comments_internal'] = 'Interna kommentarer';

$lang['Select_topic_id'] = 'Välj phpBB kommentarforum!';

$lang['Forum_id'] = 'phpBB Forum ID';
$lang['Forum_id_explain'] = 'Om phpBB kommentarer används är detta det forum där kommentarerna samlas';

$lang['Autogenerate_comments'] = 'Skapa autokommentarer';
$lang['Autogenerate_comments_explain'] = 'När en artikel ändras/skrivs, görs ett inlägg i kommentarforumet automatiskt.';

$lang['Del_topic'] = 'Ta bort forumkommentarer';
$lang['Del_topic_explain'] = 'När en artikel tas bort, skall även associerade forumkommentarer tas bort?';

$lang['Comments_pag'] = 'Kommentarer och sidbrytning';
$lang['Comments_pag_explain'] = 'Antal kommentarer att visa innan sidbrytning.';

$lang['Allow_Wysiwyg'] = 'Använd wysiwyg editor';
$lang['Allow_Wysiwyg_explain'] = 'Om aktiverad, ersätts den vanliga bbcode/html/smilies redigeraren med en wysiwyg editor.';

$lang['Allow_links'] = 'Tillåt länkar';
$lang['Allow_links_message'] = 'Default \'inga länkar\' meddelande';
$lang['Allow_links_explain'] = 'Om länkar ej är tillåtna visas detta meddelande istället';

$lang['Allow_images'] = 'Tillåt bilder';
$lang['Allow_images_message'] = 'Default \'No Images\' meddelande';
$lang['Allow_images_explain'] = 'Om bilder ej är tillåtna visas detta meddelande istället';

$lang['Max_subject_char'] = 'Max antal tecken (i titel)';
$lang['Max_subject_char_explain'] = 'Om man skriven en titel med fler tecken visas ett felmeddelande.';

$lang['Max_desc_char'] = 'Max antal tecken (i beskrivning)';
$lang['Max_desc_char_explain'] = 'Om man skriven en titel med fler tecken visas ett felmeddelande.';

$lang['Max_char'] = 'Max antal tecken';
$lang['Max_char_explain'] = 'Om man skriven en kommentar med fler tecken visas ett felmeddelande.';

$lang['Format_wordwrap'] = 'Avstavning';
$lang['Format_wordwrap_explain'] = '';

$lang['Format_truncate_links'] = 'Förkorta länkar';
$lang['Format_truncate_links_explain'] = 'Länkar skrivs om, t ex \'www.mxp-portal...\'';

$lang['Format_image_resize'] = 'Skala om bilder';
$lang['Format_image_resize_explain'] = 'Bilder omskalas till denna bredd (pixlar)';

//
// Ratings
//
$lang['Ratings_title'] = 'Betygsättning';
$lang['Ratings_title_explain'] = 'Vissa inställning är grundinställningar och kan ändras per kategori';

$lang['Use_ratings'] = 'Betygsättning (rösta)';
$lang['Use_ratings_explain'] = 'Aktivera betygsättning';

$lang['Votes_check_ip'] = 'Godkänn röstningar - IP';
$lang['Votes_check_ip_explain'] = 'Endast en röst per IP-adress godkänns.';

$lang['Votes_check_userid'] = 'Godkänn röstningar - användare';
$lang['Votes_check_userid_explain'] = 'Användare får endast rösta en gång.';

//
// Instructions
//
$lang['Instructions_title'] = 'Användarinstruktioner';

$lang['Pre_text_name'] = 'Instruktionstext';
$lang['Pre_text_explain'] = 'Aktivera instruktionstext som visas för användare då de skall skicka en artikel.';

$lang['Pre_text_header'] = 'Instruktionstext - rubrik';
$lang['Pre_text_body'] = 'Instruktionstext - text';

$lang['Show'] = 'Visa';
$lang['Hide'] = 'Dölj';

//
// Notifications
//
$lang['Notifications_title'] = 'Påminnelser';

$lang['Notify'] = 'Informera admin via: ';
$lang['Notify_explain'] = 'Bestäm på vilket sätt admin skall bli informerad om nya/redigerade artiklar';
$lang['PM'] = 'PM';

$lang['Notify_group'] = 'och till grupp: ';
$lang['Notify_group_explain'] = 'Informera dessutom medlemmarna i denna grupp.';

$lang['KB_config'] = 'KB konfiguration';
$lang['Art_types'] = 'Artikeltyp';

$lang['Click_return_kb_config'] = 'Klicka %shär%s för att återgå till KB konfiguration';
$lang['KB_config_updated'] = 'KB konfigurationen uppdaterades...';

$lang['Mod_group'] = 'KB moderatorgrupp';
$lang['Mod_group_explain'] = '- med KB adminrättigheter!';

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
$lang['Panel_art_explain'] = 'Här kan du granska artiklar, och godkänna/underkänna dem.';

//approve
$lang['Art_edit'] = 'Ändrade/redigerade artiklar';
$lang['Art_not_approved'] = 'Inte godkända';
$lang['Art_approved'] = 'Godkända';
$lang['Approve'] = 'Godkänn';
$lang['Un_approve'] = 'Underkänn';
$lang['Article_approved'] = 'Artikeln är nu godkänd.';
$lang['Article_unapproved'] = 'Artikeln är nu underkänd.';

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
$lang['Panel_cat_explain'] = 'Här kan du lägga till, ändra och ta bort kategorier.';

$lang['Use_default'] = 'Andvänd grundinställning';

$lang['Create_cat'] = 'Ny kategori:';
$lang['Create'] = 'Skapa';
$lang['Cat_settings'] = 'Kategoriinställningar';
$lang['Create_description'] = 'Här kan du ändra kategorins namn och beskrivning.';
$lang['Cat_created'] = 'Kategorin skapades...';
$lang['Click_return_cat_manager'] = 'Klicka %shär%s för att återgå till ' . $lang['2_Cat_man'];
$lang['Edit_description'] = 'Här kan du ändra dina kategoriinställningar.';
$lang['Edit_cat'] = 'Ändra kategori';
$lang['Cat_edited'] = 'Kategorin ändrades.';
$lang['Parent'] = 'Parent';

$lang['Cat_delete_title'] = 'Ta bort kategori';
$lang['Cat_delete_desc'] = 'Här kan du ta bort en kategori och flytta alla dess artiklar till en annan kategori';
$lang['Cat_deleted'] = 'Kategorin togs bort...';
$lang['Delete_all_articles'] = 'Ta bort artiklar';

//
// Admin Panels - Permissions
//
$lang['KB_Auth_Title'] = 'KB-rättigheter';
$lang['KB_Auth_Explain'] = 'Här bestäms de rättigheter olika grupper har per kategori';
$lang['Select_a_Category'] = 'Välj kategori';
$lang['Look_up_Category'] = 'Välj kategori';
$lang['KB_Auth_successfully'] = 'Rättigheterna uppdaterades';
$lang['Click_return_KB_auth'] = 'Klicka %shär%s för att återgå till KB-rättigeterna';

$lang['Upload'] = 'Skicka';
$lang['Rate'] = 'Rösta';
$lang['Comment'] = 'Kommentera';
$lang['Approval'] = 'Godkänna';
$lang['Approval_edit'] = 'Godkänna ändringar';

$lang['Allow_rating'] = 'Tillåt röstningar';
$lang['Allow_rating_explain'] = 'Tillåt användare att rösta på artiklar.';

$lang['Allow_anonymos_rating'] = 'Tillåt gäströstning';
$lang['Allow_anonymos_rating_explain'] = 'Om röstingar är aktiverade, tillåt icke-registrerade användare att rösta.';

$lang['Category_Permissions'] = 'Kategorirättigheter';
$lang['Category_Title'] = 'Kategorititel';
$lang['Category_Desc'] = 'Kategoribeskrivning';
$lang['View_level'] = 'Läsa';
$lang['Upload_level'] = 'Skicka (skriva)';
$lang['Rate_level'] = 'Rösta';
$lang['View_Comment_level'] = 'Se kommentarer';
$lang['Post_Comment_level'] = 'Skriva kommentarer';
$lang['Edit_Comment_level'] = 'Ändra kommentarer';
$lang['Delete_Comment_level'] = 'Ta bort kommentarer';
$lang['Edit_level'] = 'Ändra';
$lang['Delete_level'] = 'Ta bort';
$lang['Approval_level'] = 'Godkänna';
$lang['Approval_edit_level'] = 'Godkänna ändrade';

//
// Admin Panels - Types
//
$lang['Types_man'] = 'Artikeltyphantering';
$lang['KB_types_description'] = 'Här kan du ändra och hantera artikeltyper.';
$lang['Create_type'] = 'Skapa ny artikeltyp:';
$lang['Type_created'] = 'Artikeltypen skapades...';
$lang['Click_return_type_manager'] = 'Klicka %shär%s för att återgå till artikeltyphanteringen';

$lang['Edit_type'] = 'Ändra artikeltyp';
$lang['Edit_type_description'] = 'Här kan du ändra artikeltypens namn';
$lang['Type_edited'] = 'Artikeltyp togs bort.';

$lang['Type_delete_title'] = 'Ta bort artikeltyp';
$lang['Type_delete_desc'] = 'Ange den artikeltyp artiklar skall få som har den artikeltyp du nu tar bort.';
$lang['Change_type'] = 'Ändra artikeltyp till ';
$lang['Change_and_Delete'] = 'Ändra och ta bort';
$lang['Type_deleted'] = 'Artikeltyp togs bort...';

//
// Admin Panels - Custom Field
//
$lang['Fieldselecttitle'] = 'Välj ett alternativ';
$lang['Afield'] = 'Extra fält: lägg till';
$lang['Efield'] = 'Extra fält: ändra';
$lang['Dfield'] = 'Extra fält: ta bort';
$lang['Mfieldtitle'] = 'Extra fält';
$lang['Afieldtitle'] = 'Lägg till fält';
$lang['Efieldtitle'] = 'Ändra fält';
$lang['Dfieldtitle'] = 'Ta bort fält';
$lang['Fieldexplain'] = 'Här kan du lägga till extra fält. Om du exempelvis vill ha ett fält med artikelns publikationsdatum, skapar du ett sådant.';
$lang['Fieldname'] = 'Fältnamn';
$lang['Fieldnameinfo'] = 'Detta är fältnamnet, t ex \'Filstorlek\'';
$lang['Fielddesc'] = 'Fältbeskrivning';
$lang['Fielddescinfo'] = 'Detta är en fältbeskrivning, t ex \'Storlek på filen\'';
$lang['Fieldadded'] = 'Fältet lades till';
$lang['Fieldedited'] = 'Fältet ändrades...';
$lang['Dfielderror'] = 'Du valde inga fält att ta bort';
$lang['Fieldsdel'] = 'Fältet togs bort...';

$lang['Field_data'] = 'Options';
$lang['Field_data_info'] = 'Enter the options that the user can choose from. Separate each option with a new-line (carriage return).';
$lang['Field_regex'] = 'Regular Expression';
$lang['Field_regex_info'] = 'You may require the input field to match a regular expression %s(PCRE)%s.';
$lang['Field_order'] = 'Display Order';

$lang['Click_return'] = 'Klicka %shär%s för att återgå till föregående sida';

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
$lang['toplist_use_pagination']     = 'Bläddra \'antal rader\' i taget';
$lang['toplist_pagination']         = 'Antal rader';
$lang['toplist_filter_date'] 			= 'Datumfilter';
$lang['toplist_filter_date_explain'] 	= '- Visa inlägg från senaste dagen, veckan, måndaden, året...';
$lang['toplist_cat_id']       		= 'Begränsa till kategori';
$lang['target_block']       		= 'Associerat KB block';

//
// Mini
//
$lang['mini_display_options']    = 'Visningsalternativ';
$lang['mini_pagination']         = 'Antal rader';
$lang['mini_default_cat_id']     = 'Begränsa till kategori';

?>