import Word from '../Models/Word';

export type WordPositions = 'start' | 'middle' | 'end';

export class IHttpParams {
  [param: string]: string | ReadonlyArray<string>
}

export interface WordContext {
  id: number;
  songId: number;
  before: string;
  after: string;
}

export interface WordsIndex {
  [wordVal: string]: WordContext[];
}

export interface QueryWordsResponse {
  words: Word[] | WordsIndex;
  totalCount: number;
  nextCursor?: string | number;
}
