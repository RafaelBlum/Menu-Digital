<?php


namespace Source\Models;


use Source\Core\Model;

class Product extends Model
{

    /**
     * Product constructor
     */
    public function __construct()
    {
        parent::__construct("products", ["id"], ["title", "uri", "subtitle", "content", "price"]);
    }

    public function findProducts(?string $terms = null, ?string $params = null, string $columns = "*"): Model
    {
        $terms = "status = :status AND post_at <= NOW()" . ($terms ? " AND {$terms}" : "");
        $params = "status=post" . ($params ? "&{$params}" : "");

        return parent::find($terms, $params, $columns);
    }

    /**
     * @param string $uri
     * @param string $columns
     * @return Product|null
     * pt-br: Busca todos registros de produtos pela URI
     */
    public function findByUri(string $uri, string $columns = "*"): ?Product
    {
        $find = $this->find("uri = :uri", "uri={$uri}", $columns);
        return $find->fetch();
    }

    /**
     * @return null|Category
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
     * @param array|null $filter
     * @param int|null $limit
     * @return array|null
     * pt-br: Filtra produtos por categoria e/ou status e (opcional) limitação de registros
     */
    public function filter(?array $filter, ?int $limit = null): ?array
    {
        $category = (!empty($filter["category"]) && $filter["category"] != "all" ? "category = '{$filter["category"]}'" : null);
        $status =   (!empty($filter["status"]) && $filter["status"] == "post" ? " AND status = 'post'" :
                    (!empty($filter["status"]) && $filter["status"] == "draft" ? " AND status = 'draft'" :
                    (!empty($filter["status"]) && $filter["status"] == "trash" ? " AND status = 'trash'" :
                        null)
                    ));

        /**
         * pt-br: query de filtro. caso filtre por categoria/status ou somente status ajusta query
        */
        $prods = $this->find(($category != null ? $category ."". $status : str_replace("AND ", "", $status)))
            ->order("title");

        if($limit){
            $prods->limit($limit);
        }

        return $prods->fetch(true);
    }

    /**
     * @return bool
     * pt-br: Realizando busca por URI para garantir que seja unica.
     */
    public function save(): bool
    {
        $checkUri = (new Product())->find("uri = :uri AND id != :id", "uri={$this->uri}&id={$this->id}");

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