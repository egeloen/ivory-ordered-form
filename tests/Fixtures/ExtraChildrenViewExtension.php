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

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * Extra view children extension.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class ExtraChildrenViewExtension extends AbstractTypeExtension
{
    /** @var array */
    private $names;

    /**
     * Creates an extra view children extension.
     *
     * @param array $names The extra view names.
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
        foreach ($this->names as $name) {
            $view->children[$name] = new FormView($view);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix')
            ? 'Symfony\Component\Form\Extension\Core\Type\FormType'
            : 'form';
    }
}
