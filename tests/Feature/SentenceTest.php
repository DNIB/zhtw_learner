<?php

namespace Tests\Feature;

use App\Models\Sentence;
use App\Models\Word;
use App\Models\WordToSentence;
use Database\Seeders\Tests\SentenceTestSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertTrue;

class SentenceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Indicates whether the default seeder should run before each test.
     *
     * @var bool
     */
    protected $seed = true;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCreate()
    {
        $sentence_hello = new Sentence;
        $sentence_hello->fill([
            'content' => 'Hello from the other side',
            'language_id' => '2',
        ]);
        $this->assertFalse( $sentence_hello->createWordOfSentence() );
        $this->assertTrue( $sentence_hello->createWordOfSentence([
            '我',
            '從',
            '世界',
            '的',
            '另一頭',
            '向',
            '你',
            '問好'
        ]) );

        $sentence_nene = new Sentence;
        $sentence_nene->fill([
            'content' => 'nene',
            'language_id' => '2',
        ]);
        $this->assertTrue( $sentence_nene->createWordOfSentence(
            ['ね','ね'], '3'
        ));

        $sentence_tama = new Sentence;
        $sentence_tama->fill([
            'content' => 'tama',
            'language_id' => '2',
        ]);
        $this->assertTrue( $sentence_tama->createWordOfSentence(
            ['た','ま'], '3'
        ));

        $word_hello = $sentence_hello->words()->get()->toArray();
        $word_test_result = $sentence_hello->validateWord($word_hello);
        $this->assertTrue($word_test_result);

        $word_nene = $sentence_nene->words()->get()->toArray();
        $word_test_result = $sentence_nene->validateWord($word_nene);
        $this->assertTrue($word_test_result);

        $word_test_result = $sentence_hello->validateWord($word_nene);
        $this->assertFalse($word_test_result);

        $word_tama = $sentence_tama->words()->get()->toArray();
        $word_test_result = $sentence_nene->validateWord($word_tama);
        $this->assertFalse($word_test_result);

        $word_test_result = $sentence_nene->validateWord([]);
        $this->assertFalse($word_test_result);
    }
}
