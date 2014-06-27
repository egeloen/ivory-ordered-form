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

use Ivory\OrderedForm\Orderer\FormOrderer;
use Ivory\OrderedForm\Orderer\FormOrdererInterface;
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
    /** @var \Ivory\OrderedForm\Orderer\FormOrdererInterface */
    protected $orderer;

    /**
     * Creates an orderer resolved form type factory.
     *
     * @param \Ivory\OrderedForm\Orderer\FormOrdererInterface|null $orderer The form orderer.
     */
    public function __construct(FormOrdererInterface $orderer = null)
    {
        $this->orderer = $orderer ?: new FormOrderer();
    }

    /**
     * {@inheritdoc}
     */
    public function createResolvedType(
        FormTypeInterface $type,
        array $typeExtensions,
        ResolvedFormTypeInterface $parent = null
    ) {
        return new OrderedResolvedFormType($this->orderer, $type, $typeExtensions, $parent);
    }
}
