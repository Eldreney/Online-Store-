@extends('admin.layouts.app')

@section('content')

<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Brand</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('brand.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">

        <form action="{{ route('brand.update', $brand->id) }}" method="POST" id="brandForm">
            @csrf
            @method('PUT')

            <div class="card">
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name"
                                       value="{{ $brand->name }}"
                                       class="form-control" placeholder="Name">
                                <p></p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">Slug</label>
                                <input type="text" readonly name="slug" id="slug"
                                       value="{{ $brand->slug }}"
                                       class="form-control" placeholder="Slug">
                                <p></p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="1" {{ (int)$brand->status === 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ (int)$brand->status === 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('brand.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
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

    // ajax submit
    $('#brandForm').submit(function(e){
        e.preventDefault();

        $.ajax({
            type: "POST", // because @method('PUT')
            url: "{{ route('brand.update', $brand->id) }}",
            data: $(this).serialize(),
            dataType: "json",
            success: function(response){
                if(response.status === true){
                    window.location.href = "{{ route('brand.index') }}";
                    return;
                }

                let errors = response.errors || {};

                if(errors.name){
                    $('#name').addClass('is-invalid').siblings('p')
                        .addClass('invalid-feedback').html(errors.name[0]);
                } else {
                    $('#name').removeClass('is-invalid').siblings('p')
                        .removeClass('invalid-feedback').html('');
                }

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
