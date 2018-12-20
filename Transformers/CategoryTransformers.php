<?php


namespace Modules\Iplaces\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Modules\User\Transformers\UserProfileTransformer;
use Modules\Iplaces\Events\CategoryWasCreated;


class CategoryTransformers extends Resource
{

    public function toArray($request)
    {

      //  $dateformat= config('asgard.iplace.config.dateformat');
        $options=$this->options;
        unset($options->mainimage,$options->metatitle,$options->metadescription);
        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'status'=>$this->status,
            'parent_id' => $this->parent_id,
            'parent_category' => new CategoryTransformers($this->parent),
            'mainimage' => $this->mainimage,
            'metatitle'=>$this->metatitle??$this->title,
            'metadescription'=>$this->metadescription,
            'metakeywords'=>$this->metakeywords,
            'options' => $options,
            'created_at' => ($this->created_at),
            'updated_at' => ($this->updated_at)
        ];

        return $data;


    }


}