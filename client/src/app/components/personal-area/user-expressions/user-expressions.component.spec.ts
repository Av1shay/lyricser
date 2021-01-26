import { ComponentFixture, TestBed } from '@angular/core/testing';

import { UserExpressionsComponent } from './user-expressions.component';

describe('UserExpressionsComponent', () => {
  let component: UserExpressionsComponent;
  let fixture: ComponentFixture<UserExpressionsComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ UserExpressionsComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(UserExpressionsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
