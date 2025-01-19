<?php

namespace nguyenanhung\Platforms\WebBuilderSDK\WebBuilderHelper;

/**
 * Class Filter
 *
 * @package   nguyenanhung\Platforms\WebBuilderSDK\WebBuilderHelper
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
class Filter extends \nguyenanhung\Libraries\Filtered\Filter
{
    /**
     * Function exceptionMissingInputParams
     *
     * @return string
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 07/03/2023 04:30
     */
    public static function exceptionMissingInputParams()
    {
        $url = 'https://go.tramtro.com/esdmv96z';
        $errorMsg = 'Invalid or Missing Require Params Page Meta';
        return $errorMsg . ' - see: <a rel="nofollow" target="_blank" href="' . $url . '">' . $url . '</a>';
    }
}
