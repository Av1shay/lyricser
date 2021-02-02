import {TopWord} from '@app/typing/app';

export default class Stats {
  songsCount: number;
  wordsCount: number;
  avgWordsLength: number
  avgWordsPerStanza: number;
  avgWordsPerSong: number;
  topWords: TopWord[];
}
