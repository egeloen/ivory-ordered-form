<?php

/*
 * This file is part of the Ivory Ordered Form package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Tests\OrderedForm\Extension;

use Ivory\OrderedForm\Builder\OrderedFormBuilder;
use Ivory\OrderedForm\Extension\OrderedExtension;
use Ivory\OrderedForm\OrderedResolvedFormTypeFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Forms;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class OrderedExtensionTest extends TestCase
{
    /**
     * @var OrderedFormBuilder
     */
    private $builder;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->builder = Forms::createFormFactoryBuilder()
            ->setResolvedTypeFactory(new OrderedResolvedFormTypeFactory())
            ->addExtension(new OrderedExtension())
            ->getFormFactory()
            ->createBuilder();
    }

    /**
     * @param string $type
     *
     * @dataProvider formTypeProvider
     */
    public function testEmptyPosition($type)
    {
        $form = $this->builder->create('foo', $type)->getForm();

        $this->assertNull($form->getConfig()->getPosition());
    }

    /**
     * @param string $type
     *
     * @dataProvider formTypeProvider
     */
    public function testStringPosition($type)
    {
        $form = $this->builder->create('foo', $type, ['position' => 'first'])->getForm();

        $this->assertSame('first', $form->getConfig()->getPosition());
    }

    /**
     * @param string $type
     *
     * @dataProvider formTypeProvider
     */
    public function testArrayPosition($type)
    {
        $form = $this->builder->create('foo', $type, ['position' => ['before' => 'bar']])->getForm();

        $this->assertSame(['before' => 'bar'], $form->getConfig()->getPosition());
    }

    /**
     * @return array
     */
    public function formTypeProvider()
    {
        $preferFqcn = method_exists(AbstractType::class, 'getBlockPrefix');

        return [
            [$preferFqcn ? TextType::class : 'text'],
            [$preferFqcn ? ButtonType::class : 'button'],
        ];
    }
}
