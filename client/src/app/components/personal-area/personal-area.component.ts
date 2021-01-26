import { Component, OnInit } from '@angular/core';
import UserService from '../../services/user.service';
import User from '@app/models/User';
import WordsBag from '@app/models/WordsBag';
import Expression from '@app/models/Expression';

@Component({
  selector: 'app-personal-area',
  templateUrl: './personal-area.component.html',
  styleUrls: ['./personal-area.component.scss']
})
export class PersonalAreaComponent implements OnInit {
  currentUser: User;
  bags: WordsBag[] = [];
  expressions: Expression[] = [];

  constructor(private userService: UserService) {
    this.userService.currentUser
      .subscribe(user => {
        if (user) {
          this.currentUser = user;
          this.bags = user.metaData.wordsBags;
          this.expressions = user.metaData.expressions;
        }
      });
  }

  ngOnInit(): void {
  }

}
