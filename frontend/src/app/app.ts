import { Component, signal } from '@angular/core';
import { RouterOutlet } from '@angular/router';
import { ClientListComponent } from './components/client-list/client-list.component';

@Component({
  selector: 'app-root',
  imports: [RouterOutlet, ClientListComponent],
  templateUrl: './app.html',
  styleUrl: './app.css'
})
export class App {
  protected readonly title = signal('frontend');
}
