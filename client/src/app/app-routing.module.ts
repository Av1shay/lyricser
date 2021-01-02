import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import {SongsComponent} from './components/songs/songs.component';
import {WordsComponent} from './components/words/words.component';
import {PersonalAreaComponent} from './components/personal-area/personal-area.component';
import {SongComponent} from './components/songs/song/song.component';
import {LoginComponent} from './components/login/login.component';
import {RegisterComponent} from './components/register/register.component';

const routes: Routes = [
  { path: 'songs', component: SongsComponent, pathMatch: 'full' },
  { path: 'songs/:id', component: SongComponent, pathMatch: 'full' },
  { path: 'words', component: WordsComponent, pathMatch: 'full' },
  { path: 'personal-area', component: PersonalAreaComponent, pathMatch: 'full' },
  { path: 'login', component: LoginComponent, pathMatch: 'full' },
  { path: 'register', component: RegisterComponent, pathMatch: 'full' },
  { path: '', redirectTo: 'songs', pathMatch: 'full', },
  { path: '**', redirectTo: '', pathMatch: 'full' },
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
