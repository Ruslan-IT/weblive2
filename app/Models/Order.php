<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'product',
        'amount',
        'status',
        'payment_method',
        'robokassa_inv_id',
        'robokassa_signature',
        'robokassa_response',
        'paid_at',
    ];

    protected $casts = [
        'robokassa_response' => 'array',
        'paid_at' => 'datetime',
    ];

    // Для удобства отображения
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            1 => 'Ожидает оплаты',
            2 => 'Оплачен',
            3 => 'Отменён',
            4 => 'Ошибка',
            default => 'Создан',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            1 => 'warning',
            2 => 'success',
            3 => 'danger',
            4 => 'gray',
            default => 'secondary',
        };
    }
}

