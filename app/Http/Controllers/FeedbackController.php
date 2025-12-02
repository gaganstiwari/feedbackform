<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;

use App\Services\SignedUrlService;
use Illuminate\Support\Facades\Log;

class FeedbackController extends Controller
{
    protected $signedUrlService;

    public function __construct(SignedUrlService $signedUrlService)
    {
        $this->signedUrlService = $signedUrlService;
    }

    // LOAD FEEDBACK FORM (with or without signed URL)
    public function index(Request $request)
    {
        $tokenNumber = null;
        $requestid = null;

        // Check for signed URL parameters
        if ($request->has('token') && $request->has('sig')) {
            $result = $this->signedUrlService->validateAndExtractToken(
                $request->get('token'),
                $request->get('sig')
            );

            if ($result['valid']) {
                $tokenNumber = $result['token_number'];
                $requestid = $result['requestid'] ?? $tokenNumber; // Use requestid from payload or fallback to token_number
            } else {
                return redirect()->route('feedback.form')
                    ->with('error', 'Invalid or expired feedback link. ' . ($result['error'] ?? ''));
            }
        }

        // Check for direct requestid parameter (fallback for non-signed URLs)
        if ($request->has('requestid') && !$requestid) {
            $requestid = $request->get('requestid');
        }

      
        
        return view('feedbackform.feedback', [
            'requestid' => $requestid,
            'token_number' => $tokenNumber,
           
        ]);
    }

    // HANDLE FEEDBACK LINK (legacy support)
    public function handleLink($requestid, $token = null)
    {
        return redirect()->route('feedback.form', ['requestid' => $requestid]);
    }

    // GENERATE SIGNED URL (API endpoint)
    public function generateLink(Request $request)
    {
        $request->validate([
            'token_number' => 'nullable|string',
            'requestid' => 'nullable|string',
        ]);

        $tokenNumber = $request->get('token_number');
        $requestid = $request->get('requestid');
        
        // If only requestid provided, use it as token_number
        if ($requestid && !$tokenNumber) {
            $tokenNumber = $requestid;
        }

        $url = $this->signedUrlService->generateSignedUrl($tokenNumber, null, $requestid);

        return response()->json([
            'success' => true,
            'url' => $url,
            'token_number' => $tokenNumber,
            'requestid' => $requestid,
            'expires_in' => config('signed_url.ttl', 600) . ' seconds'
        ]);
    }

    // STORE FEEDBACK (legacy - not used with Livewire)
    public function store(Request $request)
    {
        $request->validate([
            'token_number' => 'nullable|string',
            'requestid' => 'nullable|string',
            'nps_score' => 'required|integer|min:0|max:10',
        ]);

        Feedback::create([
            'token_number' => $request->token_number,
            'requestid' => $request->requestid,
            'nps_score' => $request->nps_score,
            'main_options' => $request->main_options ?? [],
            'sub_options' => $request->sub_options ?? [],
            'comment' => $request->comment,
            'remark' => $request->remark ?? null,
            'status' => 'submitted',
            'case_status' => $request->null,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Feedback saved successfully!');
    }
}
