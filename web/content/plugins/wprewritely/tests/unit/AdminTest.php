<?php
use Codeception\Util\Stub;

// mock function from templates.php
function checked($value)
{
    return "checked";
}


class AdminTest extends \Codeception\TestCase\Test
{
    /**
     *
     *
     * @var \Plugin
     */
    private $plugin;
    /**
     *
     *
     * @var \CodeGuy
     */
    protected $codeGuy;

    protected function _before() {
        _reset_wp();
        global $wp_test_expectations;
        $this->plugin = new wprewritely_settings;
    }

    protected function _after() {
        _reset_wp();
    }

    // tests
    public function testClassExsists() {

        $this->assertTrue( class_exists( "wprewritely_settings" ) );
    }

    public function testInitWorks() {
        global $wp_test_expectations;
        $this->assertTrue( class_exists( "wprewritely_settings" ) );
        $this->assertTrue( isset( $wp_test_expectations["actions"]["admin_init"][1] ) );
        $this->assertTrue( $wp_test_expectations["actions"]["admin_init"][1] == "onAdminInit" );
    }

    public function testCheckbox_callback()
    {
        ob_start();
        $this->plugin->checkbox_callback("@ID");
        $code = ob_get_clean();
        $this->assertContains("@ID", $code);
    }

    public function testEmail_callback()
    {
        ob_start();
        $this->plugin->email_callback("@ID");
        $code = ob_get_clean();
        $this->assertContains("@ID", $code);
    }


}