import { Injectable } from '@angular/core';
import { HttpRequest, HttpHandler, HttpEvent, HttpInterceptor } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { catchError } from 'rxjs/operators';
import { Router} from '@angular/router';

import { AuthenticationService } from '../services/authentication.service';

@Injectable()
export class ErrorInterceptor implements HttpInterceptor {
    constructor(
      private authenticationService: AuthenticationService,
      private router: Router
    ) { }

    intercept(request: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
        return next.handle(request).pipe(catchError(err => {
            if (err.status === 401) {
                this.authenticationService.logout();
                location.reload(true);
            }
            else if(err.status === 403 && err.error.data && err.error.data.suggestions) {
              return throwError(err.error);
            }
            else if(err.status === 409) {
              let currentUser = this.authenticationService.currentUserValue;
              currentUser.user.favorite_drink = null;
              localStorage.setItem('currentUser', JSON.stringify(currentUser));
              this.router.navigate(['/home']);
              setTimeout(function(){ location.reload(true); }, 100);
            }
            const error = err.error.message || err.statusText;
            return throwError(error);
        }))
    }
}
