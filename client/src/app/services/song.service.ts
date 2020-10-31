import {Injectable} from '@angular/core';
import {Observable} from 'rxjs';
import {HttpClient} from '@angular/common/http';
import {QueryResponse} from '../typing/response';

@Injectable()
export default class SongService {
  constructor(private http: HttpClient) { }

  generalSelect(queryData): Observable<any> {
    return this.http.get<QueryResponse>('/api/compare', queryData);
  }
}
