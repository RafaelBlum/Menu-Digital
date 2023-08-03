<?php


namespace Source\App\Admin;


use Source\Models\Category;
use Source\Models\Product;
use Source\Supports\Pager;
use Source\Supports\Thumb;
use Source\Supports\Upload;

class ProductController extends AdminController
{
    /**
     * ProductController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param array|null $data
     * pt-br: Retorna a listagem de todos produtos (DESC), paginator e search
     * view: adm - widgets/product/home
     */
    public function home(?array $data): void
    {

        if(!empty($data["s"])){
            $s = str_search($data["s"]);
            echo json_encode(["redirect" => url("/admin/products/home/{$s}/1")]);
            return;
        }

        $search = null;
        $products = (new Product())->find();


        if(!empty($data["search"]) && str_search($data["search"]) != "all"){
            $search = str_search($data["search"]);

            $products = (new Product())->find("MATCH(title, subtitle) AGAINST(:s)", "s={$search}");

            if(!$products->count()){
                $this->message->warning("Sua pesquisa não retornou resultado!")->flash();
                redirect("/admin/products/home");
            }
        }

        $all = ($search ?? "all");
        $pager = new Pager(url("/admin/products/home/{$all}/"));
        $pager->pager($products->count(), 12, (!empty($data["page"]) ? $data["page"] : 1));

        $head = $this->seo->render(
            CONF_SITE_NAME . " | Produtos",
            CONF_SITE_DESC,
            url("/admin"),
            theme("/assets/images/image.jpg", CONF_VIEW_ADMIN),
            false
        );

        echo $this->view->render("widgets/product/home", [
            "app" => "products/home",
            "head" => $head,
            "products" => $products->limit($pager->limit())->offset($pager->offset())->order("status = 'post' DESC")->fetch(true),
            "paginator" => $pager->render(),
            "search" => $search
        ]);
    }

    /**
     * @param array|null $data
     * @throws \Exception
     * pt-br:
     * view: adm -
     */
    public function product(?array $data): void
    {

        /**
         * MCE Upload
        */
        if(!empty($data["upload"]) && !empty($_FILES["image"])){
            $files = $_FILES["image"];
            $upload = new Upload();
            $image = $upload->image($files, "product-".time());

            if(!$image){
                $json["message"] = $upload->message()->render();
                echo json_encode($json);
                return;
            }

            $json["mce_image"] = "<img style='width: 100%;' src='" . url("/storage/{$image}") . "' alt='{title}' title='{title}'>";
            echo json_encode($json);
            return;
        }

        /**
         * create
        */
        if(!empty($data["action"]) && !empty($data["action"] == "create")){
            $content = $data["content"];
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

            $product = new Product();
            $product->category = $data["category"];
            $product->title = $data["title"];
            $product->uri = str_slug($product->title);
            $product->subtitle = $data["subtitle"];
            $product->content = str_replace(["{title}"], [$product->title], $content);
            $product->price = str_replace([".", ","], ["", "."], $data["price"]);
            $product->status = $data["status"];
            $product->post_at = date_fmt_back($data["post_at"]);

            if(!empty($_FILES["cover"])){
                $files = $_FILES["cover"];

                $upload = new Upload();
                $image = $upload->image($files, $product->title, 600);

                if(!$image){
                    $json["message"] = $upload->message()->render();
                    echo json_encode($json);
                    return;
                }
                $product->cover = $image;
            }


            if(!$product->save()){
                $json["message"] = $product->message()->render();
                echo json_encode($json);
                return;
            }

            $this->message->success("Produto criado com sucesso...")->flash();
            $json["redirect"] = url("/admin/blog/post/{$product->id}");

            echo json_encode($json);
            return;
        }


        /**
         * uptade
        */
        if(!empty($data["action"]) && !empty($data["action"] == "update")){

            $content = $data["content"];
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

            $prodEdit = (new Product())->findById($data["product_id"]);

            if(!$prodEdit){
                $this->message->error("Você tentou editar um produto que não existe ou foi deletada!")->flash();
                echo json_encode(["redirect" => url("/admin/products/home")]);
                return;
            }


            $prodEdit->category = $data["category"];
            $prodEdit->title = $data["title"];
            $prodEdit->uri = str_slug($prodEdit->title);
            $prodEdit->subtitle = $data["subtitle"];
            $prodEdit->content = str_replace(["{title}"], [$prodEdit->title], $content);
            $prodEdit->price = str_replace([".", ","], ["", "."], $data["price"]);
            $prodEdit->status = $data["status"];
            $prodEdit->post_at = date_fmt_back($data["post_at"]);

            if(!empty($_FILES["cover"])){


                if($prodEdit->cover && file_exists(__DIR__ . "/../../../". CONF_UPLOAD_DIR ."/{$prodEdit->cover}")){
                    unlink(__DIR__ . "/../../../". CONF_UPLOAD_DIR ."/{$prodEdit->cover}");
                    (new Thumb())->flush($prodEdit->cover);
                }

                $files = $_FILES["cover"];

                $upload = new Upload();
                $image = $upload->image($files, $prodEdit->title, 600);

                if(!$image){
                    $json["message"] = $upload->message()->render();
                    echo json_encode($json);
                    return;
                }
                $prodEdit->cover = $image;
            }


            if(!$prodEdit->save()){
                $json["message"] = $prodEdit->message()->render();
                echo json_encode($json);
                return;
            }

            $this->message->success("Produto atualizado com sucesso...")->flash();

            echo json_encode(["reload" => true]);
            return;
        }

        /**
         * delete
        */
        if(!empty($data["action"]) && !empty($data["action"] == "delete")){
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

            $productDelete = (new Product())->findById($data["product_id"]);

            if(!$productDelete){
                $this->message->error("Você tentou deletar um produto que não existe ou foi removida!")->flash();
                echo json_encode(["redirect" => url("/admin/products/home")]);
                return;
            }

            if($productDelete->cover && file_exists(__DIR__ . "/../../../". CONF_UPLOAD_DIR ."/{$productDelete->cover}")){
                unlink(__DIR__ . "/../../../". CONF_UPLOAD_DIR ."/{$productDelete->cover}");
                (new Thumb())->flush($productDelete->cover);
            }

            $productDelete->destroy();
            $this->message->success("Produto removida com sucesso!")->flash();

            echo json_encode(["reload"=>true]);
            return;
        }

        $prodEdit = null;
        if(!empty($data["product_id"])){
            $prodId = filter_var($data["product_id"], FILTER_VALIDATE_INT);
            $prodEdit = (new Product())->findById($prodId);
        }

        $head = $this->seo->render(
            CONF_SITE_NAME . " | " . ($prodEdit->title ?? "Novo Produto"),
            CONF_SITE_DESC,
            url("/admin"),
            theme("/assets/images/image.jpg", CONF_VIEW_ADMIN),
            false
        );

        echo $this->view->render("widgets/product/product", [
            "app" => "products/product",
            "head" => $head,
            "product" => $prodEdit,
            "categories" => (new Category())->find("type = :type", "type=product")->order("title")->fetch(true)
        ]);
    }

    /**
     * @param array|null $data
     * pt-br:
     * view: adm -
     */
    public function categories(?array $data): void
    {
        $categories = (new Category())->find("type = :type", "type=product");
        $pager = new Pager(url("/admin/products/categories/"));
        $pager->pager($categories->count(), 6, (!empty($data["page"]) ? $data["page"] : 1));

        $head = $this->seo->render(
            CONF_SITE_NAME . " | Categorias produtos",
            CONF_SITE_DESC,
            url("/admin"),
            theme("/assets/images/image.jpg", CONF_VIEW_ADMIN),
            false
        );

        echo $this->view->render("widgets/product/categories", [
            "app" => "products/categories",
            "head" => $head,
            "categories" => (new Category())->find("type = 'product'")->order("title")->limit($pager->limit())->offset($pager->offset())->fetch(true),
            "paginator" => $pager->render()
        ]);
    }

    /**
     * @param array|null $data
     * @throws \Exception
     * pt-br:
     * view: adm -
     */
    public function category(?array $data): void
    {
        /**
         * CREATE
         */
        if(!empty($data["action"]) && $data["action"] == 'create'){
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

            $categoryCreate = new Category();

            $categoryCreate->title = $data['title'];
            $categoryCreate->uri = str_slug($categoryCreate->title);
            $categoryCreate->description = $data['description'];
            $categoryCreate->type = "product";



            if(!empty($_FILES["cover"])){
                $files = $_FILES['cover'];

                $upload = new Upload();
                $image = $upload->image($files, $categoryCreate->title);

                if(!$image){
                    $json['message'] = $upload->message()->render();
                    echo json_encode($json);
                    return;
                }

                $categoryCreate->cover = $image;
            }

            if(!$categoryCreate->save()) {
                $json['message'] = $categoryCreate->message()->render();
                echo json_encode($json);
                return;
            }

            $this->message->success("Categoria criada com sucesso!")->flash();
            $json["redirect"] = url("/admin/blog/category/{$categoryCreate->id}");
            echo json_encode($json);
            return;
        }

        /**
         * UPDATE
         */
        if(!empty($data["action"]) && $data["action"] == 'update'){
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $categoryEdit = (new Category())->findById($data["category_id"]);

            if(!$categoryEdit){
                $this->message->error("Você tentou editar uma categoria que não existe ou foi deletada!")->flash();
                echo json_encode(["redirect"=> url("/admin/products/categories")]);
                return;
            }

            $categoryEdit->title = $data['title'];
            $categoryEdit->uri = str_slug($categoryEdit->title);
            $categoryEdit->description = $data['description'];

            if(!empty($_FILES["cover"])){
                if($categoryEdit->cover && file_exists(__DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/{$categoryEdit->cover}")){
                    unlink(__DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/{$categoryEdit->cover}");
                    (new Thumb())->flush($categoryEdit->cover);
                }

                $files = $_FILES["cover"];
                $upload = new Upload();
                $image = $upload->image($files, $categoryEdit->title);

                if(!$image){
                    $json["message"] = $upload->message()->render();
                    echo json_encode($json);
                    return;
                }

                $categoryEdit->cover = $image;
            }

            if(!$categoryEdit->save()){
                $json["message"] = $categoryEdit->message()->render();
                echo json_encode($json);
                return;
            }

            $this->message->success("Categoria editada com sucesso!!")->flash();
            echo json_encode(["reload" => true]);
            return;
        }

        /**
         * DELETE
         */
        if(!empty($data["action"]) && $data["action"] == 'delete'){
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $categoryDelete = (new Category())->findById($data["category_id"]);

            if(!$categoryDelete){
                $json["message"] = $this->message->error("Você tentou deletar uma categoria que não existe!")->render();
                echo json_encode($json);
                return;
            }

            if($categoryDelete->typeCategory('product')->count()){
                $json["message"] = $this->message->warning("Não é possível deletar esta categoria, pois existem outros posts com esta categoria.")->render();
                echo json_encode($json);
                return;
            }

            $categoryDelete = (new Category())->findById($data["category_id"]);

            if($categoryDelete->cover && file_exists(__DIR__. "/../../../". CONF_UPLOAD_DIR."/{$categoryDelete->cover}")){
                unlink(__DIR__. "/../../../". CONF_UPLOAD_DIR."/{$categoryDelete->cover}");
                (new Thumb())->flush($categoryDelete->cover);
            }

            $categoryDelete->destroy();

            $this->message->success("Categoria deletada com sucesso!")->flash();
            echo json_encode(["reload"=>true]);
            return;
        }

        $categoryEdit = null;

        if(!empty($data["category_id"])){
            $categoryId = filter_var($data["category_id"], FILTER_VALIDATE_INT);
            $categoryEdit = (new Category())->findById($categoryId);
        }

        $head = $this->seo->render(
            CONF_SITE_NAME . " | categoria",
            CONF_SITE_DESC,
            url("/admin"),
            theme("/assets/images/image.jpg", CONF_VIEW_ADMIN),
            false
        );

        echo $this->view->render("widgets/product/category", [
            "app" => "products/categories",
            "head" => $head,
            "post" => $categoryEdit,
            "category" => $categoryEdit
        ]);

    }

}