<div class="modalGuids">
  <div class="modal" id="modalSize">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4>size guide</h4>
          <button type="button" class="close" data-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          {{-- <p><b>Bust</b></p>
          <p>Measure the circumference at the fullest part of your bust. Keep the tape level.</p>
          <p><b>Bust</b></p>
          <p>Measure the circumference at the fullest part of your bust. Keep the tape level.</p>
          <p><b>Bust</b></p>
          <p>Measure the circumference at the fullest part of your bust. Keep the tape level.</p> --}}
          @if (!is_null(getPage('sizeGuide', $lang->id)))
              {!! getPage('sizeGuide', $lang->id)->body !!}
          @endif
        </div>
      </div>
    </div>
  </div>
  <div class="modal" id="modalDelivery">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4>delivery guide</h4>
          <button type="button" class="close" data-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          {{-- <p>Delivery is the process of transporting goods from a source location to a predefined destination. There are different delivery types. Cargo (physical goods) are primarily delivered via roads and railroads on land, shipping lanes on the sea and airline networks in the air. Certain specialized goods may be delivered via other networks, such as pipelines for liquid goods, power grids for electrical power and computer networks such as the Internet or broadcast networks for electronic information.</p> --}}
          @if (!is_null(getPage('howToShop', $lang->id)))
              {!! getPage('howToShop', $lang->id)->body !!}
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
