<?php


namespace Source\Models;


use Source\Core\Model;

class Newslatter extends Model
{
    /**
     * Newslatter constructor.
     */
    public function __construct()
    {
        parent::__construct("newslatter", ["id"], ["name", "email"]);
    }

    public function bootstrap(string $name, string $email): Newslatter
    {
        $this->name = $name;
        $this->email = $email;
        return $this;
    }

    /**
     * @param $email
     * @param string $columns
     * @return Newslatter|null
     * pt-br: busca newslatter por e-mail
     */
    public function findByEmail($email, string $columns = "*"): ?Newslatter
    {
        $find = $this->find("email = :email", "email={$email}", $columns);
        return $find->fetch();
    }

    public function save(): bool
    {
        if(!$this->required()){
            $this->message->warning("Existem dados cadastrais obrigatórios!! ");
            return false;
        }

        if(!is_email($this->email)){
            $this->message->warning("O e-mail informado não é valido!!!");
            return false;
        }

        if($this->find("email = :e AND id != :i", "e={$this->email}&i={$this->id}", "id")->fetch()){
            $this->message->warning("O email informado já foi cadastrado");
            return false;
        }

        return parent::save();
    }
}