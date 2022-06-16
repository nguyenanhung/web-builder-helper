<?php
/**
 * Project web-builder-helper
 * Created by PhpStorm
 * User: 713uk13m <dev@nguyenanhung.com>
 * Copyright: 713uk13m <dev@nguyenanhung.com>
 * Date: 09/06/2022
 * Time: 11:47
 */
if (!function_exists('default_meta_http_equiv')) {
    /**
     * Function default_meta_http_equiv
     *
     * @param int $content_refresh
     *
     * @return array
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 07/25/2020 22:33
     */
    function default_meta_http_equiv(int $content_refresh = 3600): array
    {
        return [
            [
                'name'    => 'X-UA-Compatible',
                'content' => 'IE=edge',
                'type'    => 'http-equiv'
            ],
            [
                'name'    => 'refresh',
                'content' => $content_refresh,
                'type'    => 'equiv'
            ],
            [
                'name'    => 'content-language',
                'content' => 'vi',
                'type'    => 'equiv'
            ],
            [
                'name'    => 'audience',
                'content' => 'general',
                'type'    => 'equiv'
            ]
        ];
    }
}
