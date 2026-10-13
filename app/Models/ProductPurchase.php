<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPurchase extends Model
{
    protected $fillable = [
        'user_id',
        'purchase_date',
        'supplier',
        'reference',
        'total_cost',
        'funding_source',
        'items_count',
        'total_items_quantity',
        'notes',
        'photo_path',
    ];

    protected $casts = [
        'total_cost' => 'decimal:2',
        'items_count' => 'integer',
        'total_items_quantity' => 'integer',
        'purchase_date' => 'date',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(ProductPurchaseItem::class);
    }

    public function expense()
    {
        return $this->hasOne(Expense::class);
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
        return (float) Sale::where('status', 'completed')->sum('total');
    }

    /**
     * Saldo disponible de ventas para alzar y comprar nueva mercadería
     */
    public static function availableSalesBalance(): float
    {
        return max(0.0, self::rawSalesBalance());
    }

    /**
     * Saldo real. Las reposiciones nuevas generan un gasto enlazado; las compras
     * antiguas sin gasto se descuentan aquí para conservar compatibilidad.
     */
    public static function rawSalesBalance(): float
    {
        $expenses = (float) Expense::fromSales()->sum('amount');
        $legacyPurchases = (float) self::where('funding_source', 'sales_revenue')
            ->whereDoesntHave('expense')
            ->sum('total_cost');

        return self::totalSalesRevenue() - $expenses - $legacyPurchases;
    }

    public static function totalExpensesFromSales(): float
    {
        return (float) Expense::fromSales()->sum('amount');
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
