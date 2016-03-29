<?php

namespace Lthrt\GridBundle\Model;

class Cell implements \JsonSerializable
{
    use AttributesTrait;
    use GetSetTrait;
    use JsonTrait;
    use OptionsTrait;

    private $row;

    public function __construct($opt = null, $attr = null)
    {
        // For building Grid
        $this->opt        = $opt;
        $this->opt['tag'] = 'TD';

        //For rendering HTMl attributes
        $this->attr = $attr;
    }
}
