import { ComponentFixture, TestBed } from '@angular/core/testing';

import { WordsIndexComponent } from './words-index.component';

describe('WordsIndexComponent', () => {
  let component: WordsIndexComponent;
  let fixture: ComponentFixture<WordsIndexComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ WordsIndexComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(WordsIndexComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
