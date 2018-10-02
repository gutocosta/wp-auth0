<?php
/**
 * Contains Class TestLockOptions.
 *
 * @package WP-Auth0
 * @since 3.7.0
 */

use PHPUnit\Framework\TestCase;

/**
 * Class TestLockOptions.
 * Tests that Lock options output expected values based on given conditions.
 */
class TestLockOptions extends TestCase {

	use setUpTestDb;

	/**
	 * Test that a custom domain adds a correct key for CDN configuration to Lock options.
	 */
	public function testThatLockConfigBaseUrlIsBuiltProperly() {
		$opts = WP_Auth0_Options::Instance();

		$opts->set( 'domain', 'test.auth0.com' );
		$lock_options     = new WP_Auth0_Lock10_Options( [], $opts );
		$lock_options_arr = $lock_options->get_lock_options();
		$this->assertArrayNotHasKey( 'configurationBaseUrl', $lock_options_arr );

		$opts->set( 'custom_domain', 'login.example.com' );
		$this->assertEquals( 'https://cdn.auth0.com', $lock_options->get_lock_options()['configurationBaseUrl'] );

		$opts->set( 'domain', 'test.eu.auth0.com' );
		$this->assertEquals( 'https://cdn.eu.auth0.com', $lock_options->get_lock_options()['configurationBaseUrl'] );

		$opts->set( 'domain', 'test.au.auth0.com' );
		$this->assertEquals( 'https://cdn.au.auth0.com', $lock_options->get_lock_options()['configurationBaseUrl'] );
	}

	/**
	 * Test that callback URLs are built properly
	 */
	public function testThatAuthCallbacksAreCorrect() {
		$opts         = WP_Auth0_Options::Instance();
		$lock_options = new WP_Auth0_Lock10_Options( [], $opts );

		$this->assertEquals( 'http://example.org/index.php?auth0=implicit', $lock_options->get_implicit_callback_url() );
		$this->assertEquals( 'http://example.org/index.php?auth0=1', $lock_options->get_code_callback_url() );

		$opts->set( 'force_https_callback', 1 );
		$lock_options = new WP_Auth0_Lock10_Options( [], $opts );

		$this->assertEquals( 'https://example.org/index.php?auth0=implicit', $lock_options->get_implicit_callback_url() );
		$this->assertEquals( 'https://example.org/index.php?auth0=1', $lock_options->get_code_callback_url() );
	}
}
