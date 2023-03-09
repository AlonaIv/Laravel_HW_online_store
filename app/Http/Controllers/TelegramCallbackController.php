<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use pschocke\TelegramLoginWidget\Facades\TelegramLoginWidget;

class TelegramCallbackController extends Controller
{
    public function __invoke(Request $request)
    {
        if (!$telegramUser = TelegramLoginWidget::validate($request)) {
            notify()->error('Please try again :(', position: 'topRight');
            return redirect()->route('account.index');
        }

        auth()->user()->update([
            'telegram_id' => $telegramUser->get('id')
        ]);

        notify()->success('Telegram added', position: 'topRight');
        return redirect()->route('account.index');
    }
}
