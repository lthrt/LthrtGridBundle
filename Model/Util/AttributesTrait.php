<?php

namespace Lthrt\GridBundle\Model\Util;

trait AttributesTrait
{
    private $attr = [];

    public function getAttr($attr = null)
    {
        if ($attr) {
            if (isset($this->attr[$attr])) {
                return $this->attr[$attr];
            } else {
                return;
            }
        } else {
            return $this->attr;
        }
    }

    // Send array or attr, value combo

    public function setAttr($attr = null, $value = null)
    {
        if (is_array($attr)) {
            $this->attr = array_merge($this->attr, $attr);
        } elseif ($attr && isset($this->attr[$attr])) {
            return $this;
        } else {
            $this->attr[$attr] = $value;

            return $this;
        }
    }

    public function clearAttr($attrib = null)
    {
        if ($attrib && isset($this->attr[$attrib])) {
            unset($this->attr[$attrib]);

            return $this;
        } else {
            return $this;
        }
    }
}
