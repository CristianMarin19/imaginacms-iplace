<?php

namespace Modules\Iplaces\Presenters;

use Laracasts\Presenter\Presenter;
use Modules\Iplaces\Entities\Status;

class CategoryPresenter extends Presenter
{
    /**
     * @var \Modules\Iplaces\Entities\Status
     */
    protected $status;

    /**
     * @var \Modules\Iplaces\Repositories\CategoryRepository
     */
    private $category;

    public function __construct($entity)
    {
        parent::__construct($entity);
        $this->category = app('Modules\Iplaces\Repositories\CategoryRepository');
        $this->status = app('Modules\Iplaces\Entities\Status');
    }

    /**
     * Get the post status
     */
    public function status()
    {
        return $this->status->get($this->entity->status);
    }

    /**
     * Getting the label class for the appropriate status
     */
    public function statusLabelClass()
    {
        switch ($this->entity->status) {
            case Status::INACTIVE:
                return 'bg-red';
                break;

            case Status::ACTIVE:
                return 'bg-green';
                break;

            default:
                return 'bg-red';
                break;
        }
    }
}
