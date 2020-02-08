import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { BehaviorSubject, Observable} from 'rxjs';
import { map } from 'rxjs/operators';
import { environment } from '../environments/env';

@Injectable({
  providedIn: 'root'
})
export class DataService {

  private favoriteDrinksSubject: BehaviorSubject<any>;

  constructor(private http: HttpClient) {
    this.favoriteDrinksSubject = new BehaviorSubject<any>(null);
  }

  public get favoriteDrinks(): any {
    return this.favoriteDrinksSubject.value
}
  getDrinks() {
    return this.http.get<any>(`${environment.apiUrl}/drinks`)
        .pipe(map(drinks => {
          if(drinks && drinks.data && drinks.data.drinks) {
            this.favoriteDrinksSubject.next(drinks.data.drinks);
            return drinks.data.drinks;
          }
          else {
            return drinks;
          }
        }));
  }

  chooseFavoriteDrink(drink_id:number) {
    return this.http.put<any>(`${environment.apiUrl}/favorite-drink`, {drink_id})
        .pipe(map(drink => {
            return drink.data.favorite_drink;
        }));
  }

  calculateCaffeineIntake(quantity:number, serving:number) {
    return this.http.post<any>(`${environment.apiUrl}/calculate-caffeine-intake`, {quantity, serving})
        .pipe(map(intake => {
            return intake;
        }));
  }
}
