<?php
$settings['cache_ttl_4xx'] = 3600;
// Default Drupal 8 settings.
//
// These are already explained with detailed comments in Drupal's
// default.settings.php file.
//
// See https://api.drupal.org/api/drupal/sites!default!default.settings.php/8
// customized project settings
include $app_root . '/../vendor/wearewondrous/psh-toolbelt/src/site.settings.php';
// Local settings. These come last so that they can override anything.
if (file_exists($app_root . '/' . $site_path . '/settings.local.php')) {
  include $app_root . '/' . $site_path . '/settings.local.php';
}
