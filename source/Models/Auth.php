<?php


namespace Source\Models;


use Source\Core\Model;
use Source\Core\Session;
use Source\Core\View;
use Source\Supports\Email;

/**
 * Class Auth
 * @package Source\Models
 * Faz toda rotina de autenticação do usuário -:
 */
class Auth extends Model
{
    /**
     * Auth constructor.
     */
    public function __construct()
    {
        parent::__construct("users", ["id"], ["email", "password"]);
    }


    /**
     * @return User|null
     * pt-br: retorna usuário autenticado
     */
    public static function user(): ?User
    {
        $session = new Session();

        /** Verifica se user esta autenticado **/
        if(!$session->has("authUser")){
            return null;
        }

        return (new User())->findById($session->authUser);
    }


    /**
     * pt-br: desloga o usuário autenticado
     */
    public static function logout(): void
    {
        $session = new Session();
        $session->unset("authUser");
    }


    /**
     * @param User $user
     * @return bool
     * pt-br: salva novo registro de usuário e apresenta view de ativação de conta pelo e-mail enviado
     */
    public function register(User $user): bool
    {
        if(!$user->save()){
            $this->message = $user->message;
            return false;
        }

        $view = new View(__DIR__ . "/../../shared/views/mail/");
        $message = $view->render("confirm", [
            "site_name" => CONF_SITE_NAME,
            "description" => "Agradece seu contato e em breve estaremos lhe passando nossas novidades sobre nossos, produtos e serviços.",
            "first_name" => $user->first_name . " " . $user->last_name,
            "confirm_link" => url("/sucesso/" . base64_encode($user->email)),
            "logo" => "https://cardapio-digital.online//assets/theme/images/logo/logo-cardapio-digital.png",
            "icon" => "https://cardapio-digital.online//assets/theme/images/logo/logo-cardapio-digital-vv.png"
        ]);

        (new Email())->bootstrap(
            "Ative sua conta em " . CONF_SITE_NAME,
            $message,
            $user->email,
            "{$user->first_name} {$user->last_name}"
        )->send();

        return true;
    }


    /**
     * @param Newslatter $news
     * @return bool
     * pt-br: salva novo registro de newslatter
     */
    public function subscription(Newslatter $subscrib): bool
    {
        if(!$subscrib->save()){
            $this->message = $subscrib->message;
            return false;
        }

        $view = new View(__DIR__ . "/../../shared/views/mail/");

        $message = $view->render("mailSubscrib", [
            "site_name" => CONF_SITE_NAME,
            "description" => "Agradece sua inscrição! Em breve estaremos lhe passando informações sobre novidades, produtos e serviços.",
            "name" => $subscrib->name,
            "confirm_link" => url("/sucesso/" . base64_encode($subscrib->email) . "/" . base64_encode($subscrib->status)),
            "logo" => "https://cardapio-digital.online//assets/theme/images/logo/logo-cardapio-digital.png",
            "icon" => "https://cardapio-digital.online//assets/theme/images/logo/logo-cardapio-digital-vv.png",
            "unsubscribe" => "Caso queira não receber mais informações sobre nossos serviços, envie um e-mail para a gente, obrigado!"
        ]);

        (new Email())->bootstrap(
            "Ative sua conta em " . CONF_SITE_NAME,
            $message,
            $subscrib->email,
            "{$subscrib->name}"
        )->send();

        return true;
    }


    /**
     * @param string $email
     * @param string $password
     * @param bool|null $save
     * @param int $level
     * @return bool
     * pt-br: valida login de usuário tanto no App quanto no Admin CMS
     */
    public function login(string $email, string $password, bool $save = null, int $level = 1): bool
    {
        /**
         * Valida email format
        */
        if(!is_email($email)){
            $this->message->warning("O e-mail informado não é valido!");
            return false;
        }

        /**
         * pt-br: Lembrar dados
         * if salva cookie email
         * else remove cookie
        */
        if($save){
            setcookie("authEmail", $email, time() + 604800, "/");
        }else{
            setcookie("authEmail", null, time() - 604800, "/");
        }

        /**
         * validade password
        */
        if(!is_password($password)){
            $this->message->warning("A senha informada não é valida!");
            return false;
        }

        $user = (new User())->findByEmail($email);

        /**
         * pt-br: Verifica se o email informado existe no banco
        */
        if(!$user){
            $this->message->error("O email informado não esta cadastrado");
            return false;
        }

        /**
         * pt-br: Verifica se a senha informada é do usuário
         */
        if(!password_verify($password, $user->password)){
            $this->message->error("A senha informada não confere!");
            return false;
        }

        /**
         * pt-br: Verifica o acesso de level do usuario para admin modulo cms
         */
        if($user->level < $level){
            $this->message->error("Você não tem permissão de acesso para acessar-se aqui!");
            return false;
        }

        /**
         * pt-br: Verifica se a senha necessita refazer a hash
         */
        if(password_rehash($user->password)){
            $user->password = $password;
            $user->save();
        }

        (new Session())->set("authUser", $user->id);
        $this->message->success("Login efetuado com sucesso!")->flash();
        return true;

    }


    /**
     * @param string $email
     * @return bool
     * pt-br: recuperação de senha via email/code uniqid
     */
    public function forget(string $email): bool
    {
        $user = (new User())->findByEmail($email);

        if(!$user){
            $this->message->warning("O email informado, não esta cadastrado!");
            return false;
        }

        /**
         * pt-br: Gera code aleatório
         */
        $user->forget = md5(uniqid(rand(), true));
        $user->save();

        $view = new View(__DIR__ . "/../../shared/views/mail/");
        $message = $view->render("forget", [
            "site_name" => CONF_SITE_NAME,
            "first_name" => $user->first_name,
            "forget_link" => url("/recuperar/{$user->email}|{$user->forget}"),
            "logo" => "https://cardapio-digital.online//assets/theme/images/logo/logo-cardapio-digital-vv.png"
        ]);

        (new Email())->bootstrap(
            "Recupere sua senha no ". CONF_SITE_NAME,
            $message,
            $user->email,
            "{$user->first_name} {$user->last_name}"
        )->send();

        return true;
    }


    /**
     * @param string $email
     * @param string $code
     * @param string $password
     * @param string $passwordRe
     * @return bool
     * pt-br: reseta a senha do usuário autenticado
     */
    public function reset(string $email, string $code, string $password, string $passwordRe): bool
    {
        $user = (new User())->findByEmail($email);

        if(!$user){
            $this->message->warning("A conta para recuperação não foi encontrada!");
            return false;
        }

        if($user->forget != $code){
            $this->message->warning("O código verificado, não é valido!");
            return false;
        }

        if(!is_password($password)){
            $min = CONF_PASSWORD_MIN_LEN;
            $max = CONF_PASSWORD_MAX_LEN;
            $this->message->warning("Sua senha deve ter entre {$min} e {$max} caracteres!");
            return false;
        }

        if($password != $passwordRe){
            $this->message->warning("As senhas devem ser identicas!");
            return false;
        }

        $user->password = $password;
        $user->forget = null;
        $user->save();
        return true;
    }
}