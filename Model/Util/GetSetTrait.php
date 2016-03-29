<?php

namespace Lthrt\GridBundle\Model\Util;

/**
 * GetSet Trait
 *
 * For discussion see: http://www.epixa.com/2010/05/the-best-models-are-easy-models.html
 *
 */
trait GetSetTrait
{
    /**
     * Map a call to get a property to its corresponding accessor if it exists.
     * Otherwise, get the property directly.
     *
     * Ignore any properties that begin with an underscore so not all of our
     * protected properties are exposed.
     *
     * @param string $name
     *
     * @return mixed
     *
     * @throws \LogicException If no accessor/property exists by that name
     */
    public function __get($name)
    {
        if ('_' != $name[0]) {
            $accessor = 'get' . ucfirst($name);
            if (method_exists($this, $accessor)) {
                return $this->$accessor();
            }

            if (property_exists($this, $name)) {
                return $this->$name;
            }
        }

        var_dump(debug_backtrace(null, 2));

        throw new \LogicException(sprintf(
            'Bad __get(): No property named `%s` exists',
            $name
        ));
    }

    /**
     * Map a call to set a property to its corresponding mutator if it exists.
     * Otherwise, set the property directly.
     *
     * Ignore any properties that begin with an underscore so not all of our
     * protected properties are exposed.
     *
     * @param string $name
     * @param mixed  $value
     *
     * @throws \LogicException If no mutator/property exists by that name
     */
    public function __set($name, $value)
    {
        if ('_' != $name[0]) {
            $mutator = 'set' . ucfirst($name);
            if (method_exists($this, $mutator)) {
                $this->$mutator($value);

                return;
            }

            if (property_exists($this, $name)) {
                $this->$name = $value;

                return;
            }
        }

        throw new \LogicException(sprintf(
            'Bad __set(): No property named `%s` exists',
            $name
        ));
    }

    /**
     * Map a call to a non-existent mutator or accessor directly to its
     * corresponding property.
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     *
     * @throws \BadMethodCallException If no mutator/accessor can be found
     */
    public function __call($name, $arguments)
    {
        if (0 === strpos($name, 'get')) {
            $property = lcfirst(substr($name, 3));

            return $this->$property;
        }

        if (0 === strpos($name, 'set')) {
            $property = lcfirst(substr($name, 3));

            $this->$property = array_shift($arguments);

            return $this;
        }

        if (property_exists($this, $name)) {
            return $this->$name;
        }

        throw new \BadMethodCallException(sprintf(
            'No method or property named `%s` exists',
            $name
        ));
    }
}
