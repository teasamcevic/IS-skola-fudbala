import { HttpErrorResponse } from '@angular/common/http';

export type ValidationErrors = Record<string, string[] | undefined>;

export function validationErrors(error: unknown): ValidationErrors {
  if (error instanceof HttpErrorResponse && error.status === 422) {
    return (error.error?.errors ?? {}) as ValidationErrors;
  }

  return {};
}

export function apiMessage(error: unknown, fallback: string): string {
  if (error instanceof HttpErrorResponse) {
    return error.error?.message ?? fallback;
  }

  return fallback;
}
