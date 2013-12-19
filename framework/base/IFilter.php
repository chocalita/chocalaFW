<?php
/**
 *
 * @author ypra
 */
interface IFilter
{

    public function beforeAction();

    public function afterAction();

    public function afterView();

}
?>