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

/**
 * Ordered form button extension.
 *
 * @author tweini <tweini@gmail.com>
 * @author GeLo <geloen.eric@gmail.com>
 */
class OrderedButtonExtension extends AbstractOrderedExtension
{
    /**
    * {@inheritdoc}
    */
    public function getExtendedType()
    {
        return 'button';
    }
}
