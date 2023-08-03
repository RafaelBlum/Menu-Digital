<?php


namespace Source\Models\faq;


use Source\Core\Model;

class Question extends Model
{
    /**
     * Question constructor.
     */
    public function __construct()
    {
        parent::__construct("faq_questions", ["id"], ["channel_id", "question", "response"]);
    }
}