<?php
require_once('IImage.php');
require_once('ImageMimeTypes.php');
/**
 *
 * @author ypra
 */
class Image implements IImage
{

    /**
     *
     * @var string
     */
    private $fileExtension = '';

    /**
     *
     * @var resource
     */
    private $image = null;

    /**
     *
     * @var string
     */
    private $mimeExtension = '';

    /**
     *
     * @var string
     */
    private $mimeType = '';

    /**
     *
     * @var string
     */
    private $name = '';

    /**
     *
     * @var int
     */
    private $originalX = 0;

    /**
     *
     * @var int
     */
    private $originalY = 0;

    /**
     *
     * @var int
     */
    private $size = 0;

    /**
     *
     * @var string
     */
    private $tempName = '';

    private $imagenes=null;

    /**
     *
     * @return string
     */
    public function fileExtension()
    {
        return $this->fileExtension;
    }

    /**
     *
     * @param File $file
     */
    public function __construct($file)
    {
        $this->name = $file['name'];
        $this->mimeType = $file['type'];
        $this->tempName = $file['tmp_name'];
        $this->size = $file['size'];
        $this->mimeExtension = ImageMimeTypes::mimeExtensionFrom($this
            ->mimeType);
        $this->fileExtension = self::fileExtensionFrom($this->name);
        define('NAMETHUMB', $this->tempName.'oimg');
        define('NAMERESIZE', $this->tempName.'oimg');
        $tamanios = getimagesize($this->tempName);
        $this->originalX = $tamanios[0];
        $this->originalY = $tamanios[1];
    }

    /**
     *
     * @param string $filename
     * @return string
     */
    public static function fileExtensionFrom($filename)
    {
        $extension = '';
        $partes = explode('.', $filename);
        if(sizeof($partes) > 0){
            $extension = $partes[sizeof($partes)-1];
        }
        return $extension;
    }

    /**
     *
     * @return resource
     */
    public function image()
    {
        if($this->image === null){
            switch($this->mimeExtension){
                case ImageMimeTypes::BMP_EXTENSION:
                    $this->image = imagecreatefromwbmp($this->tempName);
                    break;
                case ImageMimeTypes::GIF_EXTENSION:
                    $this->image = imagecreatefromgif($this->tempName);
                    break;
                case ImageMimeTypes::JPG_EXTENSION:
                    $this->image = imagecreatefromjpeg($this->tempName);
                    break;
                case ImageMimeTypes::PNG_EXTENSION:
                    $this->image = imagecreatefrompng($this->tempName);
                    break;
                default:
                    $this->image = imagecreatefromgif($this->tempName);
                    break;
            }
        }
        return $this->image;
    }

    /**
     *
     * @param string $filename
     * @param resource $resource
     * @return void
     */
    public function saveAs($filename, $resource = null)
    {
        if($resource === null){
            $resource = $this->image();
        }
        //$filename.= '.'.$this->fileExtension();
        switch($this->mimeExtension){
            case ImageMimeTypes::BMP_EXTENSION:
                imagewbmp($resource, $filename);
                break;
            case ImageMimeTypes::GIF_EXTENSION:
                imagegif($resource, $filename);
                break;
            case ImageMimeTypes::JPG_EXTENSION:
                imagejpeg($resource, $filename);
                break;
            case ImageMimeTypes::PNG_EXTENSION:
                imagepng($resource, $filename);
                break;
            default:
                imagegif($resource, $filename);
                break;
        }
    }

    /**
     *
     * @param string $filename
     * @param int $maxSize
     * @return void
     */
    public function saveResizeMax($filename, $maxSize = 0)
    {
        $img = $this->image();
        $imgSave = $img;
        if(($this->originalX > $maxSize || $this->originalY > $maxSize)
                && $maxSize > 0){
            $maxLOf = $maxSize;
            $frOf = 1;
            if($this->originalX > $this->originalY){
                $frOf = $maxLOf / $this->originalX;
            }else{
                $frOf = $maxLOf / $this->originalY;
            }
            $xImgOf = $this->originalX * $frOf;
            $yImgOf = $this->originalY * $frOf;
            $imgOf = imagecreatetruecolor($xImgOf, $yImgOf);
            imagecopyresampled($imgOf, $img, 0, 0, 0, 0, $xImgOf, $yImgOf,
            $this->originalX, $this->originalY);
            $imgSave = $imgOf;
        }
        $this->saveAs($filename, $imgSave);
    }

    //TODO review the after code content
    /**
     *
     * @param int $newSize
     * @return string
     */
    public function cropTo($newSize)
    {
        $img = $this->image();
        $maxSize = $newSize;
        $fr = 1;
        if($this->originalSizeX < $this->originalSizeY){
            $fr = $maxSize/$this->originalSizeX;
        }else{
            $fr = $maxSize/$this->originalSizeY;
        }
        $xSig = $this->originalSizeX * $fr;
        $ySig = $this->originalSizeY * $fr;
        if($xSig > ($maxSize-1)){
            $xCrop = round(($xSig - $maxSize) / 2);
        }
        if($ySig >($maxSize-1)){
            $yCrop = round(($ySig - $maxSize) / 2);
        }
        $xIni = $xCrop;
        $yIni = $yCrop;
        $xFin = $xSig-$xCrop;
        $yFin = $ySig-$yCrop;
        $xIniOri = $xIni/$fr;
        $yIniOri = $yIni/$fr;
        $xFinOri = $xFin/$fr;
        $yFinOri = $yFin/$fr;
        $thumb = imagecreatetruecolor($xFin-$xIni, $yFin-$yIni);
        imagecopyresampled($thumb, $img, 0, 0, $xIniOri, $yIniOri, $xFin, $yFin, $xFinOri,$yFinOri);
        switch($this->mimeExtension){
            case ImageMimeTypes::BMP_EXTENSION:
                imagewbmp($thumb, NAMETHUMB);
                break;
            case ImageMimeTypes::GIF_EXTENSION:
                imagegif($thumb, NAMETHUMB);
                break;
            case ImageMimeTypes::JPG_EXTENSION:
                imagejpeg($thumb, NAMETHUMB);
                break;
            case ImageMimeTypes::PNG_EXTENSION:
                imagepng($thumb, NAMETHUMB);
                break;
            default:
                imagegif($thumb, NAMETHUMB);
                break;
        }
        //todo simplyficate the get of image's content
        $fp = fopen(NAMETHUMB, "rb");
        $tthumb = fread($fp, filesize(NAMETHUMB));
        fclose($fp);
        @unlink(NAMETHUMB);
        return $tthumb;
    }

    function resizeTo($newTamanio)
    {
        $img=$this->image();
        $maxSize=$newTamanio;
        $needResize=true;
        $maxLOf=$newTamanio;
        $frOf=1;
        if($this->originalSizeX > $this->originalSizeY){
            $frOf=$maxLOf/$this->originalSizeX;
        }else{
            $frOf=$maxLOf/$this->originalSizeY;
        }
        $xImgOf=$this->originalSizeX*$frOf;
        $yImgOf=$this->originalSizeY*$frOf;
        $imgOf = imagecreatetruecolor($xImgOf, $yImgOf);
        imagecopyresampled($imgOf, $img, 0, 0, 0, 0, $xImgOf,$yImgOf, $this->originalSizeX,$this->originalSizeY);
        switch($this->mimeExtension) {
            case ImageMimeTypes::BMP_EXTENSION:
                imagewbmp($imgOf, NAMERESIZE);
                break;
            case ImageMimeTypes::GIF_EXTENSION:
                imagegif($imgOf, NAMERESIZE);
                break;
            case ImageMimeTypes::JPG_EXTENSION:
                imagejpeg($imgOf, NAMERESIZE);
                break;
            case ImageMimeTypes::PNG_EXTENSION:
                imagepng($imgOf, NAMERESIZE);
            break;
            default:
                imagegif($imgOf, NAMERESIZE);
                break;
        }
        $fp = fopen(NAMERESIZE, "rb");
        $tImgOf = fread($fp, filesize(NAMERESIZE));
        fclose($fp);
        @unlink(NAMERESIZE);
        return $tImgOf;
    }

    public function putInFileResize($dir, $fileName, $newTamanio=0)
    {
        $img = $this->image();
        $maxSize = $newTamanio;
        $needResize = true;
        $maxLOf = $newTamanio;
        $frOf = 1;
        if($this->originalSizeX > $this->originalSizeY){
            $frOf=$maxLOf/$this->originalSizeX;
        }else{
            $frOf=$maxLOf/$this->originalSizeY;
        }
        $xImgOf=$this->originalSizeX*$frOf;
        $yImgOf=$this->originalSizeY*$frOf;
        $imgOf = imagecreatetruecolor($xImgOf, $yImgOf);
        imagecopyresampled($imgOf, $img, 0, 0, 0, 0, $xImgOf,$yImgOf,
                $this->originalSizeX,$this->originalSizeY);
        switch($this->mimeExtension) {
            case ImageMimeTypes::BMP_EXTENSION:
                imagewbmp($imgOf, NAMERESIZE);
                break;
            case ImageMimeTypes::GIF_EXTENSION:
                imagegif($imgOf, NAMERESIZE);
                break;
            case ImageMimeTypes::JPG_EXTENSION:
                imagejpeg($imgOf, NAMERESIZE);
                break;
            case ImageMimeTypes::PNG_EXTENSION:
                imagepng($imgOf, NAMERESIZE);
                break;
            default:
                imagegif($imgOf, NAMERESIZE);
                break;
        }
        file_put_contents($dir.$fileName,file_get_contents(NAMERESIZE));
        @unlink(NAMERESIZE);
    }

}