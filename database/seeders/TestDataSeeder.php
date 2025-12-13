<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Apartment;
use App\Models\Provider;
use App\Models\Service;
use App\Models\Rate;
use App\Models\Charge;
use App\Models\Payment;
use Carbon\Carbon;

class TestDataSeeder extends Seeder
{
    public function run()
    {
        // ------------------
        // Услуги
        // ------------------
        $services = ['Содержание', 'Вода', 'Свет', 'Вывоз мусора', 'Интернет', 'Домофон', 'Отопление', 'Газ'];
        foreach ($services as $service) {
            Service::create(['name' => $service]);
        }

        // ------------------
        // Поставщики
        // ------------------
        $providers = [
            ['name' => 'ООО «Чистая планета»', 'phone' => '111111', 'email' => 'water@example.com', 'site_url' => 'https://ikvp.ru/'],
            ['name' => 'Энергосбыт', 'phone' => '222222', 'email' => 'electric@example.com', 'site_url' => 'https://electric.example.com'],
            ['name' => 'Интернет-провайдер', 'phone' => '333333', 'email' => 'internet@example.com', 'site_url' => 'https://internet.example.com'],
        ];
        foreach ($providers as $p) {
            Provider::create($p);
        }

        // ------------------
        // Квартиры
        // ------------------
        $apartments = [
            ['name' => 'Комсомольский р-н', 'address' => 'ул. Лизы Чайкиной, д.56, кв.324'],
            ['name' => 'Центральный р-н', 'address' => 'ул. Горького, д.66, кв.51'],
        ];
        foreach ($apartments as $a) {
            Apartment::create($a);
        }

        // ------------------
        // Тарифы
        // ------------------
        $allApartments = Apartment::all();
        $allProviders = Provider::all();
        $allServices = Service::all();

        foreach ($allApartments as $apartment) {
            foreach ($allServices as $service) {
                $provider = $allProviders->random();
                Rate::create([
                    'apartment_id' => $apartment->id,
                    'provider_id' => $provider->id,
                    'service_id' => $service->id,
                    'price' => rand(100, 1000),
                    'type' => 'fixed',
                    'start_date' => Carbon::now()->subMonths(rand(0, 6)),
                ]);
            }
        }

        // ------------------
        // Начисления (Charges)
        // ------------------
        foreach ($allApartments as $apartment) {
            foreach ($allServices as $service) {
                for ($i = 0; $i < 3; $i++) {
                    Charge::create([
                        'apartment_id' => $apartment->id,
                        'service_id' => $service->id,
                        'amount' => rand(100, 1000),
                        'period' => Carbon::now()->subMonths($i),
                        'comment' => 'Тестовое начисление',
                    ]);
                }
            }
        }

        // ------------------
        // Платежи (Payments)
        // ------------------
        foreach ($allApartments as $apartment) {
            for ($i = 0; $i < 5; $i++) {
                Payment::create([
                    'apartment_id' => $apartment->id,
                    'amount' => rand(500, 3000),
                    'payment_date' => Carbon::now()->subDays($i * 10),
                    'comment' => 'Тестовый платеж',
                ]);
            }
        }
    }
}
