import { ComponentFixture, TestBed } from '@angular/core/testing';

import { WordsBagsFormComponent } from './words-bags-form.component';

describe('WordsBagsFormComponent', () => {
  let component: WordsBagsFormComponent;
  let fixture: ComponentFixture<WordsBagsFormComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ WordsBagsFormComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(WordsBagsFormComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
