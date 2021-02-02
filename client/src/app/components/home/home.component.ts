import { Component, OnInit } from '@angular/core';
import StatsService from '@app/services/stats.service';
import Stats from '@app/models/Stats';
import SongService from '@app/services/song.service';
import {forkJoin} from 'rxjs';
import Song from '@app/models/Song';
import {plainToClass} from 'class-transformer';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.scss']
})
export class HomeComponent implements OnInit {
  stats: Stats;
  recentSongs: Song[];

  constructor(private statsService: StatsService, private songService: SongService) { }

  ngOnInit(): void {
    const statsObserver = this.statsService.getHomepageStats();
    const songsObserver = this.songService.getRecentSongs();

    forkJoin([statsObserver, songsObserver]).subscribe((result) => {
      this.stats = result[0];
      const songs = result[1] as Song[];
      this.recentSongs = plainToClass(Song, songs);
    });

    // this.statsService.getHomepageStats()
    //   .subscribe((stats: Stats) => {
    //     this.stats = stats;
    //   });
    //
    // this.songService.getSongs()
    //   .subscribe(res => {
    //     console.log(res);
    //   })
  }

}
