@extends('admin.layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>Products</h1></div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('product.create') }}" class="btn btn-primary">New Product</a>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">

        <div class="card">
            <div class="card-header">
                <div class="card-tools">
                    <form method="GET" action="{{ route('admin.product.index') }}">
                        <div class="input-group" style="width: 420px;">
                            <input type="text" name="search" value="{{ request('search') }}"
                                   class="form-control float-right" placeholder="Search by title / slug / sku">
                            <div class="input-group-append">
                                @if(request('search'))
                                    <a href="{{ route('product.index') }}" class="btn btn-outline-secondary">Reset</a>
                                @endif
                                <button type="submit" class="btn btn-default">
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
                            <th>Title</th>
                            <th>SKU</th>
                            <th width="120">Price</th>
                            <th width="90">Status</th>
                            <th width="120">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $p)
                            <tr>
                                <td>{{ $p->id }}</td>
                                <td>{{ $p->title }}</td>
                                <td>{{ $p->sku }}</td>
                                <td>{{ number_format($p->price, 2) }}</td>
                                <td>
                                    @if((int)$p->status === 1)
                                        <i class="fas fa-check-circle text-success" title="Active"></i>
                                    @else
                                        <i class="fas fa-times-circle text-danger" title="Blocked"></i>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('product.edit', $p->id) }}" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <a href="javascript:void(0)" class="text-danger ml-2 btn-delete"
                                       data-id="{{ $p->id }}" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center p-4">No products found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($products->hasPages())
                <div class="card-footer clearfix">
                    <div class="float-right">{{ $products->links() }}</div>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection

@section('customJs')
<script>
$(document).on('click', '.btn-delete', function () {
    const id = $(this).data('id');
    if(!confirm('Delete this product?')) return;

    $.ajax({
        url: "{{ url('admin/products') }}/" + id,
        type: "DELETE",
        dataType: "json",
        success: function (res) {
            if(res.status) location.reload();
        },
        error: function () {
            alert('Delete failed.');
        }
    });
});
</script>
@endsection
