<?php


namespace Source\Models;


use Source\Core\Model;
use Source\Models\Post;
use Source\Models\Product;

class Category extends Model
{

    /**
     * Category constructor.
     */
    public function __construct()
       {
           parent::__construct("categories", ["id"], ["title", "description"]);
       }

    /**
     * @param string $uri
     * @param string $columns
     * @return Category|null
     * pt-br: Busca todas categorias pela URI
     */
    public function findByUri(string $uri, string $columns = "*"): ?Category
    {
        $find = $this->find("uri = :uri", "uri={$uri}", $columns);
        return $find->fetch();
    }


    /**
     * @param string $type
     * @return object
     * pt-br: Realiza a busca de posts ou products pela categoria selecionada. Retornando com metodo "count()" o total.
     */
    public function typeCategory(string $type): object
    {
        if($type == 'post'){
            return (new Post())->find("category = :id", "id={$this->id}");
        }

        return (new Product())->find("category = :id", "id={$this->id}");
    }

    /**
     * @return bool
     * pt-br: Realizando busca por URI para garantir que seja unica.
     */
    public function save(): bool
    {
        $checkUri = (new Category())->find("uri = :uri AND id != :id", "uri={$this->uri}&id={$this->id}");

        if ($checkUri->count()) {
            $this->uri = "{$this->uri}-{$this->lastId()}";
        }

        return parent::save();
    }
}