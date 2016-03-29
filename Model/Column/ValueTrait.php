<?php

namespace Lthrt\GridBundle\Model\Column;

trait ValueTrait
{
    public function getValue($value)
    {
        // Add anonymous function to options['value'] to override

        if ($this->getOpt('value')) {
            if (is_callable($this->getOpt('value'))) {
                $function = $this->getOpt('value');
                return $function($value);
            } else {
                // This will be a single value upa nd down the row
                return $this->getOpt('value');
            }
        } elseif ($value instanceof \DateTime) {
            // if no override defined, check if date
            $format         = $this->getOpt('dateFormat') ?: 'Y-m-d';
            $result[$field] = $value->format($format);
        } elseif (is_object($value)) {
            // if object check for string representation
            if (method_exists($value, '__toString')) {
                return $value->__toString();
            } else {
                return 'object';
            }
        } else {
            return $value;
        }
    }
}
