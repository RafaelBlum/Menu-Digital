<?php

/**
 * CONFIG PROJECT
 */


date_default_timezone_set("America/sao_paulo");
define("CONF_SITE_LANG", "pt_BR");

/**
 * CONFIG DESCRIPTION
 */
define("CONF_SITE_NAME", "Cardápio Digital");
define("CONF_SITE_MAIL", "meucardapio@cardapio-digital.online");
define("CONF_SITE_TITLE", "Criando sabores inesquecíveis para tornar seus dias mais gostosos!");
define("CONF_SITE_DESC", "Somos uma das mais tradicionais confeitarias de Porto Alegre, criando sabores inesquecíveis para tornar seus dias mais gostosos! Doces, salgados, bolos e tortas!");
define("CONF_DEV_NAME", "Rafael Blum");
define("CONF_DEV_MAIL", "rafaelblum_digital@hotmail.com");

/**
 * CONFIG ADDRESS
 */
define("CONF_ADDR_STREET", "Av. Protásio Alves");
define("CONF_ADDR_NUMBER", "2022");
define("CONF_ADDR_COMPLEMENT", "Casa");
define("CONF_ADDR_CITY", "Porto Alegre");
define("CONF_ADDR_STATE", "RS");
define("CONF_ADDR_ZIPCODE", "90660-000");
define("CONF_ADDR_FONE", "(51)55555-5555");
define("CONF_ADDR_EMAIL", "meucardapio@cardapio-digital.online");

/**
 * URLs
 */
define("CONF_URL_BASE", "https://www.cardapio-digital.online");
define("CONF_URL_ADMIN", CONF_URL_BASE . "/admin");
define("CONF_URL_TEST", "http://localhost/cardapio");
define("CONF_URL_ERROR", CONF_URL_BASE . "/errors");
define("CONF_SITE_DOMAIN", "cardapio-digital.online");



/**
 * WHATSAPP API
 */
define("CONF_TEXT_WHATSAPP", "Olá, gostaria de receber o menu para pedidos.");


/**
 * CONFIG DATES
 */
define("CONF_DATE_BR", "d/m/Y");
define("CONF_DATE_APP", "Y-m-d");

/**
 * CONFIG PASSWORD
 */
define("CONF_PASSWORD_MIN_LEN", 8);
define("CONF_PASSWORD_MAX_LEN", 40);
define("CONF_PASSWORD_ALGO", PASSWORD_DEFAULT);
define("CONF_PASSWORD_OPTION", ["cost" => 10]);

/**
 * CONFIG DB_MYSQL
 */
define("DATA_LAYER_CONFIG", [
    "driver" => "mysql",
    "host" => "localhost",
    "port" => "3306",
    "dbname" => "php_cardapio-digital",
    "username" => "root",
    "passwd" => "",
    "options" => [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_CASE => PDO::CASE_NATURAL
    ],
]);

/**
 * DATABASE
 */
define("CONF_DATABASE", "mysql");
define("CONF_HOST", "localhost");
define("CONF_USER", "root");
define("CONF_DBNAME", "cardapior-digital");
define("CONF_PASSWORD", "");

/**
 * CONFIG E-MAIL PHP_MAILER
 */
define("MAIL", [
    "host" => "smtp.hostinger.com",
    "port" => 465,
    "user" => CONF_SITE_MAIL,
    "passwd" => "Cardapio-2023",
    "from_name" => CONF_SITE_NAME,
    "from_email" => "meucardapio@cardapio-digital.online",
    "sender" => ["name" => CONF_SITE_NAME, "address" => CONF_SITE_MAIL],
    "lang" => "br",
    "html" => true,
    "auth" => true,
    "secure" => "ssl",
    "charset" => "utf-8",
    "support" => CONF_SITE_MAIL
]);

/**
 * CONFIG VIEWS
 */
define("CONF_VIEW_PATH_MAIL", __DIR__ . "/../../shared/views/mail/");
define("CONF_VIEW_THEME", "cardapioweb");
define("CONF_VIEW_ADMIN", "cardapioadm");
define("CONF_VIEW_EXT", "php");

/**
 * CONFIG UPLOADRES [images, files, medias]
 */
define("CONF_UPLOAD_DIR", "storage");
define("CONF_UPLOAD_IMAGE_DIR", "images");
define("CONF_UPLOAD_FILE_DIR", "files");
define("CONF_UPLOAD_MEDIA_DIR", "medias");

/**
 * CONFIG IMAGE
 */
define("CONF_IMAGE_CACHE", CONF_UPLOAD_DIR . "/" . CONF_UPLOAD_IMAGE_DIR . "/cache");
define("CONF_IMAGE_SIZE", 2000);
define("CONF_IMAGE_QUALITY", ["jpg"=> 75, "png"=> 5]);
define("CONF_IMAGE_DEFAULT", "https://fakeimg.pl/350x200/ff0000,128/000,255/?text=Image&font=lobster");


/**
 * CONFIG SOCIAL OPTIMIZER
 */
define("CONF_SOCIAL_TWITTER_CREATOR", "@universocode");
define("CONF_SOCIAL_TWITTER_PUBLISHER", "@universocode");
define("CONF_SOCIAL_FACEBOOK_APP", "778733016074484");
define("CONF_SOCIAL_FACEBOOK_AUTHOR", "rafael.blum.3");
define("CONF_SOCIAL_FACEBOOK_PAGE", "rafael.blum.3");
define("CONF_SOCIAL_GOOGLE_PAGE", "...");
define("CONF_SOCIAL_GOOGLE_AUTHOR", "...");
define("CONF_SOCIAL_INSTAGRAM_PAGE", "rafablum_");
define("CONF_SOCIAL_YOUTUBE_PAGE", "universocode");
define("CONF_SOCIAL_GITHUB_PAGE", "universocode");

define("CONF_URL_GITHUB", "https://github.com/RafaelBlum");
define("CONF_URL_TWITTER", "https://twitter.com/universocode");
define("CONF_URL_INSTAGRAM", "https://www.instagram.com/universo_code");
define("CONF_URL_YOUTUBE", "https://www.youtube.com/channel/UCMvtn8HZ12Ud-sdkY5KzTog");
define("CONF_URL_INSTAGRAM_PESSOAL", "https://www.instagram.com/rafablum_");
define("CONF_URL_LINKEDIN", "https://www.linkedin.com/in/rafael-blum-237133114");









