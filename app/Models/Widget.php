<?php

namespace App\Models;

use App\Exceptions\WidgetNotFoundException;
use App\Exceptions\WidgetUnknownTypeException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Widget extends Model
{
    use SoftDeletes;

    protected $casts = [
        'settings' => 'object'
    ];

    protected $fillable = [
        'user_id',
        'type',
        'sorting_index',
        'settings'
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeOrdered($query)
    {
        return $query->orderBy('sorting_index');
    }

    // Custom
    public static function getTypes(): array
    {
        return [
            'balance',
            'spent'
        ];
    }

    public function render(): string
    {
        $widgetClass = '\App\Widgets\\' . ucfirst($this->type);

        if (!class_exists($widgetClass)) {
            throw new WidgetUnknownTypeException();
        }

        $widgetInstance = new $widgetClass($this->settings);

        return $widgetInstance->render();
    }
}
