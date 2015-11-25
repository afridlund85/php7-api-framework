<?php
namespace Asd;

interface iRouter{
    public function getController(iRequest $req);
    public function getAction();
    public function addRoute(string $route);
    public function getRoutes() : array;
}