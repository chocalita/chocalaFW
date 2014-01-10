<?php
/**
 * Description of ChocalaVars
 *
 * @author ypra
 */
abstract class ChocalaVars
{

    const ADMIN_MODULE = 'admin';

    const NO_LAYOUT = 'noLayout';

    const NO_ROUTE = 'noRoute';

    const AJAX_TEMPLATE = 'ajaxTemplate';

    const AJAX_TEMPLATE_COMBO = 'ajaxTemplateCombo';

    const OFF = 'Off';

    const ON = 'On';

    const FRAMEWORK_NAME = 'Chocala';

    /**
     * Verify if the params is a posible boolean true value, based in the
     * posible true strings. ON | YES | SI
     * @param string $value
     * @return boolean
     */
    public static function asBoolean($value)
    {
        $value = strtoupper(trim($value));
        return ($value===true || $value==='ON' || $value==='YES' ||
                $value==='SI');
    }

}