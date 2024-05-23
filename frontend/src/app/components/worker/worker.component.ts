import { Component, OnInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router'; // Import Router
import { AuthService } from '../../service/auth.service'; // Import AuthService


interface ServerResponse {
  data: any[];
  links: object;
  meta: object;
}

@Component({
  selector: 'app-worker',
  templateUrl: './worker.component.html',
  styleUrls: ['./worker.component.css']
})
export class WorkerComponent implements OnInit {
  workerData: any;

  constructor(private http: HttpClient, private router: Router, private authService: AuthService) { }



  ngOnInit(): void {
    const token = localStorage.getItem('jwt');
    //console.log(token);
    if (token) {
      this.http.get<ServerResponse>('http://localhost/api/v1/workers',
        { headers: { 'Authorization': `Bearer ${token}` },
                  params: { 'includeTickets': 'true', 'includeFiles': 'true' }
        }).subscribe(
        data => {
          console.log('Server response:', data);
          this.workerData = data.data[0];
        },
        error => console.error('Error fetching worker data:', error)
      );
    }
  }

  logout(): void {
    const token = localStorage.getItem('jwt');
    console.log(token);
    this.authService.clearToken();
    if (token) {
      this.http.post('http://localhost/api/v1/logout', {}, {
        headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
      }).subscribe(
        () => {
          localStorage.removeItem('jwt'); // Remove the token from local storage
          this.router.navigate(['/login']); // Navigate to login page
        },
        error => console.error('Error logging out:', error)
      );
    }
  }

  createTicket(): void {
    this.router.navigate(['/create_ticket']);
  }
}
