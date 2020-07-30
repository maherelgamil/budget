<?php

namespace App\Widgets;

use App\Exceptions\WidgetInvalidSettingValueException;
use App\Exceptions\WidgetMissingSettingsException;
use App\Helper;
use App\Models\Space;
use App\Models\Spending;

class Spent
{
    private $requiredSettings = [
        'period'
    ];

    private $settings;

    public function __construct(object $settings)
    {
        $this->settings = $settings;

        foreach ($this->requiredSettings as $requiredSetting) {
            if (!isset($this->settings->{$requiredSetting})) {
                throw new WidgetMissingSettingsException();
            }
        }
    }

    public function render(): string
    {
        $space = Space::find(session('space_id'));

        $currencySymbol = $space->currency->symbol;

        if ($this->settings->period === 'today') {
            $spent = Spending::ofSpace(session('space_id'))
                ->whereRaw('DATE(happened_on) = ?', [date('Y-m-d')])
                ->sum('amount');
        }

        if ($this->settings->period === 'this_week') {
            $monday = date('Y-m-d', strtotime('monday this week'));
            $sunday = date('Y-m-d', strtotime('sunday this week'));

            $spent = Spending::ofSpace(session('space_id'))
                ->whereRaw('DATE(happened_on) >= ? AND DATE(happened_ON) <= ?', [$monday, $sunday])
                ->sum('amount');
        }

        if ($this->settings->period === 'this_month') {
            $spent = Spending::ofSpace(session('space_id'))
                ->whereRaw('YEAR(happened_on) = ? AND MONTH(happened_on) = ?', [date('Y'), date('n')])
                ->sum('amount');
        }

        if (!isset($spent)) {
            throw new WidgetInvalidSettingValueException();
        }

        return view('widgets.spent', [
            'currencySymbol' => $currencySymbol,
            'spent' => Helper::formatNumber($spent / 100),
            'period' => str_replace('_', ' ', $this->settings->period)
        ]);
    }
}
