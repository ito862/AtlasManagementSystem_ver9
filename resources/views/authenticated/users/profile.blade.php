<x-sidebar>
  <div class="vh-100 border">
    <div class="top_area w-75 m-auto pt-5">
      <span>{{ $user->over_name }}</span><span>{{ $user->under_name }}さんのプロフィール</span>
      <div class="user_status p-3">
        <p>名前 : <span>{{ $user->over_name }}</span><span class="ml-1">{{ $user->under_name }}</span></p>
        <p>カナ : <span>{{ $user->over_name_kana }}</span><span class="ml-1">{{ $user->under_name_kana }}</span></p>
        <p>性別 : @if($user->sex == 1)<span>男</span>@else<span>女</span>@endif</p>
        <p>生年月日 : <span>{{ $user->birth_day }}</span></p>
        <div>選択科目 :
          @foreach($user->subjects as $subject)
          <span>{{ $subject->subject }}</span>
          @endforeach
        </div>
        <div class="">
          @can('admin')
          <div class="select_subject">
            <p class="subject_edit_btn">
              <span>選択科目の選択</span>
              <span class="arrow-icon">&#8250;</span>
            </p>
            <form action="{{ route('user.edit') }}" method="post">
              <div class="subject_inner">
                @foreach($subject_lists as $subject_list)
                <div class="subject_box">
                  <label>{{ $subject_list->subject }}</label>
                  <input type="checkbox" name="subjects[]" value="{{ $subject_list->id }}">
                </div>
                @endforeach
                <input type="submit" value="編集" class="edit_button btn btn-primary">
                <input type="hidden" name="user_id" value="{{ $user->id }}">
                {{ csrf_field() }}
              </div>
            </form>
          </div>
          @endcan
        </div>
      </div>
    </div>
  </div>

</x-sidebar>
