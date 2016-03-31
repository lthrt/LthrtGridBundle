<?php

namespace Lthrt\GridBundle\Model\Row;

use Lthrt\GridBundle\Model\Cell\Cell;
use Lthrt\GridBundle\Model\Row\Row;

class Row
{
    use \Lthrt\GridBundle\Model\Util\AttributesTrait;
    use \Lthrt\GridBundle\Model\Util\GetSetTrait;
    use \Lthrt\GridBundle\Model\Util\JsonTrait;
    use \Lthrt\GridBundle\Model\Util\OptionsTrait;

    private $_cell = [];

    const TH = 'th';
    const TR = 'tr';

    public function __construct($opt = [], $attr = [])
    {
        // For building Grid
        $this->opt = $opt;
        // for Header-type Rows reset this
        $this->opt['tag'] = Cell::TR;

        //For rendering HTMl attributes
        $this->attr = $attr;
    }

    public function addCell(Cell $cell)
    {
        $this->_cell[] = $cell;
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
        } else {
            $fields = array_merge($fields,
                [
                    '_cell' => array_map(
                        function ($c) use ($full) {
                            return $c->jsonFields($full);
                        }, $this->_cell),
                ]
            );
        }

        return $fields;
    }

    public function html()
    {
        $td = implode("\n", array_map(function ($t) {return $t->html();}, $this->_cell));
        $attr = "";
        if ($this->attr) {
            foreach ($this->attr as $key => $value) {
                $attr .= " " . $key . "=\"" . $value . "\"";
            }
        }
        $tr = "<" . $this->getOpt('tag') . $attr . ">\n" . $td . "\n</" . $this->getOpt('tag') . ">";
        return $tr;
    }
}
