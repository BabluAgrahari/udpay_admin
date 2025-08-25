@extends('Website.Layout.app')
@section('content')
    <style>
        .table-light th {
            color: #000 !important;
            font-size: 14px;
            white-space: nowrap;
        }

        thead.table-light tr th {
            background: var(--thm-color-two);
        }
    </style>
    <section class="py-5">
        <div class="container">
            <div class="tab-panel active edit-form-open" id="user-level-list">

                <div class="row mb-3">
                    <div class="col-md-2">
                        <a href="{{ url('distributor/payout-generation') }}" class="tab-btn">Payout Generation</a>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ url('distributor/royalty-payout') }}" class="tab-btn">Royalty Payout</a>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ url('distributor/payout-slip') }}" class="tab-btn active">Payout Slip</a>
                    </div>
                </div>

                <div class="tab-content m-0">
                    <div class="tab-content-body">

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Month & Year</th>
                                        <th>Total Amount</th>
                                        {{-- <th>Action</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $I = 1; ?>
                                    @foreach ($payout_slip as $index => $item)
                                    <tr>
                                        <td>{{ $I }}</td>
                                        <td>{{ date('F Y', strtotime($index)) }}</td>
                                        <td>{{ number_format($item, 2) }}</td>
                                        {{-- <td>
                                            <a href="{{ url('distributor/payout-slip/1') }}" class="btn btn-primary">View</a>
                                        </td> --}}
                                    </tr>
                                    <?php $I++; ?>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
