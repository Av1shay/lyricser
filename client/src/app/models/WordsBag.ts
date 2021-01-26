import {Expose} from 'class-transformer';

@Expose()
export default class WordsBag {
  id?: string;
  title: string;
  words: string[];
}
