import { Component, OnInit } from '@angular/core';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import UserService from '../../services/user.service';
import {Router} from '@angular/router';
import User from '../../Models/User';


@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent implements OnInit {
  loginForm: FormGroup;

  constructor(private router: Router, private userService: UserService) {
    this.loginForm = new FormGroup({
      email: new FormControl(null, [Validators.required, Validators.email]),
      password: new FormControl(null, [Validators.required, Validators.minLength(6)]),
    });
  }

  ngOnInit(): void {
  }

  onSubmit(): void {
    const {email, password} = this.loginForm.value;

    this.userService.login(email, password)
      .subscribe((user: User) => {
        if (this.cameFromSongsPage) {
          this.router.navigate(['/songs'], { queryParams: { referrer: 'add-new-song' } });
        } else {
          this.router.navigate(['/personal-area']);
        }
      })
  }

  cameFromSongsPage(): boolean {
    return this.router.parseUrl(this.router.url).queryParams?.referrer === 'add-new-song';
  }
}
