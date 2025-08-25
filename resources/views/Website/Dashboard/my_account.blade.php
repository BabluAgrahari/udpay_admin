@extends('Website.Layout.app')
@section('content')
    <section class="section-padding py-5 pt-3">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="dashboard">
                        <!-- Sidebar -->
                        <div class="sidebar">
                            <a href="{{ url('my-account') }}"
                                class="tab-btn {{ request()->is('my-account') ? 'active' : '' }}"><i
                                    class="fa-solid fa-user"></i> My Account</a>
                            <a href="{{ url('order-history') }}"
                                class="tab-btn {{ request()->is('order-history') ? 'active' : '' }}"><i
                                    class="fa-solid fa-box"></i> Order History</a>
                            <a href="{{ url('address-book') }}"
                                class="tab-btn {{ request()->is('address-book') ? 'active' : '' }}"><i
                                    class="fa-solid fa-book"></i> Address Book</a>
                            <a href="{{ url('wishlist') }}"
                                class="tab-btn {{ request()->is('wishlist') ? 'active' : '' }}"><i
                                    class="fa-solid fa-heart"></i> My Wishlist</a>
                            <a href="{{ url('logout') }}" class="tab-btn logout"><i
                                    class="fa-solid fa-right-from-bracket"></i> Logout</a>
                        </div>
                        <!-- Content -->
                        <div class="tab-content">
                            <div class="tab-panel active edit-form-open" id="account">
                                <h3 class="tab-title account-top">My Account <button class="edit-link editBtn"><i
                                            class="fa-solid fa-pen-to-square"></i> Edit</button></h3>
                                <div class="tab-content-body">
                                    <div class="">
                                        <div class="d-flex gap-3 ">
                                            <div class="">
                                                <h3 class="color-one mb-0 user-sort">
                                                    {{ ucfirst($user->name[0] ?? '') }}{{ ucfirst($user->name[1] ?? '') }}</h3>
                                            </div>
                                            <div class="user-detail">
                                                <h5 class="mb-2">{{ucwords($user->name) }}</h5>
                                                <p class="mb-2">{{ $user->email }}</p>
                                                <p class="mb-2">{{ ucfirst($user->gender) }}</p>
                                                <p class="mb-2">{{ $user->mobile }}</p>
                                            </div>
                                        </div>
                                        <div class="edit-form-box" style="display: none;">
                                            <form action="{{ url('save-profile') }}" method="post">
                                                @csrf
                                                <div class="row mb-3">
                                                    <div class="col-sm-6 form-group">
                                                        <label>Name <span class="color-red">*</span></label>
                                                        <input type="text" class="form-control" name="name"
                                                            placeholder="Enter name" value="{{ $user->name }}">
                                                        <span class="text-danger error" id="name_error"></span>
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <label>Email <span class="color-red">*</span></label>
                                                        <input type="email" class="form-control" name="email"
                                                            placeholder="Enter email" value="{{ $user->email }}">
                                                        <span class="text-danger error" id="email_error"></span>
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <label>Gender <span class="color-red">*</span></label>
                                                        <select class="form-control" name="gender">
                                                            <option value="male"
                                                                {{ $user->gender == 'male' ? 'selected' : '' }}>Male
                                                            </option>
                                                            <option value="female"
                                                                {{ $user->gender == 'female' ? 'selected' : '' }}>Female
                                                            </option>
                                                            <option value="other"
                                                                {{ $user->gender == 'other' ? 'selected' : '' }}>Other
                                                            </option>
                                                        </select>
                                                        <span class="text-danger error" id="gender_error"></span>
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <label>Mobile No <span class="color-red">*</span></label>
                                                        <input type="text" class="form-control" name="mobile"
                                                            placeholder="Enter phone" value="{{ $user->mobile }}" readonly>
                                                        <span class="text-danger error" id="mobile_error"></span>
                                                    </div>
                                                </div>
                                                <button type="button" class="thm-btn" id="saveBtn">Update
                                                    Profile</button>
                                                <button type="button" class="thm-btn bg-light mx-3 editBtn">Cancel</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@push('scripts')
    <script>
        //convert into jquery
        $(document).ready(function() {
            $(".editBtn").click(function(e) {
                e.preventDefault();
                $('.edit-form-box').toggle();
            });
        });

        $("#saveBtn").click(function(e) {
            e.preventDefault();
            const formData = $(this).closest("form").serialize();
            $.ajax({
                url: "{{ url('save-profile') }}",
                type: "POST",
                data: formData,
                beforeSend: function() {
                    $("#saveBtn").html('<i class="fa-solid fa-spinner fa-spin"></i> Updating...').prop(
                        "disabled", true);
                    $("#cancelBtn").prop("disabled", true);
                },
                success: function(response) {
                    if (response.status) {
                        $("#saveBtn").html('Update Profile').prop("disabled", false);
                        $("#cancelBtn").prop("disabled", false);
                    }
                    showSnackbar(response.msg, 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                },
                error: function(xhr, status, error) {
                    $("#saveBtn").html('Update Profile').prop("disabled", false);
                    $("#cancelBtn").prop("disabled", false);
                    var errors = xhr.responseJSON.validation;
                    $(".error").html('');
                    $.each(errors, function(field, messages) {
                       $(`#${field}_error`).html(messages[0]);
                    });
                    showSnackbar(xhr.responseJSON.msg, 'error');
                }
            });
        });
    </script>
    <script>
        //convert into jquery
        document.querySelectorAll(".tab-btn").forEach(button => {
            button.addEventListener("click", () => {
                const tab = button.getAttribute("data-tab");

                // Remove active from all buttons and tabs
                document.querySelectorAll(".tab-btn").forEach(btn => btn.classList.remove("active"));
                document.querySelectorAll(".tab-panel").forEach(panel => panel.classList.remove("active"));

                // Add active to clicked button and corresponding panel
                button.classList.add("active");
                if (tab) {
                    document.getElementById(tab).classList.add("active");
                }
            });
        });
    </script>
    <script>
        const allOptionGroups = document.querySelectorAll('.weight-flower .options');

        allOptionGroups.forEach(group => {
            group.addEventListener('click', function(e) {
                if (e.target.classList.contains('option')) {
                    // Remove 'active' from all buttons in this group
                    group.querySelectorAll('.option').forEach(btn => btn.classList.remove('active'));
                    // Add 'active' to the clicked button
                    e.target.classList.add('active');
                }
            });
        });
    </script>
@endpush
