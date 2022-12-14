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
use nguyenanhung\MyCache\Cache;
use nguyenanhung\SEO\SeoUrl;
use nguyenanhung\Classes\Helper\Common;
use nguyenanhung\MyImage\ImageCache;

/**
 * Class Seo
 *
 * @package   nguyenanhung\WebBuilderHelper
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
class Seo extends SeoUrl
{
    use Version;

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
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2018-12-17 09:54
     *
     */
    public function viewPagination(array $data = array())
    {
        return $this->common->viewPagination($data);
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
    public function resizeImage($url = '', int $width = 100, int $height = 100)
    {
        try {
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
                    $defaultImage = __DIR__ . '/../assets/image/no-image-available_x700.jpg';
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
