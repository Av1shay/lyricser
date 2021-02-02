import {
  AfterContentChecked,
  Component,
  ElementRef,
  OnInit,
  ViewChild
} from '@angular/core';
import {ActivatedRoute} from '@angular/router';
import Song from '../../../models/Song';
import SongService from '../../../services/song.service';
import {plainToClass} from 'class-transformer';


@Component({
  selector: 'app-song',
  templateUrl: './song.component.html',
  styleUrls: ['./song.component.scss']
})
export class SongComponent implements OnInit, AfterContentChecked {
  @ViewChild('lyricsBlock') lyricsEl: ElementRef;

  song: Song;
  songId: number;
  lyrics: string;
  showLoader = true;
  startIndexQueryParam: number = null;
  wordQueryParam: string = null;
  wordHighlighted: boolean = false;
  lyricsHtml: string = null;

  constructor(
    private route: ActivatedRoute,
    private songService: SongService,
    private _elementRef : ElementRef,
  ) {
    this.route.params.subscribe( params => this.songId = params.id);
    this.route.queryParams.subscribe(params => {
      this.startIndexQueryParam = params['startIndex'] ? Number(params['startIndex']) : null;
      this.wordQueryParam = params['word'] ?? null;
    });
  }

  ngOnInit(): void {
    this.songService.getSongById(this.songId, { withLyrics: '1' })
      .subscribe(
        song => {
          this.song = plainToClass(Song, song);
        },
        err => console.error(err),
        () => this.showLoader = false,
      );
  }

  ngAfterContentChecked() {
    if (this.shouldHighlightWord()) {
      this.highlightWord();
      this.wordHighlighted = true;
    }
  }

  private highlightWord(): void {
    const wordValue = this.wordQueryParam.trim();
    let lyrics = this.lyricsEl.nativeElement.innerHTML;

    if (!lyrics) return;

    const indexOfWord = lyrics.indexOf(wordValue, this.startIndexQueryParam);

    if (indexOfWord !== -1) {
      lyrics = lyrics.substring(0, indexOfWord) + "<span class='highlight'>" + lyrics.substring(indexOfWord, indexOfWord + wordValue.length) + "</span>" + lyrics.substring(indexOfWord + wordValue.length);
      this.lyricsHtml = lyrics || this.song.lyrics || '';
    }
  }

  private shouldHighlightWord() {
    return!this.wordHighlighted && this.lyricsEl && this.startIndexQueryParam && this.wordQueryParam;
  }

}
