<?php

namespace Lthrt\GridBundle\Model\Cell;

class Cell
{
    use \Lthrt\GridBundle\Model\Util\AttributesTrait;
    use \Lthrt\GridBundle\Model\Util\GetSetTrait;
    use \Lthrt\GridBundle\Model\Util\JsonTrait;
    use \Lthrt\GridBundle\Model\Util\OptionsTrait;

    const TD = 'td';

    public function __construct($opt = [], $attr = [])
    {
        // For building Grid
        $this->opt = $opt;
        if (!isset($this->opt['tag'])) {
            $this->opt['tag'] = Cell::TD;
        }

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

    public function html()
    {
        $attr = "";
        if ($this->attr) {
            foreach ($this->attr as $key => $value) {
                $attr .= " " . $key . "=\"" . $value . "\"";
            }
        }

        $val = ('td' == $this->getOpt('tag')) ? $this->getOpt('value') : $this->getOpt('header');

        $td = "<" . $this->getOpt('tag') . $attr . ">" . $val . "</" . $this->getOpt('tag') . ">";
        return $td;
    }
}
