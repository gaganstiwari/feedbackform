@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <h3 class="mb-3">Feedback List</h3>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Token Number</th>
                <th>Rate</th>
                <th>Comment</th>
                <th>Remark</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($feedbacks as $feedback)
                <tr>
                    <td>{{ $feedback->id }}</td>
                    <td>{{ $feedback->token_number }}</td>
                    <td>{{ $feedback->rate }}</td>
                    <td>{{ $feedback->comment }}</td>
                    <td>{{ $feedback->remark }}</td>
                    <td>{{ $feedback->created_at->format('d-m-Y h:i A') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">No feedback found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div>
        {{ $feedbacks->links() }}
    </div>

</div>
@endsection
