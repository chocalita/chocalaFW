<?php
require_once('ISingleton.php');
/**
 * Singleton Registry Interface as variation of Singleton pattern
 * SINGLETON Pattern (SINGLETON REFACTORIZED)
 *
 * @author ypra
 */
interface ISingletonRegistry extends ISingleton
{
    public static function globalVars();

    public static function pageControl();

    public static function userControl();
}