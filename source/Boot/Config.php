<?php

/**
 * DATABASE
 * PROJECT URLs
 */
define("CONF_IP_HOST", "192.168.1.15");
if ($_SERVER['HTTP_HOST'] == "localhost" || $_SERVER['HTTP_HOST'] == CONF_IP_HOST) {
    define("CONF_DB_HOST", "mysql.ferafox.com");
    define("CONF_DB_USER", "ferafox62");
    define("CONF_DB_PASS", "194280aa");
    define("CONF_DB_NAME", "ferafox62");
    define("CONF_URL_TEST", "http://localhost");
} else {
    define("CONF_DB_HOST", "cobparis.mysql.dbaas.com.br");
    define("CONF_DB_USER", "cobparis");
    define("CONF_DB_PASS", "aA#102030");
    define("CONF_DB_NAME", "cobparis");
    define("CONF_URL_BASE", "https://www.professionalparis.com.br/cobparis");
}
/**
 * SITE
 */
define("CONF_SITE_NAME", "COBPARIS");
define("CONF_SITE_TITLE", "O melhor sistema de gestão!");
define("CONF_SITE_DESC", "O COPARIS é um gerenciador poderoso e gratuito.");
define("CONF_SITE_LANG", "pt_BR");
define("CONF_SITE_DOMAIN", "");
define("CONF_SITE_ADDR_STREET", "");
define("CONF_SITE_ADDR_NUMBER", "");
define("CONF_SITE_ADDR_COMPLEMENT", "");
define("CONF_SITE_ADDR_CITY", "");
define("CONF_SITE_ADDR_STATE", "");
define("CONF_SITE_ADDR_ZIPCODE", "");
/**
 * SOCIAL
 */
define("CONF_SOCIAL_TWITTER_CREATOR", "@creator");
define("CONF_SOCIAL_TWITTER_PUBLISHER", "@creator");
define("CONF_SOCIAL_FACEBOOK_APP", "5555555555");
define("CONF_SOCIAL_FACEBOOK_PAGE", "pagename");
define("CONF_SOCIAL_FACEBOOK_AUTHOR", "author");
define("CONF_SOCIAL_GOOGLE_PAGE", "5555555555");
define("CONF_SOCIAL_GOOGLE_AUTHOR", "5555555555");
define("CONF_SOCIAL_INSTAGRAM_PAGE", "insta");
define("CONF_SOCIAL_YOUTUBE_PAGE", "youtube");
/**
 * DATES
 */
define("CONF_DATE_BR", "d/m/Y H:i:s");
define("CONF_DATE_APP", "Y-m-d H:i:s");
/**
 * PASSWORD
 */
define("CONF_PASSWD_MIN_LEN", 8);
define("CONF_PASSWD_MAX_LEN", 40);
define("CONF_PASSWD_ALGO", PASSWORD_DEFAULT);
define("CONF_PASSWD_OPTION", ["cost" => 10]);
/**
 * VIEW
 */
define("CONF_VIEW_PATH", __DIR__ . "/../../shared/views");
define("CONF_VIEW_EXT", "php");
define("CONF_VIEW_THEME", "web");
define("CONF_VIEW_APP", "");
define("CONF_VIEW_ADMIN", "admin");
/**
 * UPLOAD
 */
define("CONF_UPLOAD_DIR", "storage");
define("CONF_UPLOAD_IMAGE_DIR", "images");
define("CONF_UPLOAD_FILE_DIR", "files");
define("CONF_UPLOAD_MEDIA_DIR", "medias");
/**
 * IMAGES
 */
define("CONF_IMAGE_CACHE", CONF_UPLOAD_DIR . "/" . CONF_UPLOAD_IMAGE_DIR . "/cache");
define("CONF_IMAGE_SIZE", 2000);
define("CONF_IMAGE_QUALITY", ["jpg" => 75, "png" => 5]);
/**
 * MAIL
 */
define("CONF_MAIL_HOST", "smtp.gmail.com");
define("CONF_MAIL_PORT", "587");
define("CONF_MAIL_USER", "sendemaildeveloper@gmail.com");
define("CONF_MAIL_PASS", "41361529j");
define("CONF_MAIL_SENDER", ["name" => "COPARIS", "address" => "sendemaildeveloper@gmail.com"]);
define("CONF_MAIL_SUPPORT", "sendemaildeveloper@gmail.com");
define("CONF_MAIL_OPTION_LANG", "br");
define("CONF_MAIL_OPTION_HTML", true);
define("CONF_MAIL_OPTION_AUTH", true);
define("CONF_MAIL_OPTION_SECURE", "tls");
define("CONF_MAIL_OPTION_CHARSET", "utf-8");
