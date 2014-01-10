<?php
/**
 * Description of ImageMimeTypes
 *
 * @author ypra
 */
class ImageMimeTypes
{

    const BMP_MIME_TYPE = 'image/bmp';

    const GIF_MIME_TYPE = 'image/gif';

    const JPEG_MIME_TYPE = 'image/jpeg';

    const JPG_MIME_TYPE = 'image/jpg';

    const PJPEG_MIME_TYPE = 'image/pjpeg';

    const PNG_MIME_TYPE = 'image/png';

    const BMP_EXTENSION = 'bmp';

    const GIF_EXTENSION = 'gif';

    const JPG_EXTENSION = 'jpg';

    const PNG_EXTENSION = 'png';

    public static function enumMimetypes()
    {
    	return array(
            self::BMP_MIME_TYPE => self::BMP_EXTENSION,
            self::GIF_MIME_TYPE => self::GIF_EXTENSION,
            self::JPEG_MIME_TYPE => self::JPG_EXTENSION,
            self::JPG_MIME_TYPE => self::JPG_EXTENSION,
            self::PJPEG_MIME_TYPE => self::JPG_EXTENSION,
            self::PNG_MIME_TYPE => self::PNG_EXTENSION
        );
    }

    public static function mimeExtensionFrom($mimeType)
    {
        $mimes = self::enumMimetypes();
        return $mimes[$mimeType];
    }

}