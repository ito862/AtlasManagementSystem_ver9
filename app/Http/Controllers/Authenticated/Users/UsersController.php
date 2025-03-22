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


        // 仮組名前は４種類あるので模索する
        if (!empty($keyword)) {
            $query->where('over_name', 'like', '%' . $keyword . '%')->get();
        }

        if (!empty($sex)) {
            $query->where('sex', 'like', $sex);
        }

        if (!empty($role)) {
            $query->where('role', 'like', $role);
        }
        // subjectはID取得？
        // 複数の科目で検索した場合、あてはまる科目を一つでも選択しているユーザーはすべて表示させる
        if ($request->filled('subject')) {
            $query->where('subjects.subject', 'like', '%' . $request->subject . '%');
        }

        // 結果を取得
        $users = $query->get();

        return view('/users/search', ['users' => $users]);
    }
}
