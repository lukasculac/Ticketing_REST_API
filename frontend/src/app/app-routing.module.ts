import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { RegisterComponent } from './components/register/register.component';
import { LoginComponent } from './components/login/login.component';
import { WorkerComponent } from './components/worker/worker.component';
import { AgentComponent} from "./components/agent/agent.component";
import { CreateTicketComponent } from './components/create-ticket/create-ticket.component';
import { EditTicketComponent} from "./components/edit-ticket/edit-ticket.component";
import { TicketHandleComponent} from "./components/ticket-handle/ticket-handle.component";

const routes: Routes = [
  { path: 'register', component: RegisterComponent },
  { path: 'login', component: LoginComponent },
  { path: 'worker', component: WorkerComponent },
  { path: 'create_ticket', component: CreateTicketComponent },
  { path: 'edit_ticket/:ticketId', component: EditTicketComponent },
  { path: 'agent', component: AgentComponent },
  { path: 'ticket_handle/:id', component: TicketHandleComponent },


];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
