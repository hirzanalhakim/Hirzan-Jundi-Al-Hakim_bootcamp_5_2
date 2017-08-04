import { Injectable } from '@angular/core';
import { Router } from '@angular/router';
import { Http, Headers, RequestOptions} from '@angular/http';
import { Observable } from 'rxjs/Rx';
import 'rxjs/add/operator/map'

@Injectable()
export class ApiService {

  constructor(private http:Http, private router:Router) { }

  //function untuk mendapatkan userlist dalam bentuk json().
  getEventList(){

    return this.http.get('http://localhost:8000/api/event/')
    .map(result => result.json());
  }

  addEvent(obj:object ){
    let body = JSON.stringify(obj);
    let headers = new Headers({
      "Content-Type" : "application/json"});
    let options = new RequestOptions({ headers : headers});

    return this.http.post('http://localhost:8000/api/event/add', body, options).map(result => result.json());

  }

  buyTicket(obj:object ){
    let body = JSON.stringify(obj);
    let headers = new Headers({
      "Content-Type" : "application/json"});
    let options = new RequestOptions({ headers : headers});

    return this.http.post('http://localhost:8000/api/event/buy', body, options).map(result => result.json());

  }

}
