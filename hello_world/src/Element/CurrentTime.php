<?php

/**
 * @file
 * Contains \Drupal\hello_world\Element\CurrentTime.
 */

namespace Drupal\hello_world\Element;

use Drupal\Core\Render\Element\RenderElement;

/**
 * Provides an example element.
 *
 * @RenderElement("element_current_time")
 */
class CurrentTime extends RenderElement {
  /**
   * {@inheritdoc}
   */
  public function getInfo() {
    $class = get_class($this);
    return [
      '#theme' => 'element_current_time',
      '#label' => '',
      '#format' => DATE_ISO8601,
      '#pre_render' => [
        [$class, 'preRenderMyElement'],
      ],
    ];
  }

  /**
   * Prepare the render array for the template.
   */
  public static function preRenderMyElement($element) {
    $element['label'] = [
      '#markup' => $element['#label'],
    ];

    $element['time_formatted'] = [
      '#markup' => date($element['#format']),
    ];

    return $element;
  }
}