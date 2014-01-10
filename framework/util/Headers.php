<?php
/**
 * Description of Headers
 *
 * @author ypra
 */
class Headers
{

    /**
     *
     * @var bool
     */
    private $allowCache = true;

    /**
     *
     * @var ContentType
     */
    private $contentType = ContentType::TYPE_HTML;

    /**
     *
     * @var ContentType
     */
    private $charset = ContentType::CHARSET_UTF8;

    /**
     * Single static instance from this class
     * @var Headers
     */
    private static $instance = null;

    /**
     * Returns a single instance from this class
     * @return Headers
     */
    public static function instance()
    {
        if(self::$instance == null){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct()
    {
    }

    /**
     *
     * @param bool $allow
     */
    public function changeAllowCacheTo($allow)
    {
        if(is_bool($allow)){
            $this->allowCache = $allow;
        }
    }

    /**
     *
     * @param ContentType $contentType
     * @param string $charset
     */
    public function changeContentTypeTo($contentType, $charset='')
    {
        $this->contentType = $contentType;
        $this->charset = $charset;
    }

    /**
     *
     * @return void
     */
    public function cacheInfo()
    {
       if(!$this->allowCache){
            header('Expires: Tue, 01 Jul 1980 23:59:00 GMT');
            header('Expires: 0', false);
            header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
            header('Cache-Control: no-store, no-cache, must-revalidate');
            header('Cache-Control: post-check=0, pre-check=0', false);
            header('Pragma: no-cache');
       }
    }

    /**
     *
     * @return void
     */
    public function contentTypeInfo()
    {
        $contentType = $this->contentType;
        if($this->charset != ''){
            $contentType.= '; charset='.$this->charset;
        }
        header('Content-Type: '.$contentType);
    }

    /**
     *
     * @return void
     */
    public function sendHeaders()
    {
        $this->contentTypeInfo();
        $this->cacheInfo();
    }

    /**
     * 
     * @param string $uri
     * @param boolean $perm . Indicate if the redirect is permanently
     */
    public function redirectTo($uri, $perm = true)
    {
        if(!headers_sent()){
            $type = $perm? '301 Moved Permanently': '302 Moved Temporarily';
            header($_SERVER['SERVER_PROTOCOL'].' '.$type);
            header('Location: '.$uri, true, $perm? 301: 302);
            exit();
        }
        exit('<meta http-equiv="refresh" content="0; url='.urldecode($uri)
                .'" />');
    }

}