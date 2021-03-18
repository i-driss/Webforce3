import { Component, OnInit } from '@angular/core';
import { ApiService } from '../services/api.service';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.scss']
})

export class HomeComponent implements OnInit{
  title = 'webforce';
  books : any[];


  constructor(public api: ApiService) {
    this.books = this.api.books;
   }

   ngOnInit() {

   }
  
}
