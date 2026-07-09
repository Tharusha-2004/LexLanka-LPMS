import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { CaseService } from '../../services/case.service';

@Component({
  selector: 'app-case-list',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './case-list.component.html'
})
export class CaseListComponent implements OnInit {
  cases: any[] = [];
  isLoading: boolean = true;
  error: string | null = null;

  constructor(private caseService: CaseService) {}

  ngOnInit(): void {
    this.caseService.getAllCases().subscribe({
      next: (data) => {
        this.cases = data;
        this.isLoading = false;
      },
      error: (err) => {
        console.error('Error fetching cases:', err);
        this.error = 'Failed to load cases. Please try again later.';
        this.isLoading = false;
      }
    });
  }
}
