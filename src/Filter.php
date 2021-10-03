<?php
/**
 * Project web-builder-helper
 * Created by PhpStorm
 * User: 713uk13m <dev@nguyenanhung.com>
 * Copyright: 713uk13m <dev@nguyenanhung.com>
 * Date: 09/09/2021
 * Time: 22:47
 */

namespace nguyenanhung\WebBuilderHelper;

/**
 * Class Filter
 *
 * @package   nguyenanhung\WebBuilderHelper
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
class Filter implements ProjectInterface
{
    use Version;

    /**
     * Function filterInputDataIsArray
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 11/26/18 09:45
     *
     * @param mixed $inputData
     * @param mixed $requireData
     *
     * @return bool
     */
    public static function filterInputDataIsArray($inputData = [], $requireData = []): bool
    {
        if (empty($inputData) || empty($requireData)) {
            return false;
        }
        if (count($requireData) <= 0 || count($inputData) <= 0) {
            return false;
        }
        if (!is_array($requireData) || !is_array($inputData)) {
            return false;
        }
        foreach ($requireData as $params) {
            if (!array_key_exists($params, $inputData)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Function exceptionMissingInputParams
     *
     * @return string
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 09/09/2021 49:46
     */
    public static function exceptionMissingInputParams(): string
    {
        $url      = 'https://go.tramtro.com/esdmv96z';
        $errorMsg = 'Invalid or Missing Require Params Page Meta';

        return $errorMsg . ' - see: <a rel="nofollow" target="_blank" href="' . $url . '">' . $url . '</a>';
    }
}
