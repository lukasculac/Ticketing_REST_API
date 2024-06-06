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
  selector: 'app-edit-ticket',
  templateUrl: './edit-ticket.component.html',
  styleUrls: ['./edit-ticket.component.css']
})
export class EditTicketComponent implements OnInit {
  ticket: Ticket = { id: 0, department: '', message: '',response:'', files: [] }; // Initialize ticket
  files: FileList | null = null;
  newFiles: File[] = [];


  constructor(private route: ActivatedRoute, private http: HttpClient, private router: Router, private cdr: ChangeDetectorRef) { }

  ngOnInit(): void {
    this.fetchTicket();
  }

  fetchTicket(): void {
    const ticketId = this.route.snapshot.paramMap.get('ticketId');
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

  submitTicket(): void {
    const token = localStorage.getItem('token');
    if (this.ticket && token) {
      const formData = new FormData();
      formData.append('department', this.ticket.department);
      formData.append('message', this.ticket.message);
      this.newFiles.forEach((file, index) => {
        formData.append(`files[${index}]`, file);
      });

      this.http.post(`http://localhost/api/v1/edit_ticket/${this.ticket.id}`, formData, {
        headers: { 'Authorization': `Bearer ${token}` }
      }).subscribe(response => {
        console.log(response);
      });
    }
    this.router.navigate(['/worker']);
  }


  onFileChange(event: any): void {
    const newFiles = event.target.files;
    for (let i = 0; i < newFiles.length; i++) {
      const file = newFiles[i];
      this.newFiles.push(file);
    }
  }

  deleteFile(fileId: number): void {
    const token = localStorage.getItem('token');
    if (token) {
      this.http.delete(`http://localhost/api/v1/delete_file/${fileId}`, {
        headers: { 'Authorization': `Bearer ${token}` }
      }).subscribe(response => {
        console.log(response);
        // Remove the file from the ticket's files array
        this.ticket.files = this.ticket.files.filter(file => file.id !== fileId);
        this.cdr.detectChanges();
      });
    }
  }
}
