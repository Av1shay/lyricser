import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import {SongsComponent} from './components/songs/songs.component';
import {WordsComponent} from './components/words/words.component';
import {PersonalAreaComponent} from './components/personal-area/personal-area.component';

const routes: Routes = [
  { path: '', component: SongsComponent, pathMatch: 'full' },
  { path: 'words', component: WordsComponent, pathMatch: 'full' },
  { path: 'personal-area', component: PersonalAreaComponent, pathMatch: 'full' },
  { path: '', component: SongsComponent, pathMatch: 'full' },
  { path: '**', redirectTo: '', pathMatch: 'full' },
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
