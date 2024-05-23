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
    if (this.userType === 'worker') {
      this.loginWorker();
    } else if (this.userType === 'agent') {
      this.loginAgent();
    }
  }

  loginWorker(): void {
    this.user.user_type = 'worker';
    console.log('Logging in as worker:', this.user);
    this.http.post('http://localhost/api/v1/login', this.user).subscribe(
      (res: any) => {
        const token = res.token;
        console.log(token);
        if (token) {
          this.authService.setToken(token); // store the token in local storage
          //console.log('Token retrieved:', token);

          localStorage.setItem('jwt', token);

          this.router.navigate(['/worker']).then(() => {
            console.log('Navigation to /workers successful');

            this.http.get('http://localhost/api/v1/workers', { headers: { 'Authorization': `Bearer ${token}` } }).subscribe(
              data => console.log(data),
              error => console.error('Error fetching worker data:', error)
            );
          }).catch(err => console.error('Error navigating to /workers:', err));
        } else {
          console.error('No token retrieved');
        }
      },
      error => console.error('Error logging in:', error)
    )
  }



  loginAgent(): void {
    this.user.user_type = 'agent';
    this.http.post('http://localhost/api/v1/login', this.user).subscribe(
      data => console.log(data),
      error => console.error(error)
    );

  }

  switchUserType(type: string) {
    this.userType = type;
  }

  navigateToRegister() {
    this.router.navigate(['/register']);
  }
}
