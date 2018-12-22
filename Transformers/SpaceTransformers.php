<?php


namespace Modules\Iplaces\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Iplaces\Entities\Status;

class SpaceTransformers extends Resource
{

    public function toArray($request)
    {

        $options=$this->options;
        unset($options->mainimage,$options->metatitle,$options->metadescription);

        $status = new Status();

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'status'=>$this->status,
            'statusName' => $status->get($this->status),
            'description' => $this->description,
            'mainimage' => $this->mainimage,
            'metatitle'=>$this->metatitle??$this->title,
            'metadescription'=>$this->metadescription,
            'metakeywords'=>$this->metakeywords,
            'options' => $options,
            'created_at' => ($this->created_at),
            'updated_at' => ($this->updated_at)
        ];

    }


}