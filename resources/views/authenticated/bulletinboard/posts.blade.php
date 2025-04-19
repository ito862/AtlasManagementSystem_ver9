<x-sidebar>
  <div class="board_area w-100 border m-auto d-flex">
    <div class="post_view w-75 mt-5">
      <p class="w-75 m-auto">投稿一覧</p>
      @foreach($posts as $post)
      <div class="post_area border w-75 m-auto p-3">
        <p><span>{{ $post->user->over_name }}</span><span class="ml-3">{{ $post->user->under_name }}</span>さん</p>
        <p><a href="{{ route('post.detail', ['id' => $post->id]) }}">{{ $post->post_title }}</a></p>
        <div class="post_bottom_area d-flex">
          <div class="d-flex post_status">
            <!-- コメント -->
            <div class="mr-5">
              <i class="fa fa-comment"></i><span class="">{{ $post->postComments->count() }}</span>
            </div>
            <!-- いいね -->
            <div>
              @if(Auth::user()->is_Like($post->id))
              <p class="m-0"><i class="fas fa-heart un_like_btn" post_id="{{ $post->id }}"></i><span class="like_counts{{ $post->id }}">{{ $like->likeCounts($post->id) }}</span></p>
              @else
              <p class="m-0"><i class="fas fa-heart like_btn" post_id="{{ $post->id }}"></i><span class="like_counts{{ $post->id }}">{{ $like->likeCounts($post->id) }}</span></p>
              @endif
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>
    <div class="other_area border w-25">
      <div class="border m-4">
        <div class=""><a href="{{ route('post.input') }}">投稿</a></div>
        <div class="">
          <input type="text" placeholder="キーワードを検索" name="keyword" form="postSearchRequest">
          <input type="submit" value="検索" form="postSearchRequest">
        </div>
        <input type="submit" name="like_posts" class="category_btn" value="いいねした投稿" form="postSearchRequest">
        <input type="submit" name="my_posts" class="category_btn" value="自分の投稿" form="postSearchRequest">

        <div class="">カテゴリー検索</div>
        <div>
          <!-- メインカテゴリーのドロップダウン -->
          <select id="mainCategorySelect" class="form-control">
            <option value="">-- メインカテゴリーを選択 --</option>
            @foreach($categories as $mainCategory)
            <option value="{{ $mainCategory->id }}">{{ $mainCategory->main_category }}</option>
            @endforeach
          </select>

          <!-- サブカテゴリーリスト -->
          <ul id="subCategoryList" class="mt-3">
            @foreach($categories as $mainCategory)
            @foreach($mainCategory->subCategories as $subCategory)
            <li class="sub-category-item" data-main-id="{{ $mainCategory->id }}" style="display: none;">
              <form method="GET" action="{{ route('post.show') }}">
                <input type="hidden" name="category_word" value="{{ $subCategory->sub_category }}">
                <button type="submit" class="btn btn-link p-0 m-0">{{ $subCategory->sub_category }}</button>
              </form>
            </li>
            @endforeach
            @endforeach
          </ul>
        </div>

      </div>
    </div>
    <form action="{{ route('post.show') }}" method="get" id="postSearchRequest"></form>
  </div>

  <!-- ドロップダウン -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const mainCategorySelect = document.getElementById('mainCategorySelect');
      const subCategoryItems = document.querySelectorAll('.sub-category-item');

      mainCategorySelect.addEventListener('change', function() {
        const selectedMainId = this.value;

        subCategoryItems.forEach(function(item) {
          item.style.display = (item.dataset.mainId === selectedMainId) ? 'list-item' : 'none';
        });
      });
    });
  </script>

</x-sidebar>
