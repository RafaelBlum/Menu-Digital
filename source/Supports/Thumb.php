<?php


namespace Source\Supports;


use CoffeeCode\Cropper\Cropper;

class Thumb
{
    /*** @var Cropper */
    private $cropper;

    /*** @var string */
    private $uploads;

    /**
     * Thumb constructor.
     * @throws \Exception
     * Instancia Cropper com path cache,
     */
    public function __construct()
    {
        $this->cropper = new Cropper(CONF_IMAGE_CACHE, CONF_IMAGE_QUALITY['jpg'], CONF_IMAGE_QUALITY['png']);
        $this->uploads = CONF_UPLOAD_DIR;
    }


    /**
     * @param string $image
     * @param int $width
     * @param int|null $heigth
     * @return string
     * Create thumbnails de qualquer tamanho
     */
    public function make(string $image, int $width, int $heigth = null): string
    {
        return $this->cropper->make("{$this->uploads}/{$image}", $width, $heigth);
    }

    /**
     * @param string|null $image
     * Liberar o cache de um arquivo ou da pasta toda.
     */
    public function flush(string $image = null): void
    {
        if($image){
            $this->cropper->flush("{$this->uploads}/{$image}");
            return;
        }

        $this->cropper->flush();
        return;
    }

    public function cropper(): Cropper
    {
        return $this->cropper;
    }

}