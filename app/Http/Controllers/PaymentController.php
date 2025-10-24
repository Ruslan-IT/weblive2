<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

use Filament\Notifications\Notification;
use Filament\Notifications\Events\DatabaseNotificationsSent;
use App\Models\User;

class PaymentController extends Controller
{
    private string $merchantLogin;
    private string $pass1;
    private string $pass2;
    private bool $isTest;

    public function __construct()
    {
        // Подтягиваем данные из config/services.php, который читает .env
        $this->merchantLogin = config('services.robokassa.merchant_login');
        //$this->pass1 = config('services.robokassa.password_1');
        //$this->pass2 = config('services.robokassa.password_2');

        $this->pass1 = config('services.robokassa.password_1');
        $this->pass2 = config('services.robokassa.password_2');

        $this->isTest = config('services.robokassa.test_mode', true);
    }

    /**
     * Создание платежа и перенаправление пользователя на страницу оплаты
     */
    public function create(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'product' => 'required|string',
            'amount' => 'required|numeric',
        ]);

        // При создании заказа
        $order = Order::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'product' => $validated['product'],
            'amount' => $validated['amount'],
            'status' => 1, // "Ожидает оплаты"
            'payment_method' => 'robokassa',
        ]);

        // Подпись для Robokassa
        $signature = md5("{$this->merchantLogin}:{$order->amount}:{$order->id}:{$this->pass1}");

        $params = [
            'MerchantLogin' => $this->merchantLogin,
            'InvId' => $order->id,
            'Description' => "Оплата заказа №{$order->id}",
            'OutSum' => $order->amount,
            'SignatureValue' => $signature,
            'Culture' => 'ru',
            'Encoding' => 'utf-8',
            'IsTest' => $this->isTest ? 1 : 0,
            'SuccessURL' => route('payment.success'),
            'FailURL' => route('payment.fail'),
        ];

        $url = 'https://auth.robokassa.ru/Merchant/Index.aspx?' . http_build_query($params);

        return response()->json(['url' => $url]);
    }

    /**
     * Успешная оплата (возврат пользователя после платежа)
     */
    public function success(Request $request)
    {
        $invId = $request->get('InvId');
        $outSum = $request->get('OutSum');
        $crc = strtoupper($request->get('SignatureValue'));

        $order = Order::find($invId);
        if (!$order) {
            return 'Ошибка: заказ не найден';
        }

        $myCrc = strtoupper(md5("$outSum:$invId:{$this->pass1}"));

        if ($myCrc === $crc) {

            $order->update([
                'status' => 2,
                'robokassa_signature' => $crc,
                'robokassa_response' => $request->all(),
                'paid_at' => now(),
            ]);

            return Inertia::render('Payment/Success', [
                'order' => $order,
            ]);
        }

        return Inertia::render('Payment/Fail', [
            'message' => 'Ошибка проверки подписи.',
        ]);
    }

    /**
     * Callback от Robokassa (серверный запрос)
     */
    public function result(Request $request)
    {
        $invId = $request->get('InvId');
        $outSum = $request->get('OutSum');
        $crc = strtoupper($request->get('SignatureValue'));

        $order = Order::find($invId);
        if (!$order) {
            return 'bad sign';
        }

        $myCrc = strtoupper(md5("$outSum:$invId:{$this->pass2}"));

        if ($myCrc === $crc) {

            $order->update([
                'status' => 2,
                'robokassa_response' => $request->all(),
                'robokassa_signature' => $crc,
                'paid_at' => now(),
            ]);

            // 🔔 Отправляем уведомление всем администраторам Filament
            $admins = User::where('is_admin', true)->get(); // Или по твоей логике ролей

            foreach ($admins as $admin) {
                Notification::make()
                    ->title('💳 Новый платёж Robokassa')
                    ->body("
                    **{$order->name}** оплатил заказ **#{$order->id}**
                    Товар: *{$order->product}*
                    Сумма: **{$order->amount} ₽**
                    ")
                    ->success()
                    ->sendToDatabase($admin);
            }

            echo "OK$invId\n";
        } else {
            $order->update([
                'status' => 4, // Ошибка подписи
                'robokassa_response' => $request->all(),
            ]);

            echo "bad sign\n";
        }
    }

    /**
     * Ошибка или отмена оплаты
     */
    public function fail(Request $request)
    {
        $invId = $request->get('InvId');

        if ($order = Order::find($invId)) {
            $order->update([
                'status' => 3,
                'robokassa_response' => $request->all(),
            ]);
        }

        return Inertia::render('Payment/Fail', [
            'message' => 'Платеж был отменён пользователем.',
        ]);
    }
}
