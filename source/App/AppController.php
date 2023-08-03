<?php


namespace Source\App;


use Source\Core\Controller;
use Source\Core\Session;
use Source\Core\View;
use Source\Models\Auth;
use Source\Models\cardapioApp\AppCategory;
use Source\Models\cardapioApp\AppInvoice;
use Source\Models\cardapioApp\AppWallet;
use Source\Models\Category;
use Source\Models\Post;
use Source\Models\Product;
use Source\Models\report\Access;
use Source\Models\report\Online;
use Source\Models\User;
use Source\Supports\Email;
use Source\Supports\Message;
use Source\Supports\Thumb;
use Source\Supports\Upload;

class AppController extends Controller
{
    /**
     * @var User|null
     */
    private $user;

    /**
     * AppController constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct(__DIR__ . "/../../themes/" . CONF_VIEW_THEME_APP . "/");

        /**
         * Restriction
         * pt-br: Restringe o acesso dos usuários não logados a dashboard
         */
        if(!$this->user = Auth::user()){
            $this->message->warning("Efetue o login para acessar o painel administrativo!")->flash();
            redirect("/entrar");
        }

        /**
         * pt-br: analise de acesso de usuários
        */
        (new Access())->report();
        (new Online())->report();

        /**
         * pt-br: Inicialização de carteira de usuário no primeiro acesso
         */
        (new AppWallet())->start($this->user);

        (new AppInvoice())->fixed($this->user, 3);

        /**
         * pt-br: Caso o usuário não tenha confirmado seu acesso a dashboard, gera msg importante e Email
         */
        if($this->user->status != "confirmed"){
            $session = new Session();
            if(!$session->has("appconfirmed")){
                $this->message->warning("IMPORTANTE! Acesse seu e-mail e confirme seu cadastro para ativar todos recursos!")->flash();
                $session->set("appconfirmed", true);
                (new Auth())->register($this->user);
            }
        }
    }

    public function dash(?array $data): void
    {

        /** Chart update */
        $chartData = (new AppInvoice())->chartData($this->user);
        $categories = str_replace("'", "", explode(",",  $chartData->categories));

        $json["chart"] = [
            "categories" => $categories,
            "income" => array_map("abs", explode(",", $chartData->income)),
            "expense" => array_map("abs", explode(",", $chartData->expense))
        ];

        /** wallte */
        $wallet = (new AppInvoice())->balance($this->user);
        $wallet->wallet = str_price($wallet->wallet);
        $wallet->status = ($wallet->balance == "positive" ? "gradient-green" : "gradient-red");
        $wallet->income = str_price($wallet->income);
        $wallet->expense = str_price($wallet->expense);
        $json["wallet"] = $wallet;

        echo json_encode($json);

    }


    /**
     * APP HOME
     * pt-br: Traz todos registros de faturas, carteira, monta gráfico e postagens.
     * view: home
     */
    public function home()
    {
        $head =  $this->seo->render(
            "Olá, {$this->user->first_name}, seja bem-vindo!",
            CONF_SITE_DESC,
            url("/"),
            theme('/assets/image/logo/logo-cd-vertical-mg.png'),
            false
        );

        /**
         * Chart data
         * pt-br: Metodo para desenvolver e passar os dados ao gráfico.
         */
        $chartData = (new AppInvoice())->chartData($this->user);


//        $json["data"] = $chartData;
//        echo json_encode($json);


        $income = (new AppInvoice())
            ->find("user_id = :id AND type = 'income' AND status = 'unpaid' AND date(due_at) <= date(now() + INTERVAL 1 MONTH)",
                "id={$this->user->id}")
            ->order('due_at')
            ->fetch(true);

        $expense = (new AppInvoice())
            ->find("user_id = :id AND type = 'expense' AND status = 'unpaid' AND date(due_at) <= date(now() + INTERVAL 1 MONTH)",
                "id={$this->user->id}")
            ->order('due_at')
            ->fetch(true);

        $wallet = (new AppInvoice())->balance($this->user);


        $posts = (new Post())
            ->findPost()
            ->limit(3)
            ->order("post_at DESC")
            ->fetch(true);

        echo $this->view->render("home", [
            "head" => $head,
            "chart" => $chartData,
            "income" => $income,
            "expense" => $expense,
            "wallet" => $wallet,
            "posts" => $posts
        ]);
    }

    /**
     * APP FILTER INVOICES - FATURAS
     * pt-br: filtro de faturas por status, categoria e data
     * view: invoices
     */
    public function filter(array $data): void
    {
        $status = (!empty($data["status"]) ? $data["status"] : "all");
        $category = (!empty($data["category"]) ? $data["category"] : "all");
        $date = (!empty($data["date"]) ? $data["date"] : date("m/Y"));

        /**
         * pt-br: separate the day and year from the date
         */
        list($m, $y) = explode("/", $date);
        $m = ($m >= 1 && $m <= 12 ? $m : date("m"));
        $y = ($y <= date("Y", strtotime("+10year")) ? $y : date("Y", strtotime("+10year")));

        /** t get last day month in date format */
        $start = new \DateTime(date("Y-m-t"));
        $end = new \DateTime(date("Y-m-t", strtotime("{$y}-{$m}+1month")));
        $diff = $start->diff($end);

        if(!$diff->invert){
            $afterMonth = (floor($diff->days / 30));
            (new AppInvoice())->fixed($this->user, $afterMonth);
        }

        $redirect = ($data["filter"] == "income" ? "receber" : "pagar");
        $json["redirect"] = url("/app/{$redirect}/{$status}/{$category}/{$m}-{$y}");
        echo json_encode($json);
    }

    /**
     * APP FILTER PRODUCTS
     * pt-br: filtro de produtos por status e categoria
     * view: products
     */
    public function filterProducts(array $data): void
    {
        $status = (!empty($data["status"]) ? $data["status"] : "all");
        $category = (!empty($data["category"]) ? $data["category"] : "all");

        $json["redirect"] = url("/app/produtos/{$status}/{$category}");
        echo json_encode($json);
    }

    /**
     * @param array $data [CREATE]
     * pt-br: Faz o lançamento de faturas
     * view: null
     */
    public function launch(array $data): void
    {
        if(request_limit('applaunch', 20, 60 * 5))
        {
            $json['message'] = $this->message->warning("Foi muito rapido, {$this->user->first_name}, tente daqui 5 minutos.")->render();
            echo json_encode($json);
            return;
        }

        if(!empty($data['enrollments']) && ($data['enrollments'] < 2 || $data['enrollments'] > 420))
        {
            $json['message'] = $this->message->warning("Oppss! {$this->user->first_name}, para lançamento das parcelas, deve ser entre 2 e 420 parcelas.")->render();
            echo json_encode($json);
            return;
        }

        /**
         * pt-br: FILTER DATA AND STATUS INVOICE DATE PAID/UNPAID
         */
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
        $status = (date($data['due_at']) <= date("Y-m-d") ? "paid":"unpaid");

        $invoice = (new AppInvoice());
        $invoice->user_id = $this->user->id;
        $invoice->wallet_id = $data["wallet"];
        $invoice->category_id = $data["category"];
        $invoice->invoice_of = null;
        $invoice->description = $data["description"];
        $invoice->type = ($data["repeat_when"] == "fixed" ? "fixed_{$data["type"]}" : $data["type"]);
        $invoice->value = str_replace([".", ","], ["", "."], $data['value']);
        $invoice->currency = $data["currency"];
        $invoice->due_at = $data["due_at"];
        $invoice->repeat_when = $data["repeat_when"];
        $invoice->period = ($data["period"] ?? "month");
        $invoice->enrollments = ($data["enrollments"] ?? 1);
        $invoice->enrollment_of = 1;
        $invoice->status = ($data["repeat_when"] == "fixed" ? "paid": $status);



        if(!$invoice->save())
        {
            $json['message'] = $invoice->message()->before("Ooppss! ")->render();
            echo json_encode($json);
            return;
        }

        if($invoice->repeat_when == "enrollment")
        {
            $invoiceOf = $invoice->id;

            for($enrollment = 1; $enrollment < $invoice->enrollments; $enrollment++)
            {
                $invoice->id = null;
                $invoice->invoice_of = $invoiceOf;
                $invoice->due_at = date("Y-m-d", strtotime($data["due_at"] . "+{$enrollment}month"));
                $invoice->status = (date($invoice->due_at) <= date("Y-m-d") ? "paid" : "unpaid");
                $invoice->enrollment_of = $enrollment + 1;
                $invoice->save();
            }

        }

        if($invoice->type == "income") {
            $this->message->success("Receita lançada com sucesso!! Use o filtro para controlar.")->flash();
        }else {
            $this->message->success("Despeza lançada com sucesso!! Use o filtro para controlar.")->flash();
        }

        $json['reload'] = true;
        echo json_encode($json);
    }

    /**
     * @param array $data
     * pt-br: Recebe notificações dos usuários via e-mail de suporte.
     * view: null
     */
    public function suport(array $data): void
    {

        if(empty($data['message']))
        {
            /**
             * valid field message
             * pt-br: Valida se o campo de mensagem se vazio.
             */
            $json['message'] = $this->message->warning("Para enviar um solicitação, escreve uma mensagem.")->render();
            echo json_encode($json);
            return;
        }

        /**
         * limit number request users, flood and spam
         * pt-br: Limitação de mensagens dos usuários por tempo e sucessivas mensagens ou spans.
         */
        if(request_limit("appsuport", 3, 60 * 5))
        {
            $json['message'] = $this->message->warning("Por favor, aguarde 5 minutos para enviar novos contatos, sugestões ou reclamações.")->render();
            echo json_encode($json);
            return;
        }

        /**
         * limit message different channels
         * pt-br: Limitação de mensagens de diferentes canais
         */
        if(request_repite('message', $data['message']))
        {
            $json['message'] = $this->message->warning("Já recebemos sua solicitação {$this->user->first_name}. Em breve estaremos lhe respondendo, obrigado!")->render();
            echo json_encode($json);
            return;
        }


        $subject = date_fmt() . " - {$data['subject']}";
        $message = filter_var($data['message'], FILTER_SANITIZE_STRING);

        $view = new View(__DIR__ . "/../../shared/views/mail/");
        $body = $view->render("mail", [
            "first_name" => $this->user->first_name,
            "subject" => $subject,
            "message" => str_textarea($message),
            "logo" => "https://cardapio-digital.online//assets/theme/images/logo/logo-cardapio-digital.png",
        ]);

        (new Email)->bootstrap(
            $subject,
            $body,
            CONF_DEV_MAIL,
            "Suporte " . CONF_SITE_NAME
        )->send();

        $this->message->success("Agradecemos seu contato, {$this->user->first_name}! Em breve estaremos respondendo.")->flash();
        $json['reload'] = true;
        echo json_encode($json);
    }

    /**
     * APP INCOME (Receber) [LIST INCOMES]
     * pt-br: Metodo de acesso a listagem de todas contas a receber
     * view: invoices
     */
    public function income(?array $data): void
    {
        $head = $this->seo->render(
            "Receitas",
            CONF_SITE_DESC,
            url("/"),
            theme('/assets/image/logo/logo-cd-vertical-mg.png'),
            false
        );

        $categories = (new AppCategory())
            ->find("type = :type", "type=income", "id, name")
            ->order("order_by, name")
            ->fetch(true);

        echo $this->view->render("invoices", [
            "head" => $head,
            "type" => "income",
            "categories" => $categories,
            "invoices" => (new AppInvoice())->filter($this->user, "income", ($data ?? null)),
            "user" => $this->user,
            "filter" => (object)[
                "status" => ($data['status'] ?? null),
                "category" => ($data['category'] ?? null),
                "data" => (!empty($data['date']) ? str_replace("-", "/", $data['date']) : null)
            ]
        ]);
        
    }

    /**
     * APP EXPENSE (Pagar) [LIST EXPENSES]
     * pt-br: Metodo de acesso a listagem de todas contas a pagas
     * view: invoices
     */
    public function expense(?array $data): void
    {
        $head = $this->seo->render(
            "Despezas",
            CONF_SITE_DESC,
            url("/"),
            theme('/assets/image/logo/logo-cd-vertical-mg.png'),
            false
        );

        $categories = (new AppCategory())
            ->find("type = :type", "type=expense", "id, name")
            ->order("order_by, name")
            ->fetch(true);

        echo $this->view->render("invoices", [
            "head" => $head,
            "type" => "expense",
            "categories" => $categories,
            "invoices" => (new AppInvoice())->filter($this->user, "expense", ($data ?? null)),
            "user" => $this->user,
            "filter" => (object)[
                "status" => ($data['status'] ?? null),
                "category" => ($data['category'] ?? null),
                "data" => (!empty($data['date']) ? str_replace("-", "/", $data['date']) : null)
            ]
        ]);
    }

    /**
     * pt-br: Busca todas faturas fixas
     * view: recurrences
     */
    public function fixed(): void
    {
        $head = $this->seo->render(
            "Minhas contas Fixas",
            CONF_SITE_DESC,
            url("/"),
            theme('/assets/image/logo/logo-cd-vertical-mg.png'),
            false
        );

        echo $this->view->render("recurrences", [
            "head" => $head,
            "invoices" => (new AppInvoice())->find("user_id = :user AND type IN('fixed_income', 'fixed_expense')",
                "user={$this->user->id}")->fetch(true)
        ]);
    }

    /**
     * pt-br: Metodo de click no icone de pago e não pago
     * view: invoices
     */
    public function onpaid(array $data): void
    {
        $invoice = (new AppInvoice())
            ->find("user_id = :user AND id = :id",
                "user={$this->user->id}&id={$data["invoice"]}")
            ->fetch();


        if(!$invoice){
            $this->message->warning("Ocorreu um erro ao tentar atualizar!")->flash();
            $json["reload"] = true;
            echo json_encode($json);
            return;
        }

        $invoice->status = ($invoice->status == "paid" ? "unpaid" : "paid");
        $invoice->save();

        /**
         * pt-br: caso não tenha filtro de data
         */
        $y = date("Y");
        $m = date("m");

        /**
         * pt-br: caso tenha filtro de data, list method deve seguir conforme data mês/ano
         */
        if(!empty($data["date"])){
            list($m, $y) = explode("/", $data["date"]);
        }

        /**
         * pt-br: method balance update values - AppInvoices class
         */
        $json["onpaid"] = (new AppInvoice())->balance($this->user, $y, $m, $invoice->type);
        echo json_encode($json);
     }

    /**
     * APP INVOICE (Fatura) [READ - UPDATE]
     * pt-br: Metodo de acesso a uma fatura
     * view: invoice
     */
    public function invoice(array $data): void
    {
        /** UPDATE */
        if(!empty($data["update"])){

            $invoice = (new AppInvoice())
                ->find("user_id = :user AND id = :id", "user={$this->user->id}&id={$data["invoice"]}")
                ->fetch();

            /**
             * pt-br: Erro de atualização da fatura.
             */
            if(!$invoice){
                $json["message"] = $this->message->error("Não foi possível carregar a fatura, {$this->user->first_name}")->render();
                echo json_encode($json);
                return;
            }

            /**
             * pt-br: validação de dias do mês da fatura.
             */
            if($data["due_day"] < 1 || $data["due_day"] > $dayOfMonth = date("t", strtotime($invoice->due_at))){
                $json["message"] = $this->message->warning("O vencimento da fatura deve ser entre 1 e {$dayOfMonth}.")->render();
                echo json_encode($json);
                return;
            }

            /**
             * pt-br: filtra datas e inserir dados
             */
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

            $due_day = date("Y-m", strtotime($invoice->due_at)) . "-" . $data["due_day"];
            $invoice->category_id = $data["category"];
            $invoice->description = $data["description"];
            $invoice->due_at = date("Y-m-d", strtotime($due_day));
            $invoice->value = str_replace(['.', ','], [',', '.'], $data["value"]);
            $invoice->wallet_id = $data["wallet"];
            $invoice->status = $data["status"];

            if(!$invoice->save()){
                $json["message"] = $invoice->message()->before("Oooops! ")->after(", {$this->user->first_name}")->render();
                echo json_encode($json);
                return;
            }

            /**
             * pt-br: Atualizar faturas fixas NÃO pagas.
             */
            $invoiceOf = (new AppInvoice)
                ->find("user_id = :user AND invoice_of = :of", "user={$this->user->id}&of={$invoice->id}")
                ->fetch(true);

            /**
             * pt-br: popular parcelas ou deletar
             */
            if(!empty($invoiceOf) && in_array($invoice->type, ['fixed_income', 'fixed_expense'])){
                foreach ($invoiceOf as $invoiceItem){
                    if($data["status"] == "unpaid" && $invoiceItem->status == "unpaid"){
                        $invoiceItem->destroy();
                    }else{
                        $due_day = date("Y-m", strtotime($invoiceItem->due_at)) . "-" . $data["due_day"];
                        $invoiceItem->category_id = $data["category"];
                        $invoiceItem->description = $data["description"];
                        $invoiceItem->wallet_id = $data["wallet"];

                        if($invoiceItem->status == "unpaid"){
                            $invoiceItem->due_at = date("Y-m-d", strtotime($due_day));
                            $invoiceItem->value = str_replace(['.', ','], [',', '.'], $data["value"]);
                        }

                        $invoiceItem->save();
                    }
                }
            }


            $json["message"] = $this->message->success("Pronto, {$this->user->first_name}! :) Fatura atualizada com sucesso!")->render();
            echo json_encode($json);
            return;
        }

        $head = $this->seo->render(
            "Faturas",
            CONF_SITE_DESC,
            url("/"),
            theme('/assets/image/logo/logo-cd-vertical-mg.png'),
            false
        );

        $invoice = (new AppInvoice())
            ->find("user_id = :user AND id = :invoice",
                "user={$this->user->id}&invoice={$data["invoice"]}")
            ->fetch();


        if(!$invoice){
            $this->message->error("Oooppsss! Ocorreu algum erro ao tentar acessar a fatura!")->flash();
            redirect("/app");
        }

        echo $this->view->render("invoice", [
            "head" => $head,
            "invoice" => $invoice,
            "wallets" => (new AppWallet())
                ->find("user_id = :id", "id={$this->user->id}", "id, wallet")
                ->order("wallet")
                ->fetch(true),
            "categories" => (new AppCategory())
                ->find("type = :type", "type={$invoice->category()->type}")
                ->order("order_by")
                ->fetch(true)
        ]);
    }

    /**
     * REMOVE
     * pt-br: Remove fatura
     * view: home (redirect)
     */
    public function remove(array $data): void
    {
        /**
         * pt-br: verifica se a existe fatura e se é do usuário
         */
        $invoice = (new AppInvoice())->find(
            "user_id = :user AND id = :invoice",
            "user={$this->user->id}&invoice={$data["invoice"]}")
            ->fetch();

        /**
         * pt-br: destroi fatura
         */
        if($invoice){
            $invoice->destroy();

            /**
             * pt-br: Destroi todas faturas da mesma se for o caso.
             */
            //if($invoice->invoice_of){
            //  $enroll = $invoice->findById($invoice->invoice_of);
            //   $enroll->destroy();
            //}
        }

        $this->message->success("Tudo pronto, {$this->user->first_name}! A fatura foi removida!")->flash();
        $json["redirect"] = url("/app");
        echo json_encode($json);
    }

    /**
     * APP PROFILE (Perfil)
     * pt-br: Traz dados do usuário logado para edição [read or update]
     * view: profile
     */
    public function profile(?array $data): void
    {

        if(!empty($data["update"])){
            $user = (new User())->findById($this->user->id);

            list($d, $m, $y) = explode("/", $data["datebirth"]);
            $user->first_name = $data["first_name"];
            $user->last_name = $data["last_name"];
            $user->genre = $data["genre"];
            $user->datebirth = "{$y}-{$m}-{$d}";
            $user->document = preg_replace("/[^0-9]/", "", $data["document"]);

            /**
             * pt-br: Existe image avatar
             */
            if(!empty($_FILES["photo"])){
                $file = $_FILES["photo"];
                $upload = new Upload();

                /**
                 * pt-br: Caso tenha um avatar registrado
                 */
                if($this->user->photo()){
                    (new Thumb())->flush("storage/{$this->user->photo}");
                    $upload->remove("storage/{$this->user->photo}");
                }

                if(!$user->photo = $upload->image($file, "{$user->first_name} {$user->last_name} " . time(), 360)){
                    $json["message"] = $upload->message()->before("Ooopss {$this->user->first_name} ")->after(".")->render();
                    echo json_encode($json);
                    return;
                }
            }

            if(!empty($data['password'])){
                if(empty($data["password_re"]) || $data["password"] != $data["password_re"]){
                    $json["message"] = $this->message->warning("Para alteração de senha, as duas deve ser idênticas!")->render();
                    echo json_encode($json);
                    return;
                }

                $user->password = $data["password"];
            }

            if(!$user->save()){
                $json["message"] = $user->message()->render();
                echo json_encode($json);
                return;
            }


            $json["message"] = $this->message->success("Atualizações realizadas com sucesso, {$this->user->first_name}.")->render();
            echo json_encode($json);
            return;
        }

        $head = $this->seo->render(
            "Perfil de usuário",
            CONF_SITE_DESC,
            url("/"),
            theme('/assets/image/logo/logo-cd-vertical-mg.png'),
            false
        );

        echo $this->view->render("profile", [
            "head" => $head,
            "user" => $this->user,
            "photo" => ($this->user->photo() ?
                image($this->user->photo, 360, 360) :
                theme("/assets/images/avatar.jpg", CONF_VIEW_THEME_APP))
        ]);
    }

    /**
     * APP LOGOUT
     * pt-br: Desloga usuário logado.
     * view: login
     */
    public function logout()
    {
        (new Message())->warning("Você saiu com sucesso, " . Auth::user()->first_name . ". Volte logo!")->flash();

        Auth::logout();
        redirect("/entrar");
    }

    /**
     * APP PRODUCTS
     * pt-br: Lista todos produtos do site
     * view: products
     */
    public function products(?array $data): void
    {
        $head = $this->seo->render(
            "Meus produtos",
            CONF_SITE_DESC,
            url("/"),
            theme('/assets/image/logo/logo-cd-vertical-mg.png'),
            false
        );

        $categories = (new Category())
            ->find("type = :type", "type=product", "id, title")
            ->order("title")
            ->fetch(true);



        echo $this->view->render("products", [
            "head" => $head,
            "type" => "product",
            "categories" => $categories,
            "products" => (new Product())->filter(($data ?? null)),
            "filter" => (object)[
                "status" => ($data['status'] ?? null),
                "category" => ($data['category'] ?? null)
            ]
        ]);
    }

    /**
     * @param array|null $data - [READ OR UPDATE]
     * pt-br: Mostra dados do produto
     * view: product
     */
    public function product(?array $data): void
    {
        if(!empty($data["update"])){

            $product = (new Product())
                ->find("id = :id", "id={$data["product"]}")
                ->fetch();

            /** description pt-br: Erro de atualização da fatura. */
            if(!$product){
                $json["message"] = $this->message->error("Não foi possível atualizar o produto {$product->name}")->render();
                echo json_encode($json);
                return;
            }

            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $product->title = $data["name"];
            $product->content = $data["description"];
            $product->price = $data["value"];
            $product->category = $data["category"];
            $product->status = $data["status"];

            if(!$product->save()){
                $json["message"] = $product->message()->before("Oooops! ")->after(", {$product->name}")->render();
                echo json_encode($json);
                return;
            }


            $json["message"] = $this->message->success("Pronto, {$this->user->first_name}! :) produto atualizado com sucesso!")->render();
            echo json_encode($json);
            return;

        }elseif (!empty($data["create"])){
            $json["message"] = $data["create"];
            echo json_encode($json);
            return;
        }


        if($data["product"]){
            $product = (new Product())->findProducts("id = :id",
                "id={$data["product"]}")
                ->fetch();

            if(!$product){
                $this->message->error("Oooppsss! Ocorreu algum erro ao tentar acessar!")->flash();
                redirect("/app");
            }
        }


        $head = $this->seo->render(
            ($product->name ? $product->name : "Novo produto"),
            CONF_SITE_DESC,
            url("/"),
            theme('/assets/image/logo/logo-cd-vertical-mg.png'),
            false
        );


        echo $this->view->render("product", [
            "head" => $head,
            "product" => $product,
            "categories" => (new Category())
                ->find("type = :type", "type=product")
                ->order("title")
                ->fetch(true)
        ]);

    }
}