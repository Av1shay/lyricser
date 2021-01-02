import {Component, ElementRef, OnDestroy, OnInit} from '@angular/core';
import {FormControl, FormGroup} from '@angular/forms';
import {Observable, Subscription} from 'rxjs';
import {debounceTime, distinctUntilChanged, switchMap, tap} from 'rxjs/operators';
import Song from '../../models/Song';
import Word from '../../models/Word';
import SongService from '../../services/song.service';
import WordService from '../../services/word.service';
import {IHttpParams, QueryWordsResponse, WordContext, WordsIndex} from '../../typing/app';
import {Expose, plainToClass, Transform, Type} from 'class-transformer';
import {MatDialog} from '@angular/material/dialog';
import {WordContextDialogComponent} from './word-context-dialog/word-context-dialog.component';


interface ISongOption {
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

//TODO pass to config
const MAX_WORDS_RESULTS = 200;
const MAX_SONGS_RESULTS = 20;

@Component({
  selector: 'app-words',
  templateUrl: './words.component.html',
  styleUrls: ['./words.component.scss']
})
export class WordsComponent implements OnInit, OnDestroy {
  readonly wordsPerPage = MAX_WORDS_RESULTS;

  showLoader: boolean = false;
  form: FormGroup;
  words: Word[] = [];
  wordsIndex: WordsIndex = null;
  queryData: QueryData;
  wordsCount: number = 0;
  page: number = 0;
  cursors: Array<string | number> = [];
  songsOptions: ISongOption[] = [];
  showSongsSpinner: boolean = false;
  songCtrlSub: Subscription;

  constructor(private songService: SongService, private wordService: WordService, private dialog: MatDialog) {
    this.form = new FormGroup({
      song: new FormControl(null),
      wordTerm: new FormControl(''),
      queryType: new FormControl('list')
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
  }

  ngOnDestroy(): void {
    this.songCtrlSub.unsubscribe();
  }

  displayFn(song: ISongOption): string {
    return song?.title || '';
  }

  onQuerySubmit(): void {
    this.queryData = plainToClass(QueryData, this.form.value);
    this.queryData.maxResults = String(this.wordsPerPage);
    this.queryData.nextCursor = null;

    this.cursors = [];

    this.setWords(this.queryData);
  }

  onWordIndexClick($event, word: string, contextList: WordContext[]): void {
    const target = new ElementRef($event.currentTarget);

    this.dialog.open(WordContextDialogComponent, {
      data: { trigger: target, word, contextList },
      autoFocus: false,
    });
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
    this.setWords(this.queryData);
  }

  private setWords(queryData: QueryData) {
    this.showLoader = true;

    this.wordService.getWords(queryData)
      .subscribe(
        (res: QueryWordsResponse) => {
          if (WordsComponent.isWordsIndex(res.words)) {
            this.wordsIndex  = res.words;
            this.words = [];
          } else {
            this.words = plainToClass(Word, res.words);
            this.wordsIndex = null;
          }

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

  private static isWordsIndex(obj: WordsIndex | Word[]): obj is WordsIndex {
    const wordContext = obj[Object.keys(obj)[0]][0];

    if (wordContext) {
      return (wordContext as WordContext).before !== undefined || (wordContext as WordContext).after !== undefined;
    }

    return false;
  }
}
