import { Component } from '@angular/core';
import { HttpClient, HttpResponse } from '@angular/common/http';
import { Router } from '@angular/router';
import { AuthService } from '../../service/auth.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent {
  user = {email: '', password: '', user_type: ''};
  userType = 'worker'; // default user type

  constructor(private http: HttpClient, private router: Router,private authService: AuthService) { }

  onSubmit(): void {
    if(!this.user.email || !this.user.password) {
      alert('Please fill all the required fields!');
      return;
    }

    if (this.userType === 'worker') {
      this.login('worker');
    } else if (this.userType === 'agent') {
      this.login('agent');
    }
  }


  login(userType: string): void {
    this.user.user_type = userType;
    //console.log(`Logging in as ${userType}:`, this.user);
    this.http.post('http://localhost/api/v1/login', this.user).subscribe(
      (res: any) => {
        const token = res.token;
        if (token) {
          //save token in local storage
          this.authService.setToken(token);
          localStorage.setItem('token', token);

          this.router.navigate([`/${userType}`]).then(() => {
            //console.log(`Navigation to /${userType} successful`);
            this.http.get(`http://localhost/api/v1/${userType}s`, { headers: { 'Authorization': `Bearer ${token}` } }).subscribe(
              data => console.log(data),
              error => console.error(`Error fetching ${userType} data:`, error)
            );
          }).catch(err => console.error(`Error navigating to /${userType}:`, err));
        } else {
          console.error('No token retrieved');
        }
      },
      error => {
        console.error('Error logging in:', error);
        if (error.status === 401) { //No user exists with the given email
          alert(error.error.message);
        }
      }
    )
  }

  //switch user type(worker/agent)
  switchUserType(type: string) {
    this.userType = type;
  }

  //register button click
  navigateToRegister() {
    this.router.navigate(['/register']);
  }
}
