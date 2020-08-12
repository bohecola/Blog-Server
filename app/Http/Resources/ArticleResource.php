<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'user_id'=> $this->user_id,
            'title' => $this->title,
            'content' => $this->content,
            'views' => $this->views,
            'created_at' => $this->created_at->format('Y年m月d日'),
            'updated_at' => $this->updated_at->format('Y年m月d日')
        ];
    }
}
