<?php

class EmptyCache
{
    public function __construct()
    {
        add_action('save_post_guide', array($this, 'emptyCache'), 99);
    }

    /**
     * Empty Redis cache after a guide is saved
     * @param $postId
     */
    public function emptyCache($postId)
    {
        shell_exec("redis-cli --scan --pattern '*" . $postId . "*' | xargs redis-cli DEL");
        shell_exec("redis-cli --scan --pattern '*guide*' | xargs redis-cli DEL");
        shell_exec("redis-cli --scan --pattern '*navigation*' | xargs redis-cli DEL");
    }
}

new EmptyCache();