<?php

/*
 * This file is part of the Ivory Ordered Form package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\OrderedForm;

use Ivory\OrderedForm\Builder\OrderedButtonBuilder;
use Ivory\OrderedForm\Builder\OrderedFormBuilder;
use Ivory\OrderedForm\Builder\OrderedSubmitButtonBuilder;
use Ivory\OrderedForm\Orderer\FormOrdererInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\ButtonTypeInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\ResolvedFormType;
use Symfony\Component\Form\ResolvedFormTypeInterface;
use Symfony\Component\Form\SubmitButtonTypeInterface;

/**
 * Ordered resolved form type.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class OrderedResolvedFormType extends ResolvedFormType
{
    /** @var \Ivory\OrderedForm\Model\FormOrdererInterface */
    protected $orderer;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        FormOrdererInterface $orderer,
        FormTypeInterface $innerType,
        array $typeExtensions = array(),
        ResolvedFormTypeInterface $parent = null
    ) {
        parent::__construct($innerType, $typeExtensions, $parent);

        $this->orderer = $orderer;
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        parent::finishView($view, $form, $options);

        $children = $view->children;
        $view->children = array();

        foreach ($this->orderer->order($form) as $name) {
            $view->children[$name] = $children[$name];
            unset($children[$name]);
        }

        foreach ($children as $name => $child) {
            $view->children[$name] = $child;
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function newBuilder($name, $dataClass, FormFactoryInterface $factory, array $options)
    {
        $innerType = $this->getInnerType();

        if ($innerType instanceof ButtonTypeInterface) {
            return new OrderedButtonBuilder($name, $options);
        }

        if ($innerType instanceof SubmitButtonTypeInterface) {
            return new OrderedSubmitButtonBuilder($name, $options);
        }

        return new OrderedFormBuilder($name, $dataClass, new EventDispatcher(), $factory, $options);
    }
}
