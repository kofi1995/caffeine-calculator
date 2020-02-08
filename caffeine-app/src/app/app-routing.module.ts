import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import {LoginComponent} from './pages/login/login.component';
import {ChooseDrinkComponent} from './pages/choose-drink/choose-drink.component';
import {CalculateCaffeineComponent} from './pages/calculate-caffeine/calculate-caffeine.component';
import {NotFoundComponent} from './pages/not-found/not-found.component';
import {AuthGuard} from './helpers/auth.guard';
import {DrinkNotSelectedGuard} from './helpers/drink-not-selected.guard';


const routes: Routes = [
  {path:  '', pathMatch:  "full",redirectTo:  "/calculate-caffeine"},
  { path: 'home', pathMatch:  "full",redirectTo:  "/calculate-caffeine"},
  { path: 'choose-drink', component: ChooseDrinkComponent, canActivate: [AuthGuard] },
  { path: 'calculate-caffeine', component: CalculateCaffeineComponent, canActivate: [AuthGuard, DrinkNotSelectedGuard] },
  { path: 'login', component: LoginComponent },
  {path: '404', component: NotFoundComponent},
  {path: '**', redirectTo: '/404'}
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
