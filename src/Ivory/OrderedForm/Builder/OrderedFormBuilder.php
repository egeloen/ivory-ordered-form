<?php

/*
 * This file is part of the Ivory Ordered Form package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\OrderedForm\Builder;

use Ivory\OrderedForm\Exception\OrderedConfigurationException;
use Ivory\OrderedForm\OrderedFormConfigInterface;
use Ivory\OrderedForm\Orderer\FormOrdererInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Exception\BadMethodCallException;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * Ordered form builder.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class OrderedFormBuilder extends FormBuilder implements OrderedFormConfigBuilderInterface, OrderedFormConfigInterface
{
    /** @var \Ivory\OrderedForm\Orderer\FormOrdererInterface */
    protected $orderer;

    /** @var null|string|array */
    protected $position;

    /**
     * Creates an ordered form builder.
     *
     * @param string                                                      $name       The form name.
     * @param string                                                      $dataClass  The form data class.
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher The form event dispatcher.
     * @param \Symfony\Component\Form\FormFactoryInterface                $factory    The form factory.
     * @param \Ivory\OrderedForm\Orderer\FormOrdererInterface             $orderer    The form orderer.
     * @param array                                                       $options    The form options.
     */
    public function __construct(
        $name,
        $dataClass,
        EventDispatcherInterface $dispatcher,
        FormFactoryInterface $factory,
        FormOrdererInterface $orderer,
        array $options = array()
    ) {
        parent::__construct($name, $dataClass, $dispatcher, $factory, $options);

        $this->orderer = $orderer;
    }

    /**
     * {@inheritdoc}
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function setPosition($position)
    {
        if ($this->locked) {
            throw new BadMethodCallException('The config builder cannot be modified anymore.');
        }

        if (is_string($position) && ($position !== 'first') && ($position !== 'last')) {
            throw OrderedConfigurationException::createInvalidStringPosition($this->getName(), $position);
        }

        if (is_array($position) && !isset($position['before']) && !isset($position['after'])) {
            throw OrderedConfigurationException::createInvalidArrayPosition($this->getName(), $position);
        }

        $this->position = $position;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getForm()
    {
        $form = parent::getForm();

        foreach ($this->orderer->order($form) as $name) {
            $child = $form->get($name);
            $form->remove($name);
            $form->add($child);
        }

        return $form;
    }
}
