<!-- Modal -->
<?php
    $imges = $product->images;
?>
<div class="modal fade" id="gallery-modal{{ $product->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </button>
        <h5 class="modal-title" id="exampleModalLabel">Imagini - {{ $product->translationByLanguage(1)->first()->name }}</h5>
      </div>

      <div class="modal-body">
          <div class="row images-live-update{{ $product->id }}">
              @include('admin::admin.quickUpload.imagesLiveUpdate', ['product' => $product])
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" >Close</button>
      </div>
    </div>
  </div>
</div>
