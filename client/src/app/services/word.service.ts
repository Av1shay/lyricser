import {Injectable} from '@angular/core';
import {Observable} from 'rxjs';
import {HttpClient, HttpParams} from '@angular/common/http';
import Word from '../models/Word';
import {IHttpParams, QueryResponse} from '../typing/app';


@Injectable({
  providedIn: 'root',
})
export default class WordService {
  constructor(private http: HttpClient) { }

  getWordsOf(songId: number): Observable<any> {
    return this.http.get<Word[]>('/api/word/song/' + songId);
  }

  getWords(params?: IHttpParams): Observable<QueryResponse<Word>> {
    let httpParams = {};

    for (const key in params) {
      if (params.hasOwnProperty(key) && (params[key] === null || params[key] === undefined)) {
        delete params[key];
      }
    }

    if (params) {
      httpParams = new HttpParams({ fromObject: params });
    }

    return this.http.get<QueryResponse<Word>>('/api/word', { params: httpParams });
  }

  getWordsWithContext(): Observable<any> {
    return this.http.get<QueryResponse<Word>>('/api/word/context');
  }
}
