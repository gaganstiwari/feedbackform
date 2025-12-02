<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;

class FeedbackLinkController extends Controller
{
    public function generate()
    {
        // Generate random 10-digit token
        $token = str_pad(random_int(0, 9999999999), 10, '0', STR_PAD_LEFT);

        // Generate signed URL (valid for 10 minutes)
        $signedUrl = URL::temporarySignedRoute(
            'feedback.form',         // route name
            now()->addMinutes(10),   // expiry time
            ['token' => $token]      // send token in URL
        );

        return view('/feedback', compact('token', 'signedUrl'));
    }
}

