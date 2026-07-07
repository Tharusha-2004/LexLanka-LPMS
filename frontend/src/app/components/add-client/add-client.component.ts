import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule, FormBuilder, FormGroup, Validators } from '@angular/forms';
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
  successMessage: string | null = null;
  errorMessage: string | null = null;

  constructor(
    private fb: FormBuilder,
    private clientService: ClientService
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
    this.successMessage = null;
    this.errorMessage = null;

    this.clientService.addClient(this.clientForm.value).subscribe({
      next: (response) => {
        this.successMessage = 'Client added successfully!';
        this.isSubmitting = false;
        this.clientForm.reset({ clientType: 'Individual' });
      },
      error: (err) => {
        console.error('Error adding client:', err);
        this.errorMessage = 'Failed to add client. Please try again.';
        this.isSubmitting = false;
      }
    });
  }
}
