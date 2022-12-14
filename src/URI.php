<?php
/**
 * Project web-builder-helper
 * Created by PhpStorm
 * User: 713uk13m <dev@nguyenanhung.com>
 * Copyright: 713uk13m <dev@nguyenanhung.com>
 * Date: 03/07/2022
 * Time: 22:58
 */

namespace nguyenanhung\WebBuilderHelper;

/**
 * URI Class
 *
 * Parses URIs and determines routing
 *
 * @package        CodeIgniter
 * @subpackage     Libraries
 * @category       URI
 * @author         EllisLab Dev Team
 * @link           https://codeigniter.com/userguide3/libraries/uri.html
 */
class URI
{

    /**
     * List of cached URI segments
     *
     * @var    array
     */
    public $keyval = array();

    /**
     * Current URI string
     *
     * @var    string
     */
    public $uri_string = '';

    /**
     * List of URI segments
     *
     * Starts at 1 instead of 0.
     *
     * @var    array
     */
    public $segments = array();

    /**
     * List of routed URI segments
     *
     * Starts at 1 instead of 0.
     *
     * @var    array
     */
    public $rsegments = array();

    /**
     * Permitted URI chars
     *
     * PCRE character group allowed in URI segments
     *
     * @var    string
     */
    protected $_permitted_uri_chars;

    /**
     * Class constructor
     *
     * @return    void
     */
    public function __construct()
    {
        // If query strings are enabled, we don't need to parse any segments.
        // However, they don't make sense under CLI.
        if ($this->is_cli()) {
            $this->_permitted_uri_chars = 'a-z 0-9~%.:_\-';

            // If it's a CLI request, ignore the configuration
            if ($this->is_cli()) {
                $uri = $this->_parse_argv();
            } else {
                $protocol = 'REQUEST_URI';

                switch ($protocol) {
                    case 'AUTO': // For BC purposes only
                    case 'REQUEST_URI':
                        $uri = $this->_parse_request_uri();
                        break;
                    case 'QUERY_STRING':
                        $uri = $this->_parse_query_string();
                        break;
                    case 'PATH_INFO':
                    default:
                        $uri = $_SERVER[$protocol] ?? $this->_parse_request_uri();
                        break;
                }
            }

            $this->_set_uri_string($uri);
        }
    }

    protected function is_cli()
    {
        return (PHP_SAPI === 'cli' or defined('STDIN'));
    }
    // --------------------------------------------------------------------

    /**
     * Set URI String
     *
     * @param string $str
     *
     * @return    void
     */
    protected function _set_uri_string($str)
    {
        $configUrlSuffix = '.html';
        // Filter out control characters and trim slashes
        $this->uri_string = trim(remove_invisible_characters($str, false), '/');

        if ($this->uri_string !== '') {
            // Remove the URL suffix, if present
            if (($suffix = $configUrlSuffix) !== '') {
                $slen = strlen($suffix);

                if (substr($this->uri_string, -$slen) === $suffix) {
                    $this->uri_string = substr($this->uri_string, 0, -$slen);
                }
            }

            $this->segments[0] = null;
            // Populate the segments array
            foreach (explode('/', trim($this->uri_string, '/')) as $val) {
                $val = trim($val);
                // Filter segments for security
                $this->filter_uri($val);

                if ($val !== '') {
                    $this->segments[] = $val;
                }
            }

            unset($this->segments[0]);
        }
    }

    // --------------------------------------------------------------------

    /**
     * Parse REQUEST_URI
     *
     * Will parse REQUEST_URI and automatically detect the URI from it,
     * while fixing the query string if necessary.
     *
     * @return    string
     */
    protected function _parse_request_uri()
    {
        if (!isset($_SERVER['REQUEST_URI'], $_SERVER['SCRIPT_NAME'])) {
            return '';
        }

        // parse_url() returns false if no host is present, but the path or query string
        // contains a colon followed by a number
        $uri = parse_url('http://dummy' . $_SERVER['REQUEST_URI']);
        $query = $uri['query'] ?? '';
        $uri = $uri['path'] ?? '';

        if (isset($_SERVER['SCRIPT_NAME'][0])) {
            if (strpos($uri, $_SERVER['SCRIPT_NAME']) === 0) {
                $uri = (string) substr($uri, strlen($_SERVER['SCRIPT_NAME']));
            } elseif (strpos($uri, dirname($_SERVER['SCRIPT_NAME'])) === 0) {
                $uri = (string) substr($uri, strlen(dirname($_SERVER['SCRIPT_NAME'])));
            }
        }

        // This section ensures that even on servers that require the URI to be in the query string (Nginx) a correct
        // URI is found, and also fixes the QUERY_STRING server var and $_GET array.
        if (trim($uri, '/') === '' && strncmp($query, '/', 1) === 0) {
            $query = explode('?', $query, 2);
            $uri = $query[0];
            $_SERVER['QUERY_STRING'] = $query[1] ?? '';
        } else {
            $_SERVER['QUERY_STRING'] = $query;
        }

        parse_str($_SERVER['QUERY_STRING'], $_GET);

        if ($uri === '/' or $uri === '') {
            return '/';
        }

        // Do some final cleaning of the URI and return it
        return $this->_remove_relative_directory($uri);
    }

    // --------------------------------------------------------------------

    /**
     * Parse QUERY_STRING
     *
     * Will parse QUERY_STRING and automatically detect the URI from it.
     *
     * @return    string
     */
    protected function _parse_query_string()
    {
        $uri = $_SERVER['QUERY_STRING'] ?? @getenv('QUERY_STRING');

        if (trim($uri, '/') === '') {
            return '';
        } elseif (strncmp($uri, '/', 1) === 0) {
            $uri = explode('?', $uri, 2);
            $_SERVER['QUERY_STRING'] = $uri[1] ?? '';
            $uri = $uri[0];
        }

        parse_str($_SERVER['QUERY_STRING'], $_GET);

        return $this->_remove_relative_directory($uri);
    }

    // --------------------------------------------------------------------

    /**
     * Parse CLI arguments
     *
     * Take each command line argument and assume it is a URI segment.
     *
     * @return    string
     */
    protected function _parse_argv()
    {
        $args = array_slice($_SERVER['argv'], 1);

        return $args ? implode('/', $args) : '';
    }

    // --------------------------------------------------------------------

    /**
     * Remove relative directory (../) and multi slashes (///)
     *
     * Do some final cleaning of the URI and return it, currently only used in self::_parse_request_uri()
     *
     * @param string $uri
     *
     * @return    string
     */
    protected function _remove_relative_directory($uri)
    {
        $uris = array();
        $tok = strtok($uri, '/');
        while ($tok !== false) {
            if ((!empty($tok) or $tok === '0') && $tok !== '..') {
                $uris[] = $tok;
            }
            $tok = strtok('/');
        }

        return implode('/', $uris);
    }

    // --------------------------------------------------------------------

    /**
     * Filter URI
     *
     * Filters segments for malicious characters.
     *
     * @param string $str
     *
     * @return    void
     */
    public function filter_uri(&$str)
    {
        if (!empty($str) && !empty($this->_permitted_uri_chars) && !preg_match('/^[' . $this->_permitted_uri_chars . ']+$/i' . (UTF8_ENABLED ? 'u' : ''), $str)) {
            if (function_exists('log_message')) {
                log_message('error', 'The URI you submitted has disallowed characters.');
            }
        }
    }

    // --------------------------------------------------------------------

    /**
     * Fetch URI Segment
     *
     * @see        CI_URI::$segments
     *
     * @param int   $n         Index
     * @param mixed $no_result What to return if the segment index is not found
     *
     * @return    mixed
     */
    public function segment($n, $no_result = null)
    {
        return $this->segments[$n] ?? $no_result;
    }

    // --------------------------------------------------------------------

    /**
     * Fetch URI "routed" Segment
     *
     * Returns the re-routed URI segment (assuming routing rules are used)
     * based on the index provided. If there is no routing, will return
     * the same result as CI_URI::segment().
     *
     * @see        CI_URI::$rsegments
     * @see        CI_URI::segment()
     *
     * @param int   $n         Index
     * @param mixed $no_result What to return if the segment index is not found
     *
     * @return    mixed
     */
    public function rsegment($n, $no_result = null)
    {
        return $this->rsegments[$n] ?? $no_result;
    }

    // --------------------------------------------------------------------

    /**
     * URI to assoc
     *
     * Generates an associative array of URI data starting at the supplied
     * segment index. For example, if this is your URI:
     *
     *    example.com/user/search/name/joe/location/UK/gender/male
     *
     * You can use this method to generate an array with this prototype:
     *
     *    array (
     *        name => joe
     *        location => UK
     *        gender => male
     *     )
     *
     * @param int   $n       Index (default: 3)
     * @param array $default Default values
     *
     * @return    array
     */
    public function uri_to_assoc($n = 3, $default = array())
    {
        return $this->_uri_to_assoc($n, $default, 'segment');
    }

    // --------------------------------------------------------------------

    /**
     * Routed URI to assoc
     *
     * Identical to CI_URI::uri_to_assoc(), only it uses the re-routed
     * segment array.
     *
     * @see        CI_URI::uri_to_assoc()
     *
     * @param int   $n       Index (default: 3)
     * @param array $default Default values
     *
     * @return    array
     */
    public function ruri_to_assoc($n = 3, $default = array())
    {
        return $this->_uri_to_assoc($n, $default, 'rsegment');
    }

    // --------------------------------------------------------------------

    /**
     * Internal URI-to-assoc
     *
     * Generates a key/value pair from the URI string or re-routed URI string.
     *
     * @used-by    CI_URI::uri_to_assoc()
     * @used-by    CI_URI::ruri_to_assoc()
     *
     * @param int    $n       Index (default: 3)
     * @param array  $default Default values
     * @param string $which   Array name ('segment' or 'rsegment')
     *
     * @return    array
     */
    protected function _uri_to_assoc($n = 3, $default = array(), $which = 'segment')
    {
        if (!is_numeric($n)) {
            return $default;
        }

        if (isset($this->keyval[$which], $this->keyval[$which][$n])) {
            return $this->keyval[$which][$n];
        }

        $total_segments = "total_{$which}s";
        $segment_array = "{$which}_array";

        if ($this->$total_segments() < $n) {
            return (count($default) === 0)
                ? array()
                : array_fill_keys($default, null);
        }

        $segments = array_slice($this->$segment_array(), ($n - 1));
        $i = 0;
        $lastval = '';
        $retval = array();
        foreach ($segments as $seg) {
            if ($i % 2) {
                $retval[$lastval] = $seg;
            } else {
                $retval[$seg] = null;
                $lastval = $seg;
            }

            $i++;
        }

        if (count($default) > 0) {
            foreach ($default as $val) {
                if (!array_key_exists($val, $retval)) {
                    $retval[$val] = null;
                }
            }
        }

        // Cache the array for reuse
        isset($this->keyval[$which]) or $this->keyval[$which] = array();
        $this->keyval[$which][$n] = $retval;

        return $retval;
    }

    // --------------------------------------------------------------------

    /**
     * Assoc to URI
     *
     * Generates a URI string from an associative array.
     *
     * @param array $array Input array of key/value pairs
     *
     * @return    string    URI string
     */
    public function assoc_to_uri($array)
    {
        $temp = array();
        foreach ((array) $array as $key => $val) {
            $temp[] = $key;
            $temp[] = $val;
        }

        return implode('/', $temp);
    }

    // --------------------------------------------------------------------

    /**
     * Slash segment
     *
     * Fetches an URI segment with a slash.
     *
     * @param int    $n     Index
     * @param string $where Where to add the slash ('trailing' or 'leading')
     *
     * @return    string
     */
    public function slash_segment($n, $where = 'trailing')
    {
        return $this->_slash_segment($n, $where, 'segment');
    }

    // --------------------------------------------------------------------

    /**
     * Slash routed segment
     *
     * Fetches an URI routed segment with a slash.
     *
     * @param int    $n     Index
     * @param string $where Where to add the slash ('trailing' or 'leading')
     *
     * @return    string
     */
    public function slash_rsegment($n, $where = 'trailing')
    {
        return $this->_slash_segment($n, $where, 'rsegment');
    }

    // --------------------------------------------------------------------

    /**
     * Internal Slash segment
     *
     * Fetches an URI Segment and adds a slash to it.
     *
     * @used-by    CI_URI::slash_segment()
     * @used-by    CI_URI::slash_rsegment()
     *
     * @param int    $n     Index
     * @param string $where Where to add the slash ('trailing' or 'leading')
     * @param string $which Array name ('segment' or 'rsegment')
     *
     * @return    string
     */
    protected function _slash_segment($n, $where = 'trailing', $which = 'segment')
    {
        $leading = $trailing = '/';

        if ($where === 'trailing') {
            $leading = '';
        } elseif ($where === 'leading') {
            $trailing = '';
        }

        return $leading . $this->$which($n) . $trailing;
    }

    // --------------------------------------------------------------------

    /**
     * Segment Array
     *
     * @return    array    CI_URI::$segments
     */
    public function segment_array()
    {
        return $this->segments;
    }

    // --------------------------------------------------------------------

    /**
     * Routed Segment Array
     *
     * @return    array    CI_URI::$rsegments
     */
    public function rsegment_array()
    {
        return $this->rsegments;
    }

    // --------------------------------------------------------------------

    /**
     * Total number of segments
     *
     * @return    int
     */
    public function total_segments()
    {
        return count($this->segments);
    }

    // --------------------------------------------------------------------

    /**
     * Total number of routed segments
     *
     * @return    int
     */
    public function total_rsegments()
    {
        return count($this->rsegments);
    }

    // --------------------------------------------------------------------

    /**
     * Fetch URI string
     *
     * @return    string    CI_URI::$uri_string
     */
    public function uri_string()
    {
        return $this->uri_string;
    }

    // --------------------------------------------------------------------
}