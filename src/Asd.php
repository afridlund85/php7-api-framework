<?php

namespace Asd;

class Asd{
    
    public function __construct(iRequest $req = null){
        if($req === null)
            throw new \Exception();
    }
}