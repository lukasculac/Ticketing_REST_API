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
  selector: 'app-agent',
  templateUrl: './agent.component.html',
  styleUrl: './agent.component.css'
})
export class AgentComponent implements OnInit{
  user:any;
  agentData: any;
  workerData: any;
  sortedTicketsByDepartment: any = {};
  departments: string[] = [];
  visibility: any = {};



  constructor(private http: HttpClient, private router: Router, private authService: AuthService) {
    this.router.events.pipe(
      filter(event => event instanceof NavigationEnd)
    ).subscribe(() => {
      // When a NavigationEnd event occurs, fetch the worker data
      this.fetchAgentData();
    });
  }

  ngOnInit(): void {
    //this.fetchAgentData();
  }

  fetchAgentData(): void {
    const token = localStorage.getItem('token');
    if (token) {
      this.http.get<{agent: any, workers: any[]}>('http://localhost/api/v1/agents',
        { headers: { 'Authorization': `Bearer ${token}` },
          params: { 'includeTickets': 'true', 'includeFiles': 'true' }
        }).subscribe(
        data => {
          console.log('Server response:', data);
          this.agentData = data.agent;
          this.workerData = data.workers;
          this.sortTicketsByDepartment();
        },
        error => {
          console.error('Error fetching agent data:', error);
        }
      );
    }
  }

  sortTicketsByDepartment() {
    this.workerData.forEach((worker: { tickets: any[]; }) => {
      worker.tickets.forEach(ticket => {
        if (!this.sortedTicketsByDepartment[ticket.department]) {
          this.sortedTicketsByDepartment[ticket.department] = [];
          this.departments.push(ticket.department);
          this.visibility[ticket.department] = false;
        }
        this.sortedTicketsByDepartment[ticket.department].push(ticket);
      });
    });
  }

  toggleVisibility(department: string) {
    this.visibility[department] = !this.visibility[department];
  }

  logout(): void {
    const token = localStorage.getItem('token');
    this.user = { user_type: 'agent' };
    //console.log(token);
    this.authService.clearToken();

    this.http.post('http://localhost/api/v1/logout', this.user, {
      headers: {'Authorization': `Bearer ${token}`, 'Accept': 'application/json'}
    }).subscribe(
      () => {
        localStorage.removeItem('token'); // Remove the token from local storage
        this.router.navigate(['/login']); // Navigate to login page
      },
      error => console.error('Error logging out:', error)
    );
  }
}
