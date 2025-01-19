<?php

namespace nguyenanhung\Platforms\WebBuilderSDK\WebBuilderHelper;

use Exception;
use nguyenanhung\Libraries\ImageHelper\ImageHelper;
use nguyenanhung\MyCache\Cache;
use nguyenanhung\Classes\Helper\Common;
use nguyenanhung\MyImage\ImageCache;

/**
 * Class Seo
 *
 * @package   nguyenanhung\Platforms\WebBuilderSDK\WebBuilderHelper
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
class Seo extends \nguyenanhung\SEO\SeoUrl
{
    /** @var Common $common */
    protected $common;

    /**
     * Seo constructor.
     *
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     */
    public function __construct()
    {
        parent::__construct();
        $this->common = new Common();
    }

    /**
     * Function viewPagination
     *
     * @param array $data
     *
     * @return string|null
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 10/02/2023 35:39
     */
    public function viewPagination(array $data = array())
    {
        return $this->common->viewPagination($data);
    }

    /**
     * Function viewVideoTVPagination
     *
     * @param array $data
     *
     * @return string|null
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 25/09/2023 52:35
     */
    public function viewVideoTVPagination(array $data = array())
    {
        return $this->common->viewVideoTVPagination($data);
    }

    /**
     * Function viewMorePagination
     *
     * @param array $data
     *
     * @return string
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 25/09/2023 52:41
     */
    public function viewMorePagination(array $data = array())
    {
        return $this->common->viewMorePagination($data);
    }

    /**
     * Function viewSelectPagination
     *
     * @param array $data
     *
     * @return string
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 25/09/2023 52:45
     */
    public function viewSelectPagination(array $data = array())
    {
        return $this->common->viewSelectPagination($data);
    }

    /**
     * Function resizeImage - Cache Image to Tmp Folder
     *
     * @param string|mixed $url
     * @param int          $width
     * @param int          $height
     *
     * @return string|mixed
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 07/25/2020 01:44
     */
    public function resizeImage($url = '', $width = 100, $height = 100)
    {
        if (empty($url)) {
            return $url;
        }
        try {
            $url = smart_bear_cms_cdn_url_http_to_https($url);
            if (isset($this->sdkConfig[self::HANDLE_CONFIG_KEY]['resizeImageStatus']) && $this->sdkConfig[self::HANDLE_CONFIG_KEY]['resizeImageStatus'] === false) {
                $resizeImageStatus = false;
            } else {
                $resizeImageStatus = true;
            }

            if (defined('WEB_BUILDER_SDK_RESIZE_IMAGE_PRIORITY_WITH_WORDPRESS_JETPACK') && WEB_BUILDER_SDK_RESIZE_IMAGE_PRIORITY_WITH_WORDPRESS_JETPACK === true) {
                return wordpress_proxy($url, 'i3', $width, $height);
            }

            if ($resizeImageStatus === false) {
                return $url;
            }

            // Only Cache with WordPress JetPack
            if (isset($this->sdkConfig[self::HANDLE_CONFIG_KEY]['onlyCacheImageWithWordPressProxy']) && $this->sdkConfig[self::HANDLE_CONFIG_KEY]['onlyCacheImageWithWordPressProxy'] === true) {
                return wordpress_proxy($url);
            }

            // Only Cache with Google User Content
            if (isset($this->sdkConfig[self::HANDLE_CONFIG_KEY]['onlyCacheImageWithGoogleProxy']) && $this->sdkConfig[self::HANDLE_CONFIG_KEY]['onlyCacheImageWithGoogleProxy'] === true) {
                return google_image_resize($url, null);
            }

            // Resize with WordPress JetPack
            if (isset($this->sdkConfig[self::HANDLE_CONFIG_KEY]['resizeImageWithWordPressProxy']) && $this->sdkConfig[self::HANDLE_CONFIG_KEY]['resizeImageWithWordPressProxy'] === true) {
                // Cache Server
                if (isset($this->sdkConfig[self::HANDLE_CONFIG_KEY]['serverWordPressProxy']) && !empty(isset($this->sdkConfig[self::HANDLE_CONFIG_KEY]['serverWordPressProxy']))) {
                    $configCacheServer = $this->sdkConfig[self::HANDLE_CONFIG_KEY]['serverWordPressProxy'];
                    $serverSupport = ImageHelper::wordpressProxyProxyServerList();
                    if (in_array($configCacheServer, $serverSupport)) {
                        $cacheServer = $configCacheServer;
                    } else {
                        $cacheServer = 'i1';
                    }
                } else {
                    $cacheServer = 'i3';
                }
                return wordpress_proxy($url, $cacheServer, $width, $height);
            }

            // Resize with Google User Content
            if (isset($this->sdkConfig[self::HANDLE_CONFIG_KEY]['resizeImageWithGoogleProxy']) && $this->sdkConfig[self::HANDLE_CONFIG_KEY]['resizeImageWithGoogleProxy'] === true) {
                // Cache Server
                if (isset($this->sdkConfig[self::HANDLE_CONFIG_KEY]['serverGoogleProxy']) && !empty(isset($this->sdkConfig[self::HANDLE_CONFIG_KEY]['serverGoogleProxy']))) {
                    $configCacheServer = $this->sdkConfig[self::HANDLE_CONFIG_KEY]['serverGoogleProxy'];
                    $serverSupport = ImageHelper::googleGadgetsProxyServerList();
                    if (in_array($configCacheServer, $serverSupport)) {
                        $cacheServer = $configCacheServer;
                    } else {
                        $cacheServer = 'images2';
                    }
                } else {
                    $cacheServer = 'images1';
                }
                return google_image_resize($url, $width, $height, $cacheServer);
            }

            // My Server Cache Setup
            $cacheSecret = md5('Web-Builder-Helper-SEO-Resize-Image');
            $cacheKey = md5($url . $width . $height);
            $cacheTtl = 15552000; // Cache 6 tháng
            $cachePath = $this->sdkConfig['OPTIONS']['cachePath'];
            $cache = new Cache();
            $cache->setCachePath($cachePath)
                  ->setCacheTtl($cacheTtl)
                  ->setCacheDriver('files')
                  ->setCacheDefaultChmod('0777')
                  ->setCacheSecurityKey($cacheSecret);
            $cache->__construct();

            if ($cache->has($cacheKey)) {
                $result = $cache->get($cacheKey);
            } else {
                $imageUrlTmpPath = $this->sdkConfig[self::HANDLE_CONFIG_KEY]['imageUrlTmpPath'];
                $imageStorageTmpPath = $this->sdkConfig[self::HANDLE_CONFIG_KEY]['imageStorageTmpPath'];
                $imageDefaultPath = $this->sdkConfig[self::HANDLE_CONFIG_KEY]['imageDefaultPath'];
                if (!empty($imageDefaultPath)) {
                    $defaultImage = $imageDefaultPath;
                } else {
                    $defaultImage = smart_bear_wbsa_default_image_system_700();
                }
                $imageCache = new ImageCache();
                $imageCache->setTmpPath($imageStorageTmpPath);
                $imageCache->setUrlPath($imageUrlTmpPath);
                $imageCache->setDefaultImage();
                $thumbnail = $imageCache->thumbnail($url, $width, $height);
                if (!empty($thumbnail)) {
                    $result = $thumbnail;
                } else {
                    $defaultThumbnail = $imageCache->thumbnail($defaultImage, $width, $height);
                    if (!empty($defaultThumbnail)) {
                        $result = $defaultThumbnail;
                    } else {
                        $result = $url;
                    }
                }
                $cache->save($cacheKey, $result);
            }
            return $result;
        } catch (Exception $e) {
            if (function_exists('log_message')) {
                log_message('error', $e->getMessage());
                log_message('error', $e->getTraceAsString());
            }

            return $url;
        }
    }
}
