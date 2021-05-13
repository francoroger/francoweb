<?php

namespace App\Helpers;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Cmixin\BusinessTime;

class DiasUteis
{

  public static function calculaHoras($from, $to, $short = true)
  {
    BusinessTime::enable('Illuminate\Support\Carbon', [
      'monday' => ['00:00-12:00', '12:00-23:59'],
      'tuesday' => ['00:00-12:00', '12:00-23:59'],
      'wednesday' => ['00:00-12:00', '12:00-23:59'],
      'thursday' => ['00:00-12:00', '12:00-23:59'],
      'friday' => ['00:00-12:00', '12:00-23:59'],
      'saturday' => [],
      'sunday' => [],
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
    BusinessTime::enable('Illuminate\Support\Carbon', [
      'monday' => ['00:00-12:00', '12:00-23:59'],
      'tuesday' => ['00:00-12:00', '12:00-23:59'],
      'wednesday' => ['00:00-12:00', '12:00-23:59'],
      'thursday' => ['00:00-12:00', '12:00-23:59'],
      'friday' => ['00:00-12:00', '12:00-23:59'],
      'saturday' => [],
      'sunday' => [],
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
