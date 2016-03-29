<?php

namespace Lthrt\GridBundle\Model;

class Body extends Section
{
    private $grid;

    public function __construct($opt = null, $attr = null)
    {
        parent::__construct($opt, $attr);
        $this->setOpt(['tag' => 'TBODY']);
    }
}
