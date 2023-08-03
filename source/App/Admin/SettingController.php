<?php


namespace Source\App\Admin;


use Source\Models\Setting;

class SettingController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }


    public function home()
    {
        $head = $this->seo->render(
            CONF_SITE_NAME . " | Configuraçoes",
            CONF_SITE_DESC,
            url("/configuracoes"),
            theme("/assets/images/image.jpg", CONF_VIEW_ADMIN),
            false
        );

        echo $this->view->render("widgets/dash/configs", [
            "app" => "dash/configuracoes",
            "confContent" => "Obtenha todo controle das configurações do seu sistema aqui...",
            "head" => $head,
            "settings" => (new Setting())->find()->fetch()
        ]);
        
    }

    public function settings(array $data): void
    {
        $about = $data["about"];
        $description = $data["description"];
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
        $settings = (new Setting())->findById($data["set_id"]);

        if(!$settings){
            $this->message->warning("Ocorreu um erro ao tentar atualizar!")->flash();
            echo json_encode(["redirect"=>url("/admin/configuracoes")]);
            return;
        }

        $settings->project_name = $data["project_name"];
        $settings->view_price = $data["view_price"];
        $settings->description = str_replace(["{title}"], [$settings->project_name], $description);
        $settings->about = str_replace(["{title}"], [$settings->project_name], $about);
        $settings->video = $data["video"];
        $settings->phone = preg_replace("/[^0-9]/", "", $data["phone"]);
//        $settings->logo = "";

        if(!$settings->save()){
            $json["message"] = $settings->message()->render();
            echo json_encode($json);
            return;
        }

        $this->message->success("As configurações foram atualizadas com sucesso...")->flash();
        echo json_encode(["reload"=>true]);
        return;
    }

}