import { BrowserModule } from '@angular/platform-browser';
import { HttpClientModule, HTTP_INTERCEPTORS } from '@angular/common/http';
import { NgModule } from '@angular/core';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { LoginComponent } from './components/login/login.component';
import { ApiInterceptorService } from './services/api-interceptor.service';
import { DashboardComponent } from './components/dashboard/dashboard.component';
import { GeneralSelectQueryComponent } from './components/dashboard/general-select-query/general-select-query.component';
import {ReactiveFormsModule} from '@angular/forms';
import SongService from './services/song.service';

@NgModule({
  declarations: [
    AppComponent,
    LoginComponent,
    DashboardComponent,
    GeneralSelectQueryComponent,
  ],
    imports: [
        BrowserModule,
        HttpClientModule,
        AppRoutingModule,
        ReactiveFormsModule,
    ],
  providers: [
    SongService,
    // { provide: HTTP_INTERCEPTORS, useClass: ApiInterceptorService, multi: true },
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
