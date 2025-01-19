<?php

namespace nguyenanhung\Platforms\WebBuilderSDK\WebBuilderHelper;

use nguyenanhung\Libraries\Slug\SlugUrl;

/**
 * Class Utils
 *
 * @package   nguyenanhung\WebBuilderHelper
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
class Utils extends \nguyenanhung\Classes\Helper\Utils
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
    public static function jsonItem($json = '', $output = '')
    {
        return (new Common())->jsonItem($json, $output);
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
    public static function slugify($str = '', $options = null)
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
    public static function searchSlugify($str = '')
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
    public static function strToEn($str = '')
    {
        return (new SlugUrl())->toEnglish($str);
    }
}
