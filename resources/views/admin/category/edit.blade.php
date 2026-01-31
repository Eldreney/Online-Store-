@extends('admin.layouts.app')

@section('content')

<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Category</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('category.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">

        <form action="{{ route('category.update', $category->id) }}" method="POST" id="categoryForm" name="categoryForm">
            @csrf
            @method('PUT')

            <input type="hidden" name="image_id" id="image_id" value="">

            <div class="card">
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name"
                                       value="{{ $category->name }}"
                                       class="form-control" placeholder="Name">
                                <p></p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">Slug</label>
                                <input type="text" readonly name="slug" id="slug"
                                       value="{{ $category->slug }}"
                                       class="form-control" placeholder="Slug">
                                <p></p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="1" {{ (int)$category->status === 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ (int)$category->status === 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>

                        {{-- Image --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Image</label>

                                @if(!empty($category->image))
                                    <div class="mb-2">
                                        <img src="{{ asset('uploads/categories/' . $category->image) }}"
                                             alt="Category Image" style="max-width: 120px; border-radius: 6px;">
                                    </div>
                                @endif

                                <div id="image" class="dropzone"></div>

                                <p id="image_error" class="invalid-feedback d-block" style="display:none;"></p>
                                <small class="text-muted">Upload 1 image (jpg/png/gif) to replace old image</small>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('category.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>

        </form>

    </div>
</section>

@endsection


@section('customJs')
<script>

    $('#name').on('change keyup', function () {
        $.ajax({
            type: "GET",
            url: "{{ route('getSlug') }}",
            data: { title: $(this).val() },
            dataType: "json",
            success: function (response) {
                if (response.status === true) {
                    $('#slug').val(response.slug);
                }
            }
        });
    });


    Dropzone.autoDiscover = false;

    const dropzone = new Dropzone("#image", {
        url: "{{ route('temp-images.store') }}",
        maxFiles: 1,
        paramName: "image",
        addRemoveLinks: true,
        acceptedFiles: "image/jpeg,image/png,image/gif",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },

        init: function () {
            this.on('addedfile', function () {
                if (this.files.length > 1) {
                    this.removeFile(this.files[0]);
                }
                $('#image_error').hide().text('');
            });

            this.on('removedfile', function () {
                $('#image_id').val('');
            });
        },

        success: function (file, response) {
            if (response.status === true) {
                $('#image_id').val(response.image_id);
                $('#image_error').hide().text('');
            } else {
                $('#image_id').val('');
                $('#image_error').show().text('Image upload failed.');
            }
        },

        error: function () {
            $('#image_id').val('');
            $('#image_error').show().text('Image upload error.');
        }
    });


    $('#categoryForm').submit(function (e) {
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: "{{ route('category.update', $category->id) }}",
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {

                if (response.status === true) {
                    window.location.href = "{{ route('category.index') }}";
                    return;
                }

                let errors = response.errors || {};

                if (errors.name) {
                    $('#name').addClass('is-invalid')
                        .siblings('p').addClass('invalid-feedback')
                        .html(errors.name[0]);
                } else {
                    $('#name').removeClass('is-invalid')
                        .siblings('p').removeClass('invalid-feedback')
                        .html('');
                }

                if (errors.slug) {
                    $('#slug').addClass('is-invalid')
                        .siblings('p').addClass('invalid-feedback')
                        .html(errors.slug[0]);
                } else {
                    $('#slug').removeClass('is-invalid')
                        .siblings('p').removeClass('invalid-feedback')
                        .html('');
                }

                if (errors.image_id) {
                    $('#image_error').show().text(errors.image_id[0]);
                } else {
                    $('#image_error').hide().text('');
                }
            },
            error: function () {
                alert('Something went wrong.');
            }
        });
    });
</script>
@endsection
