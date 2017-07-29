<?php

namespace Sweetchuck\Robo\Yarn\Task;

use Sweetchuck\Robo\Yarn\Option\CommonOptions;

class CommonTask extends BaseTask
{
    use CommonOptions;

    /**
     * {@inheritdoc}
     */
    protected function getOptions(): array
    {
        return $this->getOptionsCommon() + parent::getOptions();
    }

    public function setOptions(array $options)
    {
        parent::setOptions($options);
        $this->setOptionsCommon($options);
    }
}
