<?php

/*
 * This file is part of the Ivory Ordered Form package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Tests\OrderedForm\Orderer;

use Ivory\OrderedForm\Orderer\FormOrdererFactory;

/**
 * Form orderer factory test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class FormOrdererFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Ivory\OrderedForm\Orderer\FormOrdererFactory */
    protected $factory;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->factory = new FormOrdererFactory();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->factory);
    }

    public function testCreate()
    {
        $this->assertInstanceOf('Ivory\OrderedForm\Orderer\FormOrderer', $this->factory->create());
    }
}
