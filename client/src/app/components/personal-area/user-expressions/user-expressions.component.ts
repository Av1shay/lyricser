import {
  Component,
  ComponentFactoryResolver,
  ComponentRef, Input,
  OnDestroy,
  OnInit,
  ViewChild,
  ViewContainerRef
} from '@angular/core';
import {Subscription} from 'rxjs';
import {ExpressionFormComponent} from '@app/components/personal-area/user-expressions/expression-form/expression-form.component';
import WordsBag from '@app/models/WordsBag';
import Expression from '@app/models/Expression';
import UserService from '@app/services/user.service';

@Component({
  selector: 'app-user-expressions',
  templateUrl: './user-expressions.component.html',
  styleUrls: ['./user-expressions.component.scss']
})
export class UserExpressionsComponent implements OnInit, OnDestroy {
  @Input() expressions: Expression[];
  @ViewChild('appendForm', {static: false, read: ViewContainerRef}) target: ViewContainerRef;
  private componentRef: ComponentRef<any>;
  private sub: Subscription;

  displayedDeleteBtn: number;
  showAddNewFormBtn: boolean;

  constructor(private resolver: ComponentFactoryResolver, private userService: UserService) {
    this.displayedDeleteBtn = -1;
    this.showAddNewFormBtn = true;
  }

  ngOnInit(): void {
  }

  ngOnDestroy() {
    if (this.sub) {
      this.sub.unsubscribe();
    }
  }

  addNewForm() {
    const childComponent = this.resolver.resolveComponentFactory(ExpressionFormComponent);
    this.componentRef = this.target.createComponent(childComponent);

    this.sub = this.componentRef.instance.closeComponent
      .subscribe(() => {
        this.componentRef.destroy();
        this.showAddNewFormBtn = true;
      });

    this.showAddNewFormBtn = false;
  }

  removeExp(id: string) {
    this.userService.deleteExpression(id).subscribe();
  }

}
