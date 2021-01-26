import Word from '../models/Word';
import {Expose, Transform, Type} from 'class-transformer';

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

export interface WordsContext {
  [wordVal: string]: WordContext[];
}

export interface QueryWordsResponse {
  words: Word[];
  totalCount: number;
  nextCursor?: string | number;
}

export interface ISongOption {
  id: number;
  title: string;
}


export class QueryData extends IHttpParams {
  @Expose({ name: 'song' })
  @Transform((song: ISongOption) => song?.id ? String(song.id) : null)
  songId: string;

  @Transform((value: string) => value.length > 0 ? value.trim() : value)
  @Type(() => String)
  wordTerm: string;

  @Type(() => String)
  maxResults: string;

  @Type(() => String)
  nextCursor: string;

  @Type(() => String)
  queryType: string;
}
