import { Component } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';


@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.css']
})
export class RegisterComponent {
  user = {name: '', email: '', position: '', phone: '', password: '', confirm_password: '', department: '', user_type: ''};
  userType = 'worker'; // default user type

  constructor(private http: HttpClient, private router: Router) { }

  onSubmit(): void {
    if (this.user.password !== this.user.confirm_password) {
      alert('Passwords do not match!');
      return;
    }

    if (this.userType === 'worker') {
      this.registerWorker();
    } else if (this.userType === 'agent') {
      this.registerAgent();
    }
  }

  registerWorker(): void {
    this.user.user_type = 'worker';
    console.log('Registering as worker:', this.user);
    this.http.post('http://localhost/api/v1/register', this.user).subscribe(
      data => {
        console.log(data);
        this.router.navigate(['/login']); // Navigate to login page
      },
      error => console.error(error)
    );
  }

  registerAgent(): void {
    this.user.user_type = 'agent';
    this.http.post('http://localhost/api/v1/register', this.user).subscribe(
      data => {
        console.log(data);
        this.router.navigate(['/login']); // Navigate to login page
      },
      error => console.error(error)
    );
  }

  switchUserType(type: string) {
    this.userType = type;
  }
}