<?php
/**
 * Description of HttpManager
 *
 * @author chocalita
 */
class HttpManager
{

    const PUT_METHOD = 'PUT';

    const POST_METHOD = 'POST';

    const GET_METHOD = 'GET';

    const HEAD_METHOD = 'HEAD';

    const DELETE_METHOD = 'DELETE';

    const OPTIONS_METHOD = 'OPTIONS';

    const TRACE_METHOD = 'TRACE';

    const CONNECT_METHOD = 'CONNECT';

    const PATCH_METHOD = 'PATCH';

    /**
     * 
     * @return string
     */
    public static function requestMethod()
    {
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }

    /**
     * 
     */
    public static function responseAs404()
    {
        $code = 404;
        $title = "Not Found";
        $message = "The requested resource could not be found may be ".
                "available again in the future.";
        header($_SERVER['SERVER_PROTOCOL']. ' 404 Not Found');
        header("Status: $code $title");
        $_SERVER["REDIRECT_STATUS"] = $code;
        echo "<h1>$title</h1>";
        echo "<h3>$code</h3>";
        echo $message;
        exit();
    }

    /**
     * 
     */
    public static function responseAs405()
    {
        $code = 405;
        $title = "Method Not Allowed";
        $message = "The request was made of a resource using a request method ".
                "not supported by that resource";
        header($_SERVER['SERVER_PROTOCOL']. ' 403 Forbidden');
        header("Status: $code $title");
        $_SERVER["REDIRECT_STATUS"] = $code;
        echo "<h1>$title</h1>";
        echo "<h3>$code</h3>";
        echo $message;
        exit();
    }

}