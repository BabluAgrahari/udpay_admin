@include('CRM.Layout.header')
<style>
    .bg-label-success {
        background-color: #e8fadf !important;
        color: #4ba41b !important;
    }
    /* Global Select2 Styling */
    .select2-container--bootstrap-5 .select2-selection {
        min-height: 38px;
        border: 1px solid #d9dee3;
        border-radius: 0.375rem;
    }
    .select2-container--bootstrap-5 .select2-selection--single {
        padding: 0.375rem 0.75rem;
    }
    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__rendered {
        padding: 0.375rem 0.75rem;
    }
    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice {
        background-color: #696cff;
        border: none;
        color: #fff;
        border-radius: 0.25rem;
        padding: 0.25rem 0.5rem;
        margin: 0.25rem;
    }
    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove {
        color: #fff;
        margin-right: 0.5rem;
    }
    .select2-container--bootstrap-5 .select2-dropdown {
        border: 1px solid #d9dee3;
        border-radius: 0.375rem;
        box-shadow: 0 0.125rem 0.25rem rgba(161, 172, 184, 0.45);
    }
    .select2-container--bootstrap-5 .select2-results__option--highlighted {
        background-color: #696cff;
    }
    .select2-container--bootstrap-5 .select2-search__field {
        padding: 0.375rem 0.75rem;
        border: 1px solid #d9dee3;
        border-radius: 0.375rem;
    }
</style>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->

            @include('CRM.Layout.sidebar')
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page" style="padding-left: 12.15rem !important;">
                <!-- Navbar -->

                @include('CRM.Layout.navbar')

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">

                    <!-- Content -->

                    @yield('content')
                    <!-- / Content -->

                    <!-- Footer -->
                    @include('CRM.Layout.footer')
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- build:js assets/vendor/js/core.js -->

    <script src="{{asset('')}}assets/vendor/libs/jquery/jquery.js"></script>
    <script src="{{asset('')}}assets/vendor/libs/popper/popper.js"></script>
    <script src="{{asset('')}}assets/vendor/js/bootstrap.js"></script>
    <script src="{{asset('')}}assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="{{asset('')}}assets/vendor/js/menu.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{asset('')}}assets/vendor/libs/apex-charts/apexcharts.js"></script>

    <!-- Main JS -->
    <script src="{{asset('')}}assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="{{asset('')}}assets/js/dashboards-analytics.js"></script>
    <script src="{{asset('')}}assets/js/ui-popover.js"></script>

    <!-- Place this tag before closing body tag for github widget button. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <script>
        $('[data-toggle="tooltip"]').tooltip();

        //filter open and close
        $('#filter-btn').click(function() {
            $('#filter').toggle();
            if ($(this).text().trim() === "Filter") {
                $(this).html('<i class="far fa-times-circle"></i>&nbsp;Close');
            } else if ($(this).text().trim() === 'Close') {
                $(this).html('<i class="fas fa-filter"></i>&nbsp;Filter');
            }
        })

        $(document).ready(function() {
            $('.select2').select2()
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })
            $('[data-toggle="tooltip"]').tooltip();
        });

        //popover
        $(document).ready(function() {
            $('[data-toggle="popover"]').popover();
        });

        //Date range as a button
        $('#daterange-btn').daterangepicker({
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'All Time': ['01/01/2023', moment()],
                },
                // startDate: moment().subtract(365, 'days'),
                // endDate: moment()
            },
            function(start, end) {
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
            }
        )



        //show uploaded file name in input field

        $(document).on('change', 'input[type=file]', function() {

            var fileName = this.files[0].name;

            $(this).parent().find('label').html(fileName);

        })

        /*start single image preview*/

        function removeRecord(tr, url) {

            if (confirm('Are you sure, you want to remove it.')) {
                $.ajax({
                    url: url,
                    type: 'post',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        _method: 'delete'
                    },

                    success: function(res) {
                        if (res.status) {
                            tr.fadeOut('slow', () => {
                                $(tr).remove();
                            })
                        }
                        alertMsg(res.status, res.msg, 2000, true);
                    }
                });

            }
        }

        $(document).on('change', '#imgInp', function() {

            var fileName = imgInp.files[0].name;

            $('.file-name').html(fileName);

            const [file] = imgInp.files

            if (file) {

                $('#avatar').show();

                avatar.src = URL.createObjectURL(file)

            }

        });

        $('.data-table').on('draw.dt', function() {
            $('[data-toggle="tooltip"]').tooltip();
        });

        function alertMsg(status, msg, delay = 1000, remove = false, id = false) {
            let classN = status ? 'success' : 'danger';
            let selector = remove ? 'messageRemove' : (id ? id : 'message');
            let icon = status ? '<i class="fa-regular fa-circle-check"></i>' : '<i class="fa-regular fa-circle-xmark"></i>';
            $('#' + selector).html(`<div class="alert alert-box alert-${classN} d-flex align-items-center w-100" role="alert">
      ${icon}
      <div class="alr-mgs"> <p>${msg}</p></div></div>`).fadeIn();
            setTimeout(function() {
                $('#' + selector).fadeOut('slow');
            }, delay)
        }
        /*end single image preview*/
    </script>

    @stack('modal')
    @stack('script')

    <!-- Core JS -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Main JS -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <script>
        // Global Select2 Initialization
        $(document).ready(function() {
            // Initialize Select2 on all elements with select2 class
            $('.select2').each(function() {
                const $element = $(this);
                const isMultiple = $element.prop('multiple');
                
                $element.select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: $element.data('placeholder') || 'Select an option',
                    allowClear: true,
                    dropdownParent: $element.parent(),
                    closeOnSelect: !isMultiple
                });
            });
        });
    </script>

</body>

</html>