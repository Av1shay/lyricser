import { Component, OnInit } from '@angular/core';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import UserService from '../../services/user.service';
import {Router} from '@angular/router';
// @ts-ignore
import User from '@app/models/User';

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.scss']
})
export class RegisterComponent implements OnInit {
  registrationForm: FormGroup;

  constructor(private router: Router, private userService: UserService) {
    if (this.userService.currentUserValue) {
      this.router.navigate(['/personal-area']);
    }

    this.registrationForm = new FormGroup({
      name: new FormControl(null, Validators.required),
      email: new FormControl(null, [Validators.required, Validators.email]),
      password: new FormControl(null, [Validators.required, Validators.minLength(6)]),
    });
  }

  ngOnInit(): void {
  }

  onSubmit(): void {
    this.userService.register(this.registrationForm.value)
      .subscribe(
        (user: User) => {
          this.router.navigate(['/personal-area']);
        },
        err => {
          if (err.status === 422 && err?.error?.errors) {
            const { errors } = err.error;

            for (const field in errors) {
              if (!errors.hasOwnProperty(field)) continue;

              switch (field) {
                case 'name':
                  this.registrationForm.controls['name'].setErrors({
                    serverError: errors[field][0] || '',
                  });
                  break;
                case 'email':
                  this.registrationForm.controls['email'].setErrors({
                    serverError: errors[field][0] || '',
                  });
                  break;
                case 'password':
                  this.registrationForm.controls['password'].setErrors({
                    serverError: errors[field][0] || '',
                  });
                  break;
              }
            }
          }

          if (this.registrationForm.valid) {
            this.registrationForm.setErrors({
              generalError: 'There was an error creating the song.',
            });
          }
        }
      );
  }

}
