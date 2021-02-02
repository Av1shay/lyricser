import {Injectable} from '@angular/core';
import {Observable} from 'rxjs';
import {HttpClient, HttpParams} from '@angular/common/http';
import Song from '../models/Song';
import {IHttpParams, QueryResponse} from '../typing/app';


@Injectable({
  providedIn: 'root',
})
export default class StatsService {
  constructor(private http: HttpClient) { }

  getHomepageStats(): Observable<any> {
    return this.http.get<Song[]>('/api/stats');
  }
}
