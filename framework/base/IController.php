<?php
/**
 *
 * @author ypra
 */
interface IController
{

    public function __construct();

    public function _init();
    
    public function render($content);
    
    public function renderView($view, $module);
    
    public function renderAsJSON();
        
    public function isRendered();

}