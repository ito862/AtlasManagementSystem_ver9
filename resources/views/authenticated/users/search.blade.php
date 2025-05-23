<x-sidebar>
  <!-- ユーザー表示 -->
  <div class="search_content w-100 vh-100 border d-flex">
    <div class="reserve_users_area">
      @foreach($users as $user)
      <div class="border one_person">
        <div class="user_detail">
          <div>
            <span>ID : </span><span>{{ $user->id }}</span>
          </div>
          <div><span>名前 : </span>
            <a href="{{ route('user.profile', ['id' => $user->id]) }}">
              <span>{{ $user->over_name }}</span>
              <span>{{ $user->under_name }}</span>
            </a>
          </div>
          <div>
            <span>カナ : </span>
            <span>({{ $user->over_name_kana }}</span>
            <span>{{ $user->under_name_kana }})</span>
          </div>
          <div>
            @if($user->sex == 1)
            <span>性別 : </span><span>男</span>
            @elseif($user->sex == 2)
            <span>性別 : </span><span>女</span>
            @else
            <span>性別 : </span><span>その他</span>
            @endif
          </div>
          <div>
            <span>生年月日 : </span><span>{{ $user->birth_day }}</span>
          </div>
          <div>
            @if($user->role == 1)
            <span>権限 : </span><span>教師(国語)</span>
            @elseif($user->role == 2)
            <span>権限 : </span><span>教師(数学)</span>
            @elseif($user->role == 3)
            <span>権限 : </span><span>講師(英語)</span>
            @else
            <span>権限 : </span><span>生徒</span>
            @endif
          </div>
          <div>
            <span>選択科目 : </span>
            @foreach($user->subjects as $subject)
            <span>{{ $subject->subject }}</span>
            @endforeach
          </div>
        </div>
      </div>
      @endforeach
    </div>
    <!-- 検索機能 -->
    <div class="search_area w-25 border">
      <div class="search_detail">
        <h3 class="search_items">検索</h3>
        <form action="{{ route('user.search') }}" method="get" id="userSearchRequest">
          <div class="search_container">
            <div>
              <input type="text" class="free_word" name="keyword" placeholder="キーワードを検索" form="userSearchRequest">
            </div>
            <div>
              <label class="search_items">カテゴリ</label>
              <select class="category_select" form="userSearchRequest" name="category">
                <option value="name">名前</option>
                <option value="id">社員ID</option>
              </select>
            </div>
            <div>
              <label class="search_items">並び替え</label>
              <select class="updown_select" name="updown" form="userSearchRequest">
                <option value="ASC">昇順</option>
                <option value="DESC">降順</option>
              </select>
            </div>

            <!-- 検索条件の追加 -->
            <div class="">
              <p class="m-0 search_conditions">
                <span class="search_items">検索条件の追加</span>
                <span class="arrow-icon">&#8250;</span>
              </p>
              <div class="search_conditions_inner">
                <div>
                  <label class="search_items">性別</label>
                  <span>男</span><input type="radio" name="sex" value="1" form="userSearchRequest">
                  <span>女</span><input type="radio" name="sex" value="2" form="userSearchRequest">
                  <span>その他</span><input type="radio" name="sex" value="3" form="userSearchRequest">
                </div>
                <div>
                  <label class="search_items">権限</label>
                  <select name="role" form="userSearchRequest" class="engineer">
                    <option selected disabled>----</option>
                    <option value="1">教師(国語)</option>
                    <option value="2">教師(数学)</option>
                    <option value="3">教師(英語)</option>
                    <option value="4" class="">生徒</option>
                  </select>
                </div>
                <div class="selected_engineer">
                  <label class="search_items">選択科目</label>
                  <span>国語</span><input type="checkbox" name="subject[]" value="1" form="userSearchRequest">
                  <span>数学</span><input type="checkbox" name="subject[]" value="2" form="userSearchRequest">
                  <span>英語</span><input type="checkbox" name="subject[]" value="3" form="userSearchRequest">
                </div>
              </div>
            </div>
          </div>
          <div>
            <input class="search_button" class="search_button" type="submit" name="search_btn" value="検索" form="userSearchRequest">
          </div>
          <div>
            <input class="reset_button" type="reset" value="リセット" form="userSearchRequest">
          </div>
      </div>
      </form>
    </div>
  </div>
  </div>
</x-sidebar>
