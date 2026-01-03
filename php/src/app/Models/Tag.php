<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['tag_name'];

    public function todos()
    {
        return $this->belongsToMany(Todo::class, 'tag_todo')
                    ->withTimestamps();
    }

    /**
     * タグを保存する
     *
     * @param $request
     * @return \App\Models\Tag
     */
    public function storeTag($request)
    {
        $tagName = $request->input('tags');
        if (empty($tagName)) {
            $tagName = ' ';
        }

        $tag = Tag::create([
            'tag_name' => $tagName,
        ]);

        return $tag;
    }
}
