<?php

class TestIntegration extends PHPUnit_Framework_TestCase {

	function setUp() {
		\WP_Mock::setUp();
	}

	function tearDown() {
		\WP_Mock::tearDown();
	}

	public function test_init() {
		$plugin = new wprewritely;
		$plugin->__construct();
	}
	public function test_init_plugin() {
		$plugin = new wprewritely_settings;
		$plugin->__construct();
	}

}
