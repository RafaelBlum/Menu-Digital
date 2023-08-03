<?php


namespace Source\Models;


use Source\Core\Model;

/**
 * Class Post
 * @package Source\Models
 */
class Post extends Model
{
    /**
     * Post constructor
     */
    public function __construct()
    {
        parent::__construct("posts", ["id"], ["title", "uri", "subtitle", "content"]);
    }

    /**
     * @param string|null $terms
     * @param string|null $params
     * @param string $columns
     * @return Model
     * pt-br: (polimorfismo) Modificamos o find| Busca todos registros com data menor que atual e status post
     */
    public function findPost(?string $terms = null, ?string $params = null, string $columns = "*"): Model
    {
        $terms = "status = :status AND post_at <= NOW()" . ($terms ? " AND {$terms}" : "");
        $params = "status=post" . ($params ? "&{$params}" : "");

        return parent::find($terms, $params, $columns);
    }


    /**
     * @param string $uri
     * @param string $columns
     * @return Post|null
     * pt-br: Busca todos registros pela URI
     */
    public function findByUri(string $uri, string $columns = "*"): ?Post
    {
        $find = $this->find("uri = :uri", "uri={$uri}", $columns);
        return $find->fetch();
    }


    /**
     * @return User|null
     * pt-br: Busca o user relacionado ao post
     */
    public function user(): ?User
    {
        if($this->author)
        {
            return (new User())->findById($this->author);
        }

        return null;
    }

    /**
     * @return null|User
     */
    public function author(): ?User
    {
        if ($this->author) {
            return (new User())->findById($this->author);
        }
        return null;
    }


    /**
     * @return Category|null
     * pt-br: Busca a categoria relacionada ao post
     */
    public function category(): ?Category
    {
        if($this->category)
        {
            return (new Category())->findById($this->category);
        }

        return null;
    }

    /**
     * @return bool
     * pt-br: Realizando busca por URI para garantir que seja unica.
     */
    public function save(): bool
    {
        $checkUri = (new Post())->find("uri = :uri AND id != :id", "uri={$this->uri}&id={$this->id}");

        /**
         * pt-br: Se encontrar algum registro, modificamos a URI do post
         * pt-br: lastId retorna total de IDs + 1
        */
        if($checkUri->count()){
                $this->uri = "{$this->uri}-{$this->lastId()}";
        }
        return parent::save();
    }
}