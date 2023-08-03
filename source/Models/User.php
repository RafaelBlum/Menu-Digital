<?php


namespace Source\Models;

use Source\Core\Model;

class User extends Model
{

    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct("users", ["id"], ["first_name", "last_name", "email", "password"]);
    }

    /**
     * @param string $firstname
     * @param string $lastname
     * @param string $email
     * @param string $password
     * @param string|null $document
     * @return User|null
     * pt-br: Metodo add dados ao objeto usuário
     */
    public function bootstrap(string $firstname, string $lastname, string $email, string $password, string $document = null): ?User
    {
        $this->first_name = $firstname;
        $this->last_name = $lastname;
        $this->email = $email;
        $this->password = $password;
        $this->document = $document;
        return $this;
    }

    /**
     * @param $email
     * @param string $columns
     * @return User|null
     * pt-br: Busca usuário por e-mail
     */
    public function findByEmail($email, string $columns = "*"): ?User
    {
        $find = $this->find("email = :email", "email={$email}", $columns);
        return $find->fetch();
    }


    public function fullName(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * @return string|null
     * pt-br: retorna imagem do usuário se existir
     */
    public function photo(): ?string
    {
        if($this->photo && file_exists(__DIR__ . "/../../". CONF_UPLOAD_DIR ."/{$this->photo}")){
            return $this->photo;
        }

        return null;
    }

    /**
     * @return bool - [CREATE OR UPDATE]
     * pt-br: (polimorfismo) metodo modificado, verifica dados e salva ou cria usuário
     */
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

        if(!is_password($this->password)){
            $min = CONF_PASSWORD_MIN_LEN;
            $max = CONF_PASSWORD_MAX_LEN;
            $this->message->warning("A senha deve ter entre {$min} e {$max} de caracteres");
            return false;
        }else{
            $this->password = password($this->password);
        }

        /** User Update */
        if(!empty($this->id)){
            $user_id = $this->id;

            if($this->find("email = :e AND id != :i", "e={$this->email}&i={$this->id}", "id")->fetch()){
                $this->message->warning("O email informado já foi cadastrado");
                return false;
            }

            $this->update($this->safe(), "id = :id", "id={$user_id}");

            if($this->fail()){
                $this->message->error("Erro ao atualizar o usuário!");
                return false;
            }
        }

        /** User create */
        if(empty($this->id)){

            if($this->findByEmail($this->email, "id")){
                $this->message->warning("O email informado já existe!");
                return false;
            }


            $user_id = $this->create($this->safe());

            if($this->fail()){
                $this->message->error("Erro ao cadastrar, verifique seus dados!!");
                return false;
            }
        }

        $this->data = ($this->findById($user_id))->data();
        return true;
    }

    /**
     * @param string $words
     * @param string $columns
     * @return array|null
     * pt-br: busca todos usuário por palavra chave
     */
    public function search(string $words, string $columns = "*"): ?array
    {
        $search = $this->read("SELECT {$columns} FROM ". static::$entity ." WHERE first_name LIKE :words", "words=%{$words}%");

        if($this->fail() || !$search->rowCount()){
            return null;
        }

        return $search->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
    }

}