import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  private token!: string;

  constructor() { }

  // Call this method when the worker logs in
  setToken(token: string): void {
    this.token = token;
    localStorage.setItem('token', token);
  }

  // Call this method to get the token of the currently logged in worker
  getToken(): string {
    // @ts-ignore
    return localStorage.getItem('token');
  }

  // Call this method when the worker logs out
  clearToken(): void {
    localStorage.removeItem('token');
  }
}
