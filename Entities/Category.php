<?php

namespace Modules\Iplaces\Entities;

use Astrotomic\Translatable\Translatable;
use http\Url;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Modules\Iplaces\Presenters\CategoryPresenter;
use Modules\Iplaces\Events\CategoryWasCreated;
use Modules\Core\Traits\NamespacedEntity;
use Laracasts\Presenter\PresentableTrait;
use Modules\Media\Support\Traits\MediaRelation;
use Modules\Media\Entities\File;
use Kalnoy\Nestedset\NodeTrait;
use Modules\Core\Support\Traits\AuditTrait;
use Modules\Isite\Traits\RevisionableTrait;
use Modules\Iqreable\Traits\IsQreable;

class Category extends Model
{
  use Translatable, PresentableTrait, NamespacedEntity, MediaRelation, NodeTrait, AuditTrait, RevisionableTrait, IsQreable;

    //use Sluggable;

    public $transformer = 'Modules\Iplaces\Transformers\CategoryTransformer';

    public $entity = 'Modules\Iplaces\Entities\Category';

    public $repository = 'Modules\Iplaces\Repositories\CategoryRepository';

    protected $table = 'iplaces__categories';

    public $translatedAttributes = ['title',
        'description',
        'slug',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $fillable = [
        'title',
        'description',
        'slug',
        'parent_id',
        'options',
        'status',
        'sort_order',
        'featured',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $fakeColumns = ['options'];

    protected $presenter = CategoryPresenter::class;

    protected $casts = [
        'options' => 'array',
    ];

    /*
     * ---------
     * RELATIONS
     * ---------
     */
    public function parent()
    {
        return $this->belongsTo('Modules\Iplaces\Entities\Category', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany('Modules\Iplaces\Entities\Category', 'parent_id');
    }

    public function places()
    {
        return $this->belongsToMany(Place::class, 'iplaces__place_category');
    }

    //generar url automatica
    /* public function sluggable()
     {
         return [
             'slug' => [
                 'source' => 'title'
             ]
         ];
     }*/
    /*
     * -------------
     * IMAGE
     * -------------
     */
    public function getMainImageAttribute()
    {
        $thumbnail = $this->files()->where('zone', 'mainimage')->first();
        if (! $thumbnail) {
            return [
                'mimeType' => 'image/jpeg',
                'path' => url('modules/iblog/img/post/default.jpg'),
            ];
        }

        return json_decode(json_encode([
            'mimeType' => $thumbnail->mimetype,
            'path' => $thumbnail->path_string,
        ]));
    }

    /**
     * @return mixed
     */
    public function getUrlAttribute()
    {
        $locale = \LaravelLocalization::setLocale() ?: \App::getLocale();

        return \URL::route($locale.'.iplaces.place.category', [$this->slug]);
    }

    public function getOptionsAttribute($value)
    {
      $response = json_decode($value);

      if(is_string($response)) {
        $response = json_decode($response);
      }

      return $response;
    }

    /*
 |--------------------------------------------------------------------------
 | SCOPES
 |--------------------------------------------------------------------------
 */
    public function scopeFirstLevelItems($query)
    {
        return $query->where('depth', '1')
          ->orWhere('depth', null)
          ->orderBy('lft', 'ASC');
    }

    /**
     * Check if the post is in draft
     */
    public function scopeActive(Builder $query)
    {
        return $query->whereStatus(Status::ACTIVE);
    }

    /**
     * Check if the post is pending review
     */
    public function scopeInactive(Builder $query)
    {
        return $query->whereStatus(Status::INACTIVE);
    }

    public function getLftName()
    {
        return 'lft';
    }

    public function getRgtName()
    {
        return 'rgt';
    }

    public function getDepthName()
    {
        return 'depth';
    }

    public function getParentIdName()
    {
        return 'parent_id';
    }
}
