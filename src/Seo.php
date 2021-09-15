<?php
/**
 * Project web-builder-helper
 * Created by PhpStorm
 * User: 713uk13m <dev@nguyenanhung.com>
 * Copyright: 713uk13m <dev@nguyenanhung.com>
 * Date: 09/09/2021
 * Time: 22:43
 */

namespace nguyenanhung\WebBuilderHelper;

use Exception;
use Cocur\Slugify\Slugify;
use Hashids\Hashids;
use nguyenanhung\Classes\Helper\Common;
use nguyenanhung\MyImage\ImageCache;

/**
 * Class Seo
 *
 * @package   nguyenanhung\WebBuilderHelper
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
class Seo implements ProjectInterface
{
    use Version;

    const HANDLE_CONFIG_KEY   = 'CONFIG_HANDLE';
    const HASH_IDS_CONFIG_KEY = 'hashIdsConfig';

    /** @var array SDK Config */
    private $sdkConfig;

    /** @var Common $common */
    private $common;

    /** @var string Site Ext */
    private $siteExt = '.html';

    /**
     * Seo constructor.
     *
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     */
    public function __construct()
    {
        $this->common = new Common();
    }

    /**
     * Function setSdkConfig
     *
     * @param array $sdkConfig
     *
     * @return $this
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2018-12-09 14:04
     *
     */
    public function setSdkConfig($sdkConfig = array())
    {
        $this->sdkConfig = $sdkConfig;

        return $this;
    }

    /**
     * Function getSdkConfig
     *
     * @return array
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 08/30/2021 02:24
     */
    public function getSdkConfig()
    {
        return $this->sdkConfig;
    }

    /**
     * Function getPageNumber
     *
     * @param string $pageNumber
     *
     * @return array|string|string[]
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 08/30/2021 02:48
     */
    public function getPageNumber($pageNumber = '')
    {
        $pageNumber = trim($pageNumber);

        return str_replace('trang-', '', $pageNumber);
    }

    /**
     * Function viewPagination
     *
     * @param array $data
     *
     * @return string|null
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2018-12-17 09:54
     *
     */
    public function viewPagination($data = array())
    {
        return $this->common->viewPagination($data);
    }

    /**
     * Function siteUrl
     *
     * @param string $uri
     *
     * @return string
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 07/25/2020 00:37
     */
    public function siteUrl($uri = '')
    {
        $uri = trim($uri);
        if (empty($uri)) {
            return $this->sdkConfig[self::HANDLE_CONFIG_KEY]['homepage'];
        }

        return $this->sdkConfig[self::HANDLE_CONFIG_KEY]['homepage'] . trim($uri) . $this->siteExt;
    }

    /**
     * Function baseUrl
     *
     * @param string $uri
     *
     * @return string
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 07/25/2020 00:48
     */
    public function baseUrl($uri = '')
    {
        $uri = trim($uri);
        if (empty($uri)) {
            return $this->sdkConfig[self::HANDLE_CONFIG_KEY]['homepage'];
        }

        return $this->sdkConfig[self::HANDLE_CONFIG_KEY]['homepage'] . trim($uri);
    }

    /**
     * Function assetsUrl
     *
     * @param string $uri
     *
     * @return string
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 07/25/2020 00:54
     */
    public function assetsUrl($uri = '')
    {
        $uri = trim($uri);
        if (empty($uri)) {
            return $this->sdkConfig[self::HANDLE_CONFIG_KEY]['static_url'];
        }

        return $this->sdkConfig[self::HANDLE_CONFIG_KEY]['static_url'] . trim($uri);
    }

    /**
     * Function assetsUrlThemes
     *
     * @param string $themes
     * @param string $uri
     * @param string $assetFolder
     *
     * @return string
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 09/09/2021 17:17
     */
    public function assetsUrlThemes($themes = '', $uri = '', $assetFolder = 'yes')
    {
        $assetFolder = strtolower($assetFolder);
        $assetsPath  = 'assets/themes/';
        // Pattern
        if ($themes != '') {
            if ($assetFolder == 'no') {
                $uri = ($themes . '/' . $uri);
            } else {
                $uri = ($themes . '/assets/' . $uri);
            }
        } else {
            if ($assetFolder == 'no') {
                $uri = trim($uri);
            } else {
                $uri = ('assets/' . $uri);
            }
        }

        return $this->baseUrl($assetsPath . $uri);
    }

    /**
     * Function faviconUrl
     *
     * @param string $uri
     *
     * @return string
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 07/25/2020 01:04
     */
    public function faviconUrl($uri = '')
    {
        return $this->assetsUrl('fav/' . $uri);
    }

    /**
     * Function imageUrl
     *
     * @param string $input
     *
     * @return string
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 07/25/2020 01:08
     */
    public function imageUrl($input = '')
    {
        $config    = [
            'no_thumb' => [
                'images/system/no_avatar.jpg',
                'images/system/no_avatar_100x100.jpg',
                'images/system/no_video_available.jpg',
                'images/system/no_video_available_thumb.jpg',
                'images/system/no-image-available.jpg',
                'images/system/no-image-available_60.jpg',
                'images/system/no-image-available_330.jpg'
            ]
        ];
        $assetsUrl = $this->sdkConfig[self::HANDLE_CONFIG_KEY]['assets_url'];
        $staticUrl = $this->sdkConfig[self::HANDLE_CONFIG_KEY]['static_url'];
        $imageUrl  = trim($input);
        if (!empty($imageUrl)) {
            $noThumbnail = $config['no_thumb'];
            if (in_array($imageUrl, $noThumbnail)) {
                return $assetsUrl . trim($imageUrl);
            } else {
                $parse_input = parse_url($imageUrl);
                if (isset($parse_input['host'])) {
                    return $imageUrl;
                } else {
                    if (trim(mb_substr($imageUrl, 0, 12)) == 'crawler-news') {
                        $imageUrl = trim('uploads/' . $imageUrl);
                    }

                    return $staticUrl . $imageUrl;
                }
            }
        }

        return $imageUrl;
    }

    /**
     * Function resizeImage
     *
     * @param string $url
     * @param int    $width
     * @param int    $height
     *
     * @return string
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 07/25/2020 01:44
     */
    public function resizeImage($url = '', $width = 100, $height = 100)
    {
        try {
            $imageUrlTmpPath     = $this->sdkConfig[self::HANDLE_CONFIG_KEY]['imageUrlTmpPath'];
            $imageStorageTmpPath = $this->sdkConfig[self::HANDLE_CONFIG_KEY]['imageStorageTmpPath'];
            $imageDefaultPath    = $this->sdkConfig[self::HANDLE_CONFIG_KEY]['imageDefaultPath'];
            if (!empty($imageDefaultPath)) {
                $defaultImage = $imageDefaultPath;
            } else {
                $defaultImage = realpath(__DIR__ . '/../../assets/image/system/no-image-available_640.jpg');
            }
            $cache = new ImageCache();
            $cache->setTmpPath($imageStorageTmpPath);
            $cache->setUrlPath($imageUrlTmpPath);
            $cache->setDefaultImage();
            $thumbnail = $cache->thumbnail($url, $width, $height);
            if (!empty($thumbnail)) {
                return $thumbnail;
            }

            return $cache->thumbnail($defaultImage, $width, $height);
        } catch (Exception $e) {
            if (function_exists('log_message')) {
                log_message('error', $e->getMessage());
                log_message('error', $e->getTraceAsString());
            }

            return $url;
        }
    }

    /**
     * Function slugify
     *
     * @param string $str
     * @param null   $options
     *
     * @return bool|mixed|string|string[]|null
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 07/25/2020 02:18
     */
    public function slugify($str = '', $options = null)
    {
        try {
            if (!empty($options) && is_array($options)) {
                $data = new Slugify($options);
            } else {
                $data = new Slugify();
            }

            return $data->slugify($str);
        } catch (Exception $e) {
            if (function_exists('log_message')) {
                log_message('error', $e->getMessage());
                log_message('error', $e->getTraceAsString());
            }

            return $this->common->convertStrToEn($str);
        }
    }

    /**
     * Function searchSlugify
     *
     * @param string $str
     *
     * @return bool|mixed|string|string[]|null
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 07/25/2020 02:39
     */
    public function searchSlugify($str = '')
    {
        try {
            return $this->slugify($str, ['separator' => '+']);
        } catch (Exception $e) {
            if (function_exists('log_message')) {
                log_message('error', $e->getMessage());
                log_message('error', $e->getTraceAsString());
            }

            return $this->common->convertStrToEn($str, '+');
        }
    }

    /**
     * Function strToEn
     *
     * @param string $str
     *
     * @return bool|mixed|string|string[]|null
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 07/25/2020 02:53
     */
    public function strToEn($str = '')
    {
        try {
            return $this->slugify($str, ['separator' => ' ']);
        } catch (Exception $e) {
            if (function_exists('log_message')) {
                log_message('error', $e->getMessage());
                log_message('error', $e->getTraceAsString());
            }

            return $this->common->convertStrToEn($str, ' ');
        }
    }

    /**
     * Function cleanText
     *
     * @param string $str
     *
     * @return string
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 07/25/2020 02:58
     */
    public function cleanText($str = '')
    {
        return html_entity_decode($str);
    }

    /**
     * Function encodeId
     *
     * @param string $id
     *
     * @return string|null
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 07/25/2020 03:04
     */
    public function encodeId($id = '')
    {
        $id = trim($id);
        if (empty($id)) {
            return null;
        }
        try {
            $hashIds = $this->sdkConfig[self::HASH_IDS_CONFIG_KEY];
            $hash    = new Hashids($hashIds['salt'], $hashIds['minHashLength'], $hashIds['alphabet']);

            return $hash->encode($id);
        } catch (Exception $e) {
            if (function_exists('log_message')) {
                log_message('error', $e->getMessage());
                log_message('error', $e->getTraceAsString());
            }

            return null;
        }
    }

    /**
     * Function decodeId
     *
     * @param string $string
     *
     * @return array|null
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 07/25/2020 03:12
     */
    public function decodeId($string = '')
    {
        if (empty($string)) {
            return null;
        }
        try {
            $hashIds = $this->sdkConfig[self::HASH_IDS_CONFIG_KEY];
            $hash    = new Hashids($hashIds['salt'], $hashIds['minHashLength'], $hashIds['alphabet']);
            $decode  = $hash->decode($string);
            if (empty($decode)) {
                return null;
            }
            if (count($decode) > 1) {
                return $decode;
            }

            return $decode[0];
        } catch (Exception $e) {
            if (function_exists('log_message')) {
                log_message('error', $e->getMessage());
                log_message('error', $e->getTraceAsString());
            }

            return null;
        }
    }

    /**
     * Function urlPost
     *
     * @param string          $categorySlug
     * @param string          $postSlug
     * @param string          $postId
     * @param string|int|null $postType
     *
     * @return string
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 08/30/2021 09:17
     */
    public function urlPost($categorySlug = '', $postSlug = '', $postId = '', $postType = null)
    {
        $home = $this->sdkConfig[self::HANDLE_CONFIG_KEY]['homepage'];

        if ($postType === 'shortUrlToShare') {
            return $this->urlPostShare($postId);
        }
        $postId = $this->encodeId($postId);

        return $home . trim($categorySlug) . '/' . trim($postSlug) . '-post' . $postId . $this->siteExt;
    }

    /**
     * Function urlPostShare
     *
     * @param string $postId
     *
     * @return string
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 08/30/2021 09:20
     */
    public function urlPostShare($postId = '')
    {
        $home = $this->sdkConfig[self::HANDLE_CONFIG_KEY]['homepage'];

        return $home . 'post/' . $this->encodeId($postId) . $this->siteExt;
    }

    /**
     * Function urlPage
     *
     * @param string $pageSlug
     * @param string $pageId
     *
     * @return string
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 08/30/2021 12:44
     */
    public function urlPage($pageSlug = '', $pageId = '')
    {
        $home = $this->sdkConfig[self::HANDLE_CONFIG_KEY]['homepage'];

        return $home . 'pages/' . trim($pageSlug) . '-page' . $this->encodeId($pageId) . $this->siteExt;
    }

    /**
     * Function urlPageShare
     *
     * @param string $pageId
     *
     * @return string
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 07/25/2020 03:39
     */
    public function urlPageShare($pageId = '')
    {
        $home   = $this->sdkConfig[self::HANDLE_CONFIG_KEY]['homepage'];
        $pageId = trim($pageId);
        if (empty($pageId)) {
            return $home;
        }

        return $home . 'p/' . $this->encodeId($pageId) . $this->siteExt;
    }

    /**
     * Function helpPage
     *
     * @param string $slug
     *
     * @return bool|string|null
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 07/25/2020 03:43
     */
    public function helpPage($slug = '')
    {
        $slug = trim($slug);
        if (empty($slug)) {
            return $this->sdkConfig[self::HANDLE_CONFIG_KEY]['homepage'];
        }

        return $this->sdkConfig[self::HANDLE_CONFIG_KEY]['homepage'] . 'help/' . trim($slug) . $this->siteExt;
    }

    /**
     * Function urlCategory
     *
     * @param string $categorySlug
     *
     * @return string
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 07/25/2020 03:48
     */
    public function urlCategory($categorySlug = '')
    {
        $categorySlug = trim($categorySlug);
        if (empty($categorySlug)) {
            return $this->sdkConfig[self::HANDLE_CONFIG_KEY]['homepage'];
        }

        return $this->sdkConfig[self::HANDLE_CONFIG_KEY]['homepage'] . trim($categorySlug) . $this->siteExt;
    }

    /**
     * Function urlTopic
     *
     * @param string $topicSlug
     *
     * @return string
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 07/25/2020 03:52
     */
    public function urlTopic($topicSlug = '')
    {
        $topicSlug = trim($topicSlug);
        if (empty($topicSlug)) {
            return $this->sdkConfig[self::HANDLE_CONFIG_KEY]['homepage'];
        }

        return $this->sdkConfig[self::HANDLE_CONFIG_KEY]['homepage'] . 'chu-de/' . trim($topicSlug) . $this->siteExt;
    }

    /**
     * Function urlTags
     *
     * @param string $tagSlug
     *
     * @return string
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 07/25/2020 03:56
     */
    public function urlTags($tagSlug = '')
    {
        $tagSlug = trim($tagSlug);
        if (empty($tagSlug)) {
            return $this->sdkConfig[self::HANDLE_CONFIG_KEY]['homepage'];
        }

        return $this->sdkConfig[self::HANDLE_CONFIG_KEY]['homepage'] . 'tags/' . trim($tagSlug) . $this->siteExt;
    }

    /**
     * Function urlChannels
     *
     * @param string $channelCode
     *
     * @return string
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 07/26/2020 31:52
     */
    public function urlChannels($channelCode = '')
    {
        $channelCode = trim($channelCode);
        if (empty($channelCode)) {
            return $this->sdkConfig[self::HANDLE_CONFIG_KEY]['homepage'];
        }

        return $this->sdkConfig[self::HANDLE_CONFIG_KEY]['homepage'] . 'channel/' . trim($channelCode) . $this->siteExt;
    }
}