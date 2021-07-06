<?php
/**
 * @file
 * Contains \Drupal\kt_session\Controller\HomeController.
 */

namespace Drupal\kt_session\Controller;

use Drupal\Core\Controller\ControllerBase;

class HomeController extends ControllerBase {
  public function content() {
    return [
      '#type' => 'markup',
      '#markup' => t('This is my menu linked custom page'),
    ];
  }
}
