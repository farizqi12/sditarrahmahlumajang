<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Rate Limiter Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for rate limiting across the application.
    | You can customize the limits for different types of operations.
    |
    */

    'defaults' => [
        'attempts' => 60,
        'decay_minutes' => 1,
    ],

    'limits' => [
        // Authentication
        'login' => [
            'attempts' => 5,
            'decay_minutes' => 1,
        ],
        'logout' => [
            'attempts' => 30,
            'decay_minutes' => 1,
        ],

        // Dashboard
        'dashboard' => [
            'attempts' => 60,
            'decay_minutes' => 1,
        ],

        // Attendance
        'attendance_checkin' => [
            'attempts' => 30,
            'decay_minutes' => 1,
        ],
        'attendance_checkout' => [
            'attempts' => 30,
            'decay_minutes' => 1,
        ],
        'attendance_scan' => [
            'attempts' => 20,
            'decay_minutes' => 1,
        ],
        'attendance_view' => [
            'attempts' => 60,
            'decay_minutes' => 1,
        ],

        // User Management
        'user_crud' => [
            'attempts' => 30,
            'decay_minutes' => 1,
        ],
        'user_view' => [
            'attempts' => 60,
            'decay_minutes' => 1,
        ],

        // Course Management
        'course_crud' => [
            'attempts' => 30,
            'decay_minutes' => 1,
        ],
        'course_student_management' => [
            'attempts' => 20,
            'decay_minutes' => 1,
        ],
        'course_view' => [
            'attempts' => 60,
            'decay_minutes' => 1,
        ],

        // Wallet/Financial
        'wallet_transaction' => [
            'attempts' => 20,
            'decay_minutes' => 1,
        ],
        'wallet_view' => [
            'attempts' => 60,
            'decay_minutes' => 1,
        ],

        // Reports
        'reports_generate' => [
            'attempts' => 30,
            'decay_minutes' => 1,
        ],

        // Academic Year
        'academic_year' => [
            'attempts' => 10,
            'decay_minutes' => 1,
        ],

        // QR Code Generation
        'qr_generation' => [
            'attempts' => 10,
            'decay_minutes' => 1,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiter Response Messages
    |--------------------------------------------------------------------------
    |
    | Customize the error messages returned when rate limits are exceeded.
    |
    */
    'messages' => [
        'default' => 'Terlalu banyak request. Silakan coba lagi dalam :minutes menit.',
        'login' => 'Terlalu banyak percobaan login. Silakan coba lagi dalam :minutes menit.',
        'attendance' => 'Terlalu banyak request absensi. Silakan coba lagi dalam :minutes menit.',
        'transaction' => 'Terlalu banyak transaksi. Silakan coba lagi dalam :minutes menit.',
    ],
];
