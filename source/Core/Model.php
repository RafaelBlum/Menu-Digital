<?php


namespace Source\Core;

use Source\Models\User;
use Source\Supports\Message;
use Source\Core\Connect;

/**
 * Class Model
 * description ::
 * (1) - parametros [data, fail, message]
 * (2) - construtor [inicia Message]
 * (3) - method get [data, fail, message]
 * (4) - methods [create, read, delete, safe, filter]
 */
abstract class Model
{
    /**@var object|null*/
    protected $data;

    /**@var \PDOException|null*/
    protected $fail;

    /**@var Message|null*/
    protected $message;

    /**@var string*/
    protected $query;

    /**@var string*/
    protected $params;

    /**@var string*/
    protected $order;

    /**@var int*/
    protected $limit;

    /**@var int*/
    protected $offset;

    /** @var string $entity database table */
    protected static $entity;

    /** @var array $protected no update or create */
    protected static $protected;

    /** @var array $entity database table */
    protected static $required;


    /**
     * Model constructor.
     * @param string $entity
     * @param array $protected
     * @param array $required
     */
    public function __construct(string $entity, array $protected, array $required)
    {
        self::$entity = $entity;
        self::$protected = array_merge($protected, ['created_at', "updated_at"]);
        self::$required = $required;
        $this->message = new Message();
    }

    /**
     * @return object|null
     */
    public function data(): ?object
    {
        return $this->data;
    }

    /**
     * @return \PDOException
     */
    public function fail(): ?\PDOException
    {
        return $this->fail;
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        if(empty($this->data)){
            $this->data = new \stdClass();
        }
        $this->data->$name = $value;
    }

    /**
     * @param $name
     * @return |null
     */
    public function __get($name)
    {
        return ($this->data->$name ?? null);
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->data->$name);
    }

    /**
     * @return Message|null
     */
    public function message(): ?Message
    {
        return $this->message;
    }

    /**
     * @param string|null $terms para definir a busca se toda ou com termos de condiçao
     * @param string|null $params  se passado os terms, os params ficam salvos no arrays para utilizar depois.
     * @param string $columns
     * @return Model|mixed
     *
     * method find concatena com outros metodos ->order("id")->limit("6") ...etc
     *
     */
    public function find(?string $terms = null, ?string $params = null, string $columns = "*")
    {
        if($terms){

            $this->query = "SELECT {$columns} FROM " .static::$entity. " WHERE {$terms}";
            parse_str($params, $this->params);

            return $this;
        }
        $this->query = "SELECT {$columns} FROM " .static::$entity;
        return $this;
    }

    /**
     * @param int $id
     * @param string $columns
     * @return Model|null
     */
    public function findById(int $id, string $columns = "*"): ?Model
    {
        $find =  $this->find("id = :id", "id={$id}", $columns);
        return $find->fetch();
    }

    /**
     * @param string $collumnOrder
     * @return Model
     */
    public function order(string $collumnOrder): Model
    {
        $this->order = " ORDER BY {$collumnOrder}";
        return $this;
    }

    /**
     * @param int $limit
     * @return Model
     */
    public function limit(int $limit): Model
    {
        $this->limit = " LIMIT {$limit}";
        return $this;
    }

    /**
     * @param int $offset
     * @return Model
     */
    public function offset(int $offset): Model
    {
        $this->offset = " OFFSET {$offset}";
        return $this;
    }

    /**
     * @param bool $all
     * @return array|mixed|null
     */
    public function fetch(bool $all = false)
    {
        try{
            $stmt = Connect::getInstance()->prepare($this->query . $this->order . $this->limit . $this->offset);
            $stmt->execute($this->params);

            if(!$stmt->rowCount()){
                return null;
            }

            if($all){
                return $stmt->fetchAll(\PDO::FETCH_CLASS, static::class);
            }

            return $stmt->fetchObject(static::class);
        }catch (\PDOException $exception){
            $this->fail = $exception;
            return null;
        }
    }

    /**
     * @param string $key
     * @return int
     */
    public function count(string $key = "id"): int
    {
        $stmt = Connect::getInstance()->prepare($this->query);
        $stmt->execute($this->params);
        return $stmt->rowCount();
    }

    /**
     * @param array $data
     * @param string $terms
     * @param string $params
     * @return int|null
     */
    protected function update(array $data, string $terms, string $params): ?int
    {
        try {
            $dateSet = [];
            foreach ($data as $bind => $value) {
                $dateSet[] = "{$bind} = :{$bind}";
            }
            $dateSet = implode(", ", $dateSet);
            parse_str($params, $params);

            $stmt = Connect::getInstance()->prepare("UPDATE " . static::$entity . " SET {$dateSet} WHERE {$terms}");
            $stmt->execute($this->filter(array_merge($data, $params)));
            return ($stmt->rowCount() ?? 1);
        } catch (\PDOException $exception) {
            $this->fail = $exception;
            return null;
        }
    }

    /**
     * @return bool
     */
    public function save(): bool
    {
        /** valid all input*/
        if(!$this->required()){
            $this->message->warning("Preencha todos os campos obrigatórios!");
            return false;
        }

        /** update */
        if(!empty($this->id)){
            $id = $this->id;
            $this->update($this->safe(), "id = :id", "id={$id}");

            if($this->fail()){
                $this->message->error("Erro ao tentar atualizar!");
                return false;
            }
        }

        /** create */
        if(empty($this->id)){

            $id = $this->create($this->safe());

            if($this->fail()){

                $this->message->error("Erro ao tentar criar!");
                return false;
            }
        }

        /** Return data object
         * pt-br: Retorna o objeto e seu dados
         */
        $this->data = $this->findById($id)->data();
        return true;
    }

    /**
     * @return int
     * pt-br: busca a soma maxima (total) de ID´s + 1
     */
    public function lastId(): int
    {
        return \Source\Core\Connect::getInstance()->query("SELECT MAX(id) as maxId FROM posts")->fetch()->maxId + 1;
    }

    /**
     * @param array $data
     * @return int|null
     */
    protected function create(array $data): ?int
    {
        try {
            $columns = implode(", ", array_keys($data));
            $values = ":". implode(", :", array_keys($data));

            $stmt = Connect::getInstance()->prepare("INSERT INTO " . static::$entity . " ({$columns}) VALUES ({$values})");

            $stmt->execute($this->filter($data));

            return Connect::getInstance()->lastInsertId();
        }catch (\PDOException $exception){
            $this->fail = $exception;
            return null;
        }
    }

    /**
     * EXECUTE RECEBE UM ARRAY, CONVERTER ID PARA ARRAY PARSE_STR()
     * @param string $entity
     * @param string $terms
     * @param string $params
     * @return int|null
     */
    public function delete(string $terms, ?string $params): bool
    {
        try{
            $stmt = Connect::getInstance()->prepare("DELETE FROM " . static::$entity . " WHERE {$terms}");

            if($params){
                parse_str($params, $params);
                $stmt->execute($params);
                return true;
            }

            $stmt->execute();

            return true;
        }catch (\PDOException $exception){
            $this->fail = $exception;
            return false;
        }
    }

    /**
     * @return bool
     */
    public function destroy(): bool
    {
//        dd(get_class($this));
        if(empty($this->id)){
            return false;
        }

        $destroy = $this->delete("id = :id", "id={$this->id}");
        return $destroy;
    }

    /**
     * Elimina os campos que nao podem ser alterados, com por ex. ID.
     * @return array|null
     */
    protected function safe(): ?array
    {
        $safe = (array)$this->data;
        foreach (static::$protected as $unset) {
            unset($safe[$unset]);
        }
        return $safe;
    }

    /**
     * Faz a manutenção do dados antes de persistir
     * @param array $data
     * @return array|null
     */
    private function filter(array $data): ?array
    {
        $filter = [];
        foreach ($data as $k => $value){
            $filter[$k] = (is_null($value) ? null : filter_var($value, FILTER_DEFAULT));
        }
        return $filter;
    }

    /**
     * @return bool
     */
    protected function required(): bool
    {
        $data = (array)$this->data();

        foreach (static::$required as $field){
            if(empty($data[$field])){
                return false;
            }
        }
        return true;
    }
}