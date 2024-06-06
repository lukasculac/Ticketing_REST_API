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
  sortedTicketsByDepartment: any = {};
  departments: string[] = [];
  visibility: any = {};

  constructor(private http: HttpClient, private router: Router, private authService: AuthService) {
    this.router.events.pipe(
      filter(event => event instanceof NavigationEnd)
    ).subscribe(() => {
      this.fetchAgentData();
    });
  }

  ngOnInit(): void {
  }

  fetchAgentData(): void {
    const token = localStorage.getItem('token');
    if (token) {
      this.http.get<{
        agent: any;
        tickets: any[]}>('http://localhost/api/v1/agents',
        { headers: { 'Authorization': `Bearer ${token}` },
          params: { 'includeTickets': 'true', 'includeFiles': 'true' }
        }).subscribe(
        data => {
          //console.log('Server response:', data);
          this.agentData = data.agent;
          data.tickets.forEach(ticket => {
            if (!this.sortedTicketsByDepartment[ticket.department]) {
              this.sortedTicketsByDepartment[ticket.department] = [];
              this.departments.push(ticket.department);
              this.visibility[ticket.department] = this.agentData && ticket.department === this.agentData.department;
            }
            this.sortedTicketsByDepartment[ticket.department].push(ticket);
          });
          this.sortTicketsByDepartment();
          console.log('Tickets:', this.sortedTicketsByDepartment);
        },
        error => {
          console.error('Error fetching agent data:', error);
        }
      );
    }
  }

  sortTicketsByDepartment() {
    const statusValue = (status: string) => status === 'closed' ? 2 : 1;
    const priorityValue = (priority: string) => priority === 'high' ? 1 : (priority === 'medium' ? 2 : 3);

    for (let department in this.sortedTicketsByDepartment) {
      this.sortedTicketsByDepartment[department].sort((a: { status: string; priority: string; }, b: { status: string; priority: string; }) => {
        if (statusValue(a.status) !== statusValue(b.status)) {
          return statusValue(a.status) - statusValue(b.status);
        }
        return priorityValue(a.priority) - priorityValue(b.priority);
      });
    }
  }

  toggleVisibility(department: string) {
    this.visibility[department] = !this.visibility[department];
  }

  openTicket(ticketId: number) {
    let ticketData;
    for (let department in this.sortedTicketsByDepartment) {
      let ticket = this.sortedTicketsByDepartment[department].find((ticket: { id: number; }) => ticket.id === ticketId);
      if (ticket) {
        ticketData = ticket;
        break;
      }
    }
    const token = localStorage.getItem('token');
    if (token) {
      this.http.post(`http://localhost/api/v1/handleTicketStatus/${ticketId}`, { status: 'opened' }, {
        headers: { 'Authorization': `Bearer ${token}` }
      }).subscribe(response => {
        console.log(response);
      });
    }
    this.router.navigate(['/ticket_handle', ticketId], { state: { data: ticketData } });
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
