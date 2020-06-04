<?php
$site = [
  'api.helsingborg.dev' => 1,
  'api.helsingborg.dev/event' => 2,
][$_SERVER['HTTP_HOST']];

define('WP_ALLOW_MULTISITE', true);
define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', false);
define('DOMAIN_CURRENT_SITE', $_SERVER['HTTP_HOST']);
define('PATH_CURRENT_SITE', '/');
define('SITE_ID_CURRENT_SITE', $site);
define('BLOG_ID_CURRENT_SITE', $site);

define('WP_LOAD_PATH', __DIR__ . '/../wp/');