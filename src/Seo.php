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
use nguyenanhung\Classes\Helper\Common;
use nguyenanhung\MyImage\ImageCache;
use nguyenanhung\SEO\SeoUrl;

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
    public function resizeImage(string $url = '', int $width = 100, int $height = 100): string
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
     * Function searchSlugify
     *
     * @param string $str
     *
     * @return string
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 16/06/2022 30:25
     */
    public function searchSlugify(string $str = ''): string
    {
        return $this->search_slugify($str);
    }
}