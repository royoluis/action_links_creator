<?php

namespace Drupal\action_links_creator\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\action_links_creator\Service\ContentTypeService;
use Symfony\Component\HttpFoundation\RequestStack;

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
   * The request stack.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * Constructs a new ConfigForm object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\action_links_creator\Service\ContentTypeService $content_type_service
   *   The content type service.
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   The request stack.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, ContentTypeService $content_type_service, RequestStack $request_stack) {
    $this->entityTypeManager = $entity_type_manager;
    $this->contentTypeService = $content_type_service;
    $this->requestStack = $request_stack;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('action_links_creator.content_type_service'),
      $container->get('request_stack')
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
    $admin_content_url = $this->requestStack->getCurrentRequest()->getHost() . '/admin/content';

    $form['label'] = [
      '#type' => 'item',
      '#title' => $this->t('Content types with action links to create nodes'),
      '#markup' => $this->t('If they are active, they will be visible <a href="https://'. $admin_content_url . '">here</a>.')
    ];
      
    foreach ($content_types as $content_type => $content_type_label) {
      $form['checkbox_' . $content_type] = [
        '#type' => 'checkbox',
        '#title' => $this->t($content_type_label),
        '#default_value' => $config->get('checkbox_' . $content_type)
      ];
  
      $form['label_' . $content_type] = [
        '#type' => 'textfield',
        '#title' => $this->t('Link text'),
        '#default_value' => $config->get('checkbox_' . $content_type) ? $config->get('label_' . $content_type) : $this->t('Add ' . $content_type_label),
        '#states' => [
          'visible' => [
            ':input[name="checkbox_' . $content_type . '"]' => ['checked' => TRUE],
          ],
        ],
      ];

      $form['weight_' . $content_type] = [
        '#type' => 'number',
        '#title' => $this->t('Weight'),
        '#default_value' => $config->get('checkbox_' . $content_type) ? $config->get('weight_' . $content_type) : 1,
        '#states' => [
          'visible' => [
            ':input[name="checkbox_' . $content_type . '"]' => ['checked' => TRUE],
          ],
        ],
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
