import { Component, computed, inject, OnInit, signal } from '@angular/core';
import { FormBuilder, ReactiveFormsModule, Validators } from '@angular/forms';
import { forkJoin, finalize } from 'rxjs';
import { environment } from '../../../environments/environment';
import { Selekcija, Trener, TreningPayload } from '../../models/api.models';
import { apiMessage, validationErrors, ValidationErrors } from '../../shared/api-errors';
import { AuthService } from '../../services/auth.service';
import { TreningService } from '../../services/trening.service';

@Component({
  selector: 'app-trening-form',
  imports: [ReactiveFormsModule],
  templateUrl: './trening-form.component.html',
})
export class TreningFormComponent implements OnInit {
  private readonly fb = inject(FormBuilder);
  private readonly service = inject(TreningService);
  private readonly auth = inject(AuthService);

  readonly form = this.fb.nonNullable.group({
    datum: ['', Validators.required],
    vreme: ['', Validators.required],
    lokacija: ['', [Validators.required, Validators.maxLength(100)]],
    selekcija_id: [0, [Validators.required, Validators.min(1)]],
    trener_id: [0, [Validators.required, Validators.min(1)]],
  });

  readonly selections = signal<Selekcija[]>([]);
  readonly coaches = signal<Trener[]>([]);
  readonly selectedSelectionId = signal(0);
  readonly filteredCoaches = computed(() =>
    this.coaches().filter((coach) => coach.selekcija_id === this.selectedSelectionId()),
  );

  readonly minDate = new Date().toISOString().slice(0, 10);
  serverErrors: ValidationErrors = {};
  message = '';
  loading = true;
  submitting = false;

  trainingListUrl(): string {
    const path = this.auth.user()?.role === 'trener'
      ? '/trener/treninzi'
      : '/admin/treninzi';

    return `${environment.webBaseUrl}${path}`;
  }

  ngOnInit(): void {
    if (!this.auth.canManageTrainings()) {
      window.location.href = `${environment.webBaseUrl}/dashboard`;
      return;
    }

    this.form.controls.selekcija_id.valueChanges.subscribe((id) => {
      this.selectedSelectionId.set(Number(id));
      const selectedCoach = this.coaches().find(
        (coach) => coach.id === Number(this.form.controls.trener_id.value),
      );
      if (selectedCoach?.selekcija_id !== Number(id)) {
        this.form.controls.trener_id.setValue(0);
      }
    });

    const requests = {
      selections: this.service.selections(),
      coaches: this.service.coaches(),
    };

    forkJoin(requests)
      .pipe(finalize(() => (this.loading = false)))
      .subscribe({
        next: (response) => {
          this.selections.set(response.selections.data);
          this.coaches.set(response.coaches.data);
        },
        error: (error) => (this.message = apiMessage(error, 'Podaci za formu nisu učitani.')),
      });
  }

  submit(): void {
    this.form.markAllAsTouched();
    this.serverErrors = {};
    this.message = '';

    if (this.form.invalid) return;

    this.submitting = true;
    const payload = this.form.getRawValue() as TreningPayload;
    const request = this.service.create(payload);

    request.pipe(finalize(() => (this.submitting = false))).subscribe({
      next: () => {
        window.location.href =
          this.trainingListUrl();
      },
      error: (error) => {
        this.serverErrors = validationErrors(error);
        this.message = apiMessage(error, 'Neuspešno čuvanje treninga.');
      },
    });
  }
}
