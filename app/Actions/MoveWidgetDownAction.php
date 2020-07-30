<?php

namespace App\Actions;

use App\Exceptions\WidgetNotFoundException;
use App\Models\Widget;

class MoveWidgetDownAction
{
    public function execute(int $id): void
    {
        $widget = Widget::find($id);

        if (!$widget) {
            throw new WidgetNotFoundException();
        }

        $totalWidgetAmount = $widget->user->widgets->count();

        if ($widget->sorting_index > $totalWidgetAmount - 2) {
            return;
        }

        $nextSortingIndex = $widget->sorting_index + 1;

        $nextWidget = Widget::where('user_id', $widget->user->id)
            ->where('sorting_index', $nextSortingIndex)
            ->first();

        $nextWidget->update(['sorting_index' => $widget->sorting_index]);

        $widget->update(['sorting_index' => $nextSortingIndex]);
    }
}
