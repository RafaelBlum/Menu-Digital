<?php


namespace Source\App\Admin;


use Source\Core\Controller;
use Source\Models\Auth;

class LoginController extends Controller
{
    /**
     * LoginController constructor.
     */
    public function __construct()
    {
        parent::__construct(__DIR__ . "/../../../themes/" . CONF_VIEW_ADMIN . "/");
    }

    /**
     * pt-br: Rota raiz de verificação de level
     * view: adm - widgets/login/login ou widgets/dash/home
     */
    public function root(): void
    {
        $user = Auth::user();

        if($user && $user->level >= 5){
            redirect("/admin/dash");
        }else{
            redirect("/admin/login");
        }

    }

    /**
     * @param array|null $data
     * pt-br: login de usuário
     * view: adm - widgets/login/login
     */
    public function login(?array $data): void
    {
        $user = Auth::user();

        if($user && $user->level >= 5){
            redirect("/admin/dash");
        }

        /**
         * pt-br: verifica se exitem dado, se sim realiza processamento do form
         */
        if(!empty($data["email"]) && !empty($data["password"])){

            if(request_limit("loginLogin", 3, 5 * 60)){
                $json["message"] = $this->message->error("ACESSO NEGADO! Tente novamente daqui 5 minutos, obrigado!")->render();
                echo json_encode($json);
                return;
            }

            $auth = new Auth();
            $login = $auth->login($data["email"], $data["password"], true, 5);

            if($login){
                $json["redirect"] = url("/admin/dash");
            }else{
                $json["message"] = $auth->message()->render();
            }

            echo json_encode($json);
            return;
        }

        $head = $this->seo->render(
            CONF_SITE_NAME . " | Admin",
            CONF_SITE_DESC,
            url("/admin"),
            theme("/assets/images/image.jpg", CONF_VIEW_ADMIN),
            false
        );

        echo $this->view->render("widgets/login/login", [
            "head" => $head
        ]);
    }

}