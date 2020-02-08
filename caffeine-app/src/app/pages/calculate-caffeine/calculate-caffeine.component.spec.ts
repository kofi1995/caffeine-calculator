import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CalculateCaffeineComponent } from './calculate-caffeine.component';

describe('CalculateCaffeineComponent', () => {
  let component: CalculateCaffeineComponent;
  let fixture: ComponentFixture<CalculateCaffeineComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CalculateCaffeineComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CalculateCaffeineComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
