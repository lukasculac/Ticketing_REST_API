import { Component } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.css']
})
export class RegisterComponent {
  user = {name: '', email: '', position: '', phone: '', password: '', confirm_password: '', department: ''};

  constructor(private http: HttpClient, private router: Router) { }

  onSubmit(): void {
    //check if passwords match
    if (this.user.password !== this.user.confirm_password) {
      alert('Passwords do not match!');
      return;
    }
    //check if all required fields are filled
    if (!this.user.name || !this.user.email || !this.user.position || !this.user.phone || !this.user.password || !this.user.confirm_password) {
      alert('Please fill all the required fields!');
      return;
    }
    this.registerWorker();
  }

  registerWorker(): void {
    //console.log('Registering as worker:', this.user);
    //console.log(this.user);
    this.http.post('http://localhost/api/v1/register', this.user).subscribe(
      data => {
        //console.log(data);
        this.router.navigate(['/login']); // Navigate to login page
      },
      error => {
        console.error(error);
        if (error.status === 409) { //check if the user with same email exists
          alert(error.error.message);
        }
      }
    );
  }
}
