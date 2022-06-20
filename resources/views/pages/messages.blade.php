@extends('app')

@section('content')
    <!-- Begin Page Content -->
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Messages</h1>
        <a href="{{ route('messages.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-download fa-sm text-white-50"></i> Reset</a>
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ route('messages.index') }}?status=pending" class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Pending
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ route('messages.index') }}?status=sent" class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Sent
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['sent'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ route('messages.index') }}?status=delivered" class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Delivered
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['delivered'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Pending Requests Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ route('messages.index') }}?status=sending" class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Sending
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['sending'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <!-- Page Heading -->

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Messages</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Provider</th>
                        <th>From</th>
                        <th>Receiver Mobile</th>
                        <th>Body</th>
                        <th>Status</th>
                        <th>Sent At</th>
                        <th>Ref Code</th>
                        <th>Error</th>
                        <th>Created At</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>Provider</th>
                        <th>From</th>
                        <th>Receiver Mobile</th>
                        <th>Body</th>
                        <th>Status</th>
                        <th>Sent At</th>
                        <th>Ref Code</th>
                        <th>Error</th>
                        <th>Created At</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    @foreach($messages as $message)
                        <tr>
                            <td>{{ $message->Provider->name }}</td>
                            <td>{{ $message->from }}</td>
                            <td>{{ $message->receiver_mobile }}</td>
                            <td>{{ substr($message->body,0,50) . " .." }}</td>
                            <td>{{ $message->status->value }}</td>
                            <td>{{ $message->sent_at }}</td>
                            <td>{{ $message->ref_code }}</td>
                            <td>{{ $message->err_msg }}</td>
                            <td>{{ $message->created_at }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {!! $messages->links() !!}
            </div>
        </div>
    </div>

    <!-- /.container-fluid -->
@endsection
