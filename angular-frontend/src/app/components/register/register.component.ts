import { Component, inject } from '@angular/core';
import { AbstractControl, FormBuilder, ReactiveFormsModule, ValidationErrors, Validators } from '@angular/forms';
import { RouterLink } from '@angular/router';
import { finalize } from 'rxjs';
import { apiMessage, validationErrors, ValidationErrors as ApiValidationErrors } from '../../shared/api-errors';
import { AuthService } from '../../services/auth.service';

function passwordsMatch(control: AbstractControl): ValidationErrors | null {
  return control.get('password')?.value === control.get('password_confirmation')?.value
    ? null
    : { passwordsMismatch: true };
}

@Component({
  selector: 'app-register',
  imports: [ReactiveFormsModule, RouterLink],
  templateUrl: './register.component.html',
})
export class RegisterComponent {
  private readonly fb = inject(FormBuilder);
  private readonly auth = inject(AuthService);

  readonly form = this.fb.nonNullable.group(
    {
      name: ['', [Validators.required, Validators.maxLength(255)]],
      email: ['', [Validators.required, Validators.email]],
      password: ['', [Validators.required, Validators.minLength(8)]],
      password_confirmation: ['', Validators.required],
      ime: ['', Validators.required],
      prezime: ['', Validators.required],
      datum_rodjenja: ['', Validators.required],
      telefon_roditelja: ['', Validators.required],
    },
    { validators: passwordsMatch },
  );

  serverErrors: ApiValidationErrors = {};
  message = '';
  submitting = false;

  submit(): void {
    this.form.markAllAsTouched();
    this.serverErrors = {};
    this.message = '';

    if (this.form.invalid) return;

    this.submitting = true;
    this.auth
      .register(this.form.getRawValue())
      .pipe(finalize(() => (this.submitting = false)))
      .subscribe({
        next: (response) => {
          window.location.href = response.redirect_url;
        },
        error: (error) => {
          this.serverErrors = validationErrors(error);
          this.message = apiMessage(error, 'Registracija nije uspela.');
        },
      });
  }
}
