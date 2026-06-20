import { HttpClient } from '@angular/common/http';
import { computed, inject, Injectable, signal } from '@angular/core';
import { Observable, tap } from 'rxjs';
import { AuthResponse, User } from '../models/api.models';

@Injectable({ providedIn: 'root' })
export class AuthService {
  private readonly http = inject(HttpClient);
  private readonly apiUrl = '/api';
  private readonly tokenKey = 'skola_fudbala_api_token';
  private readonly userKey = 'skola_fudbala_api_user';

  readonly user = signal<User | null>(this.readStoredUser());
  readonly isAuthenticated = computed(() => Boolean(this.token() && this.user()));
  readonly canManageTrainings = computed(() =>
    ['administrator', 'trener'].includes(this.user()?.role ?? ''),
  );

  register(payload: {
    name: string;
    email: string;
    password: string;
    password_confirmation: string;
    ime: string;
    prezime: string;
    datum_rodjenja: string;
    telefon_roditelja: string;
  }): Observable<AuthResponse> {
    return this.http
      .post<AuthResponse>(`${this.apiUrl}/register`, payload, { withCredentials: true })
      .pipe(tap((response) => this.saveSession(response)));
  }

  login(payload: { email: string; password: string }): Observable<AuthResponse> {
    return this.http
      .post<AuthResponse>(`${this.apiUrl}/login`, payload, { withCredentials: true })
      .pipe(tap((response) => this.saveSession(response)));
  }

  logout(): Observable<{ message: string }> {
    return this.http
      .post<{ message: string }>(`${this.apiUrl}/logout`, {})
      .pipe(tap(() => this.clearSession()));
  }

  loadUser(): Observable<{ user: User }> {
    return this.http
      .get<{ user: User }>(`${this.apiUrl}/user`)
      .pipe(tap(({ user }) => this.storeUser(user)));
  }

  token(): string | null {
    return localStorage.getItem(this.tokenKey);
  }

  clearSession(): void {
    localStorage.removeItem(this.tokenKey);
    localStorage.removeItem(this.userKey);
    this.user.set(null);
  }

  private saveSession(response: AuthResponse): void {
    localStorage.setItem(this.tokenKey, response.token);
    this.storeUser(response.user);
  }

  private storeUser(user: User): void {
    localStorage.setItem(this.userKey, JSON.stringify(user));
    this.user.set(user);
  }

  private readStoredUser(): User | null {
    const value = localStorage.getItem(this.userKey);

    try {
      return value ? (JSON.parse(value) as User) : null;
    } catch {
      return null;
    }
  }

}
