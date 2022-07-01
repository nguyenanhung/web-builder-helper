<?php
/**
 * Project web-builder-helper
 * Created by PhpStorm
 * User: 713uk13m <dev@nguyenanhung.com>
 * Copyright: 713uk13m <dev@nguyenanhung.com>
 * Date: 09/09/2021
 * Time: 22:48
 */

namespace nguyenanhung\WebBuilderHelper;

use Carbon\Carbon;
use stdClass;
use DateTime;
use nguyenanhung\Libraries\Slug\SlugUrl;

/**
 * Class Utils
 *
 * @package   nguyenanhung\WebBuilderHelper
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
class Utils implements ProjectInterface
{
    use Version;

    public const HASH_ALGORITHM                 = 'md5';
    public const USER_PASSWORD_RANDOM_LENGTH    = 6;
    public const USER_PASSWORD_RANDOM_ALGORITHM = 'numeric';
    public const USER_TOKEN_ALGORITHM           = 'md5';
    public const USER_SALT_ALGORITHM            = 'md5';

    /**
     * Header Redirect
     *
     * Header redirect in two flavors
     * For very fine grained control over headers, you could use the Output
     * Library's set_header() function.
     *
     * @param string   $uri     URL
     * @param string   $method  Redirect method
     *                          'auto', 'location' or 'refresh'
     * @param int|null $code    HTTP Response status code
     *
     * @return    void
     *
     * @copyright https://www.codeigniter.com/
     */
    public static function redirect(string $uri = '', string $method = 'auto', int $code = null)
    {
        // IIS environment likely? Use 'refresh' for better compatibility
        if ($method === 'auto' &&
            isset($_SERVER['SERVER_SOFTWARE']) &&
            strpos($_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS') !== false) {
            $method = 'refresh';
        } elseif ($method !== 'refresh' && (empty($code) or !is_numeric($code))) {
            if (isset($_SERVER['SERVER_PROTOCOL'], $_SERVER['REQUEST_METHOD']) &&
                $_SERVER['SERVER_PROTOCOL'] === 'HTTP/1.1') {
                $code = ($_SERVER['REQUEST_METHOD'] !== 'GET')
                    ? 303    // reference: http://en.wikipedia.org/wiki/Post/Redirect/Get
                    : 307;
            } else {
                $code = 302;
            }
        }
        switch ($method) {
            case 'refresh':
                header('Refresh:0;url=' . $uri);
                break;
            default:
                header('Location: ' . $uri, true, $code);
                break;
        }
        exit;
    }

    /**
     * Function isJson
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 11/26/18 10:50
     *
     * @param string $string
     *
     * @return bool
     */
    public static function isJson(string $string = ''): bool
    {
        json_decode($string);

        return (json_last_error() == JSON_ERROR_NONE);
    }

    /**
     * Function arrayToObject
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 11/9/18 17:01
     *
     * @param mixed $data
     * @param bool  $toLower
     *
     * @return array|bool|\stdClass
     */
    public static function arrayToObject($data = array(), bool $toLower = false)
    {
        if (!is_array($data)) {
            return $data;
        }
        $object = new stdClass();
        if (count($data) > 0) {
            foreach ($data as $name => $value) {
                $name = trim($name);
                if ($toLower === true) {
                    $name = strtolower($name);
                }
                if (!empty($name)) {
                    $object->$name = self::arrayToObject($value, $toLower);
                }
            }

            return $object;
        }

        return false;
    }

    /**
     * Function objectToArray
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2018-12-09 19:44
     *
     * @param mixed $object
     *
     * @return false|mixed|string
     */
    public static function objectToArray($object = '')
    {
        if (!is_object($object)) {
            return $object;
        }
        $object = json_encode($object);

        return json_decode($object, true);
    }

    /**
     * Function objectFormat
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 11/26/18 10:51
     *
     * @param mixed $data
     *
     * @return array|bool|mixed|\stdClass|string
     */
    public static function objectFormat($data = '')
    {
        if (is_object($data)) {
            return $data;
        }
        if (is_array($data)) {
            return static::arrayToObject($data);
        }
        if (static::isJson($data)) {
            return json_decode($data);
        }

        return new stdClass();
    }

    /**
     * Function expireTime
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2018-12-07 11:26
     *
     * @param int $duration
     *
     * @return string
     * @throws \Exception
     */
    public static function expireTime(int $duration = 1): string
    {
        $expire = $duration <= 1 ? new DateTime("+0 days") : new DateTime("+$duration days");

        return $expire->format('Y-m-d') . ' 23:59:59';
    }

    /**
     * Function generateHashValue
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 11/18/18 03:04
     *
     * @param string $str
     *
     * @return string
     */
    public static function generateHashValue(string $str = ''): string
    {
        return hash(self::HASH_ALGORITHM, $str);
    }

    /**
     * Function generateUserPasswordRandom
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 11/19/18 10:08
     *
     * @return string
     */
    public static function generateUserPasswordRandom(): string
    {
        return random_string(self::USER_PASSWORD_RANDOM_ALGORITHM, self::USER_PASSWORD_RANDOM_LENGTH);
    }

    /**
     * Function generateUserToken
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 11/19/18 10:08
     *
     * @return string
     */
    public static function generateUserToken(): string
    {
        return random_string(self::USER_TOKEN_ALGORITHM);
    }

    /**
     * Function generateUserSaltKey
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 11/19/18 10:08
     *
     * @return string
     */
    public static function generateUserSaltKey(): string
    {
        return random_string(self::USER_SALT_ALGORITHM);
    }

    /**
     * Function generateRequestId
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 11/23/18 17:15
     *
     * @return string
     */
    public static function generateRequestId(): string
    {
        return (new Carbon())->format('YmdHis') . random_string('numeric', 10);
    }

    /**
     * Function generateOTPCode
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 11/23/18 17:16
     *
     * @param int $length
     *
     * @return string
     */
    public static function generateOTPCode(int $length = 6): string
    {
        return random_string('numeric', $length);
    }

    /**
     * Function generateOTPExpireTime
     *
     * @param int $hour
     *
     * @return string
     * @throws \Exception
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 17/06/2022 24:46
     */
    public static function generateOTPExpireTime(int $hour = 4): string
    {
        return (new DateTime('+' . $hour . ' days'))->format('Y-m-d H:i:s');
    }

    /**
     * Function zuluTime
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 11/21/18 14:48
     *
     * @return string
     */
    public static function zuluTime(): string
    {
        return (new Carbon())->toIso8601ZuluString();
    }

    /**
     * Function jsonItem
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2018-12-07 11:51
     *
     * @param string $json
     * @param string $output
     *
     * @return string|null
     */
    public static function jsonItem(string $json = '', string $output = '')
    {
        $result = json_decode(trim($json));
        $output = trim($output);
        if ($result !== null) {
            if (isset($result->$output)) {
                return trim($result->$output);
            }
        }

        return null;
    }

    /**
     * Function Slugify
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2018-12-07 13:59
     *
     * @param string $str
     * @param mixed  $options
     *
     * @return string
     */
    public static function slugify(string $str = '', $options = null): string
    {
        return (new SlugUrl())->slugify($str, $options);
    }

    /**
     * Function searchSlugify
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2018-12-07 15:25
     *
     * @param string $str
     *
     * @return string
     */
    public static function searchSlugify(string $str = ''): string
    {
        return (new SlugUrl())->searchSlugify($str);
    }

    /**
     * Function strToEn
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2018-12-07 15:25
     *
     * @param string $str
     *
     * @return string
     */
    public static function strToEn(string $str = ''): string
    {
        return (new SlugUrl())->toEnglish($str);
    }
}