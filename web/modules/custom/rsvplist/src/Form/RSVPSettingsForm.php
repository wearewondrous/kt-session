<?php
/**
 * @file
 * Contains \Drupal\rsvplist\Form\RSVPSettingsForm.
 */

namespace Drupal\rsvplist\Form;

use Drupal\Core\Form\ConfigFormBase;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines a form to configure RSVP List module settings.
 */
class RSVPSettingsForm extends ConfigFormBase {

  /**
   * (@inheritDoc)
   */
  public function getFormId() {
    return 'rsvplist_admin_settings';
  }

  /**
   * (@inheritDoc)
   */
  protected function getEditableConfigNames() {
    return [
      'rsvplist.settings'
    ];
  }

  /**
   * (@inheritDoc)
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $types = node_type_get_names();
    $config = $this->config('rsvplist.settings');
    $form['rsvplist_types'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('The content types to enable RSVP collection for'),
      '#default_value' => $config->get('allowed_types') ?: [],
      '#options' => $types,
      '#description' => $this->t('On the specified node types, an RSVP option will be available and can be enabled while that node is being edited.'),
    ];
    $form['array_filter'] = ['#type' => 'value', '#value' => TRUE];
    return parent::buildForm($form, $form_state);
  }

  /**
   * (@inheritDoc)
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $allowed_types = [$form_state->getValue('rsvplist_types')];
    sort($allowed_types);
    $this->config('')
      ->set('allowed_types', $allowed_types)
      ->save();
    parent::submitForm($form, $form_state);
  }
}
