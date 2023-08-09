<?php


namespace Source\App;


use CoffeeCode\Paginator\Paginator;
use Source\Core\Connect;
use Source\Core\Controller;
use Source\Models\Auth;
use Source\Models\Category;
use Source\Models\faq\Channel;
use Source\Models\faq\Question;
use Source\Models\Newslatter;
use Source\Models\Post;
use Source\Models\Product;
use Source\Models\report\Access;
use Source\Models\report\Online;
use Source\Models\User;
use Source\Supports\Email;
use Source\Supports\Pager;
use Source\Models\Setting;

class WebController extends Controller
{
    /**
     * WebController constructor.
     */
    public function __construct()
    {
        parent::__construct(__DIR__ . "/../../themes/" . CONF_VIEW_THEME . "/");

        $email = new Email();
//        $email->bootstrap(
//            "Teste de email: " . date("Y"),
//            "Disparo de emails for users",
//            "rafaelblumdigital@gmail.com",
//            "Rafael Blum"
//        )->sendNewsQueue();

        (new Access())->report();
        (new Online())->report();
        (new Setting())->start();
    }

    /**
     * pt-br: landing page de desenvolvimento de versão do projeto
     * view: web - development/index
     */
    public  function development(): void
    {
        $head = $this->seo->render(
            CONF_SITE_NAME . " - " . CONF_SITE_TITLE,
            CONF_SITE_DESC,
            url(),
            theme('/assets/image/logo/logo-cd-vertical-mg.png')
        );

        echo $this->view->render("development/index", [
            "head"=> $head
        ]);
    }

    /**
     * pt-br: Home page principal do site web:
     * description more:  listagem de produtos, ultimo produto como novidade, listagem de postagens
     * view: web - home
     */
    public  function home(): void
    {
        $head = $this->seo->render(
            CONF_SITE_NAME . " - " . CONF_SITE_TITLE,
        CONF_SITE_DESC,
            url(),
            theme('/assets/image/logo/logo-cd-vertical-mg.png')
        );

        echo $this->view->render("home", [
            "head"=> $head,
            "setting" => (new Setting())->find()->fetch(),
            "products" => (new Product())->findProducts()->limit(6)->order("id DESC")->fetch(true),
            "recently" => (new Product())->findProducts()->order("id DESC")->fetch(),
            "posts" => (new Post())->findPost()->limit(3)->order("post_at DESC")->fetch(true),
            "isPrice" => (new Setting())->find("id = :id","id=1", "view_price")->fetch()
        ]);
    }

    /**
     * @param array|null $data
     * pt-br: Pagina estatica sobre o site
     * view: web - about
     */
    public function about(?array $data): void
    {

        $head = $this->seo->render(
            "Sobre " . CONF_SITE_NAME,
            "Confira as novidades em nosso blog",
            url("/sobre"),
            theme('/assets/image/logo/logo-cd-vertical-mg.png')
        );

        echo $this->view->render('about', [
            "head" => $head,
            "faq" => (new Question())->find()->order("order_by")->fetch(true),
            "setting" => (new Setting())->find()->fetch()
        ]);
    }

    /**
     * @param array $data
     * pt-br: lista de erros possiveis de interação
     * view: web - error
     */
    public function error(array $data): void
    {
        $error = new \stdClass();

        switch ($data['errcode'])
        {
            case 'problemas':
                $error->code = "OPS";
                $error->title = "Ooopsss! Estamos enfrentando problemas!";
                $error->message = "Sinto muito, mas o serviço parece estar indisponivel! Já estamos resolvendo!";
                $error->linkTitle = "Suporte ao cliente";
                $error->link = "mailto:". MAIL['support'];
                break;

            case 'manutencao':
                $error->code = "OPS";
                $error->title = "Desculpe, mas estamos em manuteção!";
                $error->message = "Sinto muito, mas esta atualizando nossa plataforma para melhor atender você!";
                $error->linkTitle = null;
                $error->link = null;
                break;

            default:
                $error->code = $data['errcode'];
                $error->title = "Ooopsss! Página não encontrada!";
                $error->message = "Sinto muito, mas a página não existe ou está indisponível!";
                $error->linkTitle = "Continue a navegação!";
                $error->link = url_back();
                break;
        }


        $head = $this->seo->render(
            "{$error->code} | {$error->title}",
            $error->message,
            url("ops/{$error->code}"),
            theme('/assets/image/logo/logo-cd-vertical-mg.png'),
            false
        );

        echo $this->view->render("error", [
            "head" => $head,
            "error" => $error
        ]);
    }

    /**
     * @param array|null $data
     * pt-br: Listagem de todas postagens e paginação
     * view: web - /blog-list -> blog-card
     */
    public function blog(?array $data): void
    {
        $head = $this->seo->render(
            "Blog " . CONF_SITE_NAME,
            "Confira as novidades em nosso blog",
            url("/blog"),
            theme('/assets/image/logo/logo-cd-vertical-mg.png')
        );

        $posts = (new Post())->findPost("status = :status", "status=post");
        $page = new Pager(url("/blog/p/"));

        /**
         *  Page
         *  count: Total de posts
         *  limit: 10
         *  offset: 0
         */
        $page->pager($posts->count(), 6, ($data['page'] ?? 1));

        echo $this->view->render('blog-list', [
            "head" => $head,
            "posts" => $posts->order("id DESC")->limit($page->limit())->offset($page->offset())->fetch(true),
            "paginator"=> $page->render()
        ]);
    }

    /**
     * @param array $data
     * pt-br: Listagem de todas postagens por categoria
     * view: web - /blog-list -> blog-card
     */
    public function blogCategory(array $data): void
    {
        /**
         * pt-br: Busca o post selecionado pela uri
         */
        $categoryUri = filter_var($data['category'],  FILTER_SANITIZE_STRIPPED);
        $category = (new Category())->findByUri($categoryUri);

        if(!$category){
            redirect("/blog");
        }

        /**
         * pt-br: Search all posts wiht category post
         */
        $blogCategory = (new Post())->findPost("category = :c", "c={$category->id}");

        $page = (!empty($data['page']) && filter_var($data['page'], FILTER_VALIDATE_INT) >= 1 ? $data['page'] : 1);
        $pager = new Pager(url("/blog/em/{$blogCategory->uri}"));
        $pager->pager($blogCategory->count(), 9, $page);

        $head = $this->seo->render(
            "Postagens - " . $category->title . " " . CONF_SITE_NAME,
            $category->description,
            url("/blog/em/{$category->uri}/{$page}"),
            ($category->cover ? image($category->cover, 1200, 628) : theme('/assets/image/logo/logo-cd-vertical-mg.png'))
        );

        echo $this->view->render("blog-list", [
            "head" => $head,
            "title" => "Posts em {$category->title}",
            "desc" => $category->description,
            "posts" => $blogCategory->limit($pager->limit())->offset($pager->offset())->order("post_at DESC")->fetch(true),
            "paginator"=> $pager->render()
        ]);
    }

    /**
     * @param array $data
     * pt-br: Input de busca de posts por palavra chave
     * view: web - /blog-list -> blog-card
     */
    public function PostSearch(array $data): void
    {
        if(!empty($data['s'])){
            $search = str_search($data['s']);
            echo json_encode(["redirect"=> url("/blog/buscar/{$search}/1")]);
            return;
        }


        $search = str_search($data['search']);
        $page = (filter_var($data['page'], FILTER_VALIDATE_INT) >= 1 ? $data['page'] : 1);

        if($search == "all"){
            redirect("/blog");
        }

        $head = $this->seo->render(
            "Pesquisa " . $search . " - " . CONF_SITE_NAME,
            "Resultado de " . $search,
            url("/blog/buscar/{$search}/{$page}"),
            theme('/assets/image/logo/logo-cd-vertical-mg.png')
        );

        $post = (new Post())->findPost("MATCH(title, subtitle) AGAINST(:s IN NATURAL LANGUAGE MODE)", "s={$search}");

        if(!$post->count()){

            echo $this->view->render("blog-list", [
                "head" => $head,
                "title" => "Pesquisa realizado por ",
                "search" => $search
            ]);
            return;
        }

        $pager = new Pager(url("/blog/buscar/{$search}/"));
        $pager->pager($post->count(), 9, $page);


        echo $this->view->render("blog-list", [
            "head" => $head,
            "title" => "Pesquisa realizado por ",
            "search" => $search,
            "posts" => $post->limit($pager->limit())->offset($pager->offset())->fetch(true),
            "paginator" => $pager->render()
        ]);
    }

    /**
     * @param array $data
     * pt-br: Visualização de postagem
     * view: web - blog-post
     */
    public function post(array $data): void
    {
        $post = (new Post())->findByUri($data['uri']);

        if(empty($post)){
            redirect("/404");
        }

        $user = Auth::user();
        if(!$user || $user->level < 5){
            $post->views += 1;
            $post->save();
        }

        $head = $this->seo->render(
            $post->title . " - " . CONF_SITE_NAME,
            $post->subtitle,
            url("/blog/{$post->uri}"),
            ($post->cover ? image($post->cover, 1200, 628) : theme('/assets/image/logo/logo-cd-vertical-mg.png'))
        );

        echo $this->view->render('blog-post', [
            'head' => $head,
            "post" => $post,
            "related"=> (new Post())->findPost("category = :cat AND id != :i", "cat={$post->category}&i={$post->id}")
                ->order("rand()")
                ->limit(3)
                ->fetch(true)
        ]);
    }

    /**
     * @param array|null $data
     * pt-br: listagem de todos produtos e paginação produtos
     * view: web - /product-list -> product-card
     */
    public function products(?array $data): void
    {
        $head = $this->seo->render(
            "Produtos - " . CONF_SITE_NAME,
            "Confira todos produtos " . CONF_SITE_NAME,
            url("/produtos"),
            theme('/assets/image/logo/logo-cd-vertical-mg.png')
        );

        $products = (new Product())->findProducts();
        $page = new Pager(url("/produtos/itens/"));
        $page->pager($products->count(), 9, ($data['page'] ?? 1));

        echo $this->view->render("product-list", [
            "head" => $head,
            "products" => $products->limit($page->limit())->offset($page->offset())->order("id DESC")->fetch(true),
            "paginator"=> $page->render(),
            "isPrice" => (new Setting())->find("id = :id","id=1", "view_price")->fetch()
        ]);
    }

    public function productsCategory(array $data): void
    {
        /**
         * pt-br: Busca o post selecionado pela uri
         */
        $categoryUri = filter_var($data['category'],  FILTER_SANITIZE_STRIPPED);
        $category = (new Category())->findByUri($categoryUri);

        if(!$category){
            redirect("/produtos");
        }

        /**
         * pt-br: Search all products wiht category product
         */
        $productCategory = (new Product())->findProducts("category = :c", "c={$category->id}");

        $page = (!empty($data['page']) && filter_var($data['page'], FILTER_VALIDATE_INT) >= 1 ? $data['page'] : 1);
        $pager = new Pager(url("/produtos/em/{$productCategory->uri}"));
        $pager->pager($productCategory->count(), 9, $page);

        $head = $this->seo->render(
            "Produtos - " . $category->title . " " . CONF_SITE_NAME,
            $category->description,
            url("/produtos/em/{$category->uri}/{$page}"),
            ($category->cover ? image($category->cover, 1200, 628) : theme('/assets/image/logo/logo-cd-vertical-mg.png'))
        );

        echo $this->view->render("product-list", [
            "head" => $head,
            "title" => "Categoria {$category->title}",
            "desc" => $category->description,
            "products" => $productCategory->limit($pager->limit())->offset($pager->offset())->order("post_at DESC")->fetch(true),
            "paginator"=> $pager->render()
        ]);
    }

    /**
     * @param array $data
     * pt-br: Busca de produtos por palavra chave
     * view: web - /product-list -> product-card
     */
    public function ProductSearch(array $data): void
    {
        if(!empty($data["s"])){
            $search = str_search($data['s']);
            echo json_encode(["redirect" => url("/produtos/buscar/{$search}/1")]);
            return;
        }

        $search = str_search($data['search']);
        $page = (filter_var($data['page'], FILTER_VALIDATE_INT) >= 1 ? $data['page'] : 1);

        if($search == "all"){
            redirect("/produtos");
        }

        $head = $this->seo->render(
            "Pesquisa " . $search . " - " . CONF_SITE_NAME,
            "Resultado de " . $search,
            url("/produtos/buscar/{$search}/{$page}"),
            theme('/assets/image/logo/logo-cd-vertical-mg.png')
        );



        $products = (new Product())->findProducts("MATCH(title, subtitle) AGAINST(:s IN NATURAL LANGUAGE MODE)", "s={$search}");
        if(!$products->count()){
            echo $this->view->render("product-list", [
                "head" => $head,
                "title" => "Pesquisa realizado por ",
                "search" => $search
            ]);
            return;
        }

        $pager = new Pager(url("/produtos/buscar/{$search}/"));
        $pager->pager($products->count(), 9, $page);

        echo $this->view->render("product-list", [
            "head" => $head,
            "title" => "Pesquisa realizado por ",
            "search" => $search,
            "products" => $products->limit($pager->limit())->offset($pager->offset())->fetch(true),
            "paginator" => $pager->render()
        ]);
    }

    /**
     * @param array|null $data
     * pt-br: Mostar produto selecionado
     * view: web - /product-item
     */
    public function product(?array $data): void
    {
        $prod = (new Product())->findByUri($data['uri']);


        if(empty($prod)){
            redirect("/404");
        }

        $prod->views += 1;
        $prod->save();

        $head = $this->seo->render(
            $prod->title . " - " . CONF_SITE_NAME,
            $prod->title . " - " . CONF_SITE_NAME,
            url("/produtos{$prod->uri}"),
            ($prod->cover ? image($prod->cover, 1200, 628) : theme('/assets/image/logo/logo-cd-vertical-mg.png'))
        );

        echo $this->view->render('product-detail', [
            'head' => $head,
            "prod" => $prod,
            "related"=> (new Product())->findProducts("category = :cat AND id != :i", "cat={$prod->category}&i={$prod->id}")
                ->order("rand()")
                ->limit(3)
                ->fetch(true),
            "isPrice" => (new Setting())->find("id = :id","id=1", "view_price")->fetch()
        ]);
    }
}