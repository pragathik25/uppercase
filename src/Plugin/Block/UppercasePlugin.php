<?php

namespace Drupal\uppercase_service\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\uppercase_service\UpperCase;

/**
  * Provides simple block for d4drupal.
  * @Block (
  * id = "plugin_block_example",
  * admin_label = "uppercase plugin"
  * )
  */

class UppercasePlugin extends BlockBase implements ContainerFactoryPluginInterface {

    /**
     * @var UpperCase $service
     */
protected $service;
    /**
     * @param array $configuration
     * @param string $plugin_id
     * @param mixed $plugin_definition
     * @param Drupal\uppercase_service\UpperCase $service
     */

    public function __construct(array $configuration, $plugin_id, $plugin_definition, UpperCase $service) {
      parent::__construct($configuration, $plugin_id, $plugin_definition);
      $this->service = $service;
    }


    /**
     * {@inheritdoc}
     */

    public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
      return new static(
        $configuration,
        $plugin_id,
        $plugin_definition,
        $container->get('test_uppercase')
      );
    }

    /**
     * {@inheritdoc}
     */
    public function build() {
      $uppercase = $this->configuration['uppercase'];
        return [
            "#markup" => $this->service->upperCase("$uppercase") ,
        ];
    }


    /**
     * {@inheritdoc}
     */

    public function defaultConfiguration() {
      return [
        'uppercase' => "",
      ];
    }

    /**
     * {@inheritdoc}
     */
    public function blockForm($form, FormStateInterface $form_state) {
      $form['uppercase'] = [
        '#type' => 'textfield',
        '#title' => 'Uppercase Text',
        '#default_value' => $this->configuration['uppercase'],
      ];
      return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function blockSubmit($form, FormStateInterface $form_state) {
      $this->configuration['uppercase'] = $form_state->getValue('uppercase');
    }

}