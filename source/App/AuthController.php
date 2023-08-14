<?php


namespace Source\App;


use Source\Core\Controller;
use source\Core\View;
use Source\Models\Auth;
use Source\Models\Newslatter;
use Source\Models\Post;
use Source\Models\Product;
use Source\Models\Setting;
use Source\Models\User;

class AuthController extends Controller
{


    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        parent::__construct(__DIR__ . "/../../themes/" . CONF_VIEW_THEME . "/");
    }


    /**
     * @param array|null $data
     * pt-br: acesso e login de usuário
     * view: web - auth/login
     */
    public function login(?array $data): void
    {

        /**
         * Redirect user if online
         */
        if(Auth::user()){
            redirect("/admin/dash/home");
        }

        /**
         * pt-br: verifica ação de envio de formulário atraves da existencia do csrf
         */
        if(!empty($data['csrf'])){

            /**
             * pt-br: verifica a sessão e csrf
             */
            if(!csrf_verify($data)){
                $json['message'] = $this->message->error("Erro ao tentar enviar formulário!")->render();
                echo json_encode($json);
                return;
            }

            /**
             * pt-br: Limita a quantidade de tentativas de login
             */
            if(request_limit("authlogin", 3, 60 * 5)){
                $json['message'] = $this->message->error("Você chegou ao seu limite de tentativas, por favor aguarde 5 minutos para tentar novamente!")->render();
                echo json_encode($json);
                return;
            }

            /**
             * pt-br: verifica se email e password foram inseridos corretamente
             */
            if(empty($data['email']) || empty($data['password'])){
                $json['message'] = $this->message->error("Porfavor, informar e-mail e senha!")->render();
                echo json_encode($json);
                return;
            }

            /**
             * pt-br: verifica se foi selecionado campo lembre-me
             */
            $save = (!empty($data['save']) ? true : false );

            /**
             * pt-br: Autenticador de login
             */
            $auth = new Auth();
            $login = $auth->login($data['email'], $data['password'], $save);

            /**
             * pt-br: verifica se tudo correto true entra APP, se não msg error verifique
             */
            if($login){
                $this->message->success("Seja bem-vindo(a) de volta, " . Auth::user()->first_name . "!")->flash();
                $json['redirect'] = url("/admin/dash/home");
            }else{
                $json['message'] = $auth->message()->before("Ooopss! ")->after(" :(")->render();
            }

            echo json_encode($json);
            return;
        }

        $head = $this->seo->render(
            "Entrar - " .CONF_SITE_NAME,
            CONF_SITE_DESC,
            url("/entrar"),
            theme('/assets/image/logo/logo-cd-md.png')
        );

        echo $this->view->render("auth/login", [
            "head"=> $head,
            "cookie" => filter_input(INPUT_COOKIE, "authEmail")
        ]);
    }


    /**
     * @param array $data
     * pt-br: cadastro de email newslatter
     * email: web - shared/views/
     * view: web - home
     */
    public function newslatter(array $data): void
    {

        /**
         * pt-br: verifica ação de envio de formulário atraves da existencia do csrf
         */
        if(!empty($data['csrf'])){
            /**
             * pt-br: verifica a sessão e csrf
             */
            if(!csrf_verify($data)){
                $json['message'] = $this->message->error("Erro ao tentar enviar formulário!")->render();
                echo json_encode($json);
                return;
            }


//            if(request_limit("subscribUser", 3, 5 * 60)){
//                $json["message"] = $this->message->warning("Você exedeu o limite de tentativas para inscrição, por favor aguarde 5 minutos para tentar novamente!")->render();
//                echo json_encode($json);
//                return;
//            }

            if(empty($data['email']) || empty($data["name"])){
                $json['message'] = $this->message->error("Favor inserir nome e E-mail!")->render();
                echo json_encode($json);
                return;
            }

            /**
             * pt-br: Autenticador de login
             */
            $auth = new Auth();
            $subscrib = new Newslatter();

            $subscrib->bootstrap(
                $data["name"],
                $data["email"]
            );

            /**
             * pt-br: verifica se tudo correto true entra APP, se não msg error verifique
             */
            if($auth->subscription($subscrib)){
                $json['redirect'] = url("/confirma");
            }else{
                $json['message'] = $auth->message()->before("Ooopss! ")->render();
            }

            echo json_encode($json);
            return;
        }

        $head = $this->seo->render(
            "Receba notificações - " .CONF_SITE_NAME,
            CONF_SITE_DESC,
            url("/inscricao"),
            theme("/assets/images/logo/logo-md.png")
        );

        echo $this->view->render("subscription", [
            "head"=> $head
        ]);


    }


    /**
     * @param array|null $data
     * pt-br: Esqueceu da senha - recuperar senha
     * view: web - auth/forget
     */
    public function forget(?array $data): void
    {

        /**
         * Redirect user if online
         */
        if(Auth::user()){
            redirect("/admin/dash/home");
        }

        /**
         * pt-br: verifica se existe csrf *
         */
        if(!empty($data['csrf'])) {

            /**
             * pt-br: verifica se todos dados estão corretamente *
             */
            if (!csrf_verify($data)) {
                $json['message'] = $this->message->error("Erro ao tentar enviar formulário!")->render();
                echo json_encode($json);
                return;
            }

            if(empty($data['email'])){
                $json['message'] = $this->message->warning("Informe seu e-mail para recuperar!")->render();
                echo json_encode($json);
                return;
            }

            if(request_repite("authforget", $data['email'])){
                $json['message'] = $this->message->warning("Opsss! Você já tento este e-mail antes!")->render();
                echo json_encode($json);
                return;
            }

            $auth = new Auth();
            if($auth->forget($data['email'])){
                $json['message'] = $this->message->success("Acesse seu e-mail para recuperar")->render();
            }else{
                $json["message"] = $auth->message()->before("Ooopss! ")->render();
            }

            echo json_encode($json);
            return;
        }

        $head = $this->seo->render(
            "Recuperar senha - " .CONF_SITE_NAME,
            CONF_SITE_DESC,
            url("/recuperar"),
            theme('/assets/image/logo/logo-cd-md.png')
        );

        echo $this->view->render("auth/forget", [
            "head"=> $head
        ]);
    }


    /**
     * @param array $data
     * pt-br: resete de senha
     * email: web - shared/views/
     * view: web - auth/reset
     */
    public function reset(array $data)
    {

        /**
         * Redirect user if online
         */
        if(Auth::user()){
            redirect("/app");
        }

        /**
         * pt-br: verifica se existe csrf
         */
        if(!empty($data['csrf'])) {
            if (!csrf_verify($data)) {
                $json['message'] = $this->message->error("Erro ao tentar enviar formulário!")->render();
                echo json_encode($json);
                return;
            }

            if(empty($data['password'] || empty($data['password_re']))){
                $json['message'] = $this->message->warning("Informe e repita as senhas!")->render();
                echo json_encode($json);
                return;
            }

            list($email, $code) = explode("|", $data['code']);

            $auth = new Auth();

            if($auth->reset($email, $code, $data['password'], $data['password_re'])){
                $this->message->success("Senha alterada com sucesso!")->flash();
                $json['redirect'] = url("/entrar");
            }else{
                $json['message'] = $auth->message()->before("Ooopss! ")->render();
            }

            echo json_encode($json);
            return;
        }

        $head = $this->seo->render(
            "Recuperação de senha | ". CONF_SITE_NAME,
            CONF_SITE_NAME,
            url("/recuperar"),
            theme('/assets/image/logo/logo-cd-md.png')
        );

        echo $this->view->render("auth/reset", [
            "head" => $head,
            "code" => $data['code']
        ]);
    }


    /**
     * @param array $data
     * pt-br: confirmação de e-mail
     * view: web - auth/optin
     */
    public function confirm(): void
    {
        $head = $this->seo->render(
            "Confirmação - " .CONF_SITE_NAME,
            CONF_SITE_DESC,
            url("/cadastrar"),
            theme("/assets/images/logo/logo-md.png")
        );

        echo $this->view->render("auth/optin", [
            "head"=> $head,
            "data" => (object)[
                "confirm" => "confirm",
                "title" => "Cadastro confirmado!!!",
                "desc" => "Enviamos um <b>link</b> de confirmação para seu e-mail. Acesse e siga as instruções para concluir seu cadastro com " . CONF_SITE_NAME,
                "image" => theme("/assets/images/logo/logo-md.png"),
                "link" => url("/home"),
                "linkTitle" => "Voltar para home"
            ]
        ]);
    }


    /**
     * @param array $data
     * pt-br: mensagem de sucesso para clientes
     * view: web - auth/optin
     */
    public function success(array $data): void
    {
        $email = base64_decode($data["email"]);
        $subscrib = (new Newslatter())->findByEmail($email);


        if($subscrib && $subscrib->status != "active"){

            $subscrib->status = "active";
            $subscrib->is_verified = true;
            $subscrib->verify_code = md5(uniqid(rand(), true));

            $subscrib->save();

        }

        $settins = (new Setting())->find()->fetch();

        $head = $this->seo->render(
            "Sucesso - " . $settins->project_Name,
            $settins->description,
            url("/home"),
            theme('/assets/images/logo/logo-md.png')
        );

        echo $this->view->render("auth/optin", [
            "head"=> $head,
            "data" => (object)[
                "confirm" => "success",
                "title" => "Pronto! Confirmação realizada!",
                "desc" => "Seu registro foi realizado com sucesso!! <br>" . $settins->project_name,
                "image" => theme("/assets/images/logo/logo-md.png"),
                "link" => url("/home"),
                "linkTitle" => "Voltar para home"
            ]
        ]);

    }
}