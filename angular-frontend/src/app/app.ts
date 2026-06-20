import { Component, inject } from '@angular/core';
import { RouterLink, RouterOutlet } from '@angular/router';
import { environment } from '../environments/environment';
import { AuthService } from './services/auth.service';

interface NavigationItem {
  label: string;
  url: string;
  active?: boolean;
}

@Component({
  selector: 'app-root',
  imports: [RouterOutlet, RouterLink],
  templateUrl: './app.html',
  styleUrl: './app.css',
})
export class App {
  readonly auth = inject(AuthService);

  logout(): void {
    this.auth.logout().subscribe({
      next: () => {
        window.location.href = '/login?logout=1';
      },
      error: () => {
        this.auth.clearSession();
        window.location.href = '/login?logout=1';
      },
    });
  }

  roleLabel(role: string): string {
    return {
      administrator: 'administrator',
      trener: 'trener',
      clan_roditelj: 'član/roditelj',
    }[role] ?? role;
  }

  webUrl(path: string): string {
    return `${environment.webBaseUrl}${path}`;
  }

  navigationTitle(): string {
    return this.auth.user()?.role === 'administrator' ? 'Administracija' : 'Trener';
  }

  navigation(): NavigationItem[] {
    if (this.auth.user()?.role === 'administrator') {
      return [
        { label: 'Dashboard', url: this.webUrl('/admin/dashboard') },
        { label: 'Članovi', url: this.webUrl('/admin/clanovi') },
        { label: 'Treneri', url: this.webUrl('/admin/treneri') },
        { label: 'Selekcije', url: this.webUrl('/admin/selekcije') },
        { label: 'Treninzi', url: this.webUrl('/admin/treninzi'), active: true },
        { label: 'Utakmice', url: this.webUrl('/admin/utakmice') },
        { label: 'Timovi', url: this.webUrl('/admin/timovi') },
        { label: 'Napredak', url: this.webUrl('/admin/napredak') },
        { label: 'Članarine', url: this.webUrl('/admin/clanarine') },
        { label: 'Izveštaji', url: this.webUrl('/admin/izvestaji') },
      ];
    }

    return [
      { label: 'Dashboard', url: this.webUrl('/trener/dashboard') },
      { label: 'Moja selekcija', url: this.webUrl('/trener/selekcija') },
      { label: 'Članovi', url: this.webUrl('/trener/clanovi') },
      { label: 'Treninzi', url: this.webUrl('/trener/treninzi'), active: true },
      { label: 'Utakmice', url: this.webUrl('/trener/utakmice') },
      { label: 'Timovi', url: this.webUrl('/trener/timovi') },
      { label: 'Napredak', url: this.webUrl('/trener/napredak') },
    ];
  }
}
