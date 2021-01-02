import {Exclude, Expose, Type} from 'class-transformer';


@Expose()
export default class User {
  @Type(() => Number)
  id: number;

  @Type(() => String)
  name: string;

  @Type(() => String)
  email: string;

  @Exclude()
  @Type(() => String)
  password: string;

  @Expose({ name: 'email_verified_at' })
  @Type(() => Date)
  emailVerifiedAt?: Date;

  @Expose({ name: 'updated_at' })
  @Type(() => Date)
  updatedAt: Date;

  @Expose({ name: 'created_at' })
  @Type(() => Date)
  createdAt: Date;
}
