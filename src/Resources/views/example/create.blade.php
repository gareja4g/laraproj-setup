<div class="modal-dialog modal-md">
    <div class="modal-content">
        <form method="POST" class="needs-validation user-create" novalidate="" enctype="multipart/form-data"
            autocomplete="off">@csrf
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Create User</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="name">Full Name</label><span class="text-danger">*</span>
                            <input id="name" name="name" type="text" class="form-control" />
                            <div class="invalid-feedback name text-danger" role="alert"></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="email">Email</label><span class="text-danger">*</span>
                            <input id="email" name="email" type="text" class="form-control" />
                            <div class="invalid-feedback email text-danger" role="alert"></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="phone">Phone no:</label><span class="text-danger">*</span>
                            <input id="phone" name="phone" type="text" class="form-control" />
                            <div class="invalid-feedback phone text-danger" role="alert"></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="password">Password </label><span class="text-danger">*</span>
                            <input id="password" name="password" type="password" class="form-control" />
                            <div class="invalid-feedback password text-danger" role="alert"></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="password_confirmation">Confirm Password</label><span
                                class="text-danger">*</span>
                            <input id="password_confirmation" name="password_confirmation" type="password"
                                class="form-control" />
                            <div class="invalid-feedback password_confirmation text-danger" role="alert"></div>
                        </div>
                        <div class="form-group mb-0 col-md-12">
                            <label for="gender">
                                Select Gender
                            </label><span class="text-danger">*</span>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="male" name="gender"
                                    value="Male" required>
                                <label class="form-check-label" for="male">
                                    Male
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="female" name="gender"
                                    value="Female" required>
                                <label class="form-check-label" for="female">
                                    Female
                                </label>
                            </div>
                            <div class="invalid-feedback gender text-danger" role="alert"></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="image">Upload Profile:</label><span class="text-danger">*</span><br />
                            <input type="file" class="form-control user_profile" id="image" name="image" />
                            <div class="invalid-feedback image text-danger" role="alert"></div>
                        </div>
                        <div class="form-group mb-0 col-md-12">
                            <label for="education">
                                Select Educational Qualifications (You can select multiple)
                            </label><span class="text-danger">*</span>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="highschool" name="education[]"
                                    value="High School">
                                <label class="form-check-label" for="highschool">
                                    High School
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="bachelor" name="education[]"
                                    value="Bachelor's Degree">
                                <label class="form-check-label" for="bachelor">
                                    Bachelor's Degree
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="master" name="education[]"
                                    value="Master's Degree">
                                <label class="form-check-label" for="master">
                                    Master's Degree
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="doctorate" name="education[]"
                                    value="Doctorate">
                                <label class="form-check-label" for="doctorate">
                                    Doctorate (Ph.D.)
                                </label>
                            </div>
                            <div class="invalid-feedback education text-danger" role="alert"></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="certificate">Upload Certificate:</label><span
                                class="text-danger">*</span><br />
                            <input type="file" class="form-control user_certificate" id="certificate"
                                name="certificate" />
                            <div class="invalid-feedback file text-danger" role="alert"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary back button btn ml-auto btn-cancel mr-2"
                    data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn-process btn-primary btn-save back button btn"
                    data-btn-process="Save Changes">Save changes</button>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    $(function() {
        const userProfile = $.fn.filepond.create(document.querySelector(".user_profile"), {
            instantUpload: false,
            acceptedFileTypes: ['image/*']
        });

        const userCertificate = $.fn.filepond.create(document.querySelector(".user_certificate"), {
            instantUpload: false,
            acceptedFileTypes: ['pdf/*']
        });

        $('.user-create').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            userProfile.getFiles().forEach(fileItem => {
                formData.append('image', fileItem.file);
            });

            userCertificate.getFiles().forEach(fileItem => {
                formData.append('file', fileItem.file);
            });

            $.ajax({
                type: 'POST',
                url: "{{ route('store') }}",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                beforeSend: function() {
                    $(".btn-process").attr("disabled", true).html('Processing...');
                },
                success: function(data) {
                    if ($('.invalid-feedback').length > 0) {
                        $('.invalid-feedback').html('');
                    }
                    if (data.status == "success") {
                        $('.user-create')[0].reset();
                        $('#popup').modal('hide');
                        swal({
                            icon: data.status,
                            title: data.message
                        });
                        get_list(1);
                    } else {
                        swal({
                            icon: 'error',
                            title: data.message
                        });
                    }
                },
                error: function(xhr) {
                    if ($('.invalid-feedback').length > 0) {
                        $('.invalid-feedback').html('');
                    }
                    var errObj = JSON.parse(xhr.responseText);
                    $.each(errObj.errors, function(key, value) {
                        $('.' + key).html('<strong>' + value[0] + '</strong>')
                            .fadeIn();
                    });
                },
                complete: function() {
                    $(".btn-process").attr("disabled", false).html('Save Changes');
                }
            });
        });
    });
</script>
