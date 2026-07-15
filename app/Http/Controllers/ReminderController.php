<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;

class ReminderController extends Controller
{
    public function mark(Request $request, Reminder $reminder): RedirectResponse
    {
        $user = $request->user();

        if (! $user || $user->getKey() !== $reminder->user_id) {
            abort(403);
        }

        $reminder->acknowledged_at = now();
        $reminder->save();

        return redirect()->back()->with('reminder_acknowledged', true);
    }

    public function ack(Request $request, Reminder $reminder): JsonResponse
    {
        $user = $request->user();

        abort_unless($user && $user->getKey() === $reminder->user_id, 403);

        $reminder->acknowledged_at = now();
        $reminder->save();

        return response()->json(['ok' => true]);
    }
}
