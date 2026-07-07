import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ClientService } from '../../services/client.service';

@Component({
  selector: 'app-client-list',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './client-list.component.html'
})
export class ClientListComponent implements OnInit {
  clients: any[] = [];
  isLoading: boolean = true;
  error: string | null = null;

  constructor(private clientService: ClientService) {}

  ngOnInit(): void {
    this.clientService.getAllClients().subscribe({
      next: (data) => {
        this.clients = data;
        this.isLoading = false;
      },
      error: (err) => {
        console.error('Error fetching clients:', err);
        this.error = 'Failed to load clients. Please try again later.';
        this.isLoading = false;
      }
    });
  }
}
