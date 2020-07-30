<?php

namespace App\Http\Controllers;

use App\Actions\MoveWidgetDownAction;
use App\Actions\MoveWidgetUpAction;
use App\Models\Widget;
use Illuminate\Http\Request;

class WidgetController extends Controller
{
    public function create()
    {
        return view('widgets.create', [
            'types' => Widget::getTypes()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:' . implode(',', Widget::getTypes())
        ]);

        Widget::create([
            'user_id' => $request->user()->id,
            'type' => $request->type,
            'sorting_index' => 1,
            'settings' => (object) []
        ]);

        return redirect()->route('settings.dashboard');
    }

    public function up(Request $request, Widget $widget)
    {
        if (!$request->user()->can('access', $widget)) {
            return redirect()->route('settings.dashboard');
        }

        (new MoveWidgetUpAction())->execute($widget->id);

        return redirect()->route('settings.dashboard');
    }

    public function down(Request $request, Widget $widget)
    {
        if (!$request->user()->can('access', $widget)) {
            return redirect()->route('settings.dashboard');
        }

        (new MoveWidgetDownAction())->execute($widget->id);

        return redirect()->route('settings.dashboard');
    }

    public function destroy(Request $request, Widget $widget)
    {
        if (!$request->user()->can('access', $widget)) {
            return redirect()->route('settings.dashboard');
        }

        $widget->delete();

        return redirect()->route('settings.dashboard');
    }
}
