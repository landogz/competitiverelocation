<x-mail::message>
# @lang('Reset Password Notification')

@lang('Hello!')

@lang('You are receiving this email because we received a password reset request for your account.')

<x-mail::button :url="$actionUrl" color="primary">
@lang('Reset Password')
</x-mail::button>

@lang('This password reset link will expire in :count minutes.', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')])

@lang('If you did not request a password reset, no further action is required.')

@lang('Regards'),<br>
Competitive Relocation

<x-slot:subcopy>
@lang(
    "If you're having trouble clicking the \":actionText\" button, copy and paste the URL below\n".
    'into your web browser:',
    [
        'actionText' => __('Reset Password'),
    ]
) <span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
</x-slot:subcopy>
</x-mail::message> 