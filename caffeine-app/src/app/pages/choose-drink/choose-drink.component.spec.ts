import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ChooseDrinkComponent } from './choose-drink.component';

describe('ChooseDrinkComponent', () => {
  let component: ChooseDrinkComponent;
  let fixture: ComponentFixture<ChooseDrinkComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ChooseDrinkComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ChooseDrinkComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
