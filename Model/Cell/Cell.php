<?php

namespace Lthrt\GridBundle\Model\Cell;

class Cell
{
    use \Lthrt\GridBundle\Model\Util\AttributesTrait;
    use \Lthrt\GridBundle\Model\Util\GetSetTrait;
    use \Lthrt\GridBundle\Model\Util\JsonTrait;
    use \Lthrt\GridBundle\Model\Util\OptionsTrait;

    private $row;

    public function __construct($opt = null, $attr = null)
    {
        // For building Grid
        $this->opt        = $opt;
        $this->opt['tag'] = 'TD';

        //For rendering HTMl attributes
        $this->attr = $attr;
    }

    public function jsonFields($full = false)
    {
        $fields = array_filter(get_object_vars($this),
            function ($v) use ($full) {
                return $full || "_" != substr($v, 0, 1);
            },
            ARRAY_FILTER_USE_KEY
        );
        return $fields;
    }
}
