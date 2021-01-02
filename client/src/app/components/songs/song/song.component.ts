import {Component, Input, OnInit} from '@angular/core';
import { forkJoin } from 'rxjs';
import {ActivatedRoute} from '@angular/router';
import Song from '../../../Models/Song';
import SongService from '../../../services/song.service';
import {plainToClass} from 'class-transformer';


@Component({
  selector: 'app-song',
  templateUrl: './song.component.html',
  styleUrls: ['./song.component.scss']
})
export class SongComponent implements OnInit {
  @Input() song: Song;

  songId: number;
  lyrics: string;
  showLoader = true;

  constructor(
    private route: ActivatedRoute,
    private songService: SongService,
  ) {
    this.route.params.subscribe( params => this.songId = params.id);
  }

  ngOnInit(): void {
    this.songService.getSongById(this.songId, { withLyrics: '1' })
      .subscribe(
        song => this.song = plainToClass(Song, song),
        err => console.error(err),
        () => this.showLoader = false,
      );
  }

}
