<?php

namespace App\Http\Controllers\Authenticated\Calendar\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Calendars\Admin\CalendarView;
use App\Calendars\Admin\CalendarSettingView;
use App\Models\Calendars\ReserveSettings;
use App\Models\Calendars\Calendar;
use App\Models\USers\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CalendarsController extends Controller
{
    public function show()
    {
        $calendar = new CalendarView(time());
        return view('authenticated.calendar.admin.calendar', compact('calendar'));
    }

    public function reserveDetail($part, $date)
    {
        $reserveSetting = ReserveSettings::whereDate('setting_reserve', $date)
            ->where('setting_part', $part)
            ->first();

        if (!$reserveSetting) {
            $users = collect();
        } else {
            $users = User::whereHas('reserveSettings', function ($q) use ($reserveSetting) {
                $q->where('reserve_setting_id', $reserveSetting->id);
            })->get();
        }

        return view('authenticated.calendar.admin.reserve_detail', compact('users', 'date', 'part'));
    }

    public function reserveSettings()
    {
        $calendar = new CalendarSettingView(time());
        return view('authenticated.calendar.admin.reserve_setting', compact('calendar'));
    }

    public function updateSettings(Request $request)
    {
        $reserveDays = $request->input('reserve_day');
        foreach ($reserveDays as $day => $parts) {
            foreach ($parts as $part => $frame) {
                ReserveSettings::updateOrCreate([
                    'setting_reserve' => $day,
                    'setting_part' => $part,
                ], [
                    'setting_reserve' => $day,
                    'setting_part' => $part,
                    'limit_users' => $frame,
                ]);
            }
        }
        return redirect()->route('calendar.admin.setting', ['user_id' => Auth::id()]);
    }
}
