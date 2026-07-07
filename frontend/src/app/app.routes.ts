import { Routes } from '@angular/router';
import { ClientListComponent } from './components/client-list/client-list.component';
import { AddClientComponent } from './components/add-client/add-client.component';

export const routes: Routes = [
  { path: '', component: ClientListComponent },
  { path: 'clients', redirectTo: '', pathMatch: 'full' },
  { path: 'add-client', component: AddClientComponent },
  { path: '**', redirectTo: '' }
];
