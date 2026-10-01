<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Sale;
use App\Models\User;

class ProductPurchase extends Model
{
    protected $fillable = [
        'user_id',
        'total_cost',
        'funding_source',
        'items_count',
        'total_items_quantity',
        'notes',
    ];

    protected $casts = [
        'total_cost' => 'decimal:2',
        'items_count' => 'integer',
        'total_items_quantity' => 'integer',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Total capital externo aportado como nueva inversión
     */
    public static function totalNewInvestment(): float
    {
        return (float) self::where('funding_source', 'new_investment')->sum('total_cost');
    }

    /**
     * Total alzado del dinero de ventas para comprar mercadería
     */
    public static function totalReinvestedFromSales(): float
    {
        return (float) self::where('funding_source', 'sales_revenue')->sum('total_cost');
    }

    /**
     * Total de ingresos acumulados por ventas
     */
    public static function totalSalesRevenue(): float
    {
        return (float) Sale::sum('total');
    }

    /**
     * Saldo disponible de ventas para alzar y comprar nueva mercadería
     */
    public static function availableSalesBalance(): float
    {
        return max(0.0, self::totalSalesRevenue() - self::totalReinvestedFromSales());
    }

    /**
     * Etiqueta amigable de la fuente de financiamiento
     */
    public function getFundingSourceLabelAttribute(): string
    {
        return match ($this->funding_source) {
            'new_investment' => 'Nueva Inversión (Capital Externo)',
            'sales_revenue' => 'Alzado de Ventas Acumuladas',
            default => 'Otro',
        };
    }
}
