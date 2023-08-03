<?php


namespace Source\App\Admin;


use Source\Models\faq\Channel;
use Source\Models\faq\Question;
use Source\Supports\Pager;

class FaqController extends AdminController
{
    /**
     * FaqController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        /**
         * pt-br: Aqui pode ter condição de permissão de usuário pelo level. Caso não tenha o level adquado, redireciona.
        */
        if($this->user->level < 6){
            
        }
    }


    /**
     * @param array|null $data
     * pt-br: Retorna todos canais e perguntas
     * view: adm - widgets/faqs/home
     */
    public function home(?array $data): void
    {
        $channels = (new Channel())->find();

        $pager = new Pager(url("/admin/faq/home/"));
        $pager->pager($channels->count(), 6, (!empty($data["page"]) ? $data["page"] : 1));


        $head = $this->seo->render(
            CONF_SITE_NAME . " | FAQ",
            CONF_SITE_DESC,
            url("/admin"),
            theme("/assets/images/image.jpg", CONF_VIEW_ADMIN),
            false
        );

        echo $this->view->render("widgets/faqs/home", [
            "app" => "faq/home",
            "head" => $head,
            "channels" => $channels->order("channel")->limit($pager->limit())->offset($pager->offset())->fetch(true),
            "paginator" => $pager->render()
        ]);
        
    }


    /**
     * @param array|null $data
     * pt-br: Retorna o canal para o create, update e delete
     * view: adm - widgets/faqs/channel
     */
    public function channel(?array $data): void
    {
        /**
         * create
         */
        if(!empty($data["action"]) && $data["action"] == "create"){
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

            $channelCreate = new Channel();
            $channelCreate->channel = $data["channel"];
            $channelCreate->description = $data["description"];

            if(!$channelCreate->save()){
                $json["message"] = $channelCreate->message()->render();
                echo json_encode($json);
                return;
            }

            $this->message->success("Canal FAQ, criado com sucesso!!")->flash();
            echo json_encode(["redirect"=>url("/admin/faq/channel/{$channelCreate->id}")]);
            return;
        }

        /**
         * update
         */
        if(!empty($data["action"]) && $data["action"] == "update"){
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

            $channelEdit = (new Channel())->findById($data["channel_id"]);

            if(!$channelEdit){
                $this->message->error("Você tentou editar um canal FAQ que não existe ou foi removida!")->flash();
                echo json_encode(["redirect" => url("/admin/faq/home")]);
                return;
            }

            $channelEdit->channel = $data["channel"];
            $channelEdit->description = $data["description"];

            if(!$channelEdit->save()){
                $json["message"] = $channelEdit->message()->render();
                echo json_encode($json);
                return;
            }


            $json["message"] = $this->message->success("Canal FAQ, atualizado com sucesso!")->render();
            echo json_encode($json);
            return;
        }

        /**
         * delete
         */
        if(!empty($data["action"]) && $data["action"] == "delete"){
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $channelDelete = (new Channel())->findById($data["channel_id"]);

            if(!$channelDelete){
                $this->message->error("Você tentou deletar um canal FAQ que não existe ou foi removida!")->flash();
                echo json_encode(["redirect" => url("/admin/faq/home")]);
                return;
            }

            $channelDelete->destroy();
            $this->message->success("Canal FAQ removido com sucesso!")->flash();

            echo json_encode(["redirect"=> url("admin/faq/home")]);
            return;
        }

        /**
         * pt-br: Caso não exista um channel, é passado "$channelEdit = null",
         * caso contrario existe um channel_id em "data", então é feita um find.
        */
        $channelEdit = null;
        if(!empty($data["channel_id"])){
            $channelId = filter_var($data["channel_id"], FILTER_VALIDATE_INT);
            $channelEdit = (new Channel())->findById($channelId);
        }


        $head = $this->seo->render(
            CONF_SITE_NAME . " | " .($channelEdit ? "FAQ: {$channelEdit->channel}" : "FAQ: Novo Canal"),
            CONF_SITE_DESC,
            url("/admin"),
            theme("/assets/images/image.jpg", CONF_VIEW_ADMIN),
            false
        );

        echo $this->view->render("widgets/faqs/channel", [
            "app" => "faq/home",
            "head" => $head,
            "channel" => $channelEdit
        ]);
    }


    /**
     * @param array|null $data
     * pt-br: retorna a pergunta para create, update e delete
     * view: adm - widgets/faqs/question
     */
    public function question(?array $data): void
    {
        /**
         * create
         */
        if(!empty($data["action"]) && $data["action"] == "create"){
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

            $questionCreate = new Question();
            $questionCreate->channel_id = $data["channel_id"];

            $questionCreate->question = $data["question"];
            $questionCreate->response = $data["response"];
            $questionCreate->order_by = $data["order_by"];

            if(!$questionCreate->save()){
                $json["message"] = $questionCreate->message()->render();
                echo json_encode($json);
                return;
            }

            $this->message->success("Pergunta criado com sucesso!!")->flash();
            echo json_encode(["redirect"=>url("/admin/faq/question/{$questionCreate->channel_id}/{$questionCreate->id}")]);
            return;
        }

        /**
         * update
         */
        if(!empty($data["action"]) && $data["action"] == "update"){
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

            $questionEdit = (new Question())->findById($data["question_id"]);

            if(!$questionEdit){
                $this->message->error("Você tentou editar uma pergunta que não existe ou foi removida!")->flash();
                echo json_encode(["redirect" => url("/admin/faq/home")]);
                return;
            }
            $questionEdit->channel_id = $data["channel_id"];
            $questionEdit->question = $data["question"];
            $questionEdit->response = $data["response"];
            $questionEdit->order_by = $data["order_by"];

            if(!$questionEdit->save()){
                $json["message"] = $questionEdit->message()->render();
                echo json_encode($json);
                return;
            }


            $json["message"] = $this->message->success("Pergunta atualizada com sucesso!")->render();
            echo json_encode($json);
            return;
        }

        /**
         * delete
         */
        if(!empty($data["action"]) && $data["action"] == "delete"){
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $questionDelete = (new Question())->findById($data["question_id"]);

            if(!$questionDelete){
                $this->message->error("Você tentou deletar uma pergunta que não existe ou foi removida!")->flash();
                echo json_encode(["redirect" => url("/admin/faq/home")]);
                return;
            }

            $questionDelete->destroy();
            $this->message->success("Pergunta removida com sucesso!")->flash();

            echo json_encode(["redirect"=> url("admin/faq/home")]);
            return;
        }


        /**
         * pt-br: redenrização inicial
         */
        $channel = (new Channel())->findById($data["channel_id"]);
        $question = null;

        /**
         * pt-br: Verifica se o canal da pergunta existe realmente
        */
        if(!$channel){
            $this->message->warning("Você tentou gerenciar uma pergunta de um canal FAQ inxistente.")->flash();
            redirect("/admin/faq/home");
        }

        if(!empty($data["question_id"])){
            $questionId = filter_var($data["question_id"], FILTER_VALIDATE_INT);
            $question = (new Question())->findById($questionId);
        }


        $head = $this->seo->render(
            CONF_SITE_NAME . " | FAQ: Pergunta em {$channel->channel}",
            CONF_SITE_DESC,
            url("/admin"),
            theme("/assets/images/image.jpg", CONF_VIEW_ADMIN),
            false
        );

        echo $this->view->render("widgets/faqs/question", [
            "app" => "faq/home",
            "head" => $head,
            "channel" => $channel,
            "question" => $question
        ]);
    }

}