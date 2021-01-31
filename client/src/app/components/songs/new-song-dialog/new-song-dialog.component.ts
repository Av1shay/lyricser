import {Component, ElementRef, Inject, OnInit, ViewChild} from '@angular/core';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {RxwebValidators} from '@rxweb/reactive-form-validators';
import SongService from '../../../services/song.service';
import {plainToClass} from 'class-transformer';
import AddNewSongData from '../../../models/AddNewSongData';


@Component({
  selector: 'app-new-song-dialog',
  templateUrl: './new-song-dialog.component.html',
  styleUrls: ['./new-song-dialog.component.scss']
})
export class NewSongDialogComponent implements OnInit {
  @ViewChild('addNewSongForm') addNewSongForm: ElementRef;
  form: FormGroup;
  songFile: File = null;
  showLoader: boolean = false;

  constructor(
    private songService: SongService,
    public dialogRef: MatDialogRef<NewSongDialogComponent>,
    @Inject(MAT_DIALOG_DATA) public data: any
  ) {
    this.form = new FormGroup({
      title: new FormControl(null, Validators.required),
      writer: new FormControl(null, Validators.required),
      composers: new FormControl(''),
      performers: new FormControl(''),
      publishedAt: new FormControl(null, Validators.required),
      stanzasDelimiter: new FormControl('/'),
      file: new FormControl(null, [
        Validators.required,
        RxwebValidators.extension({extensions: ['txt']}),
        RxwebValidators.fileSize({maxSize: 20000})
      ]),
    });
  }

  ngOnInit(): void {}

  onSubmit(): void {
    if (!this.form.valid) {
      return;
    }

    this.showLoader = true;

    const formData = plainToClass(AddNewSongData, this.form.value);
    const formDataObj = new FormData();

    formDataObj.append('title', formData.title);
    formDataObj.append('writer', formData.writer);
    formDataObj.append('composers', JSON.stringify(formData.composers));
    formDataObj.append('performers', JSON.stringify(formData.performers));
    formDataObj.append('published_at', formData.publishedAt);
    formDataObj.append('stanzas_delimiter', formData.stanzasDelimiter);
    formDataObj.append('file', this.songFile);

    this.songService.createSong<FormData>(formDataObj)
      .subscribe(
        songRes => this.dialogRef.close({ data: songRes }),
        err => {
          if (err.status === 422 && err?.error?.errors) {
            const { errors } = err.error;

            for (const field in errors) {
              if (!errors.hasOwnProperty(field)) continue;

              switch (field) {
                case 'title':
                  this.form.controls['title'].setErrors({
                    serverError: errors[field][0] || '',
                  });
                  break;
              }
            }
          }

          // if this is not an input error, print general error message
          if (this.form.valid) {
            this.form.setErrors({
              generalError: 'There was an error creating the song.',
            });
            console.error(err);
          }

          this.addNewSongForm.nativeElement
            .scrollIntoView({ behavior: 'smooth' });
      })
      .add(() => this.showLoader = false)
  }

  onFileChange($event): void {
    if ($event.target.files.length > 0 && !this.form.controls['file'].errors) {
      this.songFile = $event.target.files[0];
    }
  }

  onNoClick(): void {
    this.dialogRef.close();
  }
}
