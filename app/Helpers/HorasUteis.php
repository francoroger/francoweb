<?php

namespace App\Helpers;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Cmixin\BusinessTime;

class HorasUteis
{
  public static function diffAsBusinessInterval($from, $to)
  {
    $monday = config('businesstime.monday') ? [config('businesstime.monday1') . '-' . config('businesstime.monday2'), config('businesstime.monday3') . '-' . config('businesstime.monday4')] : [];
    $tuesday = config('businesstime.tuesday') ? [config('businesstime.tuesday1') . '-' . config('businesstime.tuesday2'), config('businesstime.tuesday3') . '-' . config('businesstime.tuesday4')] : [];
    $wednesday = config('businesstime.wednesday') ? [config('businesstime.wednesday1') . '-' . config('businesstime.wednesday2'), config('businesstime.wednesday3') . '-' . config('businesstime.wednesday4')] : [];
    $thursday = config('businesstime.thursday') ? [config('businesstime.thursday1') . '-' . config('businesstime.thursday2'), config('businesstime.thursday3') . '-' . config('businesstime.thursday4')] : [];
    $friday = config('businesstime.friday') ? [config('businesstime.friday1') . '-' . config('businesstime.friday2'), config('businesstime.friday3') . '-' . config('businesstime.friday4')] : [];
    $saturday = config('businesstime.saturday') ? [config('businesstime.saturday1') . '-' . config('businesstime.saturday2'), config('businesstime.saturday3') . '-' . config('businesstime.saturday4')] : [];
    $sunday = config('businesstime.sunday') ? [config('businesstime.sunday1') . '-' . config('businesstime.sunday2'), config('businesstime.sunday3') . '-' . config('businesstime.sunday4')] : [];

    BusinessTime::enable('Illuminate\Support\Carbon', [
      'monday' => $monday,
      'tuesday' => $tuesday,
      'wednesday' => $wednesday,
      'thursday' => $thursday,
      'friday' => $friday,
      'saturday' => $saturday,
      'sunday' => $sunday,
      'exceptions' => [],
      // You can use the holidays provided by BusinessDay
      // and mark them as fully closed days using 'holidaysAreClosed'
      'holidaysAreClosed' => true,
      // Note that exceptions will still have the precedence over
      // the holidaysAreClosed option.
      'holidays' => [
        'ano-novo'                              => '01-01',
        'tiradentes'                            => '04-21',
        'sexta-feira-santa'                     => '= easter -2',
        'pascoa'                                => '= easter',
        'dia-do-trabalho'                       => '05-01',
        '2nd-sunday-of-may'                     => '= second Sunday of May',
        '2nd-sunday-of-august'                  => '= second Sunday of August',
        'independecia'                          => '09-07',
        'nossa-sra-aparecida'                   => '10-12',
        'finados'                               => '11-02',
        'proclamacao-da-republica'              => '11-15',
        'natal'                                 => '12-25',
      ],
    ]);

    $options = 0;

    return Carbon::parse($from)->diffAsBusinessInterval($to, $options);
  }

  public static function calculaHoras($from, $to, $short = true)
  {
    $monday = config('businesstime.monday') ? [config('businesstime.monday1') . '-' . config('businesstime.monday2'), config('businesstime.monday3') . '-' . config('businesstime.monday4')] : [];
    $tuesday = config('businesstime.tuesday') ? [config('businesstime.tuesday1') . '-' . config('businesstime.tuesday2'), config('businesstime.tuesday3') . '-' . config('businesstime.tuesday4')] : [];
    $wednesday = config('businesstime.wednesday') ? [config('businesstime.wednesday1') . '-' . config('businesstime.wednesday2'), config('businesstime.wednesday3') . '-' . config('businesstime.wednesday4')] : [];
    $thursday = config('businesstime.thursday') ? [config('businesstime.thursday1') . '-' . config('businesstime.thursday2'), config('businesstime.thursday3') . '-' . config('businesstime.thursday4')] : [];
    $friday = config('businesstime.friday') ? [config('businesstime.friday1') . '-' . config('businesstime.friday2'), config('businesstime.friday3') . '-' . config('businesstime.friday4')] : [];
    $saturday = config('businesstime.saturday') ? [config('businesstime.saturday1') . '-' . config('businesstime.saturday2'), config('businesstime.saturday3') . '-' . config('businesstime.saturday4')] : [];
    $sunday = config('businesstime.sunday') ? [config('businesstime.sunday1') . '-' . config('businesstime.sunday2'), config('businesstime.sunday3') . '-' . config('businesstime.sunday4')] : [];

    BusinessTime::enable('Illuminate\Support\Carbon', [
      'monday' => $monday,
      'tuesday' => $tuesday,
      'wednesday' => $wednesday,
      'thursday' => $thursday,
      'friday' => $friday,
      'saturday' => $saturday,
      'sunday' => $sunday,
      'exceptions' => [],
      // You can use the holidays provided by BusinessDay
      // and mark them as fully closed days using 'holidaysAreClosed'
      'holidaysAreClosed' => true,
      // Note that exceptions will still have the precedence over
      // the holidaysAreClosed option.
      'holidays' => [
        'ano-novo'                              => '01-01',
        'tiradentes'                            => '04-21',
        'sexta-feira-santa'                     => '= easter -2',
        'pascoa'                                => '= easter',
        'dia-do-trabalho'                       => '05-01',
        '2nd-sunday-of-may'                     => '= second Sunday of May',
        '2nd-sunday-of-august'                  => '= second Sunday of August',
        'independecia'                          => '09-07',
        'nossa-sra-aparecida'                   => '10-12',
        'finados'                               => '11-02',
        'proclamacao-da-republica'              => '11-15',
        'natal'                                 => '12-25',
      ],
    ]);

    $options = 0;

    $interval = Carbon::parse($from)->diffAsBusinessInterval($to, $options);

    if ($short) {
      return $interval->forHumans(\Carbon\CarbonInterface::DIFF_ABSOLUTE, true, 3);
    } else {
      return $interval->forHumans(\Carbon\CarbonInterface::DIFF_ABSOLUTE, false, 4);
    }
  }

  public static function calculaIntervalo($from, $to)
  {
    $monday = config('businesstime.monday') ? [config('businesstime.monday1') . '-' . config('businesstime.monday2'), config('businesstime.monday3') . '-' . config('businesstime.monday4')] : [];
    $tuesday = config('businesstime.tuesday') ? [config('businesstime.tuesday1') . '-' . config('businesstime.tuesday2'), config('businesstime.tuesday3') . '-' . config('businesstime.tuesday4')] : [];
    $wednesday = config('businesstime.wednesday') ? [config('businesstime.wednesday1') . '-' . config('businesstime.wednesday2'), config('businesstime.wednesday3') . '-' . config('businesstime.wednesday4')] : [];
    $thursday = config('businesstime.thursday') ? [config('businesstime.thursday1') . '-' . config('businesstime.thursday2'), config('businesstime.thursday3') . '-' . config('businesstime.thursday4')] : [];
    $friday = config('businesstime.friday') ? [config('businesstime.friday1') . '-' . config('businesstime.friday2'), config('businesstime.friday3') . '-' . config('businesstime.friday4')] : [];
    $saturday = config('businesstime.saturday') ? [config('businesstime.saturday1') . '-' . config('businesstime.saturday2'), config('businesstime.saturday3') . '-' . config('businesstime.saturday4')] : [];
    $sunday = config('businesstime.sunday') ? [config('businesstime.sunday1') . '-' . config('businesstime.sunday2'), config('businesstime.sunday3') . '-' . config('businesstime.sunday4')] : [];

    BusinessTime::enable('Illuminate\Support\Carbon', [
      'monday' => $monday,
      'tuesday' => $tuesday,
      'wednesday' => $wednesday,
      'thursday' => $thursday,
      'friday' => $friday,
      'saturday' => $saturday,
      'sunday' => $sunday,
      'exceptions' => [],
      // You can use the holidays provided by BusinessDay
      // and mark them as fully closed days using 'holidaysAreClosed'
      'holidaysAreClosed' => true,
      // Note that exceptions will still have the precedence over
      // the holidaysAreClosed option.
      'holidays' => [
        'ano-novo'                              => '01-01',
        'tiradentes'                            => '04-21',
        'sexta-feira-santa'                     => '= easter -2',
        'dia-do-trabalho'                       => '05-01',
        'revolucao-1932'                        => '07-09',
        'independecia'                          => '09-07',
        'nossa-sra-aparecida'                   => '10-12',
        'finados'                               => '11-02',
        'proclamacao-da-republica'              => '11-15',
        'natal'                                 => '12-25',
      ],
    ]);

    $options = 0;

    $interval = Carbon::parse($from)->diffInBusinessSeconds($to, $options);

    return $interval;
  }
}
