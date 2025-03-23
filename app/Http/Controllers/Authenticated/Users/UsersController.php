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
        $subjects = null; // ここで検索時の科目を受け取る
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
    // 検索条件
    // 名前、性別、役職、選択科目
    // 並び替え条件：IDの昇降順、名前昇降順
    public function userSearch(Request $request)
    {
        // 検索フォームに入力された値を取得
        $keyword = $request->input('keyword');
        $sex = $request->input('sex');
        $role = $request->input('role');
        $subject = $request->input('subject');

        // テーブル結合してデータを取得
        $query = User::select('users.*')
            ->join('subject_user', 'users.id', '=', 'subject_user.user_id')
            ->join('subjects', 'subject_user.subject_id', '=', 'subjects.id');


        // 仮組名前は４種類あるので模索する over_name,under_name,over_name_kana,under_name_kanaの４種類
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

        // 複数の科目で検索した場合、あてはまる科目を一つでも選択しているユーザーはすべて表示させる
        if (!empty($subject)) {
            $query->whereIn('subject.subject', (array) $subject);
        }

        // 結果を取得
        $users = $query->distinct()->get();

        return view('/users/search', ['users' => $users]);
    }
}
