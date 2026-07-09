import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class CaseService {
  private apiUrl = 'http://localhost:3000/api/cases';

  constructor(private http: HttpClient) { }

  getAllCases(): Observable<any> {
    return this.http.get<any>(this.apiUrl);
  }
}
