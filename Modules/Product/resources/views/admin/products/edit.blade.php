@extends('admin.layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>Edit Product</h1></div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.product.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
</section>

<section class="content">
<div class="container-fluid">

<form id="productForm">
    @csrf
    @method('PUT')


    <input type="hidden" id="product_id" value="{{ $product->id }}">

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="mb-3">
                        <label>Title</label>
                        <input type="text" name="title" id="title"
                               value="{{ old('title', $product->title) }}"
                               class="form-control" placeholder="Title">
                        <p class="text-danger small" id="err_title"></p>
                    </div>

                    <div class="mb-3">
                        <label>Slug</label>
                        <input type="text" name="slug" id="slug"
                               value="{{ old('slug', $product->slug) }}"
                               class="form-control" placeholder="Slug">
                        <p class="text-danger small" id="err_slug"></p>
                    </div>

                    <div class="mb-3">
                        <label>Description</label>
                        <textarea name="description" id="description" class="summernote">{{ old('description', $product->description) }}</textarea>
                        <p class="text-danger small" id="err_description"></p>
                    </div>
                </div>
            </div>

            {{-- Media --}}
            <div class="card mb-3">
                <div class="card-body">
                    <h2 class="h4 mb-3">Media</h2>

                    <div id="image" class="dropzone"></div>
                    <small class="text-muted">You can upload up to 5 images</small>
                    <p class="text-danger small" id="err_media"></p>
                </div>
            </div>

            {{-- Pricing --}}
            <div class="card mb-3">
                <div class="card-body">
                    <h2 class="h4 mb-3">Pricing</h2>
                    <div class="mb-3">
                        <label>Price</label>
                        <input type="text" name="price" id="price"
                               value="{{ old('price', $product->price) }}"
                               class="form-control" placeholder="Price">
                        <p class="text-danger small" id="err_price"></p>
                    </div>
                    <div class="mb-3">
                        <label>Compare at Price</label>
                        <input type="text" name="compare_price" id="compare_price"
                               value="{{ old('compare_price', $product->compare_price) }}"
                               class="form-control" placeholder="Compare Price">
                        <p class="text-danger small" id="err_compare_price"></p>
                    </div>
                </div>
            </div>

            {{-- Inventory --}}
            <div class="card mb-3">
                <div class="card-body">
                    <h2 class="h4 mb-3">Inventory</h2>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>SKU</label>
                            <input type="text" name="sku" id="sku"
                                   value="{{ old('sku', $product->sku) }}"
                                   class="form-control" placeholder="SKU">
                            <p class="text-danger small" id="err_sku"></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Barcode</label>
                            <input type="text" name="barcode" id="barcode"
                                   value="{{ old('barcode', $product->barcode) }}"
                                   class="form-control" placeholder="Barcode">
                            <p class="text-danger small" id="err_barcode"></p>
                        </div>

                        <div class="col-md-12">
                            <div class="custom-control custom-checkbox mb-2">
                                <input class="custom-control-input"
                                       type="checkbox"
                                       id="track_qty"
                                       name="track_qty"
                                       {{ old('track_qty', $product->track_qty) ? 'checked' : '' }}>
                                <label for="track_qty" class="custom-control-label">Track Quantity</label>
                            </div>

                            <input type="number" min="0" name="qty" id="qty"
                                   value="{{ old('qty', $product->qty) }}"
                                   class="form-control" placeholder="Qty">
                            <p class="text-danger small" id="err_qty"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right sidebar --}}
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h2 class="h4 mb-3">Status</h2>
                    <select name="status" class="form-control">
                        <option value="1" {{ (int)old('status', $product->status) === 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ (int)old('status', $product->status) === 0 ? 'selected' : '' }}>Block</option>
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
                            <option value="{{ $c->id }}" {{ (int)old('category_id', $product->category_id) === $c->id ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-danger small" id="err_category_id"></p>

                    <label>Sub Category</label>
                    <select name="sub_category_id" class="form-control">
                        <option value="">Select</option>
                        @foreach($subCategories as $sc)
                            <option value="{{ $sc->id }}" {{ (int)old('sub_category_id', $product->sub_category_id) === $sc->id ? 'selected' : '' }}>
                                {{ $sc->name }}
                            </option>
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
                            <option value="{{ $b->id }}" {{ (int)old('brand_id', $product->brand_id) === $b->id ? 'selected' : '' }}>
                                {{ $b->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-danger small" id="err_brand_id"></p>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h2 class="h4 mb-3">Featured</h2>
                    <select name="is_featured" class="form-control">
                        <option value="No"  {{ old('is_featured', $product->is_featured) === 'No' ? 'selected' : '' }}>No</option>
                        <option value="Yes" {{ old('is_featured', $product->is_featured) === 'Yes' ? 'selected' : '' }}>Yes</option>
                    </select>
                    <p class="text-danger small" id="err_is_featured"></p>
                </div>
            </div>

        </div>
    </div>

    <div class="pb-5 pt-3">
        <button type="submit" class="btn btn-primary" id="btnSave">Update</button>
        <a href="{{ route('admin.product.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
    </div>
</form>

</div>
</section>
@endsection

@section('customJs')
<script>
Dropzone.autoDiscover = false;

$(function () {
    $('.summernote').summernote({ height: 300 });
});

const productId = {{ $product->id }};

const dz = new Dropzone("#image", {
    url: "{{ url('admin/products') }}/" + productId + "/media",
    maxFiles: 5,
    addRemoveLinks: true,
    acceptedFiles: "image/jpeg,image/png,image/gif",
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
    paramName: 'file',
    init: function () {


        const existing = @json($media ?? []);
        const myDropzone = this;

        existing.forEach(function(item){

            let mockFile = { name: item.name, size: item.size, type: 'image/*' };
            myDropzone.emit("addedfile", mockFile);
            myDropzone.emit("thumbnail", mockFile, item.url);
            myDropzone.emit("complete", mockFile);


            mockFile.media_id = item.id;
        });


        this.on("removedfile", function(file){
            if(!file.media_id) return;

            $.ajax({
                url: "{{ url('admin/products') }}/" + productId + "/media/" + file.media_id,
                type: "DELETE",
                success: function(){},
                error: function(){
                    alert("Could not delete image.");
                }
            });
        });
    }
});


$('#productForm').on('submit', function(e){
    e.preventDefault();

    // clear errors
    $('[id^="err_"]').text('');

    $.ajax({
        url: "{{ route('product.update', $product->id) }}",
        type: "POST",
        data: $(this).serialize(),
        dataType: "json",
        success: function(res){
            if(res.status){
                $("#btnSave").prop('disabled', true).text('Updated');


                if(dz.getAcceptedFiles().length > 0){
                    dz.processQueue();
                }

                window.location.href = "{{ route('admin.product.index') }}";
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
