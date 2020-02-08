import { Injectable } from '@angular/core';
import { Router, CanActivate, ActivatedRouteSnapshot, RouterStateSnapshot } from '@angular/router';

@Injectable({ providedIn: 'root' })
export class DrinkNotSelectedGuard implements CanActivate {
    constructor(
        private router: Router
    ) { }

    canActivate(route: ActivatedRouteSnapshot, state: RouterStateSnapshot) {
        const currentUser = JSON.parse(localStorage.getItem('currentUser'));
        if (!currentUser.user.favorite_drink) {
            this.router.navigate(['/choose-drink'], { queryParams: { returnUrl: state.url } });
            return false;
        }
        return true;

    }
}