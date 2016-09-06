<?php
namespace Test\Unit;

use Asd\View\TemplateView;
use Asd\Http\Response;
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

    /**
     * @test
     * @covers Asd\View\TemplateView::renderToReponse
     * @covers Asd\View\TemplateView::render
     */
    public function renderToReponse()
    {
        $bodyMock = $this->getMockBuilder('\\Asd\\Http\\ResponseBody')
            ->setMethods(['write'])
            ->getMock();

        $bodyMock->expects($this->once())
            ->method('write')
            ->with($this->equalTo('<div>fake valuefake response body value</div>'));

        $responseStub = $this->getMockBuilder('\\Asd\\Http\\Response')
            ->disableOriginalConstructor()
            ->getMock();
        $responseStub->method('getBody')->willReturn($bodyMock);
        $responseStub->method('withBody')->willReturn($responseStub);
        $templateView = $this->templateView->withAddedData(['key2' => 'fake response body value']);
        $templateView->renderToReponse($responseStub);
    }
}
