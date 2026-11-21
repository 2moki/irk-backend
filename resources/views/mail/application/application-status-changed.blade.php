<x-mail::message>
# {{ __('mail.application_status_changed.greeting', ['name' => $candidateName]) }}

{{ __('mail.application_status_changed.intro') }}

| | |
|---|---|
| **{{ __('mail.application_status_changed.major') }}** | {{ $majorName }} |
| **{{ __('mail.application_status_changed.academic_year') }}** | {{ $academicYear }}/{{ $academicYear + 1 }} |
| **{{ __('mail.application_status_changed.previous_status') }}** | {{ $previousStatus }} |
| **{{ __('mail.application_status_changed.new_status') }}** | **{{ $newStatus }}** |

<x-mail::button :url="$detailsUrl">
{{ __('mail.application_status_changed.button') }}
</x-mail::button>

{{ __('mail.application_status_changed.footer') }}

{{ __('Regards,') }}<br>
{{ config('app.name') }}
</x-mail::message>
