<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class LogController extends Controller
{
    public function activityLogs(Request $request)
    {
        $models = Activity::select('subject_type')
            ->distinct()
            ->pluck('subject_type')
            ->map(fn($type) => class_basename($type))
            ->toArray();

        $events = Activity::select('event')->distinct()->pluck('event')->toArray();

        $logs = Activity::query()
            ->with('causer')
            ->when($request->search, function ($query, $search) {
                $query->whereRaw("MATCH(description) AGAINST(? IN BOOLEAN MODE)", [$search]);
            })
            ->when($request->model, function ($query, $model) {
                $query->where('subject_type', 'like', "%{$model}%");
            })
            ->when($request->subject_id, function ($query, $subject_id) {
                $query->where('subject_id', $subject_id);
            })
            ->when($request->event, function ($query, $event) {
                $query->where('event', $event);
            })
            ->orderBy('id', 'desc')
            ->paginate(10)->withQueryString();

        return view('pages.logs.activity', compact('logs', 'models', 'events'));
    }
}
