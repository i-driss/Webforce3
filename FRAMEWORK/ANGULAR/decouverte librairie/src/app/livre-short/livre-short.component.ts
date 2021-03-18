import { Component, Input, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { ApiService } from '../services/api.service';

@Component({
  selector: 'app-livre-short',
  templateUrl: './livre-short.component.html',
  styleUrls: ['./livre-short.component.scss']
})
export class LivreShortComponent implements OnInit {

  @Input() title : string 
  @Input() author : string
  @Input() picture : string = "" 
  @Input() id : number 

  constructor(private router : Router , private route: ActivatedRoute , private api: ApiService) { }

  ngOnInit(): void {
    console.log(this.id)
  }


  goToLivre(): void {
    this.router.navigate(['livre']);
  }
 
}
