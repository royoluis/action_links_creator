<?php

namespace Drupal\action_links_creator\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Drupal\action_links_creator\Service\ContentTypeService;

/**
 * Provides dynamic local tasks.
 *
 * @Deriver(
 *   id = "dynamic_action_links",
 * )
 */
class DynamicActionLinks extends DeriverBase implements ContainerDeriverInterface {

  /**
   * The configuration factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The content type service.
   *
   * @var \Drupal\action_links_creator\Service\ContentTypeService
   */
  protected $contentTypeService;

  /**
   * Constructs a DynamicActionLinks object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The configuration factory.
   * @param \Drupal\action_links_creator\Service\ContentTypeService $content_type_service
   *   The content type service.
   */
  public function __construct(ConfigFactoryInterface $config_factory, ContentTypeService $content_type_service) {
    $this->configFactory = $config_factory;
    $this->contentTypeService = $content_type_service;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static(
      $container->get('config.factory'),
      $container->get('action_links_creator.content_type_service')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    $config = $this->configFactory->getEditable('action_links_creator.configuration');
    
    $content_types = $this->contentTypeService->getAllContentTypes();

    foreach ($content_types as $content_type_machine_name => $content_type_label)  {
      $activate = $config->get('checkbox_' . $content_type_machine_name);
      if ($activate == 1) {
        $this->derivatives['action_links.' . $content_type_machine_name] = $base_plugin_definition;
        $this->derivatives['action_links.' . $content_type_machine_name]['title'] = 'Add new ' . $content_type_label;
        $this->derivatives['action_links.' . $content_type_machine_name]['route_name'] = 'node.add';
        $this->derivatives['action_links.' . $content_type_machine_name]['appears_on'][] = 'system.admin_content';
        $this->derivatives['action_links.' . $content_type_machine_name]['route_parameters']['node_type'] = $content_type_machine_name;
      }
    }

    return parent::getDerivativeDefinitions($base_plugin_definition);
  }
  
}
