<?php


namespace Source\Models\faq;


use Source\Core\Model;

class Channel extends Model {

    /**
     * Channel constructor.
     */
    public function __construct()
    {
        parent::__construct("faq_channels", ["id"], ["channel", "description"]);
    }

    /**
     * @return Question
     * pt-br: retorna todas questões do canal ativo
     */
    public function questions(): Question
    {
        return (new Question())->find("channel_id = :id", "id={$this->id}");
    }
}