<div class="row">

    <div class="col-md-4">

        <strong>Branch</strong><br>

        <span>{{ ucwords(str_replace('_', ' ', $record->Branch->branch_name ?? '')) }}</span>

    </div>

    <div class="col-md-4">

        <strong>Employee</strong><br>

        <span>{{ ucwords(str_replace('_', ' ', $record->Employee->basic_info['employee_name'] ?? '')) }}</span>

    </div>

    <div class="col-md-4">

        <strong>Status</strong><br>

        @if ($record->loan_status == 'completed')

            <span class="badge bg-label-success">{{ ucwords('Completed') }}</span>

        @elseif($record->loan_status == 'on_going')

            <span class="badge bg-label-info">{{ ucwords('On Going') }}</span>

        @else

            <span class="badge bg-label-warning">{{ ucwords($record->loan_status) }}</span>

        @endif

    </div>

</div>



<hr>

<div class="row">

    <div class="col-md-12">

        <table class="table table-sm">

            <tr>

                <th>Loan Amount</th>

                <td>{{ $record->loan['loan_amount'] ?? 0 }}</td>

            <tr>

            <tr>

                <th>Interest Rate(%)</th>

                <td>{{ $record->loan['interest_rate'] ?? 0 }}</td>

            <tr>

            <tr>

                <th>Interest Amount</th>

                <td>{{ $record->loan['interest_amount'] ?? 0 }}</td>

            <tr>

            <tr>

                <th>Total Amount With Interest</th>

                <td>{{ $record->loan['total_amount_with_interest'] ?? 0 }}</td>

            <tr>

            <tr>

                <th>Max Tenure <small>(Month)</small></th>

                <td>{{ $record->loan['max_tenure'] ?? 0 }}</td>

            <tr>

            <tr>

                <th>EMI Amount</th>

                <td>{{ $record->loan['emi_amount'] ?? 0 }} Month , <b>Last Month

                        EMI-</b>{{ $record->loan['last_emi'] ?? '' }}</td>

            <tr>

            <tr>

                <th>Comment</th>

                <td>{{ $record->comment ?? '' }}</td>

            <tr>

            <tr>

                <th>Remaning Loan Amount</th>

                <td>{{ $record->remaning_loan_amount ?? '' }}</td>

            <tr>

            <tr>

                <th>EMI LEFT</th>

                <td>{{ $record->loan['max_tenure'] - $record->emi_count }}</td>

            <tr>



        </table>



        @if (!empty($record->exist_emi))

            <h6>EMI History</h6>

            <table class="table table-sm">

                <tr>

                    <th>#</th>

                    <th>EMI Amount</th>

                    <th>Date</th>

                </tr>

                @foreach ($record->exist_emi as $key => $emi)

                    <tr>

                        <td>{{ ++$key }}</td>

                        <td>{{ $emi['emi_amount'] ?? 0 }}</td>

                        <td>{{ date('d M,Y', $record->date) }}</td>

                    </tr>

                @endforeach

            </table>

        @endif

    </div>

</div>

