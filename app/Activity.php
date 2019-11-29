<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $table = 'activities';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','activity', 'description', 'isconfirmed', 'contributors',
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
