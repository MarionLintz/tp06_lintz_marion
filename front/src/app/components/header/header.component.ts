import { Component, OnInit } from '@angular/core';
import { Store } from '@ngxs/store';
import { Observable } from 'rxjs';

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.scss']
})
export class HeaderComponent implements OnInit {

  basketContentNumber : Observable<number>;

  constructor(private store: Store) {
  }

  ngOnInit(): void {
    this.basketContentNumber = this.store.select(state => state.basket.products.length);
  }

}
