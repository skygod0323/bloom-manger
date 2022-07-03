<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entities\Plant;
use App\Entities\Task;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PlantController extends Controller
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
        $plants = Plant::where('user_id', $user->id)->get();
        return view('pages.plant.index', compact('plants'));

        // $current = Carbon::createFromFormat('Y-m-d', '1975-05-21');
        // $current->addWeeks(-1);
        // var_dump($current->toDateTimeString());

    }

    public function add(Request $request) {
        $input = $request->input();
        $user = Auth::user();

        $plant = new Plant;

        $plant->name = $input['name'];
        $plant->earliest_seed = $input['earliest_seed'];
        $plant->latest_seed = $input['latest_seed'];
        $plant->harden = $input['harden'];
        $plant->transplant = $input['transplant'];
        $plant->maturity = $input['maturity'];
        $plant->light = $input['light'];
        $plant->depth = $input['depth'];
        $plant->seed_note = $input['seed_note'];
        $plant->transplant_note = $input['transplant_note'];
        $plant->harvest_note = $input['harvest_note'];
        $plant->direct_sow = $input['direct_sow'];

        $plant->user_id = $user->id;

        $plant->save();

        return redirect()->route('plant_setting');
    }

    public function save_plant(Request $request) {
        // var_dump('test');

        $user = Auth::user();
        $frost_date = $user->last_frost_date;

        $input = $request->input();
        
        if (isset($input['plants'])) {
            $plants = $input['plants'];

            foreach($plants as $plant) {
                if (isset($plant['selected']) && $plant['selected'] == 'on') {
                    if ($plant['quantity'] < 1) {
                        //// if quantity is less than 1 then return error
                        $error = 'Quantity must be at least 1';
                        return $this->getPlantsView($error);
                    }
    
                    $pt = Plant::where('id', $plant['id'])->first();
                    

                    if (isset($plant['stagger']) && $plant['stagger'] == 'on') {
                        if (isset($plant['plant_count']) && $plant['plant_count'] >= 1) {
                            $pt->plant_count = $plant['plant_count'];
                        } else {
                            $error = 'Plant count must be at least 1';
                            return $this->getPlantsView($error);
                        }

                        
                        if (isset($plant['plant_days']) && $plant['plant_days'] >= 1) {
                            $pt->plant_days = $plant['plant_days'];
                        } else {
                            $error = 'Plant days must be at least 1';
                            return $this->getPlantsView($error);
                        }
                    }


                    //// create tasks here

                    /// create seed task
                    $seed_date = null;

                    if ($plant['harvest'] == 'Early') {
                        $seed_date = $this->addWeeks($frost_date, -$pt->earliest_seed);
                    } else if ($plant['harvest'] == 'Mid') {
                        $seed_date = $this->addWeeks($frost_date, -floor(($pt->earliest_seed + $pt->latest_seed)/2));
                    } else {
                        $seed_date = $this->addWeeks($frost_date, -$pt->latest_seed);
                    }
                    $task = new Task;
                    $task->plant_id = $pt->id;
                    $task->user_id = $user->id;
                    $task->date = $seed_date;
                    $task->type = 'seed';
                    $task->save();

                    //// add multiple seed task if stagger checked
                    if (isset($plant['stagger']) && $plant['stagger'] == 'on') {
                        $plant_count = floor($plant['plant_count']);
                        $plant_days = floor($plant['plant_days']);

                        for ($i=1; $i<$plant_count; $i++) {
                            $task = new Task;
                            $task->plant_id = $pt->id;
                            $task->user_id = $user->id;
                            $seed_date = $this->addDays($seed_date, $plant_days);
                            $task->date = $seed_date;
                            $task->type = 'seed';
                            $task->save();
                        }
                    }


                    //// add harden task
                    $task = new Task;
                    $task->plant_id = $pt->id;
                    $task->user_id = $user->id;
                    $task->date = $this->addWeeks($frost_date, -$pt->harden);;
                    $task->type = 'harden';
                    $task->save();


                    /// add transplant task
                    $task = new Task;
                    $task->plant_id = $pt->id;
                    $task->user_id = $user->id;
                    $task->date = $this->addWeeks($frost_date, $pt->transplant);;
                    $task->type = 'transplant';
                    $task->save();

                    /// add maturity task
                    $task = new Task;
                    $task->plant_id = $pt->id;
                    $task->user_id = $user->id;
                    $task->date = $this->addDays($seed_date, $pt->maturity);;
                    $task->type = 'harvest';
                    $task->save();


                    $pt->quantity = $plant['quantity'];
                    $pt->harvest = $plant['harvest'];
                    $pt->stagger = isset($plant['stagger']) && $plant['stagger'] == 'on' ? 'yes' : 'no';
                    $pt->has_task = 1;

                    $pt->save();
                }
            }

            return redirect()->route('plants');
        } else {
            return redirect()->route('plants');
        }
    }

    public function setting()
    {
        $user = Auth::user();
        $plants = Plant::where('user_id', $user->id)->get();
        return view('pages.plant.setting', compact('plants'));
    }

    public function welcome()
    {
        return view('welcome');
    }

    public function getPlantsView($error) {
        $user = Auth::user();
        $plants = Plant::where('user_id', $user->id)->get();
        return view('pages.plant.index', compact('plants', 'error'));
    }

    public function addWeeks($org_date, $weeks) {
        $carbon = Carbon::createFromFormat('Y-m-d', $org_date);
        $carbon->addWeeks($weeks);
        return $carbon->toDateString();
    }

    public function addDays($org_date, $days) {
        $carbon = Carbon::createFromFormat('Y-m-d', $org_date);
        $carbon->addDays($days);
        return $carbon->toDateString();
    }

}
