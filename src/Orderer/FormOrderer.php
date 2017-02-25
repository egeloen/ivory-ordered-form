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

use Ivory\OrderedForm\Exception\OrderedConfigurationException;
use Symfony\Component\Form\FormInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class FormOrderer implements FormOrdererInterface
{
    /**
     * @var array
     */
    private $weights;

    /**
     * @var array
     */
    private $differed;

    /**
     * @var int
     */
    private $firstWeight;

    /**
     * @var int
     */
    private $currentWeight;

    /**
     * @var int
     */
    private $lastWeight;

    /**
     * {@inheritdoc}
     */
    public function order(FormInterface $form)
    {
        $this->reset();

        foreach ($form as $child) {
            $position = $child->getConfig()->getPosition();

            if (empty($position)) {
                $this->processEmptyPosition($child);
            } elseif (is_string($position)) {
                $this->processStringPosition($child, $position);
            } else {
                $this->processArrayPosition($child, $position);
            }
        }

        asort($this->weights, SORT_NUMERIC);

        return array_keys($this->weights);
    }

    /**
     * @param FormInterface $form
     */
    private function processEmptyPosition(FormInterface $form)
    {
        $this->processWeight($form, $this->currentWeight);
    }

    /**
     * @param FormInterface $form
     * @param string        $position
     */
    private function processStringPosition(FormInterface $form, $position)
    {
        if ($position === 'first') {
            $this->processFirst($form);
        } else {
            $this->processLast($form);
        }
    }

    /**
     * @param FormInterface $form
     * @param array         $position
     */
    private function processArrayPosition(FormInterface $form, array $position)
    {
        if (isset($position['before'])) {
            $this->processBefore($form, $position['before']);
        }

        if (isset($position['after'])) {
            $this->processAfter($form, $position['after']);
        }
    }

    /**
     * @param FormInterface $form
     */
    private function processFirst(FormInterface $form)
    {
        $this->processWeight($form, $this->firstWeight++);
    }

    /**
     * @param FormInterface $form
     */
    private function processLast(FormInterface $form)
    {
        $this->processWeight($form, $this->lastWeight + 1);
    }

    /**
     * @param FormInterface $form
     * @param string        $before
     */
    private function processBefore(FormInterface $form, $before)
    {
        if (!isset($this->weights[$before])) {
            $this->processDiffered($form, $before, 'before');
        } else {
            $this->processWeight($form, $this->weights[$before]);
        }
    }

    /**
     * @param FormInterface $form
     * @param string        $after
     */
    private function processAfter(FormInterface $form, $after)
    {
        if (!isset($this->weights[$after])) {
            $this->processDiffered($form, $after, 'after');
        } else {
            $this->processWeight($form, $this->weights[$after] + 1);
        }
    }

    /**
     * @param FormInterface $form
     * @param int           $weight
     */
    private function processWeight(FormInterface $form, $weight)
    {
        foreach ($this->weights as &$weightRef) {
            if ($weightRef >= $weight) {
                ++$weightRef;
            }
        }

        if ($this->currentWeight >= $weight) {
            ++$this->currentWeight;
        }

        ++$this->lastWeight;

        $this->weights[$form->getName()] = $weight;
        $this->finishWeight($form, $weight);
    }

    /**
     * @param FormInterface $form
     * @param int           $weight
     * @param string        $position
     *
     * @return int
     */
    private function finishWeight(FormInterface $form, $weight, $position = null)
    {
        if ($position === null) {
            foreach (array_keys($this->differed) as $position) {
                $weight = $this->finishWeight($form, $weight, $position);
            }
        } else {
            $name = $form->getName();

            if (isset($this->differed[$position][$name])) {
                $postIncrement = $position === 'before';

                foreach ($this->differed[$position][$name] as $differed) {
                    $this->processWeight($differed, $postIncrement ? $weight++ : ++$weight);
                }

                unset($this->differed[$position][$name]);
            }
        }

        return $weight;
    }

    /**
     * @param FormInterface $form
     * @param string        $differed
     * @param string        $position
     *
     * @throws OrderedConfigurationException
     */
    private function processDiffered(FormInterface $form, $differed, $position)
    {
        if (!$form->getParent()->has($differed)) {
            throw OrderedConfigurationException::createInvalidDiffered($form->getName(), $position, $differed);
        }

        $this->differed[$position][$differed][] = $form;

        $name = $form->getName();

        $this->detectCircularDiffered($name, $position);
        $this->detectedSymmetricDiffered($name, $differed, $position);
    }

    /**
     * @param string $name
     * @param string $position
     * @param array  $stack
     *
     * @throws OrderedConfigurationException
     */
    private function detectCircularDiffered($name, $position, array $stack = [])
    {
        if (!isset($this->differed[$position][$name])) {
            return;
        }

        $stack[] = $name;

        foreach ($this->differed[$position][$name] as $differed) {
            $differedName = $differed->getName();

            if ($differedName === $stack[0]) {
                throw OrderedConfigurationException::createCircularDiffered($stack, $position);
            }

            $this->detectCircularDiffered($differedName, $position, $stack);
        }
    }

    /**
     * @param string $name
     * @param string $differed
     * @param string $position
     *
     * @throws OrderedConfigurationException
     */
    private function detectedSymmetricDiffered($name, $differed, $position)
    {
        $reversePosition = ($position === 'before') ? 'after' : 'before';

        if (isset($this->differed[$reversePosition][$name])) {
            foreach ($this->differed[$reversePosition][$name] as $diff) {
                if ($diff->getName() === $differed) {
                    throw OrderedConfigurationException::createSymetricDiffered($name, $differed);
                }
            }
        }
    }

    private function reset()
    {
        $this->weights = [];
        $this->differed = [
            'before' => [],
            'after'  => [],
        ];

        $this->firstWeight = 0;
        $this->currentWeight = 0;
        $this->lastWeight = 0;
    }
}
