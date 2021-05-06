<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Language extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Constuct the belong-to relation with Word
     * 
     * @return mixed
     */
    public function words()
    {
        return $this->belongsTo(
            Word::class,
            'id',
            'language_id'
        );
    }

    /**
     * Construct the belong-to relation with Sentence
     * 
     * @return mixed
     */
    public function sentences()
    {
        return $this->belongsTo(
            Sentence::class,
            'id',
            'language_id'
        );
    }
}
