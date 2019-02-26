<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use App\Resources\TeamsResource;
use Illuminate\Database\Eloquent\Collection;

class TeamsController extends Controller
{
    public function index(Request $request)
    {
        $includes = explode(',',$request->get('include',''));
        /** @var Collection $teams */
        $teams = Team::with((isset($includes) ? $includes: []))->get();
        return $this->responseJSON(new TeamsResource($teams));
    }

    public function show(Request $request ,$id)
    {
        $includes = explode(',',$request->get('include',''));
        /** @var Team $team */
        $team = Team::with((isset($includes) ? $includes: []))->find($id);
        if ($team == null) return $this->return404('Team');
        return $this->responseJSON(new TeamsResource($team));
    }

    public function update(Request $request ,$id)
    {
        /** @var Team $team */
        $team = Team::find($id);
        if ($team == null) return $this->return404('Team');
        $team->update([
            'name' => $request->get('name'),
            'description' => $request->get('description')
        ]);
        return $this->responseJSON($team->fresh());
    }
}