<?php
/**
 * Description of WebAliasController
 * @author ypra
 */
abstract class WebAliasController extends WebController
{

    /**
     * Initialization of generic operations, configurations or steps for all 
     * methods of the controller class
     * @return void
     */
    public function _init()
    {
        $this->view = new WebAliasView($this->layout);
    }

}