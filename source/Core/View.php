<?php


namespace Source\Core;


use League\Plates\Engine;

/**
 * Class View
 * @package source\Core
 */
class View
{
    /**@var Engine*/
    private $engine;

    /**
     * View constructor.
     * @param string $path :: Podemos mudar path ou deixar padrão
     * @param string $ext  :: Extensão do arquivo
     */
    public function __construct($path =  CONF_VIEW_PATH, $ext = CONF_VIEW_EXT)
    {
        $this->engine = new Engine($path);
    }


    /**
     * @param string $name :: nome da pasta
     * @param string $path :: caminho até a pasta
     * @return View
     */
    public function path(string $name, string $path): View
    {
        $this->engine->addFolder($name, $path);
        return $this;
    }


    /**
     * @param string $templateName
     * @param string $data
     * @return string
     */
    public function render(string $templateName, array $data): string
    {
        return $this->engine->render($templateName, $data);
    }


    public function engine(): Engine
    {
        return $this->engine;
    }

}