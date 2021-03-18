import { getLocaleDateFormat } from '@angular/common';
import { Component, Input, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { title } from 'process';
import { AboutComponent } from '../about/about.component';
import { ApiService } from '../services/api.service';

@Component({
  selector: 'app-livre',
  templateUrl: './livre.component.html',
  styleUrls: ['./livre.component.scss']
})
export class LivreComponent implements OnInit {

  title : string 
  author : string 
  abstract : string 
  quantity : number 
  available : boolean = true
  dateP : number = Date.now()  
  price : number 
  id : number 
  picture : string = ""
  maxPrice : number;
  livre 
 
  
  
  constructor(private router : Router , private route: ActivatedRoute , public api: ApiService) {
    
    
  }
  
  ngOnInit(): void {
    this.maxPrice = 100;
    this.id = this.route.snapshot.params['id'];
    this.livre = this.api.books.find(books => books.id == this.id)
    this.title = this.livre.title
    this.quantity = this.livre.quantity
    this.price = this.livre.price
    this.abstract = this.livre.abstract
    this.author = this.livre.author
    this.picture = this.livre.picture

 
  }
  getBookById(id : number){
    return this.api.books.find( book => book.id == id)
  }
  
  goToAbout(): void {
    this.router.navigate(['about']);
  }

  goToHome(): void {
    this.router.navigate(['']);
  }

  onBuy(): void {
    if(this.price < 100 && this.quantity > 0){
      this.price += 10 
      this.quantity -= 1
      this.router.navigate(['About']);

      if(this.price == 100 ){
        this.available = false

      }
    }
  }
  onAdd(): void {
    this.title = this.title + "a"
  }
  
}
