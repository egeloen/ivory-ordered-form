<?php

/*
 * This file is part of the Ivory Ordered Form package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Tests\OrderedForm;

use Ivory\OrderedForm\Exception\OrderedConfigurationException;
use Ivory\OrderedForm\Extension\OrderedExtension;
use Ivory\OrderedForm\OrderedResolvedFormTypeFactory;
use Ivory\Tests\OrderedForm\Fixtures\ExtraChildrenViewExtension;
use Ivory\Tests\OrderedForm\Fixtures\RemoveChildrenViewExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormFactoryBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\FormView;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class OrderedFormFunctionnalTest extends TestCase
{
    /**
     * @var FormFactoryBuilderInterface
     */
    private $factoryBuilder;

    /**
     * @var FormFactoryInterface
     */
    private $factory;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->factoryBuilder = Forms::createFormFactoryBuilder()
            ->setResolvedTypeFactory(new OrderedResolvedFormTypeFactory())
            ->addExtension(new OrderedExtension());

        $this->factory = $this->factoryBuilder->getFormFactory();
    }

    /**
     * @param array $config
     * @param array $expected
     *
     * @dataProvider getValidPositions
     */
    public function testValidPosition(array $config, array $expected)
    {
        $this->assertPositions($this->createForm($config)->createView(), $expected);
    }

    /**
     * @param array       $config
     * @param string $exceptionMessage
     *
     * @dataProvider getInvalidPositions
     */
    public function testInvalidPosition(array $config, $exceptionMessage)
    {
        $this->expectException(OrderedConfigurationException::class);
        $this->expectExceptionMessage($exceptionMessage);

        $this->createForm($config)->createView();
    }

    public function testExtraChildrenView()
    {
        $type = method_exists(AbstractType::class, 'getBlockPrefix') ? FormType::class : 'form';

        $view = $this->factoryBuilder
            ->addTypeExtension(new ExtraChildrenViewExtension(['extra1', 'extra2']))
            ->getFormFactory()
            ->createBuilder()
            ->add('foo', $type, ['position' => 'last'])
            ->add('bar', $type, ['position' => 'first'])
            ->getForm()
            ->createView();

        $this->assertPositions($view, ['bar', 'foo', 'extra1', 'extra2']);
    }

    public function testRemoveChildrenView()
    {
        $type = method_exists(AbstractType::class, 'getBlockPrefix') ? FormType::class : 'form';

        $view = $this->factoryBuilder
            ->addTypeExtension(new RemoveChildrenViewExtension(['foo']))
            ->getFormFactory()
            ->createBuilder()
            ->add('foo', $type, ['position' => 'last'])
            ->add('bar', $type, ['position' => 'first'])
            ->getForm()
            ->createView();

        $this->assertPositions($view, ['bar']);
    }

    /**
     * @return array
     */
    public function getValidPositions()
    {
        return [
            // No position
            [
                ['foo', 'bar', 'baz', 'bat'],
                ['foo', 'bar', 'baz', 'bat'],
            ],

            // First position
            [
                ['foo' => 'first', 'bar', 'baz', 'bat'],
                ['foo', 'bar', 'baz', 'bat'],
            ],
            [
                ['bar', 'baz', 'foo' => 'first', 'bat'],
                ['foo', 'bar', 'baz', 'bat'],
            ],
            [
                ['bar', 'baz', 'bat', 'foo' => 'first'],
                ['foo', 'bar', 'baz', 'bat'],
            ],
            [
                ['baz', 'foo' => 'first', 'bat', 'bar' => 'first'],
                ['foo', 'bar', 'baz', 'bat'],
            ],

            // Last position
            [
                ['foo', 'bar', 'baz', 'bat' => 'last'],
                ['foo', 'bar', 'baz', 'bat'],
            ],
            [
                ['foo', 'bar', 'bat' => 'last', 'baz'],
                ['foo', 'bar', 'baz', 'bat'],
            ],
            [
                ['bat' => 'last', 'foo', 'bar', 'baz'],
                ['foo', 'bar', 'baz', 'bat'],
            ],
            [
                ['baz' => 'last', 'foo', 'bat' => 'last', 'bar'],
                ['foo', 'bar', 'baz', 'bat'],
            ],

            // Before position
            [
                ['foo' => ['before' => 'bar'], 'bar', 'baz', 'bat'],
                ['foo', 'bar', 'baz', 'bat'],
            ],
            [
                ['bar', 'foo' => ['before' => 'bar'], 'baz', 'bat'],
                ['foo', 'bar', 'baz', 'bat'],
            ],
            [
                ['bar', 'baz', 'bat', 'foo' => ['before' => 'bar']],
                ['foo', 'bar', 'baz', 'bat'],
            ],
            [
                [
                    'bar' => ['before' => 'baz'],
                    'foo' => ['before' => 'bar'],
                    'bat',
                    'baz' => ['before' => 'bat'],
                ],
                ['foo', 'bar', 'baz', 'bat'],
            ],
            [
                [
                    'bar' => ['before' => 'bat'],
                    'foo' => ['before' => 'bar'],
                    'bat',
                    'baz' => ['before' => 'bat'],
                ],
                ['foo', 'bar', 'baz', 'bat'],
            ],

            // After position
            [
                ['foo', 'bar' => ['after' => 'foo'], 'baz', 'bat'],
                ['foo', 'bar', 'baz', 'bat'],
            ],
            [
                ['bar' => ['after' => 'foo'], 'foo', 'baz', 'bat'],
                ['foo', 'bar', 'baz', 'bat'],
            ],
            [
                ['foo', 'baz', 'bat', 'bar' => ['after' => 'foo']],
                ['foo', 'bar', 'baz', 'bat'],
            ],
            [
                [
                    'foo',
                    'baz' => ['after' => 'bar'],
                    'bat' => ['after' => 'baz'],
                    'bar' => ['after' => 'foo'],
                ],
                ['foo', 'bar', 'baz', 'bat'],
            ],
            [
                [
                    'foo',
                    'baz' => ['after' => 'bar'],
                    'bat' => ['after' => 'bar'],
                    'bar' => ['after' => 'foo'],
                ],
                ['foo', 'bar', 'baz', 'bat'],
            ],

            // First & last position
            [
                ['foo' => 'first', 'bar', 'baz', 'bat' => 'last'],
                ['foo', 'bar', 'baz', 'bat'],
            ],
            [
                ['bar', 'bat' => 'last', 'foo' => 'first', 'baz'],
                ['foo', 'bar', 'baz', 'bat'],
            ],
            [
                ['baz' => 'last', 'foo' => 'first', 'bar' => 'first', 'bat' => 'last'],
                ['foo', 'bar', 'baz', 'bat'],
            ],

            // Before & after position
            [
                ['foo', 'bar' => ['after' => 'foo', 'before' => 'baz'], 'baz', 'bat'],
                ['foo', 'bar', 'baz', 'bat'],
            ],
            [
                ['foo', 'bar' => ['before' => 'baz', 'after' => 'foo'], 'baz', 'bat'],
                ['foo', 'bar', 'baz', 'bat'],
            ],
            [
                ['bar' => ['after' => 'foo', 'before' => 'baz'], 'foo', 'baz', 'bat'],
                ['foo', 'bar', 'baz', 'bat'],
            ],
            [
                ['bar' => ['before' => 'baz', 'after' => 'foo'], 'foo', 'baz', 'bat'],
                ['foo', 'bar', 'baz', 'bat'],
            ],
            [
                ['foo', 'baz', 'bat', 'bar' => ['after' => 'foo', 'before' => 'baz']],
                ['foo', 'bar', 'baz', 'bat'],
            ],
            [
                ['foo', 'baz', 'bat', 'bar' => ['before' => 'baz', 'after' => 'foo']],
                ['foo', 'bar', 'baz', 'bat'],
            ],
            [
                ['foo' => ['before' => 'bar'], 'bar', 'baz' => ['after' => 'bar'], 'bat'],
                ['foo', 'bar', 'baz', 'bat'],
            ],
            [
                ['bar', 'foo' => ['before' => 'bar'], 'bat', 'baz' => ['after' => 'bar']],
                ['foo', 'bar', 'baz', 'bat'],
            ],
            [
                ['bar' => ['after' => 'foo'], 'foo', 'bat', 'baz' => ['before' => 'bat']],
                ['foo', 'bar', 'baz', 'bat'],
            ],
            [
                [
                    'bar' => ['after' => 'foo', 'before' => 'baz'],
                    'foo',
                    'bat',
                    'baz' => ['before' => 'bat', 'after' => 'bar'],
                ],
                ['foo', 'bar', 'baz', 'bat'],
            ],

            // First, last, before & after position
            [
                [
                    'bar' => ['after' => 'foo', 'before' => 'baz'],
                    'foo' => 'first',
                    'bat' => 'last',
                    'baz' => ['before' => 'bat', 'after' => 'bar'],
                ],
                ['foo', 'bar', 'baz', 'bat'],
            ],
            [
                [
                    'bar' => ['after' => 'foo', 'before' => 'baz'],
                    'foo' => 'first',
                    'bat',
                    'baz' => ['before' => 'bat'],
                    'nan' => 'last',
                    'pop' => ['after' => 'ban'],
                    'ban',
                    'biz' => ['before' => 'nan'],
                    'boz' => ['before' => 'biz', ['after' => 'pop']],
                ],
                ['foo', 'bar', 'baz', 'bat', 'ban', 'pop', 'boz', 'biz', 'nan'],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getInvalidPositions()
    {
        return [
            // Invalid before/after
            [
                ['foo' => ['before' => 'bar']],
                'The "foo" form is configured to be placed just before the form "bar" but the form "bar" does not exist.',
            ],
            [
                ['foo' => ['after' => 'bar']],
                'The "foo" form is configured to be placed just after the form "bar" but the form "bar" does not exist.',
            ],

            // Circular before
            [
                ['foo' => ['before' => 'foo']],
                'The form ordering cannot be resolved due to conflict in before positions ("foo" => "foo")',
            ],
            [
                ['foo' => ['before' => 'bar'], 'bar' => ['before' => 'foo']],
                'The form ordering cannot be resolved due to conflict in before positions ("bar" => "foo" => "bar").',
            ],
            [
                [
                    'foo' => ['before' => 'bar'],
                    'bar' => ['before' => 'baz'],
                    'baz' => ['before' => 'foo'],
                ],
                'The form ordering cannot be resolved due to conflict in before positions ("baz" => "bar" => "foo" => "baz").',
            ],

            // Circular after
            [
                ['foo' => ['after' => 'foo']],
                'The form ordering cannot be resolved due to conflict in after positions ("foo" => "foo").',
            ],
            [
                ['foo' => ['after' => 'bar'], 'bar' => ['after' => 'foo']],
                'The form ordering cannot be resolved due to conflict in after positions ("bar" => "foo" => "bar").',
            ],
            [
                [
                    'foo' => ['after' => 'bar'],
                    'bar' => ['after' => 'baz'],
                    'baz' => ['after' => 'foo'],
                ],
                'The form ordering cannot be resolved due to conflict in after positions ("baz" => "bar" => "foo" => "baz").',
            ],

            // Symetric before/after
            [
                ['foo' => ['before' => 'bar'], 'bar' => ['after' => 'foo']],
                'The form ordering does not support symetrical before/after option ("bar" <=> "foo").',
            ],
            [
                [
                    'bat' => ['before' => 'baz'],
                    'baz' => ['after' => 'bar'],
                    'foo' => ['before' => 'bar'],
                    'bar' => ['after' => 'foo'],
                ],
                'The form ordering does not support symetrical before/after option ("bar" <=> "foo").',
            ],
        ];
    }

    /**
     * @param array $config
     *
     * @return FormInterface
     */
    private function createForm(array $config)
    {
        $builder = $this->factory->createBuilder();
        $type = method_exists(AbstractType::class, 'getBlockPrefix') ? FormType::class : 'form';

        foreach ($config as $name => $value) {
            if ((is_string($value) && is_string($name)) || is_array($value)) {
                $builder->add($name, $type, ['position' => $value]);
            } else {
                $builder->add($value, $type);
            }
        }

        return $builder->getForm();
    }

    /**
     * @param FormView $view
     * @param array    $expected
     */
    private function assertPositions(FormView $view, array $expected)
    {
        $children = array_values($view->children);

        foreach ($expected as $index => $value) {
            $this->assertArrayHasKey($index, $children);
            $this->assertArrayHasKey($value, $view->children);

            $this->assertSame($children[$index], $view->children[$value]);
        }
    }
}
