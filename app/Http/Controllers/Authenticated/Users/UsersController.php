<?php

namespace App\Http\Controllers\Authenticated\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Gate;
use App\Models\Users\User;
use App\Models\Users\Subjects;
use App\Searchs\DisplayUsers;
use App\Searchs\SearchResultFactories;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{

    public function showUsers(Request $request)
    {
        $keyword = $request->keyword;
        $category = $request->category;
        $updown = $request->updown;
        $gender = $request->sex;
        $role = $request->role;
        $subjects = $request->subject;
        $userFactory = new SearchResultFactories();
        $users = $userFactory->initializeUsers($keyword, $category, $updown, $gender, $role, $subjects);
        $subjects = Subjects::all();
        return view('authenticated.users.search', compact('users', 'subjects'));
    }

    public function userProfile($id)
    {
        $user = User::with('subjects')->findOrFail($id);
        $subject_lists = Subjects::all();
        return view('authenticated.users.profile', compact('user', 'subject_lists'));
    }

    public function userEdit(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $user->subjects()->sync($request->subjects);
        return redirect()->route('user.profile', ['id' => $request->user_id]);
    }



    // ユーザー検索
    public function userSearch(Request $request)
    {
        // 検索フォームに入力された値を取得
        // 名前orID
        // 昇降順
        $keyword = $request->input('keyword');
        $sex = $request->input('sex');
        $role = $request->input('role');
        $subjects = $request->input('subject');

        $query = User::query();

        // over_name,under_name,over_name_kana,under_name_kanaの４種類
        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('over_name', 'like', '%' . $keyword . '%')
                    ->orWhere('under_name', 'like', '%' . $keyword . '%')
                    ->orWhere('over_name_kana', 'like', '%' . $keyword . '%')
                    ->orWhere('under_name_kana', 'like', '%' . $keyword . '%');
            });
        }

        if (!empty($sex)) {
            $query->where('sex', '=', $sex);
        }

        if (!empty($role)) {
            $query->where('role', '=', $role);
        }

        if (!empty($subjects)) {
            $query->whereHas('subjects', function ($q) use ($subjects) {
                $q->whereIn('subjects.id', $subjects);
            });
        }

        // 結果を取得
        $users = $query->distinct()->get();

        return view('/users/search', ['users' => $users]);
    }
}
