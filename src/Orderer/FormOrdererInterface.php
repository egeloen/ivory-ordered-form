<?php

/*
 * This file is part of the Ivory Ordered Form package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\OrderedForm\Orderer;

use Symfony\Component\Form\FormInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
interface FormOrdererInterface
{
    /**
     * @param FormInterface $form
     *
     * @return array
     */
    public function order(FormInterface $form);
}
