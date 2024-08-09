import { Component, OnInit, ChangeDetectorRef } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';

interface Ticket {
  id: number;
  department: string;
  message: string;
  response: string;
  files: any[];
}

@Component({
  selector: 'app-ticket-handle',
  templateUrl: './ticket-handle.component.html',
  styleUrl: './ticket-handle.component.css'
})
export class TicketHandleComponent {
  ticket: Ticket = { id: 0, department: '', message: '',response:'', files: [] };
  files: FileList | null = null;


  constructor(private route: ActivatedRoute, private http: HttpClient, private router: Router, private cdr: ChangeDetectorRef) {}

  ngOnInit() {
    this.fetchTicket();
  }

  fetchTicket(): void {
    const ticketId = this.route.snapshot.paramMap.get('id');
    const token = localStorage.getItem('token');
    if (ticketId && token) {
      this.http.get<{data: Ticket}>(`http://localhost/api/v1/tickets/${ticketId}`, {
        headers: { 'Authorization': `Bearer ${token}` },
        params: { 'includeFiles': 'true' }
      }).subscribe(response => {
        this.ticket = response.data;
      });
    }
  }

  submitResponse(){
    const token = localStorage.getItem('token');
    if (this.ticket && token) {
      const response = { response: this.ticket.response }; 

      this.http.post(`http://localhost/api/v1/edit_ticket/${this.ticket.id}/response`, response, {
        headers: { 'Authorization': `Bearer ${token}` }
      }).subscribe(response => {
        console.log(response);
      });
    }
    this.router.navigate(['/agent']);
  }
}
