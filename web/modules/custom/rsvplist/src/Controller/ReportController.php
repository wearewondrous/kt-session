<?php

namespace Drupal\rsvplist\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Laminas\Feed\Reader\Extension\Atom\Entry;

/**
 * Controller for RSVP List Report
 */
class ReportController extends ControllerBase {
  /**
   * Gets all RSVPs for all nodes.
   *
   * @return array
   */
  public function load() {
    $select = Database::getConnection()->select('rsvplist', 'r');
    $select->join('users_field_data', 'u', 'r.uid = u.uid');
    $select->join('node_field_data', 'n', 'r.nid = n.nid');
    $select->addField('u', 'name', 'username');
    $select->addField('n', 'title');
    $select->addField('r', 'mail');
    $entries = $select->execute()->fetchAll(\PDO::FETCH_ASSOC);
    var_dump($entries);
    return $entries;
  }

  /**
   * Creates the report page.
   *
   * @return array
   *  Render array for report output.
   */
  public function report() {
    $content = [];
    $content['message'] = [
      '#markup' => $this->t('Below is a list of all Event RSVPs including username, email address and the name of the event they will be attending to.'),
    ];
    $headers = [
      $this->t('Name'),
      $this->t('Event'),
      $this->t('Email'),
    ];
    $rows = [];
    foreach ($entries = $this->load() as $entry) {
      $rows[] = array_map('Drupal\Component\Utility\SafeMarkup::checkPlain', $entry);
    }
    $content['table'] = [
      '#type' => 'table',
      '#header' => $headers,
      '#rows' => $rows,
      '#empty' => $this->t('No entries available'),
    ];
    $content['#cache']['max-age'] = 0;
    return $content;
  }

}
