<?php


namespace App\Services;


use App\Enums\WordPositions;
use App\Models\Song;
use App\Services\Contracts\WordServiceInterface;
use Illuminate\Support\Facades\Cache;
use App\Repositories\Contracts\WordRepositoryInterface;
use App\Services\Contracts\UploaderInterface;
use Carbon\Carbon;
use Exception;


class StringProcessor
{
    protected WordServiceInterface $wordService;

    protected UploaderInterface $uploader;

    public function __construct(WordServiceInterface $wordService, UploaderInterface $uploader)
    {
        $this->wordService = $wordService;
        $this->uploader = $uploader;
    }

    /**
     * Go over a song content and extract the words from it,
     *  then bulk-insert them to the DB.
     *
     * @param Song $song
     * @throws Exception
     */
    public function processSongWords(Song $song): void
    {
        if (!isset($song->text_filename, $song->stanzas_delimiter)) {
            throw new Exception('Missing Song properties text_filename and/or stanzas_delimiter');
        }

        $now = Carbon::now()->toDateTimeString();
        $wordsToInsert = [];
        $songContent = $this->uploader->getFileContent($song->text_filename);
        $stanzasDelimiter = $song->stanzas_delimiter;
        $stanzas = explode($stanzasDelimiter, $songContent);

        // Go over the stanzas
        foreach ($stanzas as $stanzaIndex => $stanzaContent) {
            $wordsOffset = 0;
            $stanzaNum = $stanzaIndex + 1;
            $lines = explode(PHP_EOL, trim($stanzaContent, "\n"));
            $linesCount = count($lines);

            // Go over the lines in each stanza
            foreach ($lines as $lineIndex => $lineContent) {
                $lineNum = $lineIndex + 1;
                $words = preg_split('/\s+/', $lineContent);
                $wordsCount = count($words);

                // Go over the words in each line
                foreach ($words as $wordIndex => $word) {
                    $startIndex = strpos($stanzaContent, $word, $wordsOffset);

                    if ($startIndex == 0) { // This word is at the beginning of the stanza?
                        $position = WordPositions::Start;
                    } else if ($lineIndex == ($linesCount - 1) && $wordIndex == ($wordsCount - 1)) { // This word is at the end of the stanza?
                        $position = WordPositions::End;
                    } else {
                        $position = WordPositions::Middle;
                    }

                    $wordsToInsert[] = [
                        'value' => $word,
                        'song_id' => $song->id,
                        'start_index' => $startIndex,
                        'line' => $lineNum,
                        'position' => $position,
                        'stanza' => $stanzaNum,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];

                    // increase the offset to skip this word in the next iterations, add one for space
                    $wordsOffset += strlen($word) + 1;
                }
            }
        }

        if (count($wordsToInsert) > 0) {
            $this->wordService->addWords($wordsToInsert);
        }

        $song->words_processed = true;

        $song->save();

        $this->wordService->refreshWordsIndexCache();
    }
}
