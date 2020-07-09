<?php

namespace WebTheory\Saveyour\Concerns;

use WebTheory\Saveyour\Contracts\ChecklistItemsInterface;

trait IsBasicallyChecklistTrait
{
    /**
     * @var ChecklistItemsInterface
     */
    protected $checklistItemProvider;

    /**
     * Get the value of checklistItemProvider
     *
     * @return ChecklistItemsInterface
     */
    public function getChecklistItemProvider(): ChecklistItemsInterface
    {
        return $this->checklistItemProvider;
    }

    /**
     * Set the value of checklistItemProvider
     *
     * @param ChecklistItemsInterface $checklistItemProvider
     *
     * @return self
     */
    public function setChecklistItemProvider(ChecklistItemsInterface $checklistItemProvider)
    {
        $this->checklistItemProvider = $checklistItemProvider;

        return $this;
    }
}
