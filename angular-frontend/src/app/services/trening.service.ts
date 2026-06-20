import { HttpClient } from '@angular/common/http';
import { inject, Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import {
  ApiData,
  Selekcija,
  Trener,
  Trening,
  TreningPayload,
} from '../models/api.models';

@Injectable({ providedIn: 'root' })
export class TreningService {
  private readonly http = inject(HttpClient);
  private readonly url = '/api/treninzi';

  list(): Observable<ApiData<Trening[]>> {
    return this.http.get<ApiData<Trening[]>>(this.url);
  }

  get(id: number): Observable<ApiData<Trening>> {
    return this.http.get<ApiData<Trening>>(`${this.url}/${id}`);
  }

  create(payload: TreningPayload): Observable<ApiData<Trening>> {
    return this.http.post<ApiData<Trening>>(this.url, payload);
  }

  update(id: number, payload: TreningPayload): Observable<ApiData<Trening>> {
    return this.http.put<ApiData<Trening>>(`${this.url}/${id}`, payload);
  }

  delete(id: number): Observable<{ message: string }> {
    return this.http.delete<{ message: string }>(`${this.url}/${id}`);
  }

  selections(): Observable<ApiData<Selekcija[]>> {
    return this.http.get<ApiData<Selekcija[]>>('/api/selekcije');
  }

  coaches(): Observable<ApiData<Trener[]>> {
    return this.http.get<ApiData<Trener[]>>('/api/treneri');
  }
}
