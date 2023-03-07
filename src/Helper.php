<?php
/**
 * Project web-builder-helper
 * Created by PhpStorm
 * User: 713uk13m <dev@nguyenanhung.com>
 * Copyright: 713uk13m <dev@nguyenanhung.com>
 * Date: 21/02/2023
 * Time: 09:54
 */

namespace nguyenanhung\WebBuilderHelper;

/**
 * Class Helper
 *
 * @package   nguyenanhung\WebBuilderHelper
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
class Helper
{
    const VERSION = '2.0.5';
    const LAST_MODIFIED = '2023-05-07';
    const AUTHOR_NAME = 'Hung Nguyen';
    const AUTHOR_EMAIL = 'dev@nguyenanhung.com';
    const PROJECT_NAME = 'Helper: Web Builder by Hung Nguyen';
    const USE_BENCHMARK = false;
    const USE_DEBUG = false;

    /**
     * Function getVersion
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2018-12-31 01:39
     *
     * @return string
     */
    public function getVersion(): string
    {
        return self::VERSION;
    }
}
