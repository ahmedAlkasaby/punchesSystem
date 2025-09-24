<?php

use App\Models\Export;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;




Route::get('/', function () {
    return view('welcome');
});
Route::get('/user/toggle-lang', function () {
    $user = auth()->user();
    if ($user) {
        $newLang = $user->lang === 'ar' ? 'en' : 'ar';
        $user->update(['lang' => $newLang]);
        app()->setLocale($newLang);
    }
    return back();
})->name('user.toggle-lang')->middleware(['auth']);

Route::get('/exports/{export}/download', function (Export $export) {
    $extension = 'xlsx';

    $path = "filament_exports/{$export->id}/{$export->file_name}.{$extension}";

    if (! Storage::disk('public')->exists($path)) {
        abort(404, 'الملف غير موجود');
    }

    return Storage::disk('public')->download($path);
})->name('exports.download')->middleware('auth');



