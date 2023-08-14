<?php
/**
 * pt-br:
 * autoload:     Recursos e componentes
 * ob_start:     Controle cache
 */

ob_start();

require __DIR__ . "/vendor/autoload.php";

use Source\Core\Session;
use CoffeeCode\Router\Router;


$route = new Router(url(), ":");


/**
 * WEB ROUTERS **************************************************************** WEBSITE
 */
$route->namespace("Source\App");
$route->get("/", "WebController:development");
$route->get("/home", "WebController:home");
$route->get('/sobre', "WebController:about");
$route->get('/construcao', "WebController:construct");

/** BLOG ROUTERS*/
$route->group("/blog");
$route->get("/", "WebController:blog");
$route->get("/p/{page}", "WebController:blog");
$route->get("/{uri}", "WebController:post");

$route->get("/em/{category}", "WebController:blogCategory");
$route->get("/em/{category}/{page}", "WebController:blogCategory");

/** SEARCH FORM AJAX POSTS */
$route->post("/{buscar}", "WebController:PostSearch");
$route->get("/buscar/{search}/{page}", "WebController:PostSearch");


/** PRODUCT ROUTERS*/
$route->group("/produtos");
$route->get("/", "WebController:products");
$route->get("/itens/{page}", "WebController:products");
$route->get("/{uri}", "WebController:product");

$route->get("/em/{category}", "WebController:productsCategory");
$route->get("/em/{category}/{page}", "WebController:productsCategory");

/** SEARCH FORM AJAX PRODUTOS */
$route->post("/{buscar2}", "WebController:ProductSearch");
$route->get("/buscar/{search}/{page}", "WebController:ProductSearch");

/** AUTH ROUTERS */
$route->group(null);

/** LOGIN */
$route->get("/entrar", "AuthController:login", "auth.login");
$route->post("/entrar", "AuthController:login");



/** RECUPERAÇÃO */
$route->get("/recuperar", "AuthController:forget");
$route->post("/recuperar", "AuthController:forget");
$route->get("/recuperar/{code}", "AuthController:reset");
$route->post("/recuperar/reset", "AuthController:reset");

/** NEWSLATTER SUBSCRIPTION*/
$route->get("/inscricao", "AuthController:newslatter");
$route->post("/subscrib", "AuthController:newslatter");

/** CONFIRMAÇÕES */
$route->get("/confirma", "AuthController:confirm");                 // ok
$route->get("/confirma/{email}", "AuthController:confirm");
$route->get("/sucesso/{email}", "AuthController:success");
$route->get("/sucesso/{email}/{status}", "AuthController:success");

/**
 * ADMIN ROUTES
 */
$route->namespace("Source\App\Admin");
$route->group("/admin");

//login
$route->get("/", "LoginController:root");
$route->get("/login", "LoginController:login");
$route->post("/login", "LoginController:login");

//dash
$route->get("/dash", "DashController:dash");
$route->get("/dash/home", "DashController:home");
$route->post("/dash/home", "DashController:home");
$route->get("/logoff", "DashController:logoff");

//SETTINGS
$route->get("/dash/configuracoes", "SettingController:home");
$route->post("/dash/settings/{set_id}", "SettingController:settings");

//dash -> subscritions
$route->get("/dash/subscriptions", "DashController:subscriptions");                 // return all subscribs
$route->post("/dash/subscriptions", "DashController:subscriptions");                // send method post form search
$route->get("/dash/subscriptions/{search}/{page}", "DashController:subscriptions"); // paginator or search method : $search or $all
//dash -> method subscrib deactivete mail
$route->post("/dash/subscriptions/deactivate", "DashController:deactivate");


//blog -> read
$route->get("/blog/home", "BlogController:home");
$route->post("/blog/home", "BlogController:home");
$route->get("/blog/home/{search}/{page}", "BlogController:home");
//blog -> create
$route->get("/blog/post", "BlogController:post");
$route->post("/blog/post", "BlogController:post");
//blog -> edit or delete
$route->get("/blog/post/{post_id}", "BlogController:post");
$route->post("/blog/post/{post_id}", "BlogController:post");

//categories blog -> read
$route->get("/blog/categories", "BlogController:categories");
$route->get("/blog/categories/{page}", "BlogController:categories");
//categories blog create
$route->get("/blog/category", "BlogController:category");
$route->post("/blog/category", "BlogController:category");
//categories blog -> edit or delete
$route->get("/blog/category/{category_id}", "BlogController:category");
$route->post("/blog/category/{category_id}", "BlogController:category");

//products -> read
$route->get("/products/home", "ProductController:home");
$route->post("/products/home", "ProductController:home");
$route->get("/products/home/{search}/{page}", "ProductController:home");

//products -> create
$route->get("/products/product", "ProductController:product");
$route->post("/products/product", "ProductController:product");

//products -> edit or delete
$route->get("/products/product/{product_id}", "ProductController:product");
$route->post("/products/product/{product_id}", "ProductController:product");

//categories products -> read
$route->get("/products/categories", "ProductController:categories");
$route->get("/products/categories/{page}", "ProductController:categories");
//categories products -> create
$route->get("/products/category", "ProductController:category");
$route->post("/products/category", "ProductController:category");
//categories products -> edit or delete
$route->get("/products/category/{category_id}", "ProductController:category");
$route->post("/products/category/{category_id}", "ProductController:category");

//faqs
$route->get("/faq/home", "FaqController:home");
$route->get("/faq/home/{page}", "FaqController:home");
$route->get("/faq/channel", "FaqController:channel");
$route->post("/faq/channel", "FaqController:channel");
$route->get("/faq/channel/{channel_id}", "FaqController:channel");
$route->post("/faq/channel/{channel_id}", "FaqController:channel");
$route->get("/faq/question/{channel_id}", "FaqController:question");
$route->post("/faq/question/{channel_id}", "FaqController:question");
$route->get("/faq/question/{channel_id}/{question_id}", "FaqController:question");
$route->post("/faq/question/{channel_id}/{question_id}", "FaqController:question");

//users
$route->get("/users/home", "UsersController:home");
$route->post("/users/home", "UsersController:home");
$route->get("/users/home/{search}/{page}", "UsersController:home");
$route->get("/users/user", "UsersController:user");
$route->post("/users/user", "UsersController:user");
$route->get("/users/user/{user_id}", "UsersController:user");
$route->post("/users/user/{user_id}", "UsersController:user");

//END ADMIN
$route->namespace("Source\App");



/**
 * ERRORS ROUTER
 */
$route->namespace("Source\App")->group("/ops");
$route->get('/{errcode}', "WebController:error");


/**
 * ROUTE execute
 */
$route->dispatch();

/**
 * ROUTE REDIRECT
 */
if($route->error())
{
    $route->redirect("ops/{$route->error()}");
}

ob_end_flush();