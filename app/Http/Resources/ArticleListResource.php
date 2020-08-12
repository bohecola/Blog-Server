<?php

namespace App\Http\Resources;

use App\Article;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleListResource extends JsonResource
{
    function __construct(Article $model)
    {
        parent::__construct($model);
    }

    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'user_id'=> $this->user_id,
            'title' => $this->title,
            'views' => $this->views,
            'created_at' => $this->created_at->format('Y年m月d日'),
            'updated_at' => $this->updated_at->format('Y年m月d日')
        ];
    }
}
