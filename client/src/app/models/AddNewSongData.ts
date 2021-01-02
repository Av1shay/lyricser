import {Expose, Transform} from 'class-transformer';

@Expose()
export default class AddNewSongData {
  title: string;
  writer: string;

  @Transform(value => value.length > 0 ? value.split("\n") : '')
  composers?: string[];

  @Transform(value => value.length > 0 ? value.split("\n") : '')
  performers?: string[];

  publishedAt: string;

  @Transform(value => value || '/', { toClassOnly: true })
  stanzasDelimiter: string;
  file: string;
}
