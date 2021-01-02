import { Component, OnInit } from '@angular/core';
import UserService from '../../services/user.service';
import User from '../../Models/User';
import {Router} from '@angular/router';

@Component({
  selector: 'app-nav',
  templateUrl: './nav.component.html',
  styleUrls: ['./nav.component.scss']
})
export class NavComponent implements OnInit {
  currentUser: User;

  constructor(private router: Router, private userService: UserService) {
    this.userService.currentUser.subscribe(user => this.currentUser = user);
  }

  ngOnInit(): void {
  }

  logout(): void {
    this.userService.logout()
      .subscribe(() => this.router.navigate(['/']));
  }

}
