import 'reflect-metadata';

import { BrowserModule } from '@angular/platform-browser';
import { HttpClientModule, HTTP_INTERCEPTORS } from '@angular/common/http';
import { NgModule } from '@angular/core';
import {MatProgressSpinnerModule} from '@angular/material/progress-spinner';
import {MatDialogModule} from '@angular/material/dialog';
import {MatAutocompleteModule} from '@angular/material/autocomplete';
import {RxReactiveFormsModule} from '@rxweb/reactive-form-validators';


import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { LoginComponent } from './components/login/login.component';
import { RegisterComponent } from './components/register/register.component';
import { ApiInterceptor } from './helpers/api-interceptor';
import {ReactiveFormsModule} from '@angular/forms';
import SongService from './services/song.service';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { LayoutModule } from '@angular/cdk/layout';
import { NavComponent } from './components/nav/nav.component';
import { SongsComponent } from './components/songs/songs.component';
import { PersonalAreaComponent } from './components/personal-area/personal-area.component';
import { SongComponent } from './components/songs/song/song.component';
import WordService from './services/word.service';
import { NewSongDialogComponent } from './components/songs/new-song-dialog/new-song-dialog.component';
import {MatInputModule} from '@angular/material/input';
import {NgxPaginationModule} from 'ngx-pagination';
import { WordContextDialogComponent } from './components/words-list/word-context-dialog/word-context-dialog.component';
import UserService from './services/user.service';
import {ErrorInterceptor} from '@app/helpers/error-interceptor';
import { WordsBagsFormComponent } from './components/personal-area/user-bags/words-bags-form/words-bags-form.component';
import { UserBagsComponent } from './components/personal-area/user-bags/user-bags.component';
import { FontAwesomeModule } from '@fortawesome/angular-fontawesome';
import { WordsIndexComponent } from './components/words-index/words-index.component';
import { WordsListComponent } from './components/words-list/words-list.component';
import { UserExpressionsComponent } from './components/personal-area/user-expressions/user-expressions.component';
import { ExpressionFormComponent } from './components/personal-area/user-expressions/expression-form/expression-form.component';



@NgModule({
  declarations: [
    AppComponent,
    LoginComponent,
    NavComponent,
    SongsComponent,
    PersonalAreaComponent,
    SongComponent,
    NewSongDialogComponent,
    WordContextDialogComponent,
    RegisterComponent,
    WordsBagsFormComponent,
    UserBagsComponent,
    WordsIndexComponent,
    WordsListComponent,
    UserExpressionsComponent,
    ExpressionFormComponent,
  ],
  imports: [
    BrowserModule,
    HttpClientModule,
    AppRoutingModule,
    ReactiveFormsModule,
    BrowserAnimationsModule,
    LayoutModule,
    MatProgressSpinnerModule,
    MatDialogModule,
    MatAutocompleteModule,
    RxReactiveFormsModule,
    MatInputModule,
    NgxPaginationModule,
    FontAwesomeModule,
  ],
  providers: [
    SongService,
    WordService,
    UserService,
    { provide: HTTP_INTERCEPTORS, useClass: ApiInterceptor, multi: true },
    { provide: HTTP_INTERCEPTORS, useClass: ErrorInterceptor, multi: true },
  ],
  entryComponents: [
    NewSongDialogComponent
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
