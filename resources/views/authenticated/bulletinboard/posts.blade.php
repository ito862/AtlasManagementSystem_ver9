<x-sidebar>
  <div class="board_area w-100 border m-auto d-flex">
    <div class="post_view vh-100 w-75 mt-5">
      <!-- <p class="w-75 m-auto">投稿一覧</p> -->
      @foreach($posts as $post)
      <div class="post_area border w-75 m-auto p-3">
        <p><span>{{ $post->user->over_name }}</span><span class="ml-3">{{ $post->user->under_name }}</span>さん</p>
        <p><a class="post_detail" href="{{ route('post.detail', ['id' => $post->id]) }}">{{ $post->post_title }}</a></p>
        @foreach($post->subCategories as $subCategory)
        <P class="subcategory"><span>{{ $subCategory->sub_category }}</span></P>
        @endforeach
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
        <div class="post_create"><a href="{{ route('post.input') }}">投稿</a></div>
        <div class="keyword_alae">
          <input class="keyword" type="text" placeholder="キーワードを検索" name="keyword" form="postSearchRequest">
          <input class="keyword_done" type="submit" value="検索" form="postSearchRequest">
        </div>
        <input type="submit" name="like_posts" class="liked_btn" value="いいねした投稿" form="postSearchRequest">
        <input type="submit" name="my_posts" class="mypost_btn" value="自分の投稿" form="postSearchRequest">

        <div class="post_items">カテゴリー検索</div>
        <ul class="list-group">
          @foreach($categories as $mainCategory)
          <li class="list-group-item p-0 border-0 bg-transparent">
            <div class="accordion-toggle" data-target="#sub-{{ $mainCategory->id }}">
              <span class="category_items accordion-title">{{ $mainCategory->main_category }}</span>
              <span class="arrow-icon">&#8250;</span>
            </div>
            <ul class="sub-category-list" id="sub-{{ $mainCategory->id }}">
              @foreach($mainCategory->subCategories as $subCategory)
              <li class="list-group-item bg-transparent border-0 p-0 py-1">
                <form method="GET" action="{{ route('post.show') }}">
                  <input type="hidden" name="category_word" value="{{ $subCategory->sub_category }}">
                  <button type="submit" class="subcategory_items p-0 m-0">{{ $subCategory->sub_category }}</button>
                </form>
              </li>
              @endforeach
            </ul>
          </li>
          @endforeach
        </ul>
      </div>
      <form action="{{ route('post.show') }}" method="get" id="postSearchRequest"></form>
    </div>

    <!-- アコーディオン -->
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const toggles = document.querySelectorAll('.accordion-toggle');
        toggles.forEach(toggle => {
          toggle.addEventListener('click', function() {
            const targetId = toggle.getAttribute('data-target');
            const content = document.querySelector(targetId);
            const isOpen = toggle.classList.toggle('open');
            content.style.display = isOpen ? 'block' : 'none';
          });
        });
      });
    </script>



</x-sidebar>
