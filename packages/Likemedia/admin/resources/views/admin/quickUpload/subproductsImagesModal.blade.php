<div class="modal fade" id="subproducts-images-modal{{ $product->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </button>
        <h5 class="modal-title" id="exampleModalLabel">Imagini Subproduse - {{ $product->translationByLanguage(1)->first()->name }}</h5>
      </div>

      <form class="" action="" method="post"  enctype="multipart/form-data">
          <div class="modal-body modal-subproducts response-sub-images{{ $product->id }}">
              @include('admin::admin.quickUpload.imagesLiveSubproduct')
          </div>

          <div class="modal-footer fixed-btns">
              <small class="text-danger message-sub"></small>
              <button type="button" class="btn btn-primary save-subproduct-images" data="{{ $product->id }}" data-form="subproductsFrom{{$product->id}}">Save Images</button>
          </div>
          <input type="hidden" name="id" value="{{ $product->id }}">

      </form>
    </div>
  </div>
</div>
