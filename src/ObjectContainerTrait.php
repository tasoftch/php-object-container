<?php
/**
 * Copyright (c) 2018 TASoft Applications, Th. Abplanalp <info@tasoft.ch>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace TASoft\Util;

/**
 * Trait ObjectContainerTrait
 * @package TASoft\Utils
 */
trait ObjectContainerTrait
{
    /** @var array  */
    private $_objects = [];
    /** @var array  */
    private $_suspendedObjects = [];
    /** @var array  */
    private $_activeObjects = [];

    /**
     * Adds an object to the collection
     *
     * @param $object
     * @param bool $important
     * @return static
     */
    protected function addObject($object, bool $important = false) {
        if(!in_array($object, $this->_objects)) {
            $func = $important ? 'array_unshift' : 'array_push';
            $func($this->_objects, $object);
            $this->_activeObjects = NULL;
        }
        return $this;
    }

    /**
     * Removes an object from the collection
     *
     * @param $object
     * @return static
     */
    protected function removeObject($object) {
        if(($idx = array_search($object, $this->_objects)) !== false) {
            unset($this->_objects[$idx]);
            if(($idx = array_search($object, $this->_suspendedObjects)) !== false) {
                unset($this->_suspendedObjects[$idx]);
            }
            $this->_activeObjects = NULL;
        }
        return $this;
    }

    /**
     * Still holds an object in the collection but marks it for not to use
     *
     * @param $object
     * @return static
     */
    protected function suspendObject($object) {
        if(in_array($object, $this->_objects) && !in_array($object, $this->_suspendedObjects)) {
            $this->_suspendedObjects[] = $object;
            $this->_activeObjects = NULL;
        }
        return $this;
    }

    /**
     * Resumes a suspended object and includes it in the current collection again
     *
     * @param $object
     * @return static
     */
    protected function resumeObject($object) {
        if(in_array($object, $this->_objects) && ($idx = array_search($object, $this->_suspendedObjects)) !== false) {
            unset($this->_suspendedObjects[$idx]);
            $this->_activeObjects = NULL;
        }
        return $this;
    }

    /**
     * Gets all objects in the collection that are not suspended and pass the activity handler
     *
     * @return array
     */
    protected function getActiveObjects() {
        if(NULL === $this->_activeObjects) {
            $this->_activeObjects = [];
            foreach($this->_objects as $object) {
                if(!in_array($object, $this->_suspendedObjects) && $this->customActivationHandler($object))
                    $this->_activeObjects[] = $object;
            }
        }
        return $this->_activeObjects;
    }

    /**
     * Removes all objects
     * @return static
     */
    public function removeAllObjects() {
        $this->_suspendedObjects = [];
        $this->_objects = [];
        $this->_activeObjects = NULL;
        return $this;
    }

    /**
     * Override this class to specific select if an object is active or not.
     *
     * @param $object
     * @return bool
     */
    protected function customActivationHandler($object) {
        return true;
    }

    /**
     * Gets all objects in the collection
     *
     * @return array
     */
    protected function getObjects(): array
    {
        return array_values($this->_objects);
    }

    /**
     * Gets all suspended object of the collection
     *
     * @return array
     */
    protected function getSuspendedObjects(): array
    {
        return $this->_suspendedObjects;
    }
}