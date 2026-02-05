@extends('admin.layouts.app')

@section('content')

<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Categories</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('category.create') }}" class="btn btn-primary">New Category</a>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <div class="card-tools">

                    {{-- Search --}}
                    <form method="GET" action="{{ route('category.index') }}">
                        <div class="input-group" style="width: 360px;">
                            <input type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   class="form-control float-right"
                                   placeholder="Search by name or slug">

                            <div class="input-group-append">
                                {{-- Reset button (only show when searching) --}}
                                @if(request('search'))
                                    <a href="{{ route('category.index') }}"
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

            @if(request('search'))
                <div class="px-3 pt-3">
                    Showing results for: <strong>{{ request('search') }}</strong>
                </div>
            @endif

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
                        @forelse($categories as $category)
                            <tr id="row-{{ $category->id }}">
                                <td>{{ $category->id }}</td>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->slug }}</td>
                                <td>
                                    @if((int)$category->status === 1)
                                        <i class="fas fa-check-circle text-success" title="Active"></i>
                                    @else
                                        <i class="fas fa-times-circle text-danger" title="Inactive"></i>
                                    @endif
                                </td>

                                <td>
                                    {{-- Edit --}}
                                    <a href="{{ route('category.edit', $category->id) }}" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    {{-- Delete --}}
                                    <a href="javascript:void(0)"
                                       onclick="deleteCategory({{ $category->id }})"
                                       class="text-danger ml-2"
                                       title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center p-4">
                                    No categories found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($categories->hasPages())
                <div class="card-footer clearfix">
                    <div class="float-right">
                        {{ $categories->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

@endsection


@section('customJs')
<script>
    function deleteCategory(id) {
        if (!confirm('Are you sure you want to delete this category?')) {
            return;
        }

        $.ajax({
            url: "{{ url('admin/categories') }}/" + id,
            type: "DELETE",
            dataType: "json",
            success: function (response) {
                if (response.status === true) {
                    // remove row without refresh (nice UX)
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
