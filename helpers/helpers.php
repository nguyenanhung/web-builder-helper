<?php
/**
 * Project web-builder-helper
 * Created by PhpStorm
 * User: 713uk13m <dev@nguyenanhung.com>
 * Copyright: 713uk13m <dev@nguyenanhung.com>
 * Date: 10/04/2021
 * Time: 04:13
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
if (!function_exists('default_news_article_html_tag')) {
    function default_news_article_html_tag($firstSegment = ''): string
    {
        $result = '';
        if (empty($firstSegment) || $firstSegment == '') {
            $result .= "<html lang=\"vi\" xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:og=\"https://ogp.me/ns#\" xmlns:fb=\"https://www.facebook.com/2008/fbml\">\n";
            $result .= "<head prefix=\"og: https://ogp.me/ns#\">\n";
        } else {
            $result .= "<html lang=\"vi\" xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:og=\"https://ogp.me/ns#\" xmlns:fb=\"https://www.facebook.com/2008/fbml\" itemscope=\"itemscope\" itemtype=\"https://schema.org/NewsArticle\">\n";
            $result .= "<head prefix=\"og: https://ogp.me/ns# fb: https://ogp.me/ns/fb# article: https://ogp.me/ns/article#\">\n";
        }

        return $result;
    }
}
if (!function_exists('get_headers_url_with_fsockopen')) {
    function get_headers_url_with_fsockopen($url, $format = 0)
    {
        $url = parse_url($url);
        $end = "\r\n\r\n";
        $fp  = fsockopen($url['host'], (empty($url['port']) ? 80 : $url['port']), $errno, $errstr, 30);
        if ($fp) {
            $out = "GET / HTTP/1.1\r\n";
            $out .= "Host: " . $url['host'] . "\r\n";
            $out .= "Connection: Close\r\n\r\n";
            $var = '';
            fwrite($fp, $out);
            while (!feof($fp)) {
                $var .= fgets($fp, 1280);
                if (strpos($var, $end))
                    break;
            }
            fclose($fp);

            $var = preg_replace("/\r\n\r\n.*\$/", '', $var);
            $var = explode("\r\n", $var);
            if ($format) {
                foreach ($var as $i) {
                    if (preg_match('/^([a-zA-Z -]+): +(.*)$/', $i, $parts))
                        $v[$parts[1]] = $parts[2];
                }

                return $v;
            } else
                return $var;
        }
    }
}
if (!function_exists('check_url_is_404')) {
    function check_url_is_404($url)
    {
        $check = get_headers_url_with_fsockopen($url, 1);
        if (is_array($check) && isset($check[0]) && $check[0] === 'HTTP/1.1 404 Not Found') {
            return true;
        }

        return false;
    }
}

