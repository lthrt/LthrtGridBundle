<?php

namespace Lthrt\GridBundle\Model;

class Head extends Section
{
    private $grid;

    public function __construct($opt = null, $attr = null)
    {
        parent::__construct($opt, $attr);
        $this->setOpt(['tag' => 'THEAD']);
    }
}
