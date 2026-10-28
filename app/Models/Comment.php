<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable= ['comment','approved', 'parent_id','user_id','commentable_id','commentable_type'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function parent(){
        return $this->belongsTo(Comment::class,'parent_id');
    }

    public function child(){
        return $this->hasMany(Comment::class,'parent_id','id')->where('approved', 1)->with(['child','user'])->latest();
    }

    public function commentable(){
        return $this->morphTo();
    }
}
