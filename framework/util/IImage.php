<?php
/**
 *
 * @author ypra
 */
interface IImage
{

    public function image();

    public static function fileExtensionFrom($filename);

    public function saveAs($filename, $resource = null);

    public function saveResizeMax($filename, $maxTamanio=0);

}
?>