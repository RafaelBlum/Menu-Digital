<?php


namespace Source\App\Admin;


use Source\Models\Category;
use Source\Models\Post;
use Source\Models\User;
use Source\Supports\Pager;
use Source\Supports\Thumb;
use Source\Supports\Upload;

class BlogController extends AdminController
{
    /**
     * BlogController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param array|null $data
     * pt-br:
     * view: adm -
     */
    public function home(?array $data): void
    {

        if(!empty($data["s"])){
            $s = str_search($data["s"]);
            echo json_encode(["redirect" => url("/admin/blog/home/{$s}/1")]);
            return;
        }

        $search = null;
        $posts = (new Post())->find();


        if(!empty($data["search"]) && str_search($data["search"]) != "all"){
            $search = str_search($data["search"]);

            $posts = (new Post())->find("MATCH(title, subtitle) AGAINST(:s)", "s={$search}");

            if(!$posts->count()){
                $this->message->warning("Sua pesquisa não retornou resultado!")->flash();
                redirect("/admin/blog/home");
            }
        }

        $all = ($search ?? "all");
        $pager = new Pager(url("/admin/blog/home/{$all}/"));
        $pager->pager($posts->count(), 12, (!empty($data["page"]) ? $data["page"] : 1));

        $head = $this->seo->render(
            CONF_SITE_NAME . " | Blog",
            CONF_SITE_DESC,
            url("/admin"),
            theme("/assets/images/image.jpg", CONF_VIEW_ADMIN),
            false
        );

        echo $this->view->render("widgets/blog/home", [
            "app" => "blog/home",
            "head" => $head,
            "posts" => $posts->limit($pager->limit())->offset($pager->offset())->order("post_at DESC")->fetch(true),
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
    public function post(?array $data): void
    {
        /**
         * MCE Upload
         */
        if(!empty($data["upload"]) && !empty($_FILES["image"])){
            $files = $_FILES["image"];
            $upload = new Upload();
            $image = $upload->image($files, "post-".time());

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

            /**
             * pt-br: content não realizamos o filter, pois é necessário as tags e links para renderizar.
             */
            $content = $data["content"];
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

            $postCreate = new Post();
            $postCreate->author = $data["author"];
            $postCreate->category = $data["category"];
            $postCreate->title = $data["title"];
            $postCreate->uri = str_slug($postCreate->title);
            $postCreate->subtitle = $data["subtitle"];

            /**
             * pt-br: Passamos o title da image do mce_image nas propriedades "alt", "title" e caso tenha mais, ex. add no array ["{title}", "{link}"]
             */
            $postCreate->content = str_replace(["{title}"], [$postCreate->title], $content);
            $postCreate->video = $data["video"];

            /**
             * pt-br: Post_at será quando vai ser publicado, de forma agendada se necessário.
             */
            $postCreate->status = $data["status"];
            $postCreate->post_at = date_fmt_back($data["post_at"]);

            /**
             * UPLOAD COVER
            */
            if(!empty($_FILES["cover"])){
                $files = $_FILES["cover"];

                $upload = new Upload();
                $image = $upload->image($files, $postCreate->title);

                if(!$image){
                    $json["message"] = $upload->message()->render();
                    echo json_encode($json);
                    return;
                }
                $postCreate->cover = $image;
            }

            /**
             * pt-br: No metodo save com polimorfismo para garantir uma URI unica
            */
            if(!$postCreate->save()){
                $json["message"] = $postCreate->message()->render();
                echo json_encode($json);
                return;
            }

            $this->message->success("Post criado com sucesso...")->flash();
            $json["redirect"] = url("/admin/blog/post/{$postCreate->id}");

            echo json_encode($json);
            return;
        }

        /**
         * uptade
         */
        if(!empty($data["action"]) && !empty($data["action"] == "update")){
            $content = $data["content"];
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

            $postEdit = (new Post())->findById($data["post_id"]);

            if(!$postEdit){
                $this->message->error("Você tentou editar uma postagem que não existe ou foi deletada!")->flash();
                echo json_encode(["redirect" => url("/admin/blog/home")]);
                return;
            }

            $postEdit->author = $data["author"];
            $postEdit->category = $data["category"];
            $postEdit->title = $data["title"];
            $postEdit->uri = str_slug($postEdit->title);
            $postEdit->subtitle = $data["subtitle"];
            $postEdit->content = str_replace(["{title}"], [$postEdit->title], $content);
            $postEdit->video = $data["video"];
            $postEdit->status = $data["status"];
            $postEdit->post_at = date_fmt_back($data["post_at"]);

            /**
             * UPLOAD COVER
             */
            if(!empty($_FILES["cover"])){

                /**
                 * pt-br: Exlui a imagem atual e a thumb da cache
                */
                if($postEdit->cover && file_exists(__DIR__ . "/../../../". CONF_UPLOAD_DIR ."/{$postEdit->cover}")){
                    unlink(__DIR__ . "/../../../". CONF_UPLOAD_DIR ."/{$postEdit->cover}");
                    (new Thumb())->flush($postEdit->cover);
                }

                $files = $_FILES["cover"];
                $upload = new Upload();
                $image = $upload->image($files, $postEdit->title);
                if(!$image){
                    $json["message"] = $upload->message()->render();
                    echo json_encode($json);
                    return;
                }
                $postEdit->cover = $image;
            }

            if(!$postEdit->save()){
                $json["message"] = $postEdit->message()->render();
                echo json_encode($json);
                return;
            }

            $this->message->success("Post atualizado com sucesso...")->flash();
            /**
             * pt-br: Recarregamento da página atual
            */
            echo json_encode(["reload" => true]);
            return;
        }

        /**
         * delete
         */
        if(!empty($data["action"]) && !empty($data["action"] == "delete")){
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

            $postDelete = (new Post())->findById($data["post_id"]);


            if(!$postDelete){
                $this->message->error("Você tentou deletar uma postagem que não existe ou foi removida!")->flash();
                echo json_encode(["redirect" => url("/admin/blog/home")]);
                return;
            }

            if($postDelete->cover && file_exists(__DIR__ . "/../../../". CONF_UPLOAD_DIR ."/{$postDelete->cover}")){
                unlink(__DIR__ . "/../../../". CONF_UPLOAD_DIR ."/{$postDelete->cover}");
                (new Thumb())->flush($postDelete->cover);
            }

            $postDelete->destroy();
            $this->message->success("Postagem removida com sucesso!")->flash();

            echo json_encode(["reload"=>true]);
            return;
        }

        $postEdit = null;

        if(!empty($data["post_id"])){
            $postId = filter_var($data["post_id"], FILTER_VALIDATE_INT);
            $postEdit = (new Post())->findById($postId);
        }

        $head = $this->seo->render(
            CONF_SITE_NAME . " | " . ($postEdit->title ?? "Novo Artigo"),
            CONF_SITE_DESC,
            url("/admin"),
            theme("/assets/images/image.jpg", CONF_VIEW_ADMIN),
            false
        );

        echo $this->view->render("widgets/blog/post", [
            "app" => "blog/post",
            "head" => $head,
            "post" => $postEdit,
            "categories" => (new Category())->find("type = :type", "type=post")->order("title")->fetch(true),
            "authors" => (new User())->find("level >= :level", "level=5")->fetch(true)
        ]);

    }

    /**
     * @param array|null $data
     * pt-br:
     * view: adm -
     */
    public function categories(?array $data): void
    {
        $categories = (new Category())->find("type = 'post'");

        $pager = new Pager(url("/admin/blog/categories/"));
        $pager->pager($categories->count(), 6, (!empty($data["page"]) ? $data["page"] : 1));

        $head = $this->seo->render(
            CONF_SITE_NAME . " | Categorias",
            CONF_SITE_DESC,
            url("/admin"),
            theme("/assets/images/image.jpg", CONF_VIEW_ADMIN),
            false
        );

        echo $this->view->render("widgets/blog/categories", [
            "app" => "blog/categories",
            "head" => $head,
            "categories" => (new Category())->find("type = 'post'")->order("title")->limit($pager->limit())->offset($pager->offset())->fetch(true),
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

            $categoryCreate->title = $data["title"];
            $categoryCreate->uri = str_slug($categoryCreate->title);
            $categoryCreate->description = $data["description"];

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
            /**
             * pt-br: O flash é utilizado sempre que queremos redirecionar para outra página, caso seja na mesma, usamos o render.
             */
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
                $this->message->error("Você tentou editar uma categoria que não existe ou foi deletada!")->render();
                echo json_encode(["redirect"=> url("/admin/blog/categories")]);
                return;
            }

            $categoryEdit->title = $data["title"];
            $categoryEdit->uri = str_slug($categoryEdit->title);
            $categoryEdit->description = $data["description"];

            if(!empty($_FILES["cover"])){

                if($categoryEdit->cover && file_exists(__DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/{$categoryEdit->cover}")){
                    unlink(__DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/{$categoryEdit->cover}");
                    (new Thumb())->flush($categoryEdit->cover);
                }

                $files = $_FILES["cover"];
                $upload = new Upload();
                $image = $upload->image($files, $categoryEdit->title);

                if(!$image){
                    $json["message"] = $upload->message()->flash();
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

            if ($categoryDelete->typeCategory('post')->count()) {
                $json["message"] = $this->message->warning("Não é possível remover pois existem posts cadastrados")->render();
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

        echo $this->view->render("widgets/blog/category", [
            "app" => "blog/categories",
            "head" => $head,
            "post" => $categoryEdit,
            "category" => $categoryEdit
        ]);
    }
}