<?php


namespace Source\Supports;


use CoffeeCode\Paginator\Paginator;

class Pager extends Paginator
{
    /**
     * Construct herdado de Paginator
     */
    public function __construct(string $link = null, string $title = null, array $first = null, array $last = null)
    {
        parent::__construct($link, $title, $first, $last);
    }


}