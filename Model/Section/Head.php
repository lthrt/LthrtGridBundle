<?php

namespace Lthrt\GridBundle\Model\Section;

class Head extends Section
{
    private $grid;

    public function __construct($opt = [], $attr = [])
    {
        parent::__construct($opt, $attr);
        $this->setOpt(['tag' => 'thead']);
    }
}
