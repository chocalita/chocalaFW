<?php
require_once('ChocalaErrors.php');
/**
 * Description of ChocalaException
 *
 * @author ypra
 */
class ChocalaException extends Exception
{

    public function __construct($message)
    {
        parent::__construct($message);
    }

}