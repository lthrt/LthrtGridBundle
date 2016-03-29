<?php

namespace Lthrt\GridBundle\Model\Row;

class Row
{
    use \Lthrt\GridBundle\Model\Util\AttributesTrait;
    use \Lthrt\GridBundle\Model\Util\GetSetTrait;
    use \Lthrt\GridBundle\Model\Util\JsonTrait;
    use \Lthrt\GridBundle\Model\Util\OptionsTrait;

    private $_cell = [];

    public function __construct($opt = null, $attr = null)
    {
        // For building Grid
        $this->opt = $opt;
        // for Header-type Rows reset this
        $this->opt['tag'] = 'TR';

        //For rendering HTMl attributes
        $this->attr = $attr;
    }

    public function addCell(Cell $cell)
    {
        $this->cell[] = $cell;
    }

    public function jsonFields($full = false)
    {
        $fields = array_filter(get_object_vars($this),
            function ($v) use ($full) {
                return $full || "_" != substr($v, 0, 1);
            },
            ARRAY_FILTER_USE_KEY
        );

        if (!$full) {
            $fields = array_merge($fields,
                [
                    'cell' => array_map(
                        function ($c) use ($full) {
                            return $c->jsonFields($full);
                        }, $this->_cell),
                ]
            );
        }

        return $fields;
    }

}
