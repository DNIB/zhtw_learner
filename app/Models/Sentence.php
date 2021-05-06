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
        'language_id',
        'content'
    ];

    /**
     * Create the words of this Sentence
     * 
     * @param array $words
     * @param int $language_id
     * 
     * @return bool
     */
    public function createWordOfSentence(array $words = [], int $language_id = 1)
    {
        $isStateInvalid = ( empty($words) || empty($this->words) );
        if ($isStateInvalid) {
            return false;
        }

        $isSentenceNotExist = !isset($this->id);
        if ($isSentenceNotExist) {
            $this->save();
        }

        $order = 1;
        foreach ($words as $word) {
            $new_word = new Word;
            $new_word->fill([
                'content' => $word,
                'language_id' => $this->language_id,
            ]);
            if (!$new_word->save()){
                $new_word = Word::where('content', $word)->get()[0];
            }
            WordToSentence::create([
                'word_id' => $new_word->id,
                'sentence_id' => $this->id,
                'order' => $order,
            ]);
            $order += 1;
        }
        return true;
    }

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
     * Contruct the one-to-one relation with Language
     * 
     * @return mixed
     */
    public function language()
    {
        return $this->hasOne(
            Language::class,
            'id',
            'language_id'
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
            Word::class,
            WordToSentence::class,
            'sentence_id',
            'zhtw_word_id',
            'id',
            'id'
        );
    }
}
