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
    function default_meta_http_equiv($content_refresh = 3600)
    {
        return array(
            array(
                'name'    => 'X-UA-Compatible',
                'content' => 'IE=edge',
                'type'    => 'http-equiv'
            ),
            array(
                'name'    => 'refresh',
                'content' => $content_refresh,
                'type'    => 'equiv'
            ),
            array(
                'name'    => 'content-language',
                'content' => 'vi',
                'type'    => 'equiv'
            ),
            array(
                'name'    => 'audience',
                'content' => 'general',
                'type'    => 'equiv'
            )
        );
    }
}
if (!function_exists('default_news_article_html_tag')) {
    function default_news_article_html_tag($firstSegment = '')
    {
        $html = '';
        if (empty($firstSegment)) {
            $html .= "<html lang=\"vi\" xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:og=\"https://ogp.me/ns#\" xmlns:fb=\"https://www.facebook.com/2008/fbml\">\n";
            $html .= "<head prefix=\"og: https://ogp.me/ns#\">\n";
        } else {
            $html .= "<html lang=\"vi\" xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:og=\"https://ogp.me/ns#\" xmlns:fb=\"https://www.facebook.com/2008/fbml\" itemscope=\"itemscope\" itemtype=\"https://schema.org/NewsArticle\">\n";
            $html .= "<head prefix=\"og: https://ogp.me/ns# fb: https://ogp.me/ns/fb# article: https://ogp.me/ns/article#\">\n";
        }

        return $html;
    }
}
if (!function_exists('get_headers_url_with_fsockopen')) {
    function get_headers_url_with_fsockopen($url, $format = 0)
    {
        $url = parse_url($url);
        $end = "\r\n\r\n";
        $fp = fsockopen($url['host'], (empty($url['port']) ? 80 : $url['port']), $errno, $errstr, 30);
        if ($fp) {
            $out = "GET / HTTP/1.1\r\n";
            $out .= "Host: " . $url['host'] . "\r\n";
            $out .= "Connection: Close\r\n\r\n";
            $var = '';
            fwrite($fp, $out);
            while (!feof($fp)) {
                $var .= fgets($fp, 1280);
                if (strpos($var, $end)) {
                    break;
                }
            }
            fclose($fp);

            $var = preg_replace("/\r\n\r\n.*\$/", '', $var);
            $var = explode("\r\n", $var);
            if ($format) {
                foreach ($var as $i) {
                    if (preg_match('/^([a-zA-Z -]+): +(.*)$/', $i, $parts)) {
                        $v[$parts[1]] = $parts[2];
                    }
                }

                return $v;
            }

            return $var;
        }
    }
}
if (!function_exists('check_url_is_404')) {
    function check_url_is_404($url)
    {
        $check = get_headers_url_with_fsockopen($url, 1);

        return is_array($check) && isset($check[0]) && $check[0] === 'HTTP/1.1 404 Not Found';
    }
}
if (!function_exists('_sdk_highlight_search_keyword_')) {
    function _sdk_highlight_search_keyword_($pagination, $str, $font_color = null)
    {
        $str = trim($str);
        if (!isset($pagination['page_content_type'])) {
            return $str;
        }
        if ($pagination['page_content_type'] !== 'search') {
            return $str;
        }
        if (isset($pagination['highlight_keyword_status'], $pagination['highlight_text_keyword'])) {
            $status = $pagination['highlight_keyword_status'];
            $keyword = $pagination['highlight_text_keyword'];
            if ($status !== true || empty($keyword)) {
                return $str;
            }
            $highlight = highlight_search_keyword($keyword, $str, $font_color);

            return trim($highlight);
        }

        return $str;
    }
}
