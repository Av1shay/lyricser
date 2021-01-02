import {Expose, Transform, Type} from 'class-transformer';
import {WordPositions} from '../typing/app';

@Expose()
export default class Word {
  @Type(() => Number)
  id: number;

  @Type(() => String)
  @Transform((val: string) => val.trim())
  value: string;

  @Type(() => Number)
  @Expose({ name: 'song_id' })
  songId: number;

  @Type(() => Number)
  @Expose({ name: 'start_index' })
  startIndex: number;

  @Type(() => Number)
  line: number;

  position: WordPositions;

  @Type(() => Number)
  stanza: number;

  @Type(() => Date)
  @Expose({ name: 'created_at' })
  createdAt: Date;

  @Type(() => Date)
  @Expose({ name: 'updated_at' })
  updatedAt: Date;
}
