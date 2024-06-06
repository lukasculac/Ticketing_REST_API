import { Component, OnInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Router, NavigationEnd } from '@angular/router';
import { AuthService } from '../../service/auth.service';
import { filter } from 'rxjs/operators';


interface ServerResponse {
  data: any[];
  links: object;
  meta: object;
}

@Component({
  selector: 'app-worker',
  templateUrl: './worker.component.html',
  styleUrls: ['./worker.component.css'],

})
export class WorkerComponent implements OnInit {
  workerData: any;
  private user: any;
  hasResponse: boolean | undefined;


  constructor(private http: HttpClient, private router: Router, private authService: AuthService) {
    this.router.events.pipe(
      filter(event => event instanceof NavigationEnd)
    ).subscribe(() => {
      // When a NavigationEnd event occurs, fetch the worker data
      this.fetchWorkerData();
    });
  }

  fetchWorkerData(): void {
    const token = localStorage.getItem('token');
    if (token) {
      this.http.get<ServerResponse>('http://localhost/api/v1/workers',
        { headers: { 'Authorization': `Bearer ${token}` },
          params: { 'includeTickets': 'true', 'includeFiles': 'true' }
        }).subscribe(
        data => {
          //console.log('Server response:', data);
          this.workerData = data.data;
        },
        error => {
          console.error('Error fetching worker data:', error);
        }
      );
      this.hasResponse = this.workerData?.tickets.some((ticket: { response: null; }) => ticket.response !== null);

    }
  }

  ngOnInit(): void {
  }

  logout(): void {
    const token = localStorage.getItem('token');
    this.user = { user_type: 'worker' };
    //console.log(token);
    this.authService.clearToken();

    this.http.post('http://localhost/api/v1/logout', this.user, {
      headers: {'Authorization': `Bearer ${token}`, 'Accept': 'application/json'}
    }).subscribe(
      () => {
        localStorage.removeItem('token'); // Remove the token from local storage
        this.router.navigate(['/login']);
      },
      error => console.error('Error logging out:', error)
    );
  }

  createTicket(): void {
    this.router.navigate(['/create_ticket']);
  }

  editTicket(ticketId: number): void {
    this.router.navigate(['/edit_ticket', ticketId]);
  }

  deleteTicket(ticketId: number): void {
    const token = localStorage.getItem('token');
    if (token) {
      if (confirm('Are you sure you want to delete this ticket?')) { // Ask for confirmation
        this.http.delete(`http://localhost/api/v1/tickets/${ticketId}`, {
          headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
        }).subscribe(
          () => {
            //console.log('Ticket deleted');
            this.workerData.tickets = this.workerData.tickets.filter((ticket: any) => ticket.id !== ticketId);
          },
          error => console.error('Error deleting ticket:', error)
        );
      }
    }
  }
}
