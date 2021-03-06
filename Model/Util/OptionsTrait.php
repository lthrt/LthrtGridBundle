<?php

namespace Lthrt\GridBundle\Model\Util;

trait OptionsTrait
{
    private $opt = [];

    public function getOpt($opt = null)
    {
        if ($opt) {
            if (isset($this->opt[$opt])) {
                return $this->opt[$opt];
            } else {
                return;
            }
        } else {
            return $this->opt;
        }
    }

    // Send array or option, value combo

    public function setOpt($opt = null, $value = null)
    {
        if (is_array($opt)) {
            $this->opt = array_merge($this->opt, $opt);
        } elseif ($option && isset($this->opt[$opt])) {
            return $this;
        } else {
            $this->opt[$option] = $value;

            return $this;
        }
    }

    public function clearOpt($option = null)
    {
        if ($option && isset($this->opt[$option])) {
            unset($this->opt[$option]);

            return $this;
        } else {
            return $this;
        }
    }
}
