<?php

return [
    'user' => [
        'login_success'    => 'User logged in successfully',
        'register_success' => 'User registered successfully',
        'logout_success'   => 'User logged out successfully',
        'phone_verified_success' => 'Phone verified successfully',
        'profile_update_success' => 'Profile updated successfully',
        'not_found'        => 'User not found',
        'not_active'       => 'Your account is not activated, please check your email',
    ],
    'auth' => [
        'unauthenticated'     => 'Unauthenticated',
        'forbidden'           => 'You do not have permission to access this resource',
        'invalid_credentials' => 'Invalid credentials',
        'invalid_verification_code' => 'Invalid verification code',
        'otp_resent_success' => 'Verification code resent successfully.',
        'otp_cooldown_error' => 'Not enough time has passed to resend the verification code. Please try again later.',
        'phone_not_verified' => 'Phone number must be verified before completing registration.',
        'register_step_one_success' => 'Information saved and verification code sent successfully.',
    ],
    'general' => [
        'server_error'     => 'An unexpected error occurred. Please try again later.',
        'validation_error' => 'The given data was invalid.',
    ],
    'car_not_found' => 'Car not found',
    'days' => [
        'sunday'    => 'Sunday',
        'monday'    => 'Monday',
        'tuesday'   => 'Tuesday',
        'wednesday' => 'Wednesday',
        'thursday'  => 'Thursday',
        'friday'    => 'Friday',
        'saturday'  => 'Saturday',
    ],
    'notification' => [
        'not_found'             => 'Notification not found',
        'delete_success'        => 'Notification deleted successfully',
        'token_saved_success'   => 'Token saved successfully',
        'token_deleted_success' => 'Token deleted successfully',
    ],
    'contact' => [
        'sent_success' => 'Your message has been sent successfully, we will contact you soon.',
    ],
];
