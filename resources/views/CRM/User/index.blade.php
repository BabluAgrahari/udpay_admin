@extends('CRM.Layout.layout')
@section('content')

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header pt-3 pb-3">
                <div class="row">
                    <div class="col-md-4">
                        <h5 class="mb-0">User List</h5>
                    </div>

                    <div class="col-md-8 ml-auto">
                        <div class="d-flex justify-content-end">
                            @if (!empty($filter) || !empty($filter['status']))
                                <a href="javascript:void(0);" class="btn btn-sm btn-outline-primary btn-sm mr-c"
                                    id="filter-btn"><i class="far fa-times-circle"></i>&nbsp;Close</a>
                            @else
                                <a href="javascript:void(0);" class="btn btn-sm btn-outline-primary mr-c" id="filter-btn"><i
                                        class="fas fa-filter"></i>&nbsp;Filter</a>
                            @endif
                            <a href="{{ url('crm/user/create') }}" class=" btn-sm btn btn-outline-primary"><i
                                    class='bx bxs-plus-circle'></i>&nbsp;Create User</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <!-- @include('CRM.User.filter') -->

                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                @can('isSuperAdmin')

                                @endcan
                                <th>Name</th>
                                <th>Email</th>
                                <th>Gender</th>
                                <th scope="col">Address</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <!-- <th>Action</th> -->
                            </tr>
                        </thead>

                        <tbody>
                            @if ($records->isNotEmpty())
                                @foreach ($records as $key => $record)
                                    <tr>
                                        <td scope="row">{{ ++$key }}</td>

                                        <td>{{ ucwords($record->name ?? '') }}</td>
                                        <td>{{ $record->email ?? '' }}
                                            <br>
                                            {{$record->mobile_no ?? ''}}
                                        </td>
                                        <td>{{ ucwords($record->gender) }}
                                            <br>
                                            {{ $record->dFormat($record->dob) }}
                                        </td>
                                        <td>{{ $record->address ?? '' }}
                                            <br>
                                            {{ $record->city ?? '' }}
                                            <br>
                                            {{ $record->state ?? '' }}
                                            <br>
                                            {{ $record->pincode ?? '' }}
                                        </td>
                                        <td>{!! status($record->status) !!}</td>
                                        <td>{{ $record->dFormat($record->created) }}</td>
                                        <td>
                                            <!-- <a href="javascript:void(0);" _id="{{ $record->_id }}" class="view text-info"
                                                                                                                                    data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="bottom"
                                                                                                                                    data-bs-html="true" title="View"><i class='bx bx-show-alt'></i></a> -->

                                            <!-- <a href="{{ url('crm/user') }}/{{ $record->_id }}/edit" class="text-primary"
                                                data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="bottom"
                                                data-bs-html="true" title="Edit"><i class='bx bxs-edit'></i></a> -->

                                            <!--  <a href="{{ url('crm/lead-remove') }}/{{ $record->_id }}" class="text-danger"><i class="fa-solid fa-trash-can"></i></a> -->
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="9" class="text-center">
                                        <i class="fa fa-exclamation-circle" style="font-size: 24px; color: #999;"></i>
                                        <p>No records found</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                {{ $records->appends($_GET)->links() }}
            </div>
        </div>
    </div>

    @push('modal')
        <div class="modal fade" id="salarySetupModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
            aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered" role="document">

                <div class="modal-content">

                    <div class="modal-header">

                        <h5 class="modal-title" id="modalToggleLabel">Loan Details</h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                    </div>

                    <div class="modal-body" id="tbody">

                    </div>

                    <div class="modal-footer">

                    </div>

                </div>

            </div>

        </div>
    @endpush

    @push('script')

        <script>

            $(document).ready(function () {
                $(document).on('click', '.view', function () {
                    var id = $(this).attr('_id');
                    var url = "{{ url('crm/loan') }}/" + id;
                    var selector = $(this);
                    $.ajax({
                        url: url,
                        dataType: 'JSON',
                        type: 'GET',
                        beforeSend: function () {
                            selector.html(
                                `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`
                            ).attr('disabled', true);
                        },
                        success: function (res) {
                            selector.html(`<i class='bx bx-show-alt'></i>`).removeAttr('disabled');
                            if (res.status) {
                                $('#tbody').html(res.html);
                                $('#salarySetupModal').modal('show');
                            } else {
                                Swal.fire(
                                    `${res.status ? 'Success' : 'Error'}!`,
                                    res.msg,
                                    `${res.status ? 'success' : "error"}`,
                                );
                            }
                        }
                    })
                })
            })
        </script>
    @endpush
@endsection