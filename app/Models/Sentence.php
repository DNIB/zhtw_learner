<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sentence extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sentences';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'language',
        'sentence',
    ];

    /**
     * Construct the one-to-many relation with WordToSentence
     * 
     * @return mixed
     */
    public function wordsRelation()
    {
        return $this->hasMany(
            WordToSentence::class,
            'sentence_id',
            'id',
        );
    }

    /**
     * Construct the many-to-many relation with Word
     * 
     * @return mixed
     */
    public function words()
    {
        return $this->belongsToMany(
            Zhtw_Word::class,
            WordToSentence::class,
            'sentence_id',
            'zhtw_word_id',
            'id',
            'id',
        );
    }
}
