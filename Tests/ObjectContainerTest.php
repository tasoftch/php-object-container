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

/**
 * ObjectContainerTest.php
 * TASoft Core
 *
 * Created on 13.10.18 13:53 by thomas
 */

use PHPUnit\Framework\TestCase;
use TASoft\Util\ObjectContainerTrait;

class ObjectContainerTest extends TestCase
{
    public function testAdd() {
        $cnt = new MyContainer();
        $cnt->addObject("Thomas");
        $cnt->addObject("Daniela");
        $cnt->addObject("Test");
        $cnt->addObject("Important", true);

        $this->assertEquals([
            "Important",
            "Thomas",
            "Daniela",
            "Test"
        ], $cnt->getObjects());
    }

    public function testRemove() {
        $cnt = new MyContainer();
        $cnt->addObject("Thomas");
        $cnt->addObject("Daniela");
        $cnt->addObject("Test");
        $cnt->addObject("Important", true);

        $cnt->removeObject("Daniela");

        $this->assertEquals([
            "Important",
            "Thomas",
            "Test"
        ], $cnt->getObjects());

        $cnt->removeObject("Important");

        $this->assertEquals([
            "Thomas",
            "Test"
        ], $cnt->getObjects());
    }

    public function testAllActive() {
        $cnt = new MyContainer();
        $cnt->addObject("Thomas");
        $cnt->addObject("Daniela");
        $cnt->addObject("Test");
        $cnt->addObject("Important", true);

        $cnt->removeObject("Important");

        $this->assertEquals([
            "Thomas",
            "Daniela",
            "Test"
        ], $cnt->getActiveObjects());
    }

    public function testSuspent() {
        $cnt = new MyContainer();
        $cnt->addObject("Thomas");
        $cnt->addObject("Daniela");
        $cnt->addObject("Test");
        $cnt->addObject("Important", true);

        $cnt->suspendObject("Test");

        $this->assertEquals([
            "Important",
            "Thomas",
            "Daniela",
            "Test"
        ], $cnt->getObjects());

        $this->assertEquals([
            "Important",
            "Thomas",
            "Daniela"
        ], $cnt->getActiveObjects());

        $this->assertEquals([
            "Test"
        ], $cnt->getSuspendedObjects());

        $cnt->resumeObject("Test");

        $this->assertEquals([
            "Important",
            "Thomas",
            "Daniela",
            "Test"
        ], $cnt->getActiveObjects());

        $this->assertEquals([
        ], $cnt->getSuspendedObjects());
    }

    public function testSuspendRemove() {
        $cnt = new MyContainer();
        $cnt->addObject("Thomas");
        $cnt->addObject("Daniela");
        $cnt->addObject("Test");
        $cnt->addObject("Important", true);

        $cnt->suspendObject("Test");

        $cnt->removeObject("Test");

        $this->assertEquals([
            "Important",
            "Thomas",
            "Daniela"
        ], $cnt->getObjects());

        $this->assertEquals([
            "Important",
            "Thomas",
            "Daniela"
        ], $cnt->getActiveObjects());
        $this->assertEquals([
        ], $cnt->getSuspendedObjects());
    }
}


class MyContainer {
    use ObjectContainerTrait {
        ObjectContainerTrait::addObject as public;
        ObjectContainerTrait::removeObject as public;
        ObjectContainerTrait::resumeObject as public;
        ObjectContainerTrait::getSuspendedObjects as public;
        ObjectContainerTrait::getActiveObjects as public;
        ObjectContainerTrait::suspendObject as public;
        ObjectContainerTrait::getObjects as public;
    }
}
