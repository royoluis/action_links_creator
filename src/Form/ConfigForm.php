<?php

namespace Drupal\action_links_creator\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\action_links_creator\Service\ContentTypeService;

/**
 * Defines a form to configure action links.
 */
class ConfigForm extends ConfigFormBase {

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The content type service.
   *
   * @var \Drupal\action_links_creator\Service\ContentTypeService
   */
  protected $contentTypeService;

  /**
   * Constructs a new ConfigForm object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\action_links_creator\Service\ContentTypeService $content_type_service
   *   The content type service.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, ContentTypeService $content_type_service) {
    $this->entityTypeManager = $entity_type_manager;
    $this->contentTypeService = $content_type_service;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('action_links_creator.content_type_service')
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'action_links_creator.configuration'
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return "action_links_creator_form";
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $content_types = $this->contentTypeService->getAllContentTypes();

    $config = $this->config('action_links_creator.configuration');
      
    foreach ($content_types as $content_type => $content_type_label) {
      $form['checkbox_' . $content_type] = [
        '#type' => 'checkbox',
        '#title' => $this->t($content_type_label),
        '#default_value' => $config->get('checkbox_' . $content_type)
      ];
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    
    $config = $this->config('action_links_creator.configuration');

    $checkboxes = $form_state->getValues();

    foreach ($checkboxes as $checkbox => $value) {
      $config->set($checkbox, $value);
    }

    $config->save();

    drupal_flush_all_caches();
  }
  
}
