<?php
declare(strict_types=1);
return [
    'currency_provides' =>  [
        'HRN/RUB' => App\Parser\Minfin::class,
        'HRN/DOL' => App\Parser\Minfin::class,
        'HRN/RUR' => App\Parser\PrivatBank::class,
        'HRN/USD' => App\Parser\PrivatBank::class,
        'HRN/WMZ' => App\Parser\ObmenkaUa::class,
        'HRN/WMR' => App\Parser\ObmenkaUa::class,
        'HRN/WMB' => App\Parser\ObmenkaUa::class,
        'DOL/HRN' => App\Parser\Minfin::class,
        'DOL/RUB' => App\Parser\SberBankRF::class,
        'RUB/HRN' => App\Parser\Minfin::class,
        'RUB/DOL' => App\Parser\SberBankRF::class
    ],
    'txt_path' => 'D:\\courses.txt'
];