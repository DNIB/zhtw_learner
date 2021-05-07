<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sentence;

class SentenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sentence_hello = new Sentence;
        $sentence_hello->fill([
            'content' => 'Hello from the other side',
            'language_id' => '2',
        ]);
        $sentence_hello->createWordOfSentence();
        $sentence_hello->createWordOfSentence([
            '我',
            '從',
            '世界',
            '的',
            '另一頭',
            '向',
            '你',
            '問好'
        ]);

        $sentence_nene = new Sentence;
        $sentence_nene->fill([
            'content' => 'nene',
            'language_id' => '2',
        ]);
        $sentence_nene->createWordOfSentence(['ね','ね'], '3');

        $sentence_tama = new Sentence;
        $sentence_tama->fill([
            'content' => 'tama',
            'language_id' => '2',
        ]);
        $sentence_tama->createWordOfSentence(['た','ま'], '3');
    }
}
