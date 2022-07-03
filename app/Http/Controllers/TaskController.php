<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entities\Plant;
use App\Entities\Task;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TaskController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $tasks = Task::where('user_id', $user->id)->with(['plant'])->get();

        $carbon = Carbon::now();
        $today = $carbon->toDateString();
        $carbon->addDays(1);
        $tomorrow = $carbon->toDateString();
        $tomorrow_day = $carbon->format('F jS');

        $due_tasks = Task::where([['user_id', $user->id], ['completed', 0]])->where('date', '<', $today)->with(['plant'])->get();
        $today_tasks = Task::where([['user_id', $user->id], ['completed', 0]])->where('date', '=', $today)->with(['plant'])->get();
        $tomorrow_tasks = Task::where([['user_id', $user->id], ['completed', 0]])->where('date', '=', $tomorrow)->with(['plant'])->get();
        return view('pages.task.index', compact('tasks', 'due_tasks', 'today_tasks', 'tomorrow_tasks', 'tomorrow_day'));
    }

    public function complete(Request $request) {
        $input = $request->input();

        $task_id = $input['task_id'];

        Task::where('id', $task_id)->update(['completed' => 1]);

        return redirect()->route('tasks');
    }

    public function complete_multitasks(Request $request) {
        $input = $request->input();

        $task_ids = explode (",", $input['task_ids']); 

        Task::whereIn('id', $task_ids)->update(['completed' => 1]);

        return redirect()->route('tasks');
    }
}