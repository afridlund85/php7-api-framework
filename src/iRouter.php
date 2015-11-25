<?php
namespace Asd;

interface iRouter{
    public function getController(string $uri);
}