import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {FormControl, FormGroup} from '@angular/forms';
import Expression from '@app/models/Expression';
import {MAX_SONGS_RESULTS} from '@app/components/songs/songs.component';

@Component({
  selector: 'app-query-form',
  templateUrl: './query-form.component.html',
  styleUrls: ['./query-form.component.scss']
})
export class QueryFormComponent implements OnInit {
  @Input() userExpressions: Expression[];
  @Input()
  @Output() songsUpdate: EventEmitter<object> = new EventEmitter();

  form: FormGroup;

  constructor() {
    this.form = new FormGroup({
      title: new FormControl(''),
      writer: new FormControl(''),
      words: new FormControl(null),
      expressionId: new FormControl(null),
    });
  }

  ngOnInit(): void {
  }

  onSubmit(): void {
    let { title, writer, words, expressionId } = this.form.value;
    title = title.trim();
    writer = writer.trim();

    const queryData = {
      title: title.length > 0 ? title : null,
      writer: writer.length > 0 ? writer : null,
      expressionId: expressionId || null,
    }

    if (words !== null && words?.trim().length > 0) {
      words = words.split(',').map(word => word.trim());
      queryData['words'] = encodeURIComponent(words);
    } else {
      queryData['words'] = null;
    }

    this.songsUpdate.emit(queryData);
  }
}
