<?php
/**
 * Description of RTEManager
 *
 * @author ypra
 */
class RTCManager
{

    const ALFA = 'alfa';

    const BETA = 'beta';

    public static function content($filename, $type=self::ALFA)
    {
        $content = file_get_contents(RTC_DIR.$filename.'.'.$type);
        return $content;
    }

    public static function updateContent($filename, $content, $type=self::BETA)
    {
        chmod(RTC_DIR.$filename.'.'.$type, 0777);
        file_put_contents(RTC_DIR.$filename.'.'.$type, $content);

    }

}