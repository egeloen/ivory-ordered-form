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

use Symfony\Component\Form\FormConfigInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
interface OrderedFormConfigInterface extends FormConfigInterface
{
    /**
     * @return string|array|null
     */
    public function getPosition();
}
