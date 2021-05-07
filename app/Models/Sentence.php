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
     * Validate if the order of input Word is correct or not
     * 
     * @param array $words
     * 
     * @return bool
     */
    public function validateWord(array $words = [])
    {
        if (empty($words)) {
            return false;
        }

        $correct_words = $this->wordIdWithOrder();
        if (count($words) != count($correct_words)) {
            return false;
        }

        foreach ($words as $key => $word) {
            if ($word['id'] != $correct_words[$key]['word_id']) {
                return false;
            }
        }
        return true;
    }

    /**
     * Return the words of this sentence with order
     * 
     * @return array
     */
    public function wordIdWithOrder()
    {
        return $this->word_to_sentence()->orderBy('order', 'asc')->get('word_id')->toArray();
    }

    /**
     * Construct the one-to-many relation with WordToSentence
     * 
     * @return mixed
     */
    public function word_to_sentence()
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
            'word_id',
            'id',
            'id'
        );
    }
}
