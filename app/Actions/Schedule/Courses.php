<?php

namespace App\Actions\Schedule;

use App\Models\ExchangeRate;
use Illuminate\Support\Facades\Http;

class Courses
{
    public function __invoke(): void
    {
        $response = Http::get('https://developerhub.alfabank.by:8273/partner/1.0.1/public/rates', [
            'verify' => false,
        ]);
        if ($response->status() == 200) {
            foreach ($response->json('rates') as $rate) {
                $rateModel = ExchangeRate::query()
                    ->where('from', $rate['buyIso'])
                    ->where('to', $rate['sellIso'])
                    ->first();
                if ($rateModel) {
                    $rateModel->rate = intval(round($rate['buyRate'], 4) * 10000);
                    $rateModel->save();

                    $rateModelReverse = ExchangeRate::query()
                        ->where('from', $rate['sellIso'])
                        ->where('to', $rate['buyIso'])
                        ->first();
                    $rateModelReverse->rate = intval(1 / round($rate['buyRate'], 2) * 10000);
                    $rateModelReverse->save();
                }
            }
        }
    }
}
