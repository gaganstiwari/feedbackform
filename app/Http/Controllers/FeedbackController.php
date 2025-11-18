<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    // Normal form (if opened without signed link)
    public function index(Request $request)
    {
        // Get policy if exists in URL
        $policy = $request->policy ?? null;

        return view('feedbackform.feedback', compact('policy'));
    }

    // Save feedback
    public function store(Request $request)
    {
        Feedback::create([
            'token_number' => $request->policy,   // SAVE POLICY HERE
            'nps_score'    => $request->nps_score,
            'main_options' => $request->main_options,
            'sub_options'  => $request->sub_options,
            'comment'      => $request->comment,
        ]);

        return back()->with('success', 'Thank you for your feedback!');
    }

    // Signed URL handler
    public function handleLink(Request $request)
    {
        $policy = $request->policy;
        $exp    = $request->exp;
        $sig    = $request->sig;

        $secret = "my_super_secret_key";

        if (time() > $exp) {
            abort(403, "Link Expired");
        }

        $expected = hash_hmac("sha256", $policy . $exp, $secret);

        if (!hash_equals($expected, $sig)) {
            abort(403, "Invalid Signature");
        }

        // Send verified policy to blade
        return view('feedbackform.feedback', compact('policy'));
    }
}
