<x-sidebar>
  <div class="vh-100 pt-5" style="background:#ECF1F6;">
    <div class="border w-75 m-auto pt-5 pb-5" style="border-radius:5px; background:#FFF;">
      <div class="w-75 m-auto border" style="border-radius:5px;">

        <h3 class="calendar_title">{{ $calendar->getTitle() }}</h3>
        <div class="">
          {!! $calendar->render() !!}
        </div>
      </div>
      <div class="text-right w-75 m-auto">
        <input type="submit" class="btn btn-primary" value="予約する" form="reserveParts">
      </div>
    </div>
  </div>

  <!-- モーダル -->
  <div class="modal js-modal">
    <div class="modal__bg js-modal-close">
      <div class="modal__content">
        <!-- 予約日・部・場所 -->
        <div class="cancel_detail">
          <p>予約日：<span id="modal-date"></span></p>
          <p>時間：<span id="modal-part"></span></p>
          <p>上記の予約をキャンセルしてもよろしいでしょうか</p>
        </div>
        <!-- キャンセルボタン -->
        <div class="modal_buttons">
          <form method="POST" action="{{route('reserve.cancel')}}">
            @csrf
            <button type="button" class="close_button btn btn-primary js-modal-close mt-2">閉じる</button>
            <input type="hidden" name="cancel_date" id="cancel-date">
            <input type="hidden" name="cancel_part" id="cancel-part">
            <button type="submit" class="cancel_button btn btn-danger mt-3">予約キャンセル</button>
          </form>
        </div>
      </div>
    </div>
  </div>

</x-sidebar>
