<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default QrCode Writer
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default writer that should be used when generating
    | QR codes. The following writers are supported: "eps", "png", "svg".
    |
    */

    'writer' => 'png',

    /*
    |--------------------------------------------------------------------------
    | Writer-specific Options
    |--------------------------------------------------------------------------
    |
    | The following array holds the options for each writer.
    |
    */

    'writer_options' => [
        'eps' => [
            'color' => [
                'r' => 0,
                'g' => 0,
                'b' => 0,
            ],
            'background_color' => [
                'r' => 255,
                'g' => 255,
                'b' => 255,
            ],
        ],
        'png' => [
            'quality' => 90,
        ],
        'svg' => [
            'color' => '#000000',
            'background_color' => '#ffffff',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Error Correction Level
    |--------------------------------------------------------------------------
    |
    | Here you may specify the error correction level that should be used when
    | generating QR codes. The following levels are supported: "L", "M", "Q", "H".
    |
    */

    'error_correction' => 'M',

    /*
    |--------------------------------------------------------------------------
    | Encoding
    |--------------------------------------------------------------------------
    |
    | Here you may specify the encoding that should be used when generating
    | QR codes. The following encodings are supported: "UTF-8", "ISO-8859-1".
    |
    */

    'encoding' => 'UTF-8',

    /*
    |--------------------------------------------------------------------------
    | QR Code Size
    |--------------------------------------------------------------------------
    |
    | Here you may specify the size of the QR code that should be generated.
    |
    */

    'size' => 300,

    /*
    |--------------------------------------------------------------------------
    | QR Code Margin
    |--------------------------------------------------------------------------
    |
    | Here you may specify the margin that should be used when generating
    | QR codes.
    |
    */

    'margin' => 10,

    /*
    |--------------------------------------------------------------------------
    | QR Code Logo
    |--------------------------------------------------------------------------
    |
    | Here you may specify the logo that should be used when generating
    | QR codes. The logo should be a path to an image file.
    |
    */

    'logo' => false,

    /*
    |--------------------------------------------------------------------------
    | QR Code Logo Size
    |--------------------------------------------------------------------------
    |
    | Here you may specify the size of the logo that should be used when
    | generating QR codes.
    |
    */

    'logo_size' => null,

    /*
    |--------------------------------------------------------------------------
    | QR Code Style
    |--------------------------------------------------------------------------
    |
    | Here you may specify the style of the QR code that should be generated.
    | The following styles are supported: "square", "dot", "round".
    |
    */

    'style' => 'square',

    /*
    |--------------------------------------------------------------------------
    | QR Code Eye
    |--------------------------------------------------------------------------
    |
    | Here you may specify the eye style of the QR code that should be generated.
    | The following eye styles are supported: "square", "circle".
    |
    */

    'eye' => 'square',

    /*
    |--------------------------------------------------------------------------
    | QR Code Color
    |--------------------------------------------------------------------------
    |
    | Here you may specify the color of the QR code that should be generated.
    |
    */

    'color' => [
        'r' => 0,
        'g' => 0,
        'b' => 0,
    ],

    /*
    |--------------------------------------------------------------------------
    | QR Code Background Color
    |--------------------------------------------------------------------------
    |
    | Here you may specify the background color of the QR code that should be
    | generated.
    |
    */

    'background_color' => [
        'r' => 255,
        'g' => 255,
        'b' => 255,
    ],

    /*
    |--------------------------------------------------------------------------
    | QR Code Gradient
    |--------------------------------------------------------------------------
    |
    | Here you may specify the gradient of the QR code that should be generated.
    |
    */

    'gradient' => [
        'start_color' => [
            'r' => 0,
            'g' => 0,
            'b' => 0,
        ],
        'end_color' => [
            'r' => 0,
            'g' => 0,
            'b' => 0,
        ],
        'type' => 'linear',
    ],

    /*
    |--------------------------------------------------------------------------
    | QR Code Eye Color
    |--------------------------------------------------------------------------
    |
    | Here you may specify the eye color of the QR code that should be generated.
    |
    */

    'eye_color' => [
        0 => [
            'r' => 0,
            'g' => 0,
            'b' => 0,
        ],
        1 => [
            'r' => 0,
            'g' => 0,
            'b' => 0,
        ],
        2 => [
            'r' => 0,
            'g' => 0,
            'b' => 0,
        ],
    ],

];
