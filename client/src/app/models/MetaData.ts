import {Expose} from 'class-transformer';
import WordsBag from '@app/models/WordsBag';
import Expression from '@app/models/Expression';

@Expose()
export default class MetaData {
  @Expose({ name: 'words_bags' })
  wordsBags: WordsBag[];
  expressions: Expression[];
}
