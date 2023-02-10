<?php
/**
 * Project web-builder-helper
 * Created by PhpStorm
 * User: 713uk13m <dev@nguyenanhung.com>
 * Copyright: 713uk13m <dev@nguyenanhung.com>
 * Date: 09/09/2021
 * Time: 22:48
 */

namespace nguyenanhung\WebBuilderHelper;

use nguyenanhung\Libraries\Slug\SlugUrl;
use nguyenanhung\Classes\Helper\Utils as HelperUtils;
use nguyenanhung\Classes\Helper\Common as HelperCommon;

/**
 * Class Utils
 *
 * @package   nguyenanhung\WebBuilderHelper
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
class Utils extends HelperUtils
{
    /**
     * Function jsonItem
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2018-12-07 11:51
     *
     * @param string $json
     * @param string $output
     *
     * @return string|null
     */
    public static function jsonItem(string $json = '', string $output = '')
    {
        return (new HelperCommon())->jsonItem($json, $output);
    }

    /**
     * Function Slugify
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2018-12-07 13:59
     *
     * @param string $str
     * @param mixed  $options
     *
     * @return string
     */
    public static function slugify(string $str = '', $options = null): string
    {
        return (new SlugUrl())->slugify($str, $options);
    }

    /**
     * Function searchSlugify
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2018-12-07 15:25
     *
     * @param string $str
     *
     * @return string
     */
    public static function searchSlugify(string $str = ''): string
    {
        return (new SlugUrl())->searchSlugify($str);
    }

    /**
     * Function strToEn
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2018-12-07 15:25
     *
     * @param string $str
     *
     * @return string
     */
    public static function strToEn(string $str = ''): string
    {
        return (new SlugUrl())->toEnglish($str);
    }
}
