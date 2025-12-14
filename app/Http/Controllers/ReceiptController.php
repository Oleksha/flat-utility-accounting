<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use App\Models\Charge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ReceiptController extends Controller
{
    /**
     * Сохранение квитанции
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'apartment_id' => 'required|exists:apartments,id',
            'month'        => 'required|integer|min:1|max:12',
            'year'         => 'required|integer|min:2000|max:2100',
            'file'         => 'required|file|mimes:pdf|max:10240',
            'charges'      => 'nullable|array',
            'charges.*'    => 'exists:charges,id',
        ]);

        // Период квитанции (1-е число месяца)
        $period = Carbon::create($data['year'], $data['month'], 1);

        // Папка хранения
        $directory = sprintf(
            'receipts/%d/%d/%02d',
            $data['apartment_id'],
            $period->year,
            $period->month
        );

        // Сохраняем файл
        $file = $request->file('file');

        $path = $file->store($directory, 'public');

        // Создаём квитанцию
        $receipt = Receipt::create([
            'apartment_id'  => $data['apartment_id'],
            'period'        => $period,
            'file_path'     => $path,
            'original_name' => $file->getClientOriginalName(),
        ]);

        // Привязываем начисления (если выбраны)
        if (!empty($data['charges'])) {
            $receipt->charges()->sync($data['charges']);
        }

        return back()->with('success', 'Квитанция успешно загружена');
    }

    /**
     * Удаление квитанции
     */
    public function destroy(Receipt $receipt)
    {
        // Удаляем файл
        if ($receipt->file_path) {
            Storage::disk('public')->delete($receipt->file_path);
        }

        // Отвязываем начисления и удаляем запись
        $receipt->charges()->detach();
        $receipt->delete();

        return back()->with('success', 'Квитанция удалена');
    }

    /**
     * Скачать квитанцию
     */
    public function download(Receipt $receipt)
    {
        return Storage::disk('public')->download(
            $receipt->file_path,
            $receipt->original_name ?? 'receipt.pdf'
        );
    }
}
