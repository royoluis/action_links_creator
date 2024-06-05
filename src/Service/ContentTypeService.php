<?php

namespace Drupal\action_links_creator\Service;

use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Class ContentTypeService.
 *
 * Provides methods to work with content types in Drupal.
 */
class ContentTypeService {

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a new ContentTypeService object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * Get all content types.
   *
   * @return array
   *   An associative array of content type machine names and labels.
   */
  public function getAllContentTypes() {
    $content_types_array = [];
    
    // Load all content types.
    $content_types = $this->entityTypeManager->getStorage('node_type')->loadMultiple();

    // Build the content types array.
    foreach ($content_types as $content_type) {
      $content_types_array[$content_type->id()] = $content_type->label();
    }

    return $content_types_array;
  }

}
