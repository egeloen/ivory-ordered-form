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

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;

/**
 * @author GeLo <geloen.eric@gmail.com>
 * @author tweini <tweini@gmail.com>
 */
class OrderedButtonExtension extends AbstractOrderedExtension
{
    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return method_exists(AbstractType::class, 'getBlockPrefix') ? ButtonType::class : 'button';
    }
}
