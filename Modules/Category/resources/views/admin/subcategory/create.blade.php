@extends('admin.layouts.app')

@section('content')

<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Sub Category</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('subcategory.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">

        <form action="{{ route('subcategory.store') }}" method="POST" id="subCategoryForm">
            @csrf

            <div class="card">
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="category_id">Parent Category</label>
                                <select name="category_id" id="category_id" class="form-control">
                                    <option value="">-- Select Category --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                <p></p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Name">
                                <p></p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">Slug</label>
                                <input type="text" readonly name="slug" id="slug" class="form-control" placeholder="Slug">
                                <p></p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Create</button>
                <a href="{{ route('subcategory.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>

        </form>
    </div>
</section>

@endsection

@section('customJs')
<script>
    // auto slug
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

    $('#subCategoryForm').submit(function(e){
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: "{{ route('subcategory.store') }}",
            data: $(this).serialize(),
            dataType: "json",
            success: function(response){
                if(response.status === true){
                    window.location.href = "{{ route('subcategory.index') }}";
                    return;
                }

                let errors = response.errors || {};

                // category_id
                if(errors.category_id){
                    $('#category_id').addClass('is-invalid').siblings('p')
                        .addClass('invalid-feedback').html(errors.category_id[0]);
                } else {
                    $('#category_id').removeClass('is-invalid').siblings('p')
                        .removeClass('invalid-feedback').html('');
                }

                // name
                if(errors.name){
                    $('#name').addClass('is-invalid').siblings('p')
                        .addClass('invalid-feedback').html(errors.name[0]);
                } else {
                    $('#name').removeClass('is-invalid').siblings('p')
                        .removeClass('invalid-feedback').html('');
                }

                // slug
                if(errors.slug){
                    $('#slug').addClass('is-invalid').siblings('p')
                        .addClass('invalid-feedback').html(errors.slug[0]);
                } else {
                    $('#slug').removeClass('is-invalid').siblings('p')
                        .removeClass('invalid-feedback').html('');
                }
            },
            error: function(){
                alert('Something went wrong.');
            }
        });
    });
</script>
@endsection
