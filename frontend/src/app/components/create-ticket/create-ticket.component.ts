import { Component , ViewChild} from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { NgForm } from '@angular/forms';
import { AuthService } from '../../service/auth.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-create-ticket',
  templateUrl: './create-ticket.component.html',
  styleUrls: ['./create-ticket.component.css']
})

export class CreateTicketComponent {
  @ViewChild('ticketForm') ticketForm!: NgForm;
  department!: string;
  message!: string;
  files!: FileList;
  constructor(private http: HttpClient, private authService: AuthService,private router: Router) {}

  submitTicket(): void {
    if(!this.department || !this.message) {
      alert('Please fill in all required fields');
      return;
    }
    const url = 'http://localhost/api/v1/store_ticket';
    const formData = new FormData();

    formData.append('department', this.department);
    formData.append('message', this.message);

    // Append each file to the form data
    if (this.files) {
      for (let i = 0; i < this.files.length; i++) {
        formData.append('files[]', this.files[i]);
      }
    }


    //const token = this.authService.getToken();
    const token = localStorage.getItem('token');
    console.log(token);
    const headers = { 'Authorization': `Bearer ${token}` , 'Accept': 'application/json' };
    console.log(formData);
    this.http.post(url, formData, { headers }).subscribe(response => {
      //console.log(response);  //output the post request the ticket contains
      // Handle response here
    }, error => {
      console.error(error);
      // Handle error here
    });
    this.router.navigate(['/worker']);
  }

  onFileChange(event: any): void {
    this.files = event.target.files;
  }
}
