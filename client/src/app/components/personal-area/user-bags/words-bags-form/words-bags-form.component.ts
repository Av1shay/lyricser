import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {faPlus} from '@fortawesome/free-solid-svg-icons';
import UserService from '@app/services/user.service';
import {plainToClass} from 'class-transformer';
import WordsBag from '@app/models/WordsBag';

@Component({
  selector: 'app-words-bags-form',
  templateUrl: './words-bags-form.component.html',
  styleUrls: ['./words-bags-form.component.scss']
})
export class WordsBagsFormComponent implements OnInit {
  @Input() data: any;
  @Output() closeComponent: EventEmitter<any> = new EventEmitter();

  form: FormGroup;
  faPlus = faPlus;

  constructor(private fb: FormBuilder, private userService: UserService) {
    this.form = new FormGroup({
      title: new FormControl(null, Validators.required),
      words: new FormControl(null, Validators.required)
    });
  }

  ngOnInit(): void {
  }

  onSubmit() {
    const wordsStr = this.form.value.words.trim();
    let words = [];

    if (wordsStr.length > 0) {
      words = wordsStr.split("\n")
        .map(word => word.trim());
    } else {
      this.form.controls['words'].setErrors({
        required: true,
      });
      return;
    }

    const bag = plainToClass(WordsBag, {
      id: this.form.value.id || null,
      title: this.form.value.title.trim(),
      words,
    });

    this.userService.updateWordsBag(bag).subscribe(() => {
      this.form.reset();
    });
  }

  selfClose(): void {
    this.closeComponent.emit(null);
  }
}
