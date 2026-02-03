@extends('admin.layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>Create Product</h1></div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('product.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
</section>

<section class="content">
<div class="container-fluid">
<form id="productForm">
    @csrf

    {{-- IMPORTANT:
        We create product first, then upload images to /products/{id}/media
    --}}

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="mb-3">
                        <label>Title</label>
                        <input type="text" name="title" id="title" class="form-control" placeholder="Title">
                        <p class="text-danger small" id="err_title"></p>
                    </div>

                    <div class="mb-3">
                        <label>Slug</label>
                        <input type="text" name="slug" id="slug" class="form-control" placeholder="Slug">
                        <p class="text-danger small" id="err_slug"></p>
                    </div>

                    <div class="mb-3">
                        <label>Description</label>
                        <textarea name="description" id="description" class="summernote"></textarea>
                        <p class="text-danger small" id="err_description"></p>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h2 class="h4 mb-3">Media</h2>
                    <div id="image" class="dropzone"></div>
                    <small class="text-muted">Upload after saving product (max 5)</small>
                    <p class="text-danger small" id="err_media"></p>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h2 class="h4 mb-3">Pricing</h2>
                    <div class="mb-3">
                        <label>Price</label>
                        <input type="text" name="price" id="price" class="form-control" placeholder="Price">
                        <p class="text-danger small" id="err_price"></p>
                    </div>
                    <div class="mb-3">
                        <label>Compare at Price</label>
                        <input type="text" name="compare_price" id="compare_price" class="form-control" placeholder="Compare Price">
                        <p class="text-danger small" id="err_compare_price"></p>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h2 class="h4 mb-3">Inventory</h2>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>SKU</label>
                            <input type="text" name="sku" id="sku" class="form-control" placeholder="SKU">
                            <p class="text-danger small" id="err_sku"></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Barcode</label>
                            <input type="text" name="barcode" id="barcode" class="form-control" placeholder="Barcode">
                            <p class="text-danger small" id="err_barcode"></p>
                        </div>

                        <div class="col-md-12">
                            <div class="custom-control custom-checkbox mb-2">
                                <input class="custom-control-input" type="checkbox" id="track_qty" name="track_qty" checked>
                                <label for="track_qty" class="custom-control-label">Track Quantity</label>
                            </div>
                            <input type="number" min="0" name="qty" id="qty" class="form-control" placeholder="Qty">
                            <p class="text-danger small" id="err_qty"></p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h2 class="h4 mb-3">Status</h2>
                    <select name="status" class="form-control">
                        <option value="1">Active</option>
                        <option value="0">Block</option>
                    </select>
                    <p class="text-danger small" id="err_status"></p>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h2 class="h4 mb-3">Category</h2>

                    <label>Category</label>
                    <select name="category_id" class="form-control mb-3">
                        <option value="">Select</option>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                    <p class="text-danger small" id="err_category_id"></p>

                    <label>Sub Category</label>
                    <select name="sub_category_id" class="form-control">
                        <option value="">Select</option>
                        @foreach($subCategories as $sc)
                            <option value="{{ $sc->id }}">{{ $sc->name }}</option>
                        @endforeach
                    </select>
                    <p class="text-danger small" id="err_sub_category_id"></p>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h2 class="h4 mb-3">Brand</h2>
                    <select name="brand_id" class="form-control">
                        <option value="">Select</option>
                        @foreach($brands as $b)
                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                        @endforeach
                    </select>
                    <p class="text-danger small" id="err_brand_id"></p>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h2 class="h4 mb-3">Featured</h2>
                    <select name="is_featured" class="form-control">
                        <option value="No">No</option>
                        <option value="Yes">Yes</option>
                    </select>
                    <p class="text-danger small" id="err_is_featured"></p>
                </div>
            </div>

        </div>
    </div>

    <div class="pb-5 pt-3">
        <button type="submit" class="btn btn-primary" id="btnSave">Create</button>
        <a href="{{ route('product.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
    </div>

</form>
</div>
</section>
@endsection

@section('customJs')
{{-- add these assets in your app layout if not already: summernote + dropzone --}}
<script>
Dropzone.autoDiscover = false;

$(function () {
    $('.summernote').summernote({ height: 300 });
});

let productId = null;
const dz = new Dropzone("#image", {
    url: function() {
        if(!productId) return "#";
        return "{{ url('admin/products') }}/" + productId + "/media";
    },
    maxFiles: 5,
    addRemoveLinks: true,
    acceptedFiles: "image/jpeg,image/png,image/gif",
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
    autoProcessQueue: false,
    init: function () {
        this.on("addedfile", function () {
            if(!productId){
                $("#err_media").text("Save product first, then upload images.");
            } else {
                $("#err_media").text("");
            }
        });
    }
});

$('#productForm').on('submit', function(e){
    e.preventDefault();

    // clear errors
    $('[id^="err_"]').text('');

    $.ajax({
        url: "{{ route('product.store') }}",
        type: "POST",
        data: $(this).serialize(),
        dataType: "json",
        success: function(res){
            if(res.status){
                productId = res.id;
                $("#btnSave").prop('disabled', true).text('Saved');

                // now upload images (if any)
                if(dz.getAcceptedFiles().length > 0){
                    dz.options.url = "{{ url('admin/products') }}/" + productId + "/media";
                    dz.options.autoProcessQueue = true;
                    dz.processQueue();
                }

                // redirect to listing after a moment
                window.location.href = "{{ route('product.index') }}";
                return;
            }

            const errors = res.errors || {};
            Object.keys(errors).forEach(function(key){
                $("#err_" + key).text(errors[key][0]);
            });
        },
        error: function(xhr){
            const errors = xhr.responseJSON?.errors || {};
            Object.keys(errors).forEach(function(key){
                $("#err_" + key).text(errors[key][0]);
            });
        }
    });
});
</script>
@endsection
