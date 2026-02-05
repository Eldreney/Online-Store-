@extends('admin.layouts.app')

@section('content')

<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Brands</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('brand.create') }}" class="btn btn-primary">New Brand</a>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">

        <div class="card">
            <div class="card-header">
                <div class="card-tools">

                    <form method="GET" action="{{ route('brand.index') }}">
                        <div class="input-group" style="width: 360px;">
                            <input type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   class="form-control float-right"
                                   placeholder="Search by name or slug">

                            <div class="input-group-append">
                                @if(request('search'))
                                    <a href="{{ route('brand.index') }}"
                                       class="btn btn-outline-secondary"
                                       title="Reset">
                                        Reset
                                    </a>
                                @endif

                                <button type="submit" class="btn btn-default" title="Search">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>

            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th width="60">ID</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th width="100">Status</th>
                            <th width="120">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($brands as $brand)
                            <tr id="row-{{ $brand->id }}">
                                <td>{{ $brand->id }}</td>
                                <td>{{ $brand->name }}</td>
                                <td>{{ $brand->slug }}</td>

                                <td>
                                    @if((int)$brand->status === 1)
                                        <i class="fas fa-check-circle text-success" title="Active"></i>
                                    @else
                                        <i class="fas fa-times-circle text-danger" title="Inactive"></i>
                                    @endif
                                </td>

                                <td>
                                    <a href="{{ route('brand.edit', $brand->id) }}" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <a href="javascript:void(0)"
                                       onclick="deleteBrand({{ $brand->id }})"
                                       class="text-danger ml-2" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center p-4">No brands found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($brands->hasPages())
                <div class="card-footer clearfix">
                    <div class="float-right">
                        {{ $brands->links() }}
                    </div>
                </div>
            @endif

        </div>
    </div>
</section>

@endsection

@section('customJs')
<script>
    function deleteBrand(id) {
        if (!confirm('Are you sure you want to delete this brand?')) return;

        $.ajax({
            url: "{{ url('admin/brands') }}/" + id,
            type: "DELETE",
            dataType: "json",
            success: function (response) {
                if (response.status === true) {
                    $('#row-' + id).remove();
                } else {
                    alert(response.message ?? 'Delete failed.');
                }
            },
            error: function () {
                alert('Something went wrong.');
            }
        });
    }
</script>
@endsection
