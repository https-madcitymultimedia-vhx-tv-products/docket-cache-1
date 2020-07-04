<?php
/**
 * @wordpress-plugin
 * Plugin Name:         Docket Cache Drop-in
 * Version:             20200701
 * Description:         A persistent WP Object Cache stored on local disk.
 * Author:              Nawawi Jamili
 * Author URI:          https://rutweb.com
 * Requires at least:   5.4
 * Requires PHP:        7.2
 * License:             MIT
 * License URI:         https://opensource.org/licenses/MIT
 */

/**
 * Based on:
 *  wp-includes/cache.php
 *  wp-includes/class-wp-object-cache.php.
 */

/**
 * Check if caching is not disabled.
 * If false, prevent functions and classes from being defined.
 * See wp_start_object_cache() -> wp-includes/load.php.
 */
if (\defined('DOCKET_CACHE_DISABLED') && DOCKET_CACHE_DISABLED) {
    return;
}

/*
 * Check for minimum php version.
 * If not match, prevent functions and classes from being defined.
 * See wp_start_object_cache() -> wp-includes/load.php.
 *
 */
if (version_compare(PHP_VERSION, '7.2', '<')) {
    return;
}

/**
 * Adds data to the cache, if the cache key doesn't already exist.
 *
 * @see WP_Object_Cache::add()
 *
 * @global WP_Object_Cache $wp_object_cache Object cache global instance.
 *
 * @param int|string $key    the cache key to use for retrieval later
 * @param mixed      $data   the data to add to the cache
 * @param string     $group  Optional. The group to add the cache to. Enables the same key
 *                           to be used across groups. Default empty.
 * @param int        $expire Optional. When the cache data should expire, in seconds.
 *                           Default 0 (no expiration).
 *
 * @return bool true on success, false if cache key and group already exist
 */
function wp_cache_add($key, $data, $group = '', $expire = 0)
{
    global $wp_object_cache;

    return $wp_object_cache->add($key, $data, $group, (int) $expire);
}

/**
 * Closes the cache.
 *
 * This function has ceased to do anything since WordPress 2.5. The
 * functionality was removed along with the rest of the persistent cache. This
 * does not mean that plugins can't implement this function when they need to
 * make sure that the cache is cleaned up after WordPress no longer needs it.
 *
 * @return bool Always returns True
 */
function wp_cache_close()
{
    return true;
}

/**
 * Decrements numeric cache item's value.
 *
 * @see WP_Object_Cache::decr()
 *
 * @global WP_Object_Cache $wp_object_cache Object cache global instance.
 *
 * @param int|string $key    the cache key to decrement
 * @param int        $offset Optional. The amount by which to decrement the item's value. Default 1.
 * @param string     $group  Optional. The group the key is in. Default empty.
 *
 * @return int|false the item's new value on success, false on failure
 */
function wp_cache_decr($key, $offset = 1, $group = '')
{
    global $wp_object_cache;

    return $wp_object_cache->decr($key, $offset, $group);
}

/**
 * Removes the cache contents matching key and group.
 *
 * @see WP_Object_Cache::delete()
 *
 * @global WP_Object_Cache $wp_object_cache Object cache global instance.
 *
 * @param int|string $key   what the contents in the cache are called
 * @param string     $group Optional. Where the cache contents are grouped. Default empty.
 *
 * @return bool true on successful removal, false on failure
 */
function wp_cache_delete($key, $group = '')
{
    global $wp_object_cache;

    return $wp_object_cache->delete($key, $group);
}

/**
 * Removes all cache items.
 *
 * @see WP_Object_Cache::flush()
 *
 * @global WP_Object_Cache $wp_object_cache Object cache global instance.
 *
 * @return bool true on success, false on failure
 */
function wp_cache_flush()
{
    global $wp_object_cache;

    return $wp_object_cache->flush();
}

/**
 * Retrieves the cache contents from the cache by key and group.
 *
 * @see WP_Object_Cache::get()
 *
 * @global WP_Object_Cache $wp_object_cache Object cache global instance.
 *
 * @param int|string $key   the key under which the cache contents are stored
 * @param string     $group Optional. Where the cache contents are grouped. Default empty.
 * @param bool       $force Optional. Whether to force an update of the local cache from the persistent
 *                          cache. Default false.
 * @param bool       $found Optional. Whether the key was found in the cache (passed by reference).
 *                          Disambiguates a return of false, a storable value. Default null.
 *
 * @return bool|mixed False on failure to retrieve contents or the cache
 *                    contents on success
 */
function wp_cache_get($key, $group = '', $force = false, &$found = null)
{
    global $wp_object_cache;

    return $wp_object_cache->get($key, $group, $force, $found);
}

/**
 * Increment numeric cache item's value.
 *
 * @see WP_Object_Cache::incr()
 *
 * @global WP_Object_Cache $wp_object_cache Object cache global instance.
 *
 * @param int|string $key    the key for the cache contents that should be incremented
 * @param int        $offset Optional. The amount by which to increment the item's value. Default 1.
 * @param string     $group  Optional. The group the key is in. Default empty.
 *
 * @return int|false the item's new value on success, false on failure
 */
function wp_cache_incr($key, $offset = 1, $group = '')
{
    global $wp_object_cache;

    return $wp_object_cache->incr($key, $offset, $group);
}

/**
 * Sets up Object Cache Global and assigns it.
 *
 * @global WP_Object_Cache $wp_object_cache
 */
function wp_cache_init()
{
    $GLOBALS['wp_object_cache'] = new WP_Object_Cache();
}

/**
 * Replaces the contents of the cache with new data.
 *
 * @see WP_Object_Cache::replace()
 *
 * @global WP_Object_Cache $wp_object_cache Object cache global instance.
 *
 * @param int|string $key    the key for the cache data that should be replaced
 * @param mixed      $data   the new data to store in the cache
 * @param string     $group  Optional. The group for the cache data that should be replaced.
 *                           Default empty.
 * @param int        $expire Optional. When to expire the cache contents, in seconds.
 *                           Default 0 (no expiration).
 *
 * @return bool False if original value does not exist, true if contents were replaced
 */
function wp_cache_replace($key, $data, $group = '', $expire = 0)
{
    global $wp_object_cache;

    return $wp_object_cache->replace($key, $data, $group, (int) $expire);
}

/**
 * Saves the data to the cache.
 *
 * Differs from wp_cache_add() and wp_cache_replace() in that it will always write data.
 *
 * @see WP_Object_Cache::set()
 *
 * @global WP_Object_Cache $wp_object_cache Object cache global instance.
 *
 * @param int|string $key    the cache key to use for retrieval later
 * @param mixed      $data   the contents to store in the cache
 * @param string     $group  Optional. Where to group the cache contents. Enables the same key
 *                           to be used across groups. Default empty.
 * @param int        $expire Optional. When to expire the cache contents, in seconds.
 *                           Default 0 (no expiration).
 *
 * @return bool true on success, false on failure
 */
function wp_cache_set($key, $data, $group = '', $expire = 0)
{
    global $wp_object_cache;

    return $wp_object_cache->set($key, $data, $group, (int) $expire);
}

/**
 * Switches the internal blog ID.
 *
 * This changes the blog id used to create keys in blog specific groups.
 *
 * @see WP_Object_Cache::switch_to_blog()
 *
 * @global WP_Object_Cache $wp_object_cache Object cache global instance.
 *
 * @param int $blog_id site ID
 */
function wp_cache_switch_to_blog($blog_id)
{
    global $wp_object_cache;

    $wp_object_cache->switch_to_blog($blog_id);
}

/**
 * Adds a group or set of groups to the list of global groups.
 *
 * @see WP_Object_Cache::add_global_groups()
 *
 * @global WP_Object_Cache $wp_object_cache Object cache global instance.
 *
 * @param string|array $groups a group or an array of groups to add
 */
function wp_cache_add_global_groups($groups)
{
    global $wp_object_cache;

    $wp_object_cache->add_global_groups($groups);
}

/**
 * Adds a group or set of groups to the list of non-persistent groups.
 *
 * @param string|array $groups a group or an array of groups to add
 */
function wp_cache_add_non_persistent_groups($groups)
{
    global $wp_object_cache;
    $wp_object_cache->add_non_persistent_groups($groups);
}

/**
 * Adds a key or set of keys to the list of non-persistent keys.
 *
 * @param string|array $keys a key or an array of keys to add
 */
function wp_cache_add_non_persistent_keys($keys)
{
    global $wp_object_cache;
    $wp_object_cache->add_non_persistent_keys($keys);
}

function wp_cache_stats()
{
    global $wp_object_cache;
    $wp_object_cache->stats();
}

/**
 * Core class that implements an object cache.
 */
class WP_Object_Cache
{
    /**
     * Holds the cached objects.
     *
     * @var array
     */
    private $cache = [];

    /**
     * The amount of times the cache data was already stored in the cache.
     *
     * @var int
     */
    public $cache_hits = 0;

    /**
     * Amount of times the cache did not have the request in cache.
     *
     * @var int
     */
    public $cache_misses = 0;

    /**
     * List of global cache groups.
     *
     * @var array
     */
    protected $global_groups = [
        'blog-details',
        'blog-id-cache',
        'blog-lookup',
        'global-posts',
        'networks',
        'rss',
        'sites',
        'site-details',
        'site-lookup',
        'site-options',
        'site-transient',
        'users',
        'useremail',
        'userlogins',
        'usermeta',
        'user_meta',
        'userslugs',
    ];

    /**
     * List of non persistent groups.
     *
     * @var array
     */
    protected $non_persistent_groups = [
        'user_meta',
        'counts',
        'plugins',
        'themes',
        'comment',
        'wc_session_id',
        'bp_notifications',
        'bp_messages',
        'bp_pages',
    ];

    /**
     * List of non persistent keys.
     *
     * @var array
     */
    protected $non_persistent_keys = [];

    /**
     * The blog prefix to prepend to keys in non-global groups.
     *
     * @var string
     */
    private $blog_prefix;

    /**
     * Holds the value of is_multisite().
     *
     * @var bool
     */
    private $multisite;

    /**
     * The cache path.
     *
     * @var string
     */
    private $store_path;

    /**
     * The cache maximum time-to-live.
     *
     * @var int
     */
    private $maxttl = 0;

    /**
     * Sets up object properties.
     */
    public function __construct()
    {
        $this->multisite = is_multisite();
        $this->blog_prefix = $this->switch_to_blog(get_current_blog_id());
        $this->docket_init();
    }

    /**
     * Adds data to the cache if it doesn't already exist.
     *
     * @uses WP_Object_Cache::_exists() Checks to see if the cache already has data.
     * @uses WP_Object_Cache::set()     Sets the data after the checking the cache
     *                                  contents existence.
     *
     * @param int|string $key    what to call the contents in the cache
     * @param mixed      $data   the contents to store in the cache
     * @param string     $group  Optional. Where to group the cache contents. Default 'default'.
     * @param int        $expire Optional. When to expire the cache contents. Default 0 (no expiration).
     *
     * @return bool true on success, false if cache key and group already exist
     */
    public function add($key, $data, $group = 'default', $expire = 0)
    {
        if (wp_suspend_cache_addition()) {
            return false;
        }

        if (empty($group)) {
            $group = 'default';
        }

        $id = $this->define_key($key, $group);
        if ($this->_exists($id, $group)) {
            return false;
        }

        return $this->set($key, $data, $group, (int) $expire);
    }

    /**
     * Sets the list of global cache groups.
     *
     * @param array $groups list of groups that are global
     */
    public function add_global_groups($groups)
    {
        $groups = (array) $groups;

        $groups = array_fill_keys($groups, true);
        $this->global_groups = array_merge($this->global_groups, $groups);
    }

    /**
     * Decrements numeric cache item's value.
     *
     * @param int|string $key    the cache key to decrement
     * @param int        $offset Optional. The amount by which to decrement the item's value. Default 1.
     * @param string     $group  Optional. The group the key is in. Default 'default'.
     *
     * @return int|false the item's new value on success, false on failure
     */
    public function decr($key, $offset = 1, $group = 'default')
    {
        if (empty($group)) {
            $group = 'default';
        }

        $key = $this->define_key($key, $group);

        if (!$this->_exists($key, $group)) {
            return false;
        }

        if (!is_numeric($this->cache[$group][$key])) {
            $this->cache[$group][$key] = 0;
        }

        $offset = (int) $offset;

        $this->cache[$group][$key] -= $offset;

        if ($this->cache[$group][$key] < 0) {
            $this->cache[$group][$key] = 0;
        }

        $this->docket_update($key, $this->cache[$group][$key], $group);

        return $this->cache[$group][$key];
    }

    /**
     * Removes the contents of the cache key in the group.
     *
     * If the cache key does not exist in the group, then nothing will happen.
     *
     * @param int|string $key        what the contents in the cache are called
     * @param string     $group      Optional. Where the cache contents are grouped. Default 'default'.
     * @param bool       $deprecated Optional. Unused. Default false.
     *
     * @return bool false if the contents weren't deleted and true on success
     */
    public function delete($key, $group = 'default', $deprecated = false)
    {
        if (empty($group)) {
            $group = 'default';
        }

        $key = $this->define_key($key, $group);

        if (!$this->_exists($key, $group)) {
            return false;
        }

        unset($this->cache[$group][$key]);
        $this->docket_remove($key, $group);

        return true;
    }

    /**
     * Clears the object cache of all data.
     *
     * @return true always returns true
     */
    public function flush()
    {
        $this->cache = [];

        return $this->docket_flush();
    }

    /**
     * Retrieves the cache contents, if it exists.
     *
     * The contents will be first attempted to be retrieved by searching by the
     * key in the cache group. If the cache is hit (success) then the contents
     * are returned.
     *
     * On failure, the number of cache misses will be incremented.
     *
     * @param int|string $key   what the contents in the cache are called
     * @param string     $group Optional. Where the cache contents are grouped. Default 'default'.
     * @param bool       $force Optional. Unused. Whether to force a refetch rather than relying on the local
     *                          cache. Default false.
     * @param bool       $found Optional. Whether the key was found in the cache (passed by reference).
     *                          Disambiguates a return of false, a storable value. Default null.
     *
     * @return mixed|false the cache contents on success, false on failure to retrieve contents
     */
    public function get($key, $group = 'default', $force = false, &$found = null)
    {
        if (empty($group)) {
            $group = 'default';
        }

        $key = $this->define_key($key, $group);

        if ($this->_exists($key, $group)) {
            $found = true;
            ++$this->cache_hits;
            if (\is_object($this->cache[$group][$key])) {
                return clone $this->cache[$group][$key];
            }

            return $this->cache[$group][$key];
        }

        $found = false;
        ++$this->cache_misses;

        return false;
    }

    /**
     * Increments numeric cache item's value.
     *
     * @param int|string $key    The cache key to increment
     * @param int        $offset Optional. The amount by which to increment the item's value. Default 1.
     * @param string     $group  Optional. The group the key is in. Default 'default'.
     *
     * @return int|false the item's new value on success, false on failure
     */
    public function incr($key, $offset = 1, $group = 'default')
    {
        if (empty($group)) {
            $group = 'default';
        }

        $key = $this->define_key($key, $group);

        if (!$this->_exists($key, $group)) {
            return false;
        }

        if (!is_numeric($this->cache[$group][$key])) {
            $this->cache[$group][$key] = 0;
        }

        $offset = (int) $offset;

        $this->cache[$group][$key] += $offset;

        if ($this->cache[$group][$key] < 0) {
            $this->cache[$group][$key] = 0;
        }

        $this->docket_update($key, $this->cache[$group][$key], $group);

        return $this->cache[$group][$key];
    }

    /**
     * Replaces the contents in the cache, if contents already exist.
     *
     * @see WP_Object_Cache::set()
     *
     * @param int|string $key    what to call the contents in the cache
     * @param mixed      $data   the contents to store in the cache
     * @param string     $group  Optional. Where to group the cache contents. Default 'default'.
     * @param int        $expire Optional. When to expire the cache contents. Default 0 (no expiration).
     *
     * @return bool false if not exists, true if contents were replaced
     */
    public function replace($key, $data, $group = 'default', $expire = 0)
    {
        if (empty($group)) {
            $group = 'default';
        }

        $id = $this->define_key($key, $group);

        if (!$this->_exists($id, $group)) {
            return false;
        }

        return $this->set($key, $data, $group, (int) $expire);
    }

    /**
     * Sets the data contents into the cache.
     *
     * The cache contents are grouped by the $group parameter followed by the
     * $key. This allows for duplicate ids in unique groups. Therefore, naming of
     * the group should be used with care and should follow normal function
     * naming guidelines outside of core WordPress usage.
     *
     * @param int|string $key    what to call the contents in the cache
     * @param mixed      $data   the contents to store in the cache
     * @param string     $group  Optional. Where to group the cache contents. Default 'default'.
     * @param int        $expire the expiration time, defaults to 0
     *
     * @return true always returns true
     */
    public function set($key, $data, $group = 'default', $expire = 0)
    {
        if (empty($group)) {
            $group = 'default';
        }

        $key = $this->define_key($key, $group);

        if (\is_object($data)) {
            $data = clone $data;
        }

        $this->cache[$group][$key] = $data;

        if (!$this->is_non_persistent_groups($group) && !$this->is_non_persistent_keys($key)) {
            $expire = (int) $expire;
            if (0 !== $this->maxttl && 0 === $expire) {
                $expire = $this->maxttl;
            }

            $this->docket_save($key, $this->cache[$group][$key], $group, $expire);
        }

        return true;
    }

    /**
     * Echoes the stats of the caching.
     *
     * Gives the cache hits, and cache misses. Also prints every cached group,
     * key and the data.
     */
    public function stats()
    {
        $ret = '';
        $ret .= '<p>';
        $ret .= "<strong>Cache Hits:</strong> {$this->cache_hits}<br />";
        $ret .= "<strong>Cache Misses:</strong> {$this->cache_misses}<br />";
        $ret .= '</p>';
        $ret .= '<ul>';
        foreach ($this->cache as $group => $cache) {
            $ret .= '<li><strong>Group:</strong> '.esc_html($group).' - ( '.number_format(\strlen(serialize($cache)) / KB_IN_BYTES, 2).'K )</li>';
        }
        $ret .= '</ul>';
        echo $ret;
    }

    /**
     * Switches the internal blog ID.
     *
     * This changes the blog ID used to create keys in blog specific groups.
     *
     * @param int $blog_id blog ID
     */
    public function switch_to_blog($blog_id)
    {
        $blog_id = (int) $blog_id;
        $this->blog_prefix = $this->multisite ? $blog_id.':' : '';
    }

    /**
     * Serves as a utility function to determine whether a key exists in the cache.
     *
     * @since 3.4.0
     *
     * @param int|string $key   cache key to check for existence
     * @param string     $group cache group for the key existence check
     *
     * @return bool whether the key exists in the cache for the given group
     */
    protected function _exists($key, $group)
    {
        $is_exists = isset($this->cache[$group]) && (isset($this->cache[$group][$key]) || \array_key_exists($key, $this->cache[$group]));
        if (!$is_exists) {
            $data = $this->docket_get($key, $group);
            if (false !== $data) {
                $this->cache[$group][$key] = $data;
                $is_exists = true;
                $hash = $this->item_key($key, $group);
                $this->debug('hit', $group.':'.$key, $hash);
            }
        }

        return $is_exists;
    }

    /**
     * Sets the list of non persistent groups.
     *
     * @param array $groups list of groups that are to be ignored
     */
    public function add_non_persistent_groups($groups)
    {
        $groups = (array) $groups;

        $this->non_persistent_groups = array_unique(array_merge($this->non_persistent_groups, $groups));
    }

    /**
     * Check if group in non persistent groups.
     *
     * @param bool $group cache group
     */
    protected function is_non_persistent_groups($group)
    {
        return !empty($this->non_persistent_groups) && \in_array($group, $this->non_persistent_groups);
    }

    /**
     * Sets the list of non persistent keys.
     *
     * @param array $keys list of keys that are to be ignored
     */
    public function add_non_persistent_keys($keys)
    {
        $keys = (array) $keys;

        $this->non_persistent_keys = array_unique(array_merge($this->non_persistent_keys, $keys));
    }

    /**
     * Check if key in non persistent groups.
     *
     * @param bool $key cache group
     */
    protected function is_non_persistent_keys($key)
    {
        $key = str_replace($this->blog_prefix, '', $key);

        return !empty($this->non_persistent_keys) && \in_array($key, $this->non_persistent_keys);
    }

    private function define_key($key, $group)
    {
        if ($this->multisite && !isset($this->global_groups[$group])) {
            $key = $this->blog_prefix.$key;
        }

        return $key;
    }

    private function export_var($data)
    {
        try {
            $data = Symfony\Component\VarExporter\VarExporter::export($data);
        } catch (\Exception $e) {
            $error = $e->getMessage();
            if (false !== strpos($error, 'Cannot export value of type "stdClass"')) {
                $data = var_export($data, 1);
                $data = str_replace('stdClass::__set_state', 'docket_cache_fix_object', $data);
            } else {
                $this->debug('err', __FUNCTION__, $e->getMessage());
            }
        }

        return $data;
    }

    private function docket_flush()
    {
        $dir = $this->store_path;

        clearstatcache();
        $dir = realpath($dir);
        $cnt = 0;
        if (false !== $dir && is_dir($dir) && is_writable($dir)) {
            $dir = realpath($dir);
            try {
                foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS)) as $object) {
                    $f = $object->getPathName();
                    if (is_file($f) && is_writable($f)) {
                        unlink($f);
                        if ('index.php' !== basename($f)) {
                            ++$cnt;
                        }
                    }
                }
            } catch (\Exception $e) {
                $this->debug('err', __FUNCTION__, $e->getMessage());
            }
        }

        if (!@file_exists($dir.'/index.php')) {
            $this->debug('flush', 'OK', $cnt);

            return true;
        }

        $this->debug('err', __FUNCTION__, 'Object cache could not be flushed');

        return false;
    }

    private function item_hash($str, $length = 12)
    {
        return substr(md5($str), 0, $length);
    }

    private function item_key($key, $group)
    {
        return $this->item_hash($group).'-'.$this->item_hash($key);
    }

    private function get_file_path($key, $group)
    {
        return $this->store_path.$this->item_key($key, $group).'.php';
    }

    private function docket_remove($key, $group)
    {
        $file = $this->get_file_path($key, $group);

        if (file_exists($file) && is_writable($file)) {
            $file = $this->get_file_path($key, $group);
            if (!@unlink($file)) {
                $this->debug('err', __FUNCTION__, 'Failed to remove file: '.$file);

                return false;
            }

            $this->debug('del', $group.':'.$key, basename($file, '.php'));

            return false;
        }

        return false;
    }

    private function docket_get($key, $group)
    {
        $file = $this->get_file_path($key, $group);
        if (!file_exists($file)) {
            return false;
        }
        $data = @include $file;
        if (empty($data)) {
            return false;
        }

        if ($data['timeout'] > 0 && time() >= $data['timeout']) {
            $this->debug('exp', $group.':'.$key, basename($file, '.php'));
            $this->docket_remove($key, $group);

            return false;
        }

        return $data['data'];
    }

    private function docket_save($key, $data, $group = 'default', $expire = 0)
    {
        if (!wp_mkdir_p($this->store_path)) {
            $this->debug('err', __FUNCTION__, 'Unable to create directory: '.$this->store_path);

            return false;
        }

        @$this->file_put($this->store_path.'/index.php', ' ');

        $file = $this->get_file_path($key, $group);

        $timeout = ($expire > 0 ? time() + $expire : 0);

        $type = \gettype($data);
        $meta = [
            'group' => $group,
            'key' => $key,
            'type' => $type,
            'timeout' => $timeout,
            'data' => $data,
        ];

        $data = $this->export_var($meta);

        $code = '<?php ';
        $code .= 'return '.$data.';'.PHP_EOL;

        if ($this->file_put($file, $code)) {
            $this->debug('set', $group.':'.$key, basename($file, '.php'));

            return true;
        }

        return false;
    }

    private function docket_update($key, $data, $group)
    {
        $meta = $this->docket_get($key, $group);
        if (false === $meta) {
            return false;
        }

        $file = $this->get_file_path($key, $group);
        $meta['data'] = $data;
        $data = $this->export_var($meta);

        $code = '<?php ';
        $code .= 'return '.$data.';'.PHP_EOL;

        return $this->file_put($file, $code);
    }

    private function chmod($file)
    {
        static $cache = [];

        if (isset($cache[$file])) {
            return $cache[$file];
        }

        $stat = @stat(\dirname($file));
        $perms = $stat['mode'] & 0007777;
        $perms = $perms & 0000666;
        if (@chmod($file, $perms)) {
            $cache[$file] = $perms;
        }

        clearstatcache();
    }

    private function file_put($file, $data)
    {
        if (!$handle = @fopen($file, 'wb')) {
            $this->debug('err', __FUNCTION__, 'Unable to write to file: '.$file);

            return false;
        }

        $ok = @fwrite($handle, $data);
        fclose($handle);
        clearstatcache();

        if (false !== $ok) {
            $this->chmod($file);
            $ok = true;
        }

        return $ok;
    }

    private function docket_init()
    {
        if (\defined('DOCKET_CACHE_GLOBAL_GROUPS') && \is_array(DOCKET_CACHE_GLOBAL_GROUPS)) {
            $this->global_groups = DOCKET_CACHE_GLOBAL_GROUPS;
        }

        if (\defined('DOCKET_CACHE_IGNORED_GROUPS') && \is_array(DOCKET_CACHE_IGNORED_GROUPS)) {
            $this->non_persistent_groups = DOCKET_CACHE_IGNORED_GROUPS;
        }

        if (\defined('DOCKET_CACHE_IGNORED_KEYS') && \is_array(DOCKET_CACHE_IGNORED_KEYS)) {
            $this->non_persistent_keys = DOCKET_CACHE_IGNORED_KEYS;
        }

        if (\defined('DOCKET_CACHE_MAXTTL') && \is_int(DOCKET_CACHE_MAXTTL)) {
            $this->maxttl = (int) DOCKET_CACHE_MAXTTL;
            if ($this->maxttl > PHP_INT_MAX - 100) {
                $this->maxttl = PHP_INT_MAX;
            }
        }

        if (!\defined('DOCKET_CACHE_DEBUG')) {
            \define('DOCKET_CACHE_DEBUG', false);
        }

        if (!\defined('DOCKET_CACHE_DEBUG_FILE')) {
            \define('DOCKET_CACHE_DEBUG_FILE', WP_CONTENT_DIR.'/object-cache.log');
        }

        if (!\defined('DOCKET_CACHE_DEBUG_FLUSH')) {
            \define('DOCKET_CACHE_DEBUG_FLUSH', true);
        }

        if (!\defined('DOCKET_CACHE_DEBUG_SIZE')) {
            \define('DOCKET_CACHE_DEBUG_SIZE', 10000000);
        }

        $autoload = sprintf(
                        '%s/docket-cache/includes/load.php',
                        \defined('WP_PLUGIN_DIR') ? WP_PLUGIN_DIR : WP_CONTENT_DIR.'/plugins'
                    );

        if (!file_exists($autoload)) {
            throw new \Exception('Docket library not found. Re-install Docket Cache plugin or delete object-cache.php.');
        }

        include_once $autoload;
        $this->store_path = \defined('DOCKET_CACHE_PATH') && is_dir(DOCKET_CACHE_PATH) && '/' !== DOCKET_CACHE_PATH ? DOCKET_CACHE_PATH : WP_CONTENT_DIR.'/cache/docket-cache/';

        foreach (['added', 'updated', 'deleted'] as $prefix) {
            add_action(
                $prefix.'_option',
                function ($option) use ($prefix) {
                    if (!wp_installing()) {
                        $alloptions = wp_load_alloptions();
                        if (isset($alloptions[$option])) {
                            add_action(
                                'shutdown',
                                function () {
                                    wp_cache_delete('alloptions', 'options');
                                },
                                PHP_INT_MAX
                            );
                        }
                    }
                },
                PHP_INT_MAX
            );
        }

        foreach (['activate', 'deactivate'] as $prefix) {
            add_action(
                $prefix.'_plugin',
                function ($plugin, $network) use ($prefix) {
                    if ($this->multisite) {
                        add_action(
                            'shutdown',
                            function () {
                                wp_cache_delete(get_network()->site_id.':active_sitewide_plugins', 'site-options');
                            },
                            PHP_INT_MAX
                        );
                    }
                    add_action(
                        'shutdown',
                        function () {
                            wp_cache_delete('uninstall_plugins', 'options');
                        },
                        PHP_INT_MAX
                    );
                },
                PHP_INT_MAX,
                2
            );
        }
    }

    private function debug($tag, $id, $data, $is_append = true)
    {
        if (!DOCKET_CACHE_DEBUG) {
            return false;
        }
        static $duplicate = [];

        $flag = LOCK_EX;
        if ($is_append) {
            $flag = FILE_APPEND | LOCK_EX;
        }

        $file_log = DOCKET_CACHE_DEBUG_FILE;

        if (file_exists($file_log)) {
            if (DOCKET_CACHE_DEBUG_FLUSH && 'flush' === $tag || filesize($file_log) >= (int) DOCKET_CACHE_DEBUG_SIZE) {
                $flag = LOCK_EX;
            }
        }

        $log = '['.date('Y-m-d H:i:s e').'] '.$tag.': "'.$id.'" "'.trim($data).'"';
        if (!empty($_SERVER['REQUEST_URI'])) {
            $log .= ' "'.$_SERVER['REQUEST_URI'].'"';
        }

        if (isset($duplicate[$log])) {
            return true;
        }

        if (@file_put_contents($file_log, $log."\n", $flag)) {
            $duplicate[$log] = 1;
            $this->chmod($log);

            return true;
        }

        return false;
    }
}

if (!\function_exists('docket_cache_fix_object')) {
    function docket_cache_fix_object($arr)
    {
        return (object) $arr;
    }
}
