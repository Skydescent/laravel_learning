<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostHistory extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $guarded = [];

    protected $casts = [
        'changed_fields' => 'array'
    ];

    /**
     * @return BelongsTo
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function post() : BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
