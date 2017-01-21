<?php
/*
 * French language for ts-website
 * @copy FleuryK <contact@fleuryk.tk>
 */

$lang = array();

/************* General *************/

$lang["general"]["langcode"] = "fr_FR";
$lang["general"]["scripterror"] = "Une erreur s&#039;est produite ! {0} : {1}";

/************* Navbar *************/

$lang["navbar"]["navigation"] = "Navigation";

$lang["navbar"]["viewer"] = "Affichage du Serveur";
$lang["navbar"]["bans"] = "Liste des Bans";
$lang["navbar"]["rules"] = "R&egrave;gles du Serveur";

$lang["navbar"]["connect"] = "Se connecter au Serveur";
$lang["navbar"]["connecttooltip"] = "Cliquez pour vous connecter &agrave; {0}";


/************* Footer *************/

$lang["footer"]["css"] = "CSS par";
$lang["footer"]["background"] = "arri&egrave;re-plan";


/************* Server status *************/

$lang["serverstatus"]["title"] = "Statut du Serveur";
$lang["serverstatus"]["address"] = "Adresse";
$lang["serverstatus"]["loading"] = "Chargement ...";

$lang["serverstatus"]["online"] = "En ligne";
$lang["serverstatus"]["offline"] = "Hors ligne";
$lang["serverstatus"]["uptime"] = "Tps de fonction.";
$lang["serverstatus"]["version"] = "Version";
$lang["serverstatus"]["avgping"] = "Moyenne du Ping";
$lang["serverstatus"]["avgpl"] = "Perte moyenne de paquets";


/************* Admin list *************/

$lang["adminlist"]["title"] = "Liste d&#039;Admin";
$lang["adminlist"]["emptygroup"] = "Ce groupe est vide";
$lang["adminlist"]["status"]["online"] = "En ligne";
$lang["adminlist"]["status"]["away"] = "Away";
$lang["adminlist"]["status"]["offline"] = "Hors ligne";
$lang["adminlist"]["lastupdate"] = "Derni&egrave;re mise &agrave; jour : {0}";


/************* Server viewer promo box (under adminlist and contact) *************/

$lang["svpb"]["title"] = "Affichage du Serveur";
$lang["svpb"]["takealook"] = "Regarder &raquo;";


/************************************/
/************* SUBPAGES *************/
/************************************/


/************* News - index.php *************/

$lang["index"]["title"] = "News";
$lang["index"]["showmore"] = "Lire la suite";
$lang["index"]["showless"] = "Moins montrer";

$lang["index"]["errortitle"] = "Erreur : le dossier des news n&#039;a pas &eacute;t&eacute; trouv&eacute; !";
$lang["index"]["errorsubtitle"] = "Assurez-vous que l&#039;emplacement du répertoire défini dans <b><code>config/config.php</code></b> est valide.";


/************* Server viewer - viewer.php *************/

$lang["viewer"]["title"] = "Affichage du Serveur";
$lang["viewer"]["lastupdate"] = "Derni&egrave;re mise &agrave; jour : {0}";


/************* Ban List - bans.php *************/

$lang["banlist"]["title"] = "Liste des Bans";
$lang["banlist"]["datatablesurl"] = "//cdn.datatables.net/plug-ins/1.10.12/i18n/French.json";
$lang["banlist"]["emptylist"] = "LA LISTE DES BANS EST VIDE";
$lang["banlist"]["lastupdate"] = "Derni&egrave;re mise &agrave; jour : {0}";

$lang["banlist"]["table"]["emptyreason"] = "(pas de raison)";
$lang["banlist"]["table"]["permaban"] = "Jamais";

$lang["banlist"]["table"]["nickname"] = "Surnom";
$lang["banlist"]["table"]["reason"] = "Raison";
$lang["banlist"]["table"]["bannedby"] = "Banni par";
$lang["banlist"]["table"]["bandate"] = "Date du Ban";
$lang["banlist"]["table"]["expires"] = "Expire";

/************* Rules - rules.php *************/

$lang["rules"]["title"] = "R&egrave;gles du Serveur";
$lang["rules"]["filenotfound"] = "Erreur : Le fichier <code>config/rules.md</code> n&#039;a pas &eacute;t&eacute; trouv&eacute; !";
$lang["rules"]["readerror"] = "Erreur : Impossible d&#039;acc&eacute;der au fichier <code>config/rules.md</code> !";
