<?php
namespace Test\Unit;

use Asd\View\TemplateView;
use InvalidArgumentException;

class TemplateViewTest extends \PHPUnit_Framework_TestCase
{
    protected $templateView;
    private $fakeTemplatePath;
    
    public function setUp()
    {
        $this->fakeTemplatePath = dirname(__FILE__) . '/../../Fakes/FakeTemplate.php';
        $this->fakeData = ['key1' => 'fake value'];
        $this->templateView = new TemplateView($this->fakeTemplatePath, $this->fakeData);
    }

    /**
     * @test
     * @covers Asd\View\TemplateView::__construct
     * @covers Asd\View\TemplateView::validateTemplate
     * @expectedException InvalidArgumentException
     */
    public function constructor_invalidTemplatePath()
    {
        new TemplateView('not/a/path/to/template.php');
    }

    /**
     * @test
     * @covers Asd\View\TemplateView::__construct
     * @covers Asd\View\TemplateView::validateData
     * @expectedException InvalidArgumentException
     */
    public function constructor_invalidData_nonAssocArray()
    {
        new TemplateView($this->fakeTemplatePath, ['not', 'an', 'assoc', 'array']);
    }

    /**
     * @test
     * @covers Asd\View\TemplateView::withTemplate
     * @covers Asd\View\TemplateView::validateTemplate
     * @expectedException InvalidArgumentException
     */
    public function withTemplate_invalidTemplatePath()
    {
        $this->templateView->withTemplate('not/a/path/to/template.php');
    }

    /**
     * @test
     * @covers Asd\View\TemplateView::withTemplate
     */
    public function withTemplate_isImutable()
    {
        $templateView = $this->templateView->withTemplate($this->fakeTemplatePath);

        $this->assertNotSame($this->templateView, $templateView);
    }

    /**
     * @test
     * @covers Asd\View\TemplateView::withData
     * @covers Asd\View\TemplateView::validateData
     * @expectedException InvalidArgumentException
     */
    public function withData_invalidData()
    {
        $this->templateView->withData(['not', 'assoc', 'array']);
    }

    /**
     * @test
     * @covers Asd\View\TemplateView::withData
     */
    public function withData_isImutable()
    {
        $templateView = $this->templateView->withData(['a'=>'b']);

        $this->assertNotSame($this->templateView, $templateView);
    }

    /**
     * @test
     * @covers Asd\View\TemplateView::withAddedData
     * @covers Asd\View\TemplateView::validateData
     * @expectedException InvalidArgumentException
     */
    public function withAddedData_invalidData()
    {
        $this->templateView->withData(['not', 'assoc', 'array']);
    }

    /**
     * @test
     * @covers Asd\View\TemplateView::render
     * @covers Asd\View\TemplateView::withAddedData
     */
    public function render()
    {
        $templateView = $this->templateView->withAddedData(['key2' => 'fake value 2']);
        $output = $templateView->render();
        $expected = '<div>fake valuefake value 2</div>';
        $this->assertEquals($output, $expected);
    }
}
