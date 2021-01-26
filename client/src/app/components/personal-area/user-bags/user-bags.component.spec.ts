import { ComponentFixture, TestBed } from '@angular/core/testing';

import { UserBagsComponent } from './user-bags.component';

describe('UserBagsComponent', () => {
  let component: UserBagsComponent;
  let fixture: ComponentFixture<UserBagsComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ UserBagsComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(UserBagsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
