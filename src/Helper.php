<?php

namespace nguyenanhung\Platforms\WebBuilderSDK\WebBuilderHelper;

/**
 * Class Helper
 *
 * @package   nguyenanhung\Platforms\WebBuilderSDK\WebBuilderHelper
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
class Helper
{
    const VERSION = '2.0.4';
    const LAST_MODIFIED = '2023-09-07';
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
