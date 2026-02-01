@extends('admin.layouts.app')

@section('content')

<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Sub Categories</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('subcategory.create') }}" class="btn btn-primary">New Sub Category</a>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">

        <div class="card">
            <div class="card-header">
                <div class="card-tools">
                    <form method="GET" action="{{ route('subcategory.index') }}">
                        <div class="input-group" style="width: 650px;">

                            {{-- Parent category filter --}}
                            <select name="category_id" class="form-control" style="max-width: 220px;">
                                <option value="">All Categories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ (string)$categoryId === (string)$cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>

                            {{-- Search --}}
                            <input type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   class="form-control"
                                   placeholder="Search name, slug, or category">

                            <div class="input-group-append">
                                {{-- Reset --}}
                                @if(request('search') || request('category_id'))
                                    <a href="{{ route('subcategory.index') }}"
                                       class="btn btn-outline-secondary"
                                       title="Reset">
                                        Reset
                                    </a>
                                @endif

                                <button type="submit" class="btn btn-default" title="Filter/Search">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            @if(request('search') || request('category_id'))
                <div class="px-3 pt-3">
                    <strong>Filters:</strong>
                    @if(request('category_id'))
                        Category ID: <strong>{{ request('category_id') }}</strong>
                    @endif
                    @if(request('search'))
                        | Search: <strong>{{ request('search') }}</strong>
                    @endif
                </div>
            @endif

            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th width="60">ID</th>
                            <th>Category</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th width="100">Status</th>
                            <th width="120">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($subcategories as $subcategory)
                            <tr id="row-{{ $subcategory->id }}">
                                <td>{{ $subcategory->id }}</td>
                                <td>{{ $subcategory->category?->name }}</td>
                                <td>{{ $subcategory->name }}</td>
                                <td>{{ $subcategory->slug }}</td>

                                <td>
                                    @if((int)$subcategory->status === 1)
                                        <i class="fas fa-check-circle text-success" title="Active"></i>
                                    @else
                                        <i class="fas fa-times-circle text-danger" title="Inactive"></i>
                                    @endif
                                </td>

                                <td>
                                    <a href="{{ route('subcategory.edit', $subcategory->id) }}" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <a href="javascript:void(0)"
                                       onclick="deleteSubCategory({{ $subcategory->id }})"
                                       class="text-danger ml-2" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center p-4">No sub categories found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($subcategories->hasPages())
                <div class="card-footer clearfix">
                    <div class="float-right">
                        {{ $subcategories->links() }}
                    </div>
                </div>
            @endif

        </div>
    </div>
</section>

@endsection

@section('customJs')
<script>
    function deleteSubCategory(id) {
        if (!confirm('Are you sure you want to delete this sub category?')) return;

        $.ajax({
            url: "{{ url('admin/subcategories') }}/" + id,
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
