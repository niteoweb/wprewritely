<?php
use Codeception\Util\Stub;

function is_email($v)
{
    return true;
}

class PluginTest extends \Codeception\TestCase\Test
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
        $this->plugin = new wprewritely;
    }

    protected function _after() {
        _reset_wp();
    }

    // tests
    public function testClassExsists() {

        $this->assertTrue( class_exists( "wprewritely" ) );
    }

    public function testInitWorks() {
        global $wp_test_expectations;
        
        $this->assertTrue( isset( $wp_test_expectations["actions"]["save_post"][1] ) );
        $this->assertTrue( $wp_test_expectations["actions"]["save_post"][1] == "onPostSave" ); 

        $this->assertTrue( isset( $wp_test_expectations["actions"]["add_meta_boxes"][1] ) );
        $this->assertTrue( $wp_test_expectations["actions"]["add_meta_boxes"][1] == "onSetupPostMeta" );

        $this->assertTrue( isset( $wp_test_expectations["actions"]["admin_footer"][1] ) );
        $this->assertTrue( $wp_test_expectations["actions"]["admin_footer"][1] == "onInjectScript" );
        _reset_wp();
    }

    public function testOptionContainsNameOfClass()
    {
        update_option("wprewritely_test", "!!@@!!");
        $this->assertEquals( "!!@@!!", $this->plugin->option("test") );
        _reset_wp();
    }

    public function testFunctionGet_namespaceContainsNameOfClass()
    {
        $this->assertEquals( "wprewritely_", $this->plugin->get_namespace() );
        _reset_wp();
    }

    public function testInjectScript()
    {
        ob_start();
        $this->plugin->onInjectScript();
        $code = ob_get_clean();
        $this->assertContains("#wprewritely_plugin", $code);
        $this->assertContains("#save_placeholder", $code);
        $this->assertContains("jQuery", $code);
    }

    public function testRenderMetaBox()
    {   
        $post = new stdClass();
        $post->post_content = "New line. Another new Line.";
        ob_start();
        $this->plugin->renderMetaBox($post);
        $code = ob_get_clean();
        $this->assertContains("wprewritely_field_default[0][0]", $code);
        $this->assertContains("wprewritely_field_default[0][1]", $code);
        $this->assertContains("save_placeholder", $code);
    }

}