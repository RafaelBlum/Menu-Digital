<?php


namespace Source\App\Admin;


use Source\Models\Auth;
use Source\Models\Category;
use Source\Models\Newslatter;
use Source\Models\Post;
use Source\Models\Product;
use Source\Models\report\Online;
use Source\Models\User;
use Source\Supports\Pager;

class DashController extends AdminController
{
    /**
     * DashController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * pt-br: rota de configurações e redireciona para dashboard rota
     * view: adm - widgets/dash/home
     */
    public function dash(): void
    {
        redirect("/admin/dash/home");
    }

    /**
     * @param array|null $data
     * @throws \Exception
     * pt-br: Pagina home que passa os dados de trafego para o script js monitorar e os dados de renderização
     * view: adm - widgets/dash/home
     */
    public function home(?array $data): void
    {
        /**
         * pt-br: Verifica refresh e passa usuários online (total) e listagem de cada user (data, name, paágina, url)
         */
        if(!empty($data["refresh"])){
            $list = null;

            $items = (new Online())->findByActive();
            if($items){
                foreach ($items as $item) {
                    $list[] =[
                        "dates" => date_fmt($item->created_at, "H\hi") . " - " . date_fmt($item->updated_at, "H\hi"),
                        "user" => ($item->user ? $item->user()->fullName() : "Anônimo usuário"),
                        "pages" => $item->pages,
                        "url" => $item->url
                    ];
                }
            }

            echo json_encode([
               "count" => (new Online())->findByActive(true),
                "list" => $list
            ]);

            return;
        }

        $head = $this->seo->render(
            CONF_SITE_NAME . " | Dashboard",
            CONF_SITE_DESC,
            url("/admin"),
            theme("/assets/images/image.jpg", CONF_VIEW_ADMIN),
            false
        );

        echo $this->view->render("widgets/dash/home", [
            "app" => "dash",
            "confContent" => "Tenha insights poderosos para escalar seus resultaods...",
            "head" => $head,
            "subscriptions" => (object)[
                "subscribs" => (new Newslatter())->find()->count()
            ],
            "blog" => (object)[
                "posts" => (new Post())->find("status = 'post'")->count(),
                "drafts" => (new Post())->find("status = 'draft'")->count(),
                "categories" => (new Category())->find("type = 'post'")->count()
            ],
            "product" => (object)[
                "posts" => (new Product())->find("status = 'post'")->count(),
                "drafts" => (new Product())->find("status = 'draft'")->count(),
                "tot" => (new Product())->find()->count(),
                "categories" => (new Category())->find("type = 'product'")->count()
            ],
            "users" => (object)[
                "users" => (new User())->find("level < 5")->count(),
                "admins" => (new User())->find("level >= 5")->count()
            ],
            "online" => (new Online())->findByActive(),
            "onlineCount" => (new Online())->findByActive(true)
        ]);
    }

    public function subscriptions(?array $data): void
    {
        if(!empty($data["s"])){
            $s = str_search($data["s"]);
            echo json_encode(["redirect" => url("/admin/dash/subscriptions/{$s}/1")]);
            return;
        }

        $search = null;
        $subscriptions = (new Newslatter())->find();

        if(!empty($data["search"]) && str_search($data["search"]) != "all"){
            $search = str_search($data["search"]);

            $subscriptions = (new Newslatter())->find("MATCH(name, email) AGAINST(:s)", "s={$search}");

            if(!$subscriptions->count()){
                $this->message->warning("Sua pesquisa não retornou resultado!")->flash();
                redirect("/admin/dash/subscriptions");
            }
        }

        $all = ($search ?? "all");

        $pager = new Pager(url("/admin/dash/subscriptions/{$all}/"));
        $pager->pager($subscriptions->count(), 10, (!empty($data["page"]) ? $data["page"] : 1));

        $head = $this->seo->render(
            CONF_SITE_NAME . " | Dashboard",
            CONF_SITE_DESC,
            url("/admin"),
            theme("/assets/images/image.jpg", CONF_VIEW_ADMIN),
            false
        );

        echo $this->view->render("widgets/dash/subscriptions", [
            "app" => "dash/subscriptions",
            "confContent" => "Veja todos resultados de seus inscritos aqui...",
            "head" => $head,
            "subscribCount" => (new Newslatter())->find()->count(),
            "subscriptions" => $subscriptions->order("name")->limit($pager->limit())->offset($pager->offset())->fetch(true),
            "search" => "",
            "paginator" => $pager->render()
        ]);
    }

    public function deactivate(array $data): void
    {
        $subscrit = (new Newslatter())->findById($data["subscrib_id"]);

        if(!$subscrit){
            $this->message->warning("Ocorreu um erro ao tentar atualizar!")->flash();
            $json["reload"] = true;
            echo json_encode($json);
            return;
        }

        $subscrit->status = ($subscrit->status == 'active' ? 'inactive' : 'active');
        $subscrit->save();

        $this->message->success("Inscrição atualizada com sucesso!")->flash();
        $json["reload"] = true;
        echo json_encode($json);
        return;

    }

    public function logoff(): void
    {
        $this->message->success("Você saiu com sucesso, {$this->user->first_name}.")->flash();
        Auth::logout();
        redirect("/entrar");
    }
}