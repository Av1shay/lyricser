import { Component, OnInit } from '@angular/core';
import UserService from '../../services/user.service';
import User from '@app/models/User';
import WordsBag from '@app/models/WordsBag';
import Expression from '@app/models/Expression';
import {FormControl, FormGroup, Validators} from '@angular/forms';


@Component({
  selector: 'app-personal-area',
  templateUrl: './personal-area.component.html',
  styleUrls: ['./personal-area.component.scss']
})
export class PersonalAreaComponent implements OnInit {
  currentUser: User;
  bags: WordsBag[] = [];
  expressions: Expression[] = [];
  form: FormGroup;
  sererError: string;
  showUpdateProfilerLoader: boolean;

  constructor(private userService: UserService) {
    this.userService.currentUser
      .subscribe(user => {
        this.currentUser = user;
        this.bags = user.metaData.wordsBags;
        this.expressions = user.metaData.expressions;

        this.form = new FormGroup({
          fullName: new FormControl(user.name, Validators.required),
          email: new FormControl(user.email, Validators.required),
        });
      });
  }

  ngOnInit(): void {
  }

  onSubmit(): void {
    this.sererError = null;
    this.showUpdateProfilerLoader = true

    this.userService.updateUser(this.form.value)
      .subscribe(
        () => console.log('User updated'),
        err => {
          const errorMsg = err.error?.message;

          if (errorMsg) {
            this.sererError = errorMsg;
          }
        },
        () => this.showUpdateProfilerLoader = false,
      );
  }

  isSameProfileData(): boolean {
    return this.form.controls['fullName'].value === this.currentUser.name && this.form.controls['email'].value === this.currentUser.email;
  }

}
