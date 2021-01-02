import {Expose, Transform, Type} from 'class-transformer';

@Expose()
export default class Song {
  @Type(() => Number)
  id: number;

  title: string;

  writer: string;

  composers: string[];

  performers: string[];

  @Type(() => Date)
  @Expose({ name: 'published_at' })
  publishedAt: Date;

  @Expose({ name: 'text_filename' })
  textFilename: string;

  @Expose({ name: 'text_file_format' })
  textFileFormat: string;

  @Expose({ name: 'stanza_delimiter' })
  stanzaDelimiter: string;

  @Type(() => String)
  lyrics?: string

  @Expose({ name: 'words_processed' })
  @Type(() => Boolean)
  wordsProcessed: boolean;

  @Type(() => Number)
  @Expose({ name: 'upload_by' })
  uploadBy: number;

  @Type(() => Date)
  @Expose({ name: 'created_at' })
  createdAt: Date;

  @Type(() => Date)
  @Expose({ name: 'updated_at' })
  updatedAt: Date;
}
