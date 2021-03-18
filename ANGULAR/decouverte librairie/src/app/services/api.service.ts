import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class ApiService {

  constructor() { }

  books = [
    {
      "id" : 1,
      "picture": "../assets/pictures/dbz.jpg",
      "author": "Juana Klein",
      "title": "XTH",
      "price": 10,
      "quantity": 5,
      "abstract": "Ut est occaecat laborum consequat laboris consectetur id cupidatat consequat. Velit amet enim reprehenderit amet aliquip ad duis aute. Labore laboris elit culpa ex qui voluptate sint qui amet aliqua magna voluptate. Et est elit eu laborum et in sit duis commodo fugiat.\r\n"
    },
    {
      "id" : 2,
      "picture": "../assets/pictures/mld.jpg",
      "author": "Dennis Maddox",
      "title": "TECHMANIA",
      "price": 78,
      "quantity": 5,
      "abstract": "Nostrud consequat est sunt ipsum ipsum enim ut magna Lorem. Eiusmod occaecat qui mollit enim reprehenderit consectetur ad irure exercitation incididunt minim. Culpa cillum ut ea cupidatat dolor aute laborum cillum duis exercitation adipisicing consequat adipisicing deserunt.\r\n"
    },
    {
      "id" : 3,
      "picture": "../assets/pictures/rld.jpg",
      "author": "Terry Powell",
      "title": "SUREPLEX",
      "price": 58,
      "quantity": 30,
      "abstract": "Fugiat excepteur cupidatat ipsum aliqua. Incididunt voluptate adipisicing proident exercitation non dolore quis est esse exercitation consequat ex. Aliqua tempor deserunt fugiat ad anim in quis eu ipsum consequat. Officia magna labore ut non labore irure.\r\n"
    },
    {
      "id" : 4,
      "picture": "../assets/pictures/mld.jpg",
      "author": "Theresa Mcmahon",
      "title": "RODEMCO",
      "price": 148,
      "quantity": 13,
      "abstract": "Occaecat mollit dolor cillum velit excepteur et reprehenderit. Est tempor et in voluptate ad in. Ut elit laboris aliquip non anim sint. Non esse Lorem reprehenderit velit adipisicing.\r\n"
    },
    {
      "id" : 5,
      "picture": "../assets/pictures/mld.jpg",
      "author": "Moore Maxwell",
      "title": "BEDDER",
      "price": 65,
      "quantity": 10,
      "abstract": "Occaecat duis officia commodo laborum enim anim labore sit ullamco eu ut eiusmod amet esse. Eiusmod fugiat exercitation ullamco veniam. Lorem commodo anim tempor laboris ullamco mollit in ut. Nostrud nulla mollit incididunt anim eiusmod anim excepteur culpa esse nostrud cillum mollit cillum esse. Sit est nostrud elit cillum cillum veniam dolor proident commodo. Occaecat elit sunt tempor laboris ad.\r\n"
    }
  ]
}
