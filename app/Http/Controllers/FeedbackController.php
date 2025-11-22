<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;
use App\Models\Customer; // Assuming your customer table is called 'Caller'

class FeedbackController extends Controller
{
    // LOAD FEEDBACK FORM (no requestid)
    public function index()
    {
        $customers = Customer::all(); // fetch all callers
        return view('feedbackform.feedback', [
            'requestid' => null,
            'customers' => $customers,
        ]);
    }

    // HANDLE FEEDBACK LINK
    public function handleLink($requestid, $token=null)
    {
        $customer = Customer::where('requestid', $requestid)->first();

        return view('feedbackform.feedback', [
            'requestid' => $requestid,
            'customers' => $customer ? [$customer] : [],
        ]);
    }

    // STORE FEEDBACK
    public function store(Request $request)
    {
        $request->validate([
            'requestid' => 'required|string',
            'nps_score' => 'required|integer',
        ]);

        Feedback::create([
            'requestid'     => $request->requestid,
            'nps_score'     => $request->nps_score,
            'main_options'  => json_encode($request->main_options ?? []),
            'sub_options'   => json_encode($request->sub_options ?? []),
            'comment'       => $request->comment,
            'remark'        => $request->remark ?? null,
            'status'        => 'submitted',
        ]);

        return redirect()
            ->back()
            ->with('success', 'Feedback saved successfully!');
    }
}
