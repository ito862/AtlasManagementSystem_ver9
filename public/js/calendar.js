$(function () {
  $('.js-modal-open').on('click', function () {
    // モーダルの中身(class="js-modal")の表示
    $('.js-modal').fadeIn();
    const date = $(this).data('reserve-date');
    const part = $(this).data('reserve-part');

    // 表示部分
    $('#modal-date').text(date);
    $('#modal-part').text(part + '部');
    // 送信用
    $('#cancel-date').val(date);
    $('#cancel-part').val(part);
  });

  // 背景部分をクリックすると閉じる
  $('.js-modal-close').on('click', function (e) {
    if (($e.target).is('.modal-bg')) {
      $('.js-modal').fadeOut();
    }
  });
  // 閉じるボタンで閉じる
  $('.js-modal-close').on('click', function () {
    $('.js-modal').fadeOut();
    return false;
  });
  // モーダルを閉じるイベントが干渉しないよう
  $('.modal__content').on('click', function (e) {
    e.stopPropagation();
  });

});
