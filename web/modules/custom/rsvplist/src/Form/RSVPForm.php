<?php
/**
 * @file
 * Contains \Drupal\rsvplist\Form\RSVPForm.
 */

namespace Drupal\rsvplist\Form;

use Drupal\Core\Database\Connection;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\NodeInterface;
use Drupal\Component\Utility\EmailValidator;
use Drupal\user\UserStorageInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
/**
 * Provides an RSVP Email form.
 */
class RSVPForm extends FormBase {

  /**
   * @var EmailValidator $emailValidator
   */
  protected $emailValidator;

  /**
   * @var Connection $database
   */
  protected $database;

  /**
   * @var UserStorageInterface $user
   */
  protected $user;

  /**
   * (@inheritDoc)
   */
  public function __construct(EmailValidator $emailValidator, Connection $database, UserStorageInterface $user_storage) {
    $this->emailValidator = $emailValidator;
    $this->database = $database;
    $this->user = $user_storage;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    // Instantiates this form class.
    return new static(
      // Load the service required to construct this class.
      $container->get('email.validator'),
      $container->get('database'),
      $container->get('entity_type.manager')->getStorage('user')
    );
  }

  /**
   * (@inheritDoc)
   */
  public function getFormId() {
    return 'rsvplist_email_form';
  }

  /**
   * (@inheritDoc)
   */
  public function buildForm(array $form, FormStateInterface $form_state) : array {
    $node = \Drupal::routeMatch()->getParameter('node');
    $nid = null;
    if ($node instanceof NodeInterface) {
      $nid = $node->id();
    }
    $form['email'] = [
      '#title' => t('Email Address'),
      '#type' => 'textfield',
      '#size' => 25,
      '#description' => t("We'll send updates to the email address you provided."),
      '#required' => TRUE,
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => t("RSVP"),
    ];
    $form['nid'] = [
      '#type' => 'hidden',
      '#value' => $nid,
    ];
    return $form;
  }

  /**
   * (@inheritDoc)
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $value = $form_state->getValue('email');

    // \Drupal::service() is not really handy for investigating isValid member function.
    // Let's use dependency injection to get it more portable and also debug friendly.
    if ($value == !$this->emailValidator->isValid($value)) {
      $form_state->setErrorByName('email', t('The email address %mail is not valid.', ['%mail' => $value]));
    }
  }

  /**
   * (@inheritDoc)
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $user = $this->user->load($this->currentUser()->id());

    $fields = [
      'mail' => $form_state->getValue('email'),
      'mail' => $form_state->getValue('nid'),
      'mail' => $user->id(),
      'mail' => time(),
    ];

    $this->database->insert('rsvplist')->fields($fields)->execute();

    $message = t('Thank you for your RSVP, you are on the list for the event.');
    \Drupal::messenger()->addMessage($message);
  }
}
