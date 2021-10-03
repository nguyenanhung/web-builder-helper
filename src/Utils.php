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
use Cocur\Slugify\Slugify;
use Exception;
use stdClass;
use DateTime;

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
     * @param string $uri       URL
     * @param string $method    Redirect method
     *                          'auto', 'location' or 'refresh'
     * @param int    $code      HTTP Response status code
     *
     * @return    void
     *
     * @copyright https://www.codeigniter.com/
     */
    public static function redirect($uri = '', $method = 'auto', $code = null)
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
     * @param $string
     *
     * @return bool
     */
    public static function isJson($string = '')
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
     * @param array $data
     * @param bool  $str_to_lower
     *
     * @return array|bool|\stdClass
     */
    public static function arrayToObject($data = [], $str_to_lower = false)
    {
        if (!is_array($data)) {
            return $data;
        }
        $object = new stdClass();
        if (count($data) > 0) {
            foreach ($data as $name => $value) {
                $name = trim($name);
                if ($str_to_lower === true) {
                    $name = strtolower($name);
                }
                if (!empty($name)) {
                    $object->$name = self::arrayToObject($value, $str_to_lower);
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
     * @param string $object
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
     * @param string $data
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
    public static function expireTime($duration = 1)
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
    public static function generateHashValue($str = '')
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
    public static function generateUserPasswordRandom()
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
    public static function generateUserToken()
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
    public static function generateUserSaltKey()
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
    public static function generateRequestId()
    {
        $time = new Carbon();

        return $time->format('YmdHis') . random_string('numeric', 10);
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
    public static function generateOTPCode($length = 6)
    {
        return random_string('numeric', $length);
    }

    /**
     * Function generateOTPExpireTime
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2018-12-07 11:26
     *
     * @param int $hour
     *
     * @return string
     * @throws \Exception
     */
    public static function generateOTPExpireTime($hour = 4)
    {
        $time = new DateTime('+' . $hour . ' days');

        return $time->format('Y-m-d H:i:s');
    }

    /**
     * Function zuluTime
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 11/21/18 14:48
     *
     * @return string
     */
    public static function zuluTime()
    {
        $time = new Carbon();

        return $time->toIso8601ZuluString();
    }

    /**
     * Function jsonItem
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2018-12-07 11:51
     *
     * @param string $json_string
     * @param string $item_output
     *
     * @return string|null
     */
    public static function jsonItem($json_string = '', $item_output = '')
    {
        $result      = json_decode(trim($json_string));
        $item_output = trim($item_output);
        if ($result !== null) {
            if (isset($result->$item_output)) {
                return trim($result->$item_output);
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
     * @param null   $options
     *
     * @return string
     */
    public static function slugify($str = '', $options = null)
    {
        try {
            if (!empty($options) && is_array($options)) {
                $slugify = new Slugify($options);
            } else {
                $slugify = new Slugify();
            }

            return $slugify->slugify($str);
        } catch (Exception $e) {
            if (function_exists('log_message')) {
                log_message('error', $e->getMessage());
                log_message('error', $e->getTraceAsString());
            }

            return $str;
        }
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
    public static function searchSlugify($str = '')
    {
        try {
            $slugify = new Slugify();

            return $slugify->slugify($str, '+');
        } catch (Exception $e) {
            if (function_exists('log_message')) {
                log_message('error', $e->getMessage());
                log_message('error', $e->getTraceAsString());
            }

            return trim($str);
        }
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
    public static function strToEn($str = '')
    {
        try {
            $options = array('separator' => ' ');
            $slugify = new Slugify($options);

            return $slugify->slugify($str);
        } catch (Exception $e) {
            if (function_exists('log_message')) {
                log_message('error', $e->getMessage());
                log_message('error', $e->getTraceAsString());
            }

            return trim($str);
        }
    }
}