import { Component, OnInit } from '@angular/core';
import SongService from '../../services/song.service';
import Song from '../../models/Song';
import {plainToClass} from 'class-transformer';
import {MatDialog} from '@angular/material/dialog';
import {NewSongDialogComponent} from './new-song-dialog/new-song-dialog.component';
import User from '../../models/User';
import UserService from '../../services/user.service';
import {Router} from '@angular/router';
import {QueryResponse} from '@app/typing/app';
import PageableComponent from '@app/components/PageableComponent';

//TODO pass to config
export const MAX_SONGS_RESULTS = 10;

@Component({
  selector: 'app-songs',
  templateUrl: './songs.component.html',
  styleUrls: ['./songs.component.scss']
})
export class SongsComponent extends PageableComponent implements OnInit {
  currentUser: User;
  songs: Song[] = null;
  showSpinner: boolean = true;
  hasError: boolean = false;
  songsPerPage: number;
  songsCount: number = 0;
  queryData;

  constructor(
    private router: Router,
    private userService: UserService,
    private songService: SongService,
    private dialog: MatDialog
  ) {
    super();

    this.userService.currentUser.subscribe(user => this.currentUser = user);

    this.songsPerPage = MAX_SONGS_RESULTS;

    this.queryData = {
      maxResults: String(this.songsPerPage),
      nextCursor: null,
    }

    if (this.currentUser && this.router.parseUrl(this.router.url).queryParams?.referrer === 'add-new-song') {
      this.openDialog();
    }
  }

  ngOnInit(): void {
   this.setSongs(this.queryData);
  }

  openDialog(): void {
    if (!this.currentUser) {
      this.router.navigate(['/login'], { queryParams: { referrer: 'add-new-song' } });
      return;
    }

    const dialogRef = this.dialog.open(NewSongDialogComponent, {
      width: '850px',
      maxHeight: '90vh',
    });

    dialogRef.afterClosed().subscribe(res => {
      if (!res) {
        return;
      }

      // Reset the view
      this.queryData = {
        maxResults: String(this.songsPerPage),
        nextCursor: null,
      }
      this.setSongs();
    });
  }

  onSongsUpdate(queryData): void {
    this.setSongs(queryData);
  }

  pageChanged(page: number) {
    const nextCursor = this.getNextCursor(page);
    this.queryData.nextCursor = String(nextCursor);
    this.setSongs();
  }

  private setSongs(queryData?): void {
    this.showSpinner = true;

    if (queryData) {
      Object.assign(this.queryData, queryData);
    }

    this.songService.getSongs(this.queryData)
      .subscribe(
        (res: QueryResponse<Song>) => {
          if (res.items.length > 0) {
            this.songs = plainToClass(Song, res.items);
            this.songsCount = res.totalCount;

            if (this.shouldPushCursor(res.nextCursor)) {
              this.cursors.push(res.nextCursor);
            }
          } else {
            this.songs = [];
          }
        },
        err => {
          console.error(err)
          this.hasError = true;
        },
        () => this.showSpinner = false,
      )
  }
}
