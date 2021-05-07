<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'content',
        'language_id',
    ];

    /**
     * Check if the word is exist when saving new word
     * If the word is not exist, saving it
     * 
     * @return bool
     */
    public function save(array $options = [])
    {
        $word = $this->content;
        $isWordExit = !empty( Word::where('content', $word)->get()->toArray() );
        if ($isWordExit) {
            return false;
        }
        return parent::save();
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
}
