import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {faPlus} from '@fortawesome/free-solid-svg-icons';
import UserService from '@app/services/user.service';

@Component({
  selector: 'app-expression-form',
  templateUrl: './expression-form.component.html',
  styleUrls: ['./expression-form.component.scss']
})
export class ExpressionFormComponent implements OnInit {
  @Input() data: any;
  @Output() closeComponent: EventEmitter<any> = new EventEmitter();

  form: FormGroup;
  faPlus = faPlus;

  constructor(private userService: UserService) {
    this.form = new FormGroup({
      id: new FormControl(null),
      expression: new FormControl(null, Validators.required),
    });
  }

  ngOnInit(): void {
  }

  onSubmit() {
    const expressionData = {
      id: this.form.value.id || null,
      expression: this.form.value.expression.trim(),
    }

    this.userService.updateExpression(expressionData).subscribe((res) => {
      this.form.reset();
    });
  }

  selfClose(): void {
    this.closeComponent.emit(null);
  }

}
