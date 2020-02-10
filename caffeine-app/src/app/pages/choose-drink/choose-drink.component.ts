import { Component, OnInit } from '@angular/core';

import {NgbModal, NgbModalRef} from '@ng-bootstrap/ng-bootstrap';
import {DataService} from '../../services/data.service';
import { first } from 'rxjs/operators';
import {AuthenticationService} from '../../services/authentication.service';
import { Router, ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-choose-drink',
  templateUrl: './choose-drink.component.html',
  styleUrls: ['./choose-drink.component.scss']
})
export class ChooseDrinkComponent implements OnInit {

  openedModal: NgbModalRef;
  selectedDrink: string;
  selectedDrinkID: number;
  drinks: any;
  currentUser: any;
  returnUrl: string;

  constructor(
    private modalService: NgbModal,
    private dataService: DataService,
    private authenticationService: AuthenticationService,
    private route: ActivatedRoute,
    private router: Router
  ) {
    this.selectedDrink = '';
    this.selectedDrinkID = 0;
    this.currentUser = this.authenticationService.currentUserValue
  }

  ngOnInit(): void {
    this.drinks = this.dataService.favoriteDrinks;
    this.getDrinks()
    this.returnUrl = this.route.snapshot.queryParams['returnUrl'] || '/';
  }

  getDrinks() {
    if(!this.drinks) {
      this.dataService.getDrinks()
      .pipe(first())
      .subscribe(
          data => {
            this.drinks = this.dataService.favoriteDrinks;
          },
          error => {
            alert("Something went wrong, please try again.")
          });
    }
  }

  selectDrink(drink_id:number) {
      this.dataService.chooseFavoriteDrink(drink_id)
      .subscribe(
          data => {
            this.currentUser.user.favorite_drink = data;
            localStorage.setItem('currentUser', JSON.stringify(this.currentUser));
            this.router.navigate([this.returnUrl]);
            this.openedModal.dismiss()
            setTimeout(function(){ location.reload(true); }, 100);
          },
          error => {
            alert("Something went wrong, please try again.")
          });
  }

  favDrinkPopup(content, drink:string, drink_id:number) {
    this.openedModal = this.modalService.open(content, {ariaLabelledBy: 'modal-basic-title'});
    this.selectedDrink = drink;
    this.selectedDrinkID = drink_id;
  }

  selectFavoriteDrink(drink_id:number) {
    this.selectDrink(drink_id);
  }

}
