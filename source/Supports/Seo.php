<?php


namespace Source\Supports;


use CoffeeCode\Optimizer\Optimizer;

/**
 * Optimizer é um componente de otimização de sites para motores de buscas e redes sociais. Simplificado e direto
 * ao ponto ele cria as tags e links necessários em sua head para o melhor resultado de busca e compartilhamento.
*/
class Seo
{
    /**@var Optimizer*/
    private $optimizer;

    /**
     * Seo constructor.
     * @param string $schema  : Tipo de optimização e por padrão é article
    */
    public function __construct(string $schema = "article")
    {
        $this->optimizer = new Optimizer();
        $this->optimizer->openGraph(
            CONF_SITE_NAME,
            CONF_SITE_LANG,
            $schema
        )->twitterCard(
            CONF_SOCIAL_TWITTER_CREATOR,
            CONF_SOCIAL_TWITTER_PUBLISHER,
            CONF_SITE_DOMAIN
        )->publisher(
            CONF_SOCIAL_FACEBOOK_PAGE,
            CONF_SOCIAL_FACEBOOK_AUTHOR
        )->facebook(
            CONF_SOCIAL_FACEBOOK_APP
        );
    }

    /**
     * @param $name
     * @return mixed
     * É method utilizado para ler dados de propriedades inacessíveis
     */
    public function __get($name)
    {
        $this->optimizer()->meta()->$name;
    }

    /**
     * @param string $title
     * @param string $description
     * @param string $url
     * @param string $image
     * @param bool $fallow
     * @return string
     */
    public function render(string $title, string $description, string $url, string $image, bool $fallow = true): string
    {
        return $this->optimizer->optimize($title, $description, $url, $image, $fallow)->render();
    }

    /**
     * @return Optimizer
     */
    public function optimizer(): Optimizer
    {
        return $this->optimizer;
    }

    /**
     * @param string|null $title
     * @param string|null $desc
     * @param string|null $url
     * @param string|null $image
     * @return object|null
     */
    public function data(string $title = null, string $desc = null, string $url = null, string $image = null)
    {
        return $this->optimizer->data($title, $desc, $url, $image);
    }

}