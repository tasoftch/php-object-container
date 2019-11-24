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
 * An instance to provide the object container trait
 * @package TASoft\Utils
 */
class ObjectContainer
{
    use ObjectContainerTrait {
        ObjectContainerTrait::addObject as public;
        ObjectContainerTrait::removeObject as public;
        ObjectContainerTrait::resumeObject as public;
        ObjectContainerTrait::getSuspendedObjects as public;
        ObjectContainerTrait::getActiveObjects as public;
        ObjectContainerTrait::suspendObject as public;
        ObjectContainerTrait::getObjects as public;
        ObjectContainerTrait::removeAllObjects as public;
        ObjectContainerTrait::customActivationHandler as _t_customActivationHandler;
    }
    /** @var callable|null */
    private $validator;

    /**
     * ObjectContainer constructor.
     * @param callable|null $validator
     */
    public function __construct(array $objects = NULL, callable $validator = NULL)
    {
        if($objects) {
            foreach($objects as $object)
                $this->addObject($object);
        }
        $this->validator = $validator;
    }

    /**
     * Replaces all objects in the collection with new ones.
     *
     * @param array $objects
     */
    public function setObjects(array $objects) {
        $this->removeAllObjects();
        foreach($objects as $object)
            $this->addObject($object);
    }

    /**
     * @inheritDoc
     */
    protected function customActivationHandler($object)
    {
        if($this->validator)
            return ($this->validator)($object);
        return $this->_t_customActivationHandler($object);
    }
}