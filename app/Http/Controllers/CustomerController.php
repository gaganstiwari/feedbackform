<?php

namespace App\Http\Controllers;
use App\Services\SignedUrlService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;


class CustomerController extends Controller
{
    protected $signedUrlService;
    protected string $recordsEndpoint;

    public function __construct(SignedUrlService $signedUrlService)
    {
        $this->signedUrlService = $signedUrlService;
        $this->recordsEndpoint = config(
            'services.customer_records.endpoint',
            env('CUSTOMER_RECORDS_ENDPOINT', 'http://localhost:5000/records')
        );
    }

    public function index()
    {
        $records = $this->fetchCustomerRecords();

        return view('customer.index', ['records' => $records]);
    }

    public function getRecords()
    {
      
        return response()->json($this->fetchCustomerRecords());

    }

    protected function fetchCustomerRecords(): Collection
    {
        try {
            $response = Http::timeout(5)->get($this->recordsEndpoint);
        } catch (\Throwable $exception) {
            report($exception);
            return collect();
        }

        if ($response->failed()) {
            return collect();
        }

        $records = collect($response->json() ?? []);

        return $records->map(function (array $record) {
            $record['feedback_url'] = $this->signedUrlService->generateSignedUrl(
                $record['token_number'] ?? null,
                url('/feedback'),
                $record['request_id'] ?? null
            );

            return $record;
        });
    }
}

