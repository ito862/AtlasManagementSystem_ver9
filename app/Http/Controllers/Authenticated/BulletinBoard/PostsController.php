<?php

namespace App\Http\Controllers\Authenticated\BulletinBoard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories\MainCategory;
use App\Models\Categories\SubCategory;
use App\Models\Posts\Post;
use App\Models\Posts\PostComment;
use App\Models\Posts\Like;
use App\Models\Users\User;
use App\Http\Requests\BulletinBoard\PostFormRequest;
use App\Http\Requests\BulletinBoard\MainCategoryRequest;
use App\Http\Requests\BulletinBoard\SubCategoryRequest;
use App\Http\Requests\BulletinBoard\PostCommentRequest;
use Illuminate\Support\Facades\Auth;

class PostsController extends Controller
{
    public function show(Request $request)
    {
        $posts = Post::with('user', 'postComments', 'subCategories')->get();
        $categories = MainCategory::get();
        $like = new Like;
        $post_comment = new Post;

        if ($request->filled('keyword')) {
            //サブカテゴリーと完全一致検索
            $subCategory = SubCategory::where('sub_category', $request->keyword)->first();

            if ($subCategory) {
                $posts = $subCategory->posts()->with('user', 'postComments', 'subCategories')->get();
            } else {
                $posts = Post::with('user', 'postComments', 'subCategories')
                    ->where('post_title', 'like', '%' . $request->keyword . '%')
                    ->orWhere('post', 'like', '%' . $request->keyword . '%')
                    ->get();
            }
        } elseif ($request->filled('category_word')) {
            //カテゴリークリック（サブカテゴリ名）
            $subCategory = SubCategory::where('sub_category', $request->category_word)->first();

            if ($subCategory) {
                $posts = $subCategory->posts()->with('user', 'postComments', 'subCategories')->get();
            }
        } elseif ($request->filled('like_posts')) {
            //いいねした投稿
            $likes = Auth::user()->likePostId()->pluck('like_post_id');
            $posts = Post::with('user', 'postComments', 'subCategories')
                ->whereIn('id', $likes)
                ->get();
        } elseif ($request->filled('my_posts')) {
            //自分の投稿
            $posts = Post::with('user', 'postComments', 'subCategories')
                ->where('user_id', Auth::id())
                ->get();
        } else {
            $posts = Post::with('user', 'postComments', 'subCategories')->get();
        }

        return view('authenticated.bulletinboard.posts', compact('posts', 'categories', 'like', 'post_comment'));
    }

    public function postDetail($post_id)
    {
        $post = Post::with('user', 'postComments', 'subCategories')->findOrFail($post_id);
        return view('authenticated.bulletinboard.post_detail', compact('post'));
    }

    public function postInput()
    {
        $main_categories = MainCategory::get();
        $sub_categories = SubCategory::get();
        return view('authenticated.bulletinboard.post_create', compact('main_categories', 'sub_categories'));
    }


    public function postCreate(PostFormRequest $request)
    {
        $post = Post::create([
            'user_id' => Auth::id(),
            'post_title' => $request->post_title,
            'post' => $request->post_body
        ]);
        // サブカテゴリーへ紐付け
        if ($request->has('sub_category_id')) {
            $post->subCategories()->attach($request->sub_category_id);
        }

        return redirect()->route('post.show', ['post' => $post]);
    }

    public function postEdit(PostFormRequest $request)
    {
        Post::where('id', $request->post_id)->update([
            'post_title' => $request->post_title,
            'post' => $request->post_body,
        ]);
        return redirect()->route('post.detail', ['id' => $request->post_id]);
    }

    public function postDelete($id)
    {
        Post::findOrFail($id)->delete();
        return redirect()->route('post.show');
    }

    public function mainCategoryCreate(MainCategoryRequest $request)
    {
        MainCategory::create(['main_category' => $request->main_category_name]);
        return redirect()->route('post.input');
    }
    public function subCategoryCreate(SubCategoryRequest $request)
    {
        SubCategory::create([
            'sub_category' => $request->sub_category_name,
            // メインカテゴリーに紐付け
            'main_category_id' => $request->main_category_id,
        ]);
        return redirect()->route('post.input');
    }

    public function commentCreate(PostCommentRequest $request)
    {
        PostComment::create([
            'post_id' => $request->post_id,
            'user_id' => Auth::id(),
            'comment' => $request->comment
        ]);
        return redirect()->route('post.detail', ['id' => $request->post_id]);
    }

    public function myBulletinBoard()
    {
        $posts = Auth::user()->posts()->get();
        $like = new Like;
        return view('authenticated.bulletinboard.post_myself', compact('posts', 'like'));
    }

    public function likeBulletinBoard()
    {
        $like_post_id = Like::with('users')->where('like_user_id', Auth::id())->get('like_post_id')->toArray();
        $posts = Post::with('user')->whereIn('id', $like_post_id)->get();
        $like = new Like;
        return view('authenticated.bulletinboard.post_like', compact('posts', 'like'));
    }

    public function postLike(Request $request)
    {
        $user_id = Auth::id();
        $post_id = $request->post_id;

        $like = new Like;

        $like->like_user_id = $user_id;
        $like->like_post_id = $post_id;
        $like->save();

        return response()->json();
    }

    public function postUnLike(Request $request)
    {
        $user_id = Auth::id();
        $post_id = $request->post_id;

        $like = new Like;

        $like->where('like_user_id', $user_id)
            ->where('like_post_id', $post_id)
            ->delete();

        return response()->json();
    }
}
