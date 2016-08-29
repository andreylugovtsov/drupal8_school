<?php

namespace Drupal\testmodule\Tests;

use \Drupal\simpletest\WebTestBase;
use Drupal\user\Entity\User;

/**
 * Class FruitFormTest
 * @package Drupal\testmodule
 *
 * @group Lesson9
 */
class FruitFormTest extends WebTestBase {
  /**
   * Enabled modules
   */
  public static $modules = ['testmodule', 'node'];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
  }

  /**
   * Test form is available.
   */
  public function testForm() {
    $this->drupalGet('testmodule/form');
    $this->assertResponse(200, 'Form is accessible.');

    $this->assertField('email_address', 'Email field is present.');

    $this->assertFieldByXPath('//*[@id="edit-submit"]', NULL, 'Submit button is present.');

    $this->drupalPostForm(NULL, array(
      'favorite_fruit' => 'Apple',
      'email_address' => 'root@mail.ru',
    ), t('Submit!'));
    $this->assertText('Sorry, we only accept Gmail or Yahoo email addresses at this time.', 'Validation triggered correctly.');

    $this->drupalGet('testmodule/form');
    $this->assertResponse(200);

    $this->drupalPostForm(NULL, array(
      'favorite_fruit' => 'Apple',
      'email_address' => 'root@gmail.com',
    ), t('Submit!'));
    $this->assertText('Apple! Wow! Nice choice! Thanks for telling us!', 'The form was saved correctly.');
  }
}
