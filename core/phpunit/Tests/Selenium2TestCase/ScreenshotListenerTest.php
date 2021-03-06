<?php
class Tests_Selenium2TestCase_ScreenshotListenerTest extends Tests_Selenium2TestCase_BaseTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->directory = sys_get_temp_dir();
        $existing = glob("$this->directory/Tests_Selenium2TestCase_ScreenshotListenerTest__*.png");
        foreach ($existing as $file) {
            unlink($file);
        }
        $this->listener = new PHPUnit_Extensions_Selenium2TestCase_ScreenshotListener(
            $this->directory
        );
    }

    public function testStoresAScreenshotInCaseOfError()
    {
        $this->url('html/test_open.html');

        $this->listener->addError($this, new Exception(), null);

        $this->assertThereIsAScreenshotNamed('Tests_Selenium2TestCase_ScreenshotListenerTest__testStoresAScreenshotInCaseOfError__*.png');
    }

    public function testStoresAScreenshotInCaseOfFailure()
    {
        $this->url('html/test_open.html');

        $exception = $this->getMock('PHPUnit_Framework_AssertionFailedError');
        $this->listener->addFailure($this, $exception, null);

        $this->assertThereIsAScreenshotNamed('Tests_Selenium2TestCase_ScreenshotListenerTest__testStoresAScreenshotInCaseOfFailure*.png');
    }

    private function assertThereIsAScreenshotNamed($filename)
    {
        $images = glob("$this->directory/$filename");
        $this->assertEquals(1, count($images), 'No screenshot were saved.');
    }
}
