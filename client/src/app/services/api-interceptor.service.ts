import {HttpEvent, HttpInterceptor, HttpHandler, HttpRequest} from '@angular/common/http';
import {Observable} from 'rxjs';
import {environment} from '../../environments/environment';

export class ApiInterceptorService implements HttpInterceptor {
  apiUrl: string;

  constructor() {
    this.apiUrl = environment.apiUrl;
  }

  intercept(req: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
    const apiReq = req.clone({ url: this.apiUrl });
    return next.handle(apiReq);
  }
}
