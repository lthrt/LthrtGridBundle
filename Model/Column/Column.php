<?php

namespace Lthrt\GridBundle\Model\Column;

class Column
{
    use \Lthrt\GridBundle\Model\Column\ValueTrait;
    use \Lthrt\GridBundle\Model\Util\AttributesTrait;
    use \Lthrt\GridBundle\Model\Util\GetSetTrait;
    use \Lthrt\GridBundle\Model\Util\JsonTrait;
    use \Lthrt\GridBundle\Model\Util\OptionsTrait;

    private $_grid;

    public function __construct($opt = [], $attr = [])
    {
        // For building Grid
        $this->opt = $opt;

        //For rendering HTMl attributes
        $this->attr = $attr;
    }
}
