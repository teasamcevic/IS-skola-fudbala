export interface User {
  id: number;
  name: string;
  email: string;
  role: 'administrator' | 'trener' | 'clan_roditelj';
  clan_id: number | null;
  trener_id: number | null;
}

export interface AuthResponse {
  message: string;
  token: string;
  user: User;
  redirect_url: string;
}

export interface Selekcija {
  id: number;
  naziv: string;
  uzrasna_kategorija: string;
}

export interface Trener {
  id: number;
  ime: string;
  prezime: string;
  puno_ime: string;
  selekcija_id: number | null;
  selekcija?: Pick<Selekcija, 'id' | 'naziv'> | null;
}

export interface Trening {
  id: number;
  datum: string;
  vreme: string;
  lokacija: string;
  selekcija_id: number;
  trener_id: number;
  selekcija: Selekcija;
  trener: Trener;
}

export interface TreningPayload {
  datum: string;
  vreme: string;
  lokacija: string;
  selekcija_id: number;
  trener_id: number;
}

export interface ApiData<T> {
  data: T;
  message?: string;
}
