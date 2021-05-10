<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Return a sentence that fit user's rank
     * 
     * @return App\Models\Sentence | bool
     */
    public function getExamSentence()
    {
        $incompleted_sentences = $this->getSentenceIncompleted();
        if (empty($incompleted_sentences)) {
            return false;
        }

        $index = random_int(0, count($incompleted_sentences)-1);
        return $incompleted_sentences[$index];
    }

    /**
     * Return a sentence that fit user's rank
     * 
     * @return array
     */
    private function getSentenceIncompleted()
    {
        $sentences = Sentence::where('rank', $this->rank)->get();
        $completed_sentences = $this->completed_sentences();
        $incompleted_sentences = [];

        foreach ($sentences as $sentence) {
            $isIncompleted = empty($completed_sentences->where('sentence_id', $sentence->id)->first());
            if ($isIncompleted) {
                $incompleted_sentences[] = $sentence;
            }
        
        }
        return $incompleted_sentences;
    }


    /**
     * Construct the many-to-many relation with Sentence
     * 
     * @return mixed
     */
    public function completed_sentences()
    {
        return $this->belongsToMany(
            Sentence::class,
            CompletedSentence::class,
            'user_id',
            'sentence_id',
            'id',
            'id'
        );
    }
}
