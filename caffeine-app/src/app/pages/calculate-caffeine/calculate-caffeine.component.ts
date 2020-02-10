import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import {DataService} from '../../services/data.service'
import {AuthenticationService} from '../../services/authentication.service'
import { first, finalize } from 'rxjs/operators';

@Component({
  selector: 'app-calculate-caffeine',
  templateUrl: './calculate-caffeine.component.html',
  styleUrls: ['./calculate-caffeine.component.scss']
})
export class CalculateCaffeineComponent implements OnInit {
  calculateCaffeineForm: FormGroup;
  loading: boolean = false;
  submitted: boolean = false;
  success: any = null;
  error: string = null;
  warn: string = null;
  info: any = null;
  currentUser: any;

  constructor(
    private formBuilder: FormBuilder,
    private authenticationService: AuthenticationService,
    private dataService: DataService
  ) {
      this.currentUser = this.authenticationService.currentUserValue;
  }

  ngOnInit(): void {
    this.calculateCaffeineForm = this.formBuilder.group({
      quantity: ['', [Validators.required, Validators.min(1)]],
      serving: ['', [Validators.required, Validators.min(1), Validators.max(this.currentUser.user.favorite_drink.serving)]]
    });
  }

  get f() { return this.calculateCaffeineForm.controls; }

  onSubmit() {
    this.submitted = true;
    this.success = this.error = this.warn = this.info = null;

    // stop here if form is invalid
    if (this.calculateCaffeineForm.invalid) {
        return;
    }

    this.loading = true;
    this.dataService.calculateCaffeineIntake(this.f.quantity.value, this.f.serving.value)
      .pipe(finalize(() => {
        this.loading = false;
      }))
      .subscribe(
          data => {
            this.success = data
            this.submitted = false
          },
          error => {
            if(error.data) {
              this.warn = error.message
              if(error.data.suggestions) {
                this.info = error.data.suggestions;
              }
            }
            else {
              this.error = error;
            }
          });
  }

}
