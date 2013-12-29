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

use Ivory\OrderedForm\Orderer\FormOrdererFactoryInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\ResolvedFormTypeFactory;
use Symfony\Component\Form\ResolvedFormTypeInterface;

/**
 * Ordered resolved form type factory.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class OrderedResolvedFormTypeFactory extends ResolvedFormTypeFactory
{
    /** @var \Ivory\OrderedForm\Orderer\FormOrdererFactoryInterface */
    protected $ordererFactory;

    /**
     * Creates an orderer resolved form type factory.
     *
     * @param \Ivory\OrderedForm\Orderer\FormOrdererFactoryInterface $ordererFactory The form orderer factory.
     */
    public function __construct(FormOrdererFactoryInterface $ordererFactory)
    {
        $this->ordererFactory = $ordererFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function createResolvedType(
        FormTypeInterface $type,
        array $typeExtensions,
        ResolvedFormTypeInterface $parent = null
    ) {
        return new OrderedResolvedFormType($this->ordererFactory->create(), $type, $typeExtensions, $parent);
    }
}
