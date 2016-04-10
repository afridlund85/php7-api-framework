<?php
namespace Test\Integration\Fakes;

use Asd\Controller;

class FakeController extends Controller
{
    private $value = 'Some value';
    public function jsonAction($req, $res)
    {
        return $this->withJsonResponse($res, $this->value);
    }
    public function textAction($req, $res)
    {
        return $this->withTextResponse($res, $this->value);
    }
}