import { ComponentFixture, TestBed } from '@angular/core/testing';

import { WordContextDialogComponent } from './word-context-dialog.component';

describe('WordContextDialogComponent', () => {
  let component: WordContextDialogComponent;
  let fixture: ComponentFixture<WordContextDialogComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ WordContextDialogComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(WordContextDialogComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
