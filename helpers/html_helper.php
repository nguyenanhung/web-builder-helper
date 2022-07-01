<?php
/**
 * Project web-builder-helper
 * Created by PhpStorm
 * User: 713uk13m <dev@nguyenanhung.com>
 * Copyright: 713uk13m <dev@nguyenanhung.com>
 * Date: 02/07/2022
 * Time: 02:46
 */
if (!function_exists('default_news_article_html_tag')) {
    function default_news_article_html_tag($firstSegment = ''): string
    {
        $result = '';
        if (empty($firstSegment) || $firstSegment == '') {
            $result .= "<html lang=\"vi\" xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:og=\"http://ogp.me/ns#\" xmlns:fb=\"http://www.facebook.com/2008/fbml\">\n";
            $result .= "<head prefix=\"og: http://ogp.me/ns#\">\n";
        } else {
            $result .= "<html lang=\"vi\" xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:og=\"http://ogp.me/ns#\" xmlns:fb=\"http://www.facebook.com/2008/fbml\" itemscope=\"itemscope\" itemtype=\"http://schema.org/NewsArticle\">\n";
            $result .= "<head prefix=\"og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#\">\n";
        }

        return $result;
    }
}
