import {Component, OnDestroy, OnInit} from '@angular/core';
import {FormControl, FormGroup} from '@angular/forms';
import {IHttpParams, QueryWordsResponse} from '@app/typing/app';
import {Expose, plainToClass, Transform, Type} from 'class-transformer';
import Word from '@app/models/Word';
import WordService from '@app/services/word.service';
import UserService from '@app/services/user.service';
import WordsBag from '@app/models/WordsBag';
import {debounceTime, distinctUntilChanged, switchMap, tap} from 'rxjs/operators';
import Song from '@app/models/Song';
import {Observable, Subscription} from 'rxjs';
import SongService from '@app/services/song.service';
import User from '@app/models/User';

interface ISongOption {
  id: number;
  title: string;
}

class QueryData extends IHttpParams {
  @Expose({ name: 'song' })
  @Transform((song: ISongOption) => song?.id ? String(song.id) : null)
  songId?: string;

  @Type(() => String)
  bagId?: string;

  @Type(() => String)
  maxResults?: string;

  @Type(() => String)
  nextCursor?: string;
}

//TODO pass to config
const MAX_WORDS_RESULTS = 108;
const MAX_SONGS_RESULTS = 20;

@Component({
  selector: 'app-words-index',
  templateUrl: './words-index.component.html',
  styleUrls: ['./words-index.component.scss']
})
export class WordsIndexComponent implements OnInit, OnDestroy {
  readonly wordsPerPage = MAX_WORDS_RESULTS;

  form: FormGroup;
  currentUser: User;
  showLoader: boolean = true;
  words: Word[];
  wordsCount: number = 0;
  cursors: Array<string | number> = [];
  page: number = 0;
  bags: WordsBag[];
  showSongsSpinner: boolean = false;
  songsOptions: ISongOption[] = [];
  queryData: QueryData;
  songCtrlSub: Subscription = null;
  isQueryWithBagId: boolean = false;


  constructor(private wordService: WordService, private userService: UserService, private songService: SongService) {
    this.form = new FormGroup({
      song: new FormControl(null),
      bagId: new FormControl(null),
    });

    this.queryData = {
      maxResults: String(MAX_WORDS_RESULTS),
      nextCursor: null,
    }

    this.userService.currentUser
      .subscribe(user => {
        if (user) {
          this.currentUser = user;
          this.bags = user.metaData.wordsBags;
        } else {
          this.currentUser = null;
          this.bags = [];
        }
      });
  }

  ngOnInit(): void {
    this.songCtrlSub = this.form.controls.song.valueChanges
      .pipe(
        distinctUntilChanged(),
        tap(() => this.showSongsSpinner = true),
        debounceTime(900),
        switchMap((term: string) => this.getSongsByTitle(term)),
        tap(() => this.showSongsSpinner = false),
      )
      .subscribe((songs: Song[]) => {
        this.songsOptions = songs.map(song => {
          return { id: song.id, title: String(song.title) }
        })
      });

    this.setWords();
  }

  ngOnDestroy(): void {
    if (this.songCtrlSub) {
      this.songCtrlSub.unsubscribe();
    }
  }

  getBagWords(): string[] {
    const bag = this.bags.find(bag => bag.id === this.form.controls['bagId'].value);
    let words = [];

    if (bag) {
      words = bag.words;
    }

    return words;
  }

  pageChanged(page: number) {
    let nextCursor;
    const prev = page < this.page;

    this.page = page;

    if (prev) {
      this.cursors.pop();
      const len = this.cursors.length;

      if (len > 1) {
        nextCursor = this.cursors[len - 2];
      } else {
        nextCursor = null;
      }
    } else {
      nextCursor = this.cursors[this.cursors.length - 1];
    }

    this.queryData.nextCursor = String(nextCursor);

    this.setWords();
  }

  displayFn(song: ISongOption): string {
    return song?.title || '';
  }

  onSubmit(): void {
    this.queryData = plainToClass(QueryData, this.form.value);
    this.page = 0;
    this.isQueryWithBagId = this.queryData.bagId !== null;

    this.setWords();
  }

  resetForm(): void {
    this.form.reset();
    this.onSubmit();
  }

  private setWords() {
    this.showLoader = true;

    this.wordService.getWords(this.queryData)
      .subscribe(
        (res: QueryWordsResponse) => {
          this.words = plainToClass(Word, res.words);

          this.wordsCount = res.totalCount;

          if (this.shouldPushCursor(res.nextCursor)) {
            this.cursors.push(res.nextCursor);
          }
        },
        err => console.error(err),
        () => this.showLoader = false,
      )
  }

  private shouldPushCursor(cursor: string | number) {
    const cursorsLen = this.cursors.length;
    return cursor && (cursorsLen === 0 || this.cursors[cursorsLen - 1] !== cursor);
  }

  private getSongsByTitle(title?: string): Observable<Song[]> {
    if (title && title.length > 0) {
      return this.songService.getSongs({ title });
    }

    return this.songService.getSongs({ maxResults: String(MAX_SONGS_RESULTS) });
  }

}
