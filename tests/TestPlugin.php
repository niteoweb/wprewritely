<?php

class TestIntegration extends PHPUnit_Framework_TestCase {
	use \Xpmock\TestCaseTrait;

	function setUp() {
		\WP_Mock::setUp();
	}

	function tearDown() {
		\WP_Mock::tearDown();
	}

	public function test_init() {
		$plugin = new wprewritely_settings;
		$plugin->__construct();
	}


}