<?php

namespace App\Actions;

use App\Exceptions\WidgetNotFoundException;
use App\Models\Widget;

class MoveWidgetUpAction
{
    public function execute(int $id): void
    {
        $widget = Widget::find($id);

        if (!$widget) {
            throw new WidgetNotFoundException();
        }

        if ($widget->sorting_index === 0) {
            return;
        }

        $previousSortingIndex = $widget->sorting_index - 1;

        $previousWidget = Widget::where('user_id', $widget->user->id)
            ->where('sorting_index', $previousSortingIndex)
            ->first();

        $previousWidget->update(['sorting_index' => $widget->sorting_index]);

        $widget->update(['sorting_index' => $previousSortingIndex]);
    }
}
