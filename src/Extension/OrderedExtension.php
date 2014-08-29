<?php

/*
 * This file is part of the Ivory Ordered Form package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\OrderedForm\Extension;

use Symfony\Component\Form\AbstractExtension;

/**
 * Ordered extension.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class OrderedExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    protected function loadTypeExtensions()
    {
        return array(
            new OrderedFormExtension(),
            new OrderedButtonExtension(),
        );
    }
}
