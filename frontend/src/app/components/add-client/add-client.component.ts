import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule, FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { ClientService } from '../../services/client.service';

@Component({
  selector: 'app-add-client',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule],
  templateUrl: './add-client.component.html'
})
export class AddClientComponent {
  clientForm: FormGroup;
  isSubmitting = false;
  errorMessage: string | null = null;

  constructor(
    private fb: FormBuilder,
    private clientService: ClientService,
    private router: Router
  ) {
    this.clientForm = this.fb.group({
      fullName: ['', Validators.required],
      nicNumber: ['', Validators.required],
      email: [''],
      phone: ['', Validators.required],
      address: ['', Validators.required],
      clientType: ['Individual', Validators.required]
    });
  }

  onSubmit(): void {
    if (this.clientForm.invalid) {
      this.clientForm.markAllAsTouched();
      return;
    }

    this.isSubmitting = true;
    this.errorMessage = null;

    this.clientService.addClient(this.clientForm.value).subscribe({
      next: () => {
        // Navigate back to the home page (client list)
        this.router.navigate(['/']);
      },
      error: (err) => {
        console.error('Error adding client:', err);
        this.errorMessage = 'Failed to add client. Please try again.';
        this.isSubmitting = false;
      }
    });
  }
}

