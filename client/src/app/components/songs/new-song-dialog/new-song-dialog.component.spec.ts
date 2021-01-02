import { ComponentFixture, TestBed } from '@angular/core/testing';

import { NewSongDialogComponent } from './new-song-dialog.component';

describe('NewSongDialogComponent', () => {
  let component: NewSongDialogComponent;
  let fixture: ComponentFixture<NewSongDialogComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ NewSongDialogComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(NewSongDialogComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
