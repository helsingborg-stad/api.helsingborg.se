<?php

class EmptyCache
{
    public function __construct()
    {
        add_action('save_post_guide', array($this, 'emptyCache'), 99);
    }

    /**
     * Empty Redis cache after a guide is saved
     */
    public function emptyCache()
    {
        shell_exec('redis-cli flushall');
    }
}

new EmptyCache();