import {Injectable} from '@angular/core';
import {Observable} from 'rxjs';
import {HttpClient, HttpParams} from '@angular/common/http';
import Song from '../models/Song';
import {IHttpParams} from '../typing/app';


@Injectable({
  providedIn: 'root',
})
export default class SongService {
  constructor(private http: HttpClient) { }

  createSong<T extends object>(data: T): Observable<any> {
    return this.http.post<Song>('/api/song', data);
  }

  getSongs(params?: IHttpParams): Observable<Song[]> {
    let httpParams = {};

    if (params) {
      httpParams = new HttpParams({ fromObject: params });
    }

    return this.http.get<Song[]>('/api/song', { params: httpParams });
  }

  getSongById(id: number, params?: { [param: string]: string }): Observable<any> {
    return this.http.get<Song[]>('/api/song/' + id, { params });
  }
}
