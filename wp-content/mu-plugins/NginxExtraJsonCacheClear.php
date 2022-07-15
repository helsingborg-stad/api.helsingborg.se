<?php

/*
Plugin Name: Nginx Helper Extra Json Cache Clear
Description: Hooks into Nginx Helper plugin and Modularity to do a more complete cache clear, query strings etc.
Version:     1.0
Author:      Joel Bernerman, Helsingborg Stad
*/

namespace NginxExtraJsonCacheClear;

class NginxExtraJsonCacheClear
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        // Only run if nginx helper local cache file path constant is set.
        if (defined('RT_WP_NGINX_HELPER_CACHE_PATH')) {
            add_filter('rt_nginx_helper_purge_urls', [$this, 'clearJsonPath'], 10, 1);
        }
    }

    /**
     * Generate the cache key based on url.
     * @param string $url URL to use for cache key generation.
     *
     * @return string The cache key.
     */
    public function generateCacheKey($url)
    {
        $urlData = wp_parse_url($url);
        return $urlData['scheme'] . 'GET' . $urlData['host'] . $urlData['path'];
    }

    /**
     * Cache clear json path.
     * @param string $url Url sent in filter.
     *
     * @return string Return url untouched.
     */
    public function clearJsonPath($urls)
    {
        // Skip home url so we dont purge everything all the time.
        $jsonUrl = get_home_url() . '/json/';

        // Build the cache key and add wildcard * in the end.
        $cacheKeyRegex = $this->generateCacheKey($jsonUrl) . '\?.*';

        // Command to grep for key in cache files.
        $command = 'find ' . RT_WP_NGINX_HELPER_CACHE_PATH . ' -type f | ' .
                    'xargs --no-run-if-empty -n1000 grep -El -m 1 ' .
                    '"^KEY: ' . $$cacheKeyRegex . '"';

        // Get recursive files and nuke em!
        $cacheFiles = [];
	exec($command, $cacheFiles);
        foreach ($cacheFiles as $cacheFile) {
            if (file_exists($cacheFile)) {
                unlink($cacheFile);
            }
        }

        return $urls;
    }
}

new \NginxExtraJsonCacheClear\NginxExtraJsonCacheClear();
