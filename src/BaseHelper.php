<?php
/**
 * Project web-builder-helper
 * Created by PhpStorm
 * User: 713uk13m <dev@nguyenanhung.com>
 * Copyright: 713uk13m <dev@nguyenanhung.com>
 * Date: 21/02/2023
 * Time: 09:51
 */

namespace nguyenanhung\WebBuilderHelper;

/**
 * Class BaseHelper
 *
 * @package   nguyenanhung\WebBuilderHelper
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
class BaseHelper
{
    const VERSION = '1.1.4';
    const LAST_MODIFIED = '2023-02-21';
    const AUTHOR_NAME = 'Hung Nguyen';
    const AUTHOR_EMAIL = 'dev@nguyenanhung.com';
    const PROJECT_NAME = 'Helper: Web Builder by Hung Nguyen';
    const USE_BENCHMARK = false;
    const USE_DEBUG = false;

    /**
     * Function getVersion
     *
     * @return string
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 21/02/2023 51:29
     */
    public function getVersion()
    {
        return self::VERSION;
    }
}
