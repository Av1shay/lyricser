import {Injectable} from '@angular/core';
import {Observable} from 'rxjs';
import {HttpClient, HttpParams} from '@angular/common/http';
import Song from '../models/Song';
import {IHttpParams, QueryResponse} from '../typing/app';


@Injectable({
  providedIn: 'root',
})
export default class SongService {
  constructor(private http: HttpClient) { }

  createSong<T extends object>(data: T): Observable<any> {
    return this.http.post<Song>('/api/song', data);
  }

  getSongs(params?: IHttpParams): Observable<QueryResponse<Song>> {
    let httpParams = {};

    if (params) {
      for (let key in params) {
        if (params.hasOwnProperty(key) && params[key]) {
          httpParams[key] = params[key];
        }
      }

      httpParams = new HttpParams({ fromObject: httpParams });
    }

    return this.http.get<QueryResponse<Song>>('/api/song', { params: httpParams });
  }

  getSongById(id: number, params?: { [param: string]: string }): Observable<any> {
    return this.http.get<Song[]>('/api/song/' + id, { params });
  }

  getRecentSongs(): Observable<any>  {
    return this.http.get<Song[]>('/api/song/recent');
  }
}
