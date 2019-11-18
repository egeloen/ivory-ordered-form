<?php

/*
 * This file is part of the Ivory Ordered Form package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Tests\OrderedForm\Fixtures;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class RemoveChildrenViewExtension extends AbstractTypeExtension
{
    /**
     * @var array
     */
    private $names;

    /**
     * @param array $names
     */
    public function __construct(array $names)
    {
        $this->names = $names;
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        if ($form->isRoot()) {
            return;
        }

        foreach ($this->names as $name) {
            unset($view[$name]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getExtendedTypes(): iterable
    {
        return [method_exists(AbstractType::class, 'getBlockPrefix') ? FormType::class : 'form'];
    }

    public function getExtendedType()
    {
        return method_exists(AbstractType::class, 'getBlockPrefix') ? FormType::class : 'form';
    }
}
