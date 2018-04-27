<?php

require_once 'vendor/autoload.php';

use PHPUnit\Framework\TestCase;

trait HasCustomAdminFilters {
  public function addAdminFilter( $name, $options, $postType, callable $queryModifier ) {
    // allow user to define how they want the admin query modified
    add_action('pre_get_posts', function(\WP_Query $query) use($name, $postType, $queryModifier) {
      // call the user-defined query modifying code
      $queryModifier($query);
    });
  }
}

class HasCustomAdminFiltersTest extends TestCase {
  public function setUp() {
    WP_Mock::setUp();
  }

  public function tearDown() {
    WP_Mock::tearDown();
  }

  public function testAddAdminFilter() {
    $callback = function() {};

    WP_Mock::expectActionAdded('pre_get_posts', WP_Mock\Functions::type('callable'));

    // register the action handlers
    $this->getObjectForTrait('HasCustomAdminFilters')->addAdminFilter(
      'custom_filter',
      ['dropdown' => 'options'],
      'my_post_type',
      $callback
    );
  }
}
