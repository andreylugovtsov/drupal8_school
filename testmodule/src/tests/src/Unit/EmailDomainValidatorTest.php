<?php

namespace Drupal\Tests\testmodule\Unit;

use Drupal\testmodule\Email\EmailParser;
use Drupal\Tests\UnitTestCase;
use Drupal\testmodule\Email\EmailDomainValidator;

/**
 * Class EmailDomainValidatorTest
 * @package Drupal\Tests\testmodule\Unit
 */
class EmailDomainValidatorTest extends UnitTestCase {
  /**
   * @var EmailDomainValidator
   */
  protected $validator;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    $this->validator = new EmailDomainValidator(new EmailParser());
  }

  /**
   * Provides emails examples.
   *
   * @return array
   */
  public function providerEmails() {
    return [
      ['root@mail.ru', FALSE],
      ['root@rambler.ru', FALSE],
      ['root@gmail.com', TRUE],
    ];
  }

  /**
   * Test email.
   *
   * @dataProvider providerEmails
   *
   */
  public function testEmail($email, $expected) {
    $this->assertEquals(
      $this->validator->validate($email, $this->getDomains()),
      $expected
    );
  }

  /**
   * Test email exception.
   *
   * @expectedException Drupal\testmodule\Email\EmailDomainException
   *
   */
  public function testException() {
    $this->validator->validate('/////@rambler.ru', $this->getDomains());
  }

  /**
   * @return array
   */
  protected function getDomains() {
    return ['gmail.com', 'yahoo.com'];
  }
}
