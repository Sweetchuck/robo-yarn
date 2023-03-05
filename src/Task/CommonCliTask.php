<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\Yarn\Task;

use Sweetchuck\Robo\Yarn\Option\CommonOptions;

class CommonCliTask extends BaseCliTask
{
    use CommonOptions;

    /**
     * {@inheritdoc}
     */
    protected function getOptions(): array
    {
        return $this->getOptionsCommon() + parent::getOptions();
    }

    public function setOptions(array $options): static
    {
        parent::setOptions($options);
        $this->setOptionsCommon($options);

        return $this;
    }
}
