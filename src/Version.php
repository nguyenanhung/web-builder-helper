<?php
/**
 * Project seo.
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 2018-12-31
 * Time: 01:39
 */

namespace nguyenanhung\WebBuilderHelper;

/**
 * Trait Version
 *
 * @package   nguyenanhung\WebBuilderHelper
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
trait Version
{
    /**
     * Function getVersion
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2018-12-31 01:39
     *
     * @return string
     */
    public function getVersion()
    {
        return self::VERSION;
    }
}
