import { Component, OnInit } from '@angular/core';
import SongService from '../../services/song.service';
import Song from '../../models/Song';
import {plainToClass} from 'class-transformer';
import {MatDialog} from '@angular/material/dialog';
import {NewSongDialogComponent} from './new-song-dialog/new-song-dialog.component';
import User from '../../models/User';
import UserService from '../../services/user.service';
import {Router} from '@angular/router';


@Component({
  selector: 'app-songs',
  templateUrl: './songs.component.html',
  styleUrls: ['./songs.component.scss']
})
export class SongsComponent implements OnInit {
  currentUser: User;
  songs: Song[] = null;
  showSpinner: boolean = true;
  hasError: boolean = false;

  constructor(
    private router: Router,
    private userService: UserService,
    private songService: SongService,
    private dialog: MatDialog
  ) {
    this.userService.currentUser.subscribe(user => this.currentUser = user);

    if (this.currentUser && this.router.parseUrl(this.router.url).queryParams?.referrer === 'add-new-song') {
      this.openDialog();
    }
  }

  ngOnInit(): void {
    this.songService.getSongs()
      .subscribe(
        (songs: Song[]) => {
          if (songs.length > 0) {
            this.songs = plainToClass(Song, songs);
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

      const song = plainToClass(Song, res.data);
      this.songs = [song, ...this.songs];
    });
  }

}
