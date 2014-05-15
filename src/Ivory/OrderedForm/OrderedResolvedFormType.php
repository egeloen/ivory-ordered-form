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
use Symfony\Component\Form\FormTypeInterface;
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
    protected function newBuilder(
        $name,
        $dataClass,
        FormFactoryInterface $factory,
        array $options
    ) {
        $innerType = $this->getInnerType();

        if ($innerType instanceof ButtonTypeInterface) {
            return new OrderedButtonBuilder($name, $options);
        }

        if ($innerType instanceof SubmitButtonTypeInterface) {
            return new OrderedSubmitButtonBuilder($name, $options);
        }

        return new OrderedFormBuilder(
            $name,
            $dataClass,
            new EventDispatcher(),
            $factory,
            $this->orderer,
            $options
        );
    }
}
