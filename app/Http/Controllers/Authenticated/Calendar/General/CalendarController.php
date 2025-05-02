<?php

namespace App\Http\Controllers\Authenticated\Calendar\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Calendars\General\CalendarView;
use App\Models\Calendars\ReserveSettings;
use App\Models\Calendars\Calendar;
use App\Models\USers\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CalendarController extends Controller
{
    public function show()
    {
        $calendar = new CalendarView(time());
        return view('authenticated.calendar.general.calendar', compact('calendar'));
    }

    public function reserve(Request $request)
    {
        DB::beginTransaction();
        try {
            $getPart = $request->getPart;
            $getDate = $request->getData;
            $reserveDays = array_filter(array_combine($getDate, $getPart));
            foreach ($reserveDays as $key => $value) {
                $reserve_settings = ReserveSettings::where('setting_reserve', $key)->where('setting_part', $value)->first();
                $reserve_settings->decrement('limit_users');
                $reserve_settings->users()->attach(Auth::id());
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }
        return redirect()->route('calendar.general.show', ['user_id' => Auth::id()]);
    }

    // 予約キャンセル
    public function cancel(Request $request)
    {
        $date = $request->input('cancel_date');
        $part = $request->input('cancel_part');
        $userId = Auth::user()->id;
        // 予約枠取得
        $reserveSetting = ReserveSettings::whereDate('setting_reserve', $date)
            ->where('setting_part', $part)
            ->first();

        if ($reserveSetting) {
            $reserveSetting->users()->detach($userId);
            // 予約枠を戻す
            $reserveSetting->increment('limit_users');
        }

        return redirect()->route('calendar.general');
    }
}
