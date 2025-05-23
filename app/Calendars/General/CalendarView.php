<?php

namespace App\Calendars\General;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CalendarView
{

  private $carbon;
  function __construct($date)
  {
    $this->carbon = new Carbon($date);
  }

  public function getTitle()
  {
    return $this->carbon->format('Y年n月');
  }

  function render()
  {
    $html = [];
    $html[] = '<div class="calendar text-center">';
    $html[] = '<table class="table">';
    $html[] = '<thead>';
    $html[] = '<tr>';
    $html[] = '<th>月</th>';
    $html[] = '<th>火</th>';
    $html[] = '<th>水</th>';
    $html[] = '<th>木</th>';
    $html[] = '<th>金</th>';
    $html[] = '<th class="day-sat">土</th>';
    $html[] = '<th class="day-sun">日</th>';
    $html[] = '</tr>';
    $html[] = '</thead>';
    $html[] = '<tbody>';
    $weeks = $this->getWeeks();

    foreach ($weeks as $week) {
      $html[] = '<tr class="' . $week->getClassName() . '">';
      $days = $week->getDays();

      foreach ($days as $day) {
        $dayDate = new Carbon($day->everyDay());
        $isCurrentMonth = $dayDate->format('y-m') === $this->carbon->format('y-m');
        // 今日より後なら過去日とする
        $isPast = $dayDate->lt(Carbon::today());
        // 曜日を取得
        $dayOfWeek = $dayDate->dayOfWeek;
        // 背景色の設定
        $tdClass = 'calendar-td ' . $day->getClassName();
        $dayClass = '';

        if ($dayOfWeek === 0) {
          $dayClass .= 'day-sun';
        } elseif ($dayOfWeek === 6) {
          $dayClass .= 'day-sat';
        }

        if (!$isCurrentMonth) {
          $tdStyle = 'background-color: #ccc';
        } elseif ($isPast) {
          $tdStyle = 'background-color: #EEEEEE';
        } else {
          $tdStyle = '';
        }
        $html[] = '<td class="' . $tdClass . '" style="' . $tdStyle . '">';
        $html[] = $day->render();

        if (!$isCurrentMonth) {
          $html[] = '<p class="text-center m-0" style="font-size:12px;">&nbsp;</p>';
        } else {
          if (in_array($day->everyDay(), $day->authReserveDay())) {
            $reservePart = $day->authReserveDate($day->everyDay())->first()->setting_part;
            // 予約前の表示
            if ($reservePart == 1) {
              $reservePart = "リモ1部";
            } elseif ($reservePart == 2) {
              $reservePart = "リモ2部";
            } elseif ($reservePart == 3) {
              $reservePart = "リモ3部";
            }
            if ($isPast) {
              $html[] = '<p class="text-center m-0" style="font-size:12px;">' . $reservePart . '</p>';
            } else {
              // 予約している時の表示
              $settingDate = $day->authReserveDate($day->everyDay())->first()->setting_reserve;
              $settingPart = $day->authReserveDate($day->everyDay())->first()->setting_part;

              $html[] = '<button type="button" class="btn btn-danger p-0 w-75  js-modal-open" style="font-size:12px;"
              data-reserve-date="' . $settingDate . '"
              data-reserve-part="' . $settingPart . '">' . $reservePart . '</button>';
            }
            $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
          } else {
            if ($isPast) {
              $html[] = '<p class="text-center m-0 text-secondary" style="font-size:12px;">受付終了</p>';
              $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
            } else {
              $html[] = $day->selectPart($day->everyDay());
            }
          }
        }
        $html[] = $day->getDate();
        $html[] = '</td>';
      }
      $html[] = '</tr>';
    }

    $html[] = '</tbody>';
    $html[] = '</table>';
    $html[] = '</div>';
    $html[] = '<form action="/reserve/calendar" method="post" id="reserveParts">' . csrf_field() . '</form>';
    $html[] = '<form action="/delete/calendar" method="post" id="deleteParts">' . csrf_field() . '</form>';

    return implode('', $html);
  }

  protected function getWeeks()
  {
    $weeks = [];
    $firstDay = $this->carbon->copy()->firstOfMonth();
    $lastDay = $this->carbon->copy()->lastOfMonth();
    $week = new CalendarWeek($firstDay->copy());
    $weeks[] = $week;
    $tmpDay = $firstDay->copy()->addDay(7)->startOfWeek();
    while ($tmpDay->lte($lastDay)) {
      $week = new CalendarWeek($tmpDay, count($weeks));
      $weeks[] = $week;
      $tmpDay->addDay(7);
    }
    return $weeks;
  }
}
