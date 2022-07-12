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
use Ivory\OrderedForm\OrderedFormConfigInterface;
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
    private OrderedFormBuilder $builder;

    protected function setUp(): void
    {
        $builder = Forms::createFormFactoryBuilder()
            ->setResolvedTypeFactory(new OrderedResolvedFormTypeFactory())
            ->addExtension(new OrderedExtension())
            ->getFormFactory()
            ->createBuilder();

        assert($builder instanceof OrderedFormBuilder);

        $this->builder = $builder;
    }

    /**
     * @dataProvider formTypeProvider
     */
    public function testEmptyPosition(string $type): void
    {
        $form = $this->builder->create('foo', $type)->getForm();

        /** @var OrderedFormConfigInterface $formConfig */
        $formConfig = $form->getConfig();

        $this->assertNull($formConfig->getPosition());
    }

    /**
     * @dataProvider formTypeProvider
     */
    public function testStringPosition(string $type): void
    {
        $form = $this->builder->create('foo', $type, ['position' => 'first'])->getForm();

        /** @var OrderedFormConfigInterface $formConfig */
        $formConfig = $form->getConfig();

        $this->assertSame('first', $formConfig->getPosition());
    }

    /**
     * @dataProvider formTypeProvider
     */
    public function testArrayPosition(string $type): void
    {
        $form = $this->builder->create('foo', $type, ['position' => ['before' => 'bar']])->getForm();

        /** @var OrderedFormConfigInterface $formConfig */
        $formConfig = $form->getConfig();

        $this->assertSame(['before' => 'bar'], $formConfig->getPosition());
    }

    /**
     * @return array<int, array<int, string>>
     */
    public function formTypeProvider(): array
    {
        $preferFqcn = method_exists(AbstractType::class, 'getBlockPrefix');

        return [
            [$preferFqcn ? TextType::class : 'text'],
            [$preferFqcn ? ButtonType::class : 'button'],
        ];
    }
}
