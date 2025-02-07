@extends('layouts.app')
@section('moduleName', 'Example')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">User List</div>

                    <div class="card-body">
                        <form method="get" action="{!! route($module . 'list') !!}" class="clfrm" novalidate="">@csrf
                            <div class="row">
                                <div class="form-group mb-0 col-md-4">
                                    <label for="search">Search</label>
                                    <div class="effect">
                                        <input class="form-control" type="text" id="search_text" name="search_text"
                                            placeholder="User Name, Email"
                                            value="{{ \Request::get('search_text') }}" /><span
                                            class="focus-border"><i></i></span>
                                    </div>
                                </div>
                                <div class="form-group mb-0 col-md-3">
                                    <label for="gender">Gender</label>
                                    <div class="effect">
                                        <select class="form-control" id="gender" name="gender">
                                            <option value="">Select Gender</option>
                                            <option value="Male"
                                                {{ \Request::get('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female"
                                                {{ \Request::get('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                        </select>
                                        <span class="focus-border"><i></i></span>
                                    </div>
                                </div>
                                <div class="form-group col-md-3 mt-2">
                                    <label for="search"></label>
                                    <div class="effect">
                                        <button type="submit" class="btn btn-primary">Search</button>
                                        <button type="button" class="btn btn-secondary btn-clear ml-2">Clear</button>
                                    </div>
                                </div>
                                <div class="form-group col-md-2 d-flex mt-4 justify-content-end">
                                    <label for="search"></label>
                                    <div class="effect mt-2">
                                        <span onclick="ajaxScript(this);" class="btn btn-primary"
                                            url="{{ route($module . 'create') }}" data-id="" method="get"
                                            class="cursor-pointer">Create</span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive table-data"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        const route = "{{ route($module . 'list') }}";
        const page = 1;
        const className = ".table-data";
        get_list(route, page, className);

        function get_list(route, page, className) {
            $.ajax({
                type: 'GET',
                url: route,
                data: {
                    search_text: $('#search_text').length > 0 ? $('#search_text').val() : '',
                    page: page,
                    id: $(className).data('id'),
                },
                cache: false,
                beforeSend: function() {},
                success: function(data) {
                    $(className).html(data)
                },
                error: function(data) {},
                complete: function() {},
            })
        }

        $(document).ready(function() {
            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault()
                var type = "usr-data";
                var url = $(this).attr('href')
                getPaginate('.' + type, url)
            })

            $(document).on('click', 'th a', function(event) {
                event.preventDefault()
                var type = "usr-data";
                var url = $(this).attr('href')
                getPaginate('.' + type, url)
            })
        })

        function getPaginate(type, url) {
            $.ajax({
                url: url,
                type: 'get',
                datatype: 'json',
                success: function(data) {
                    $(type).html(data);
                },
                fail: function(jqXHR, ajaxOptions, thrownError) {
                    console.log('No response from server')
                },
            })
        }

        $('.clfrm').submit(function(e) {
            e.preventDefault();
            let page = 1;
            var formData = new FormData(this);
            $.ajax({
                type: 'GET',
                url: $(this).attr('action'),
                data: $('.clfrm').serialize(),
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'html',
                success: function(data) {
                    $('.usr-data').html(data);
                },
            });
        });

        $(".btn-clear").on("click", function() {
            $(".clfrm").trigger("reset");
            $(".gender").val("*").trigger("change");
            get_list('.usr-data', 1);
        });

        function delete_records(t) {
            var page = $('.pagination .page-item.active .page-link').html();
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
            swal({
                title: "Are you sure you want to delete this?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: '#007bff',
                buttons: true,
                dangerMode: true,
                confirmButtonText: 'Yes, delete it!'
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: $(t).attr('method'),
                        url: $(t).attr('url'),
                        data: {
                            _token: CSRF_TOKEN
                        },
                        cache: false,
                        beforeSend: function() {},
                        success: function(data) {
                            if (data.status == "success") {
                                swal({
                                    icon: data.status,
                                    title: data.message
                                });
                                get_list(page);
                            } else {
                                swal({
                                    icon: data.status,
                                    title: data.message
                                });
                                setTimeout(() => {
                                    location.reload();
                                }, 1500);
                            }
                        },
                        error: function(data) {
                            swal({
                                icon: data.status,
                                title: data.message
                            });
                        },
                        complete: function() {}
                    });
                }
            });
        }
    </script>
@endsection
