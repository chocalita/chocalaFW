<?php
/**
 * Description of SecurityFilter
 *
 * @author ypra
 */
class SecurityFilter extends ChocalaFilter
{

    public function beforeAction()
    {
        Lessc::ccompile(PUBLIC_DIR.'css/style.less', PUBLIC_DIR.'css/style.css');
    }

    public function afterAction()
    { 
    }

    public function afterView()
    {
    }

}