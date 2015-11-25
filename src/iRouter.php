<?php
namespace Asd;

interface iRouter{
    public function getController(iRequest $req);
    public function getAction();
}