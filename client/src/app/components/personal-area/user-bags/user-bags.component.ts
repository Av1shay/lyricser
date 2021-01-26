import {
  Component,
  OnInit,
  ViewChild,
  ComponentRef,
  ViewContainerRef,
  ComponentFactoryResolver,
  Input, OnDestroy,
} from '@angular/core';
import {WordsBagsFormComponent} from '@app/components/personal-area/user-bags/words-bags-form/words-bags-form.component';
import WordsBag from '@app/models/WordsBag';
import {Subscription} from 'rxjs';
import UserService from '@app/services/user.service';

@Component({
  selector: 'app-user-bags',
  templateUrl: './user-bags.component.html',
  styleUrls: ['./user-bags.component.scss']
})
export class UserBagsComponent implements OnInit, OnDestroy {
  @Input() bags: WordsBag[];
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
    const childComponent = this.resolver.resolveComponentFactory(WordsBagsFormComponent);
    this.componentRef = this.target.createComponent(childComponent);

    this.sub = this.componentRef.instance.closeComponent
      .subscribe(() => {
        this.componentRef.destroy();
        this.showAddNewFormBtn = true;
      });

    this.showAddNewFormBtn = false;
  }

  removeBag(id: string) {
    this.userService.deleteWordsBag(id).subscribe();
  }
}
