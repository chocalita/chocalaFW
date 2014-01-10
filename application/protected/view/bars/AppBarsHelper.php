<?php
/**
 * This class is a helper that scope the variables for the bar renderization
 *
 * @author ypra
 */
class AppBarsHelper extends BarView
{

    public function myBar()
    {
        $this->setVar('myName', 'Yecid');
    }

}