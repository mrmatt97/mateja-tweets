<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tweet extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'body',
        'user_id',
        'likes',
    ];
    protected $appends = ['editable'];

    public function getEditableAttribute()
    {
        return ($this->user_id == auth()->id()) || auth()->user()->role == "admin";
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function like()
    {
        $this->increment('likes');
    }
    public function unlike()
    {
        $this->decrement('likes');
    }
}
