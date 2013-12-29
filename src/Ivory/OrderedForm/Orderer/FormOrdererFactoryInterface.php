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

/**
 * Form orderer factory.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
interface FormOrdererFactoryInterface
{
    /**
     * Creates a form orderer.
     *
     * @return \Ivory\OrderedForm\Orderer\FormOrdererInterface The form orderer.
     */
    public function create();
}
