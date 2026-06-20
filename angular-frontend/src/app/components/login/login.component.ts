import { Component, inject, OnInit } from '@angular/core';
import { FormBuilder, ReactiveFormsModule, Validators } from '@angular/forms';
import { ActivatedRoute, Router, RouterLink } from '@angular/router';
import { finalize } from 'rxjs';
import { apiMessage, validationErrors, ValidationErrors } from '../../shared/api-errors';
import { AuthService } from '../../services/auth.service';

@Component({
  selector: 'app-login',
  imports: [ReactiveFormsModule, RouterLink],
  templateUrl: './login.component.html',
})
export class LoginComponent implements OnInit {
  private readonly fb = inject(FormBuilder);
  private readonly auth = inject(AuthService);
  private readonly route = inject(ActivatedRoute);
  private readonly router = inject(Router);

  readonly form = this.fb.nonNullable.group({
    email: ['', [Validators.required, Validators.email]],
    password: ['', Validators.required],
  });

  serverErrors: ValidationErrors = {};
  message = '';
  submitting = false;

  ngOnInit(): void {
    if (this.route.snapshot.queryParamMap.get('logout') === '1') {
      this.auth.clearSession();
      this.router.navigate(['/login'], { replaceUrl: true });
    }
  }

  submit(): void {
    this.form.markAllAsTouched();
    this.serverErrors = {};
    this.message = '';

    if (this.form.invalid) return;

    this.submitting = true;
    this.auth
      .login(this.form.getRawValue())
      .pipe(finalize(() => (this.submitting = false)))
      .subscribe({
        next: (response) => {
          window.location.href = response.redirect_url;
        },
        error: (error) => {
          this.serverErrors = validationErrors(error);
          this.message = apiMessage(error, 'Prijava nije uspela.');
        },
      });
  }
}
