<?php

namespace App\Http\Controllers;

use App\Http\Requests\TreamRequest;
use App\Models\Team;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class TeamController extends Controller
{
    public function getTeam(Request $request)
    {
        try {
            $query = Team::query();

            if ($request->search) {
                $query->where('name', 'like', '%' . $request->search . '%')
                      ->orWhere('designation', 'like', '%' . $request->search . '%');
            }
            $teams = $query->latest()->paginate(10);
            return $this->sendResponse($teams, 'Team members fetched successfully');
        } catch (Exception $e) {
            return $this->sendError('Failed to fetch team members: '. $e->getMessage(),[], 500);
        }
    }

    public function createTeam(TreamRequest $request)
    {
        try {
            $data = $request->validated();
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/team'), $filename);
                $data['photo'] = 'uploads/team/' . $filename;
            }

            $team = Team::create($data);
            $team->photo = $team->photo ? url($team->photo) : null;

            return $this->sendResponse($team, 'Team member created successfully');
        } catch (Exception $e) {
            return $this->sendError('Error creating team member', [$e->getMessage()], 500);
        }
    }

    public function updateTeam(TreamRequest $request)
    {
        try {
            $team = Team::find($request->team_id);
            if (!$team) {
                return $this->sendError('Team member not found');
            }
            $data = $request->validated();

            if ($request->hasFile('photo')) {
                if ($team->photo && File::exists(public_path($team->photo))) {
                    File::delete(public_path($team->photo));
                }
                $file = $request->file('photo');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/team'), $filename);
                $data['photo'] = 'uploads/team/' . $filename;
            }
            $team->update($data);
            $team->photo = $team->photo ? url($team->photo) : null;
            return $this->sendResponse($team, 'Team member updated successfully');
        } catch (Exception $e) {
            return $this->sendError('Error updating team member', [$e->getMessage()], 500);
        }
    }
    public function deleteTeam(Request $request)
    {
        try {
            $team = Team::find($request->team_id);
            if (!$team) {
                return $this->sendError('Team member not found');
            }
            if ($team->photo && File::exists(public_path($team->photo))) {
                File::delete(public_path($team->photo));
            }
            $team->delete();
            return $this->sendResponse([], 'Team member deleted successfully');
        } catch (Exception $e) {
            return $this->sendError('Error deleting team member', [$e->getMessage()], 500);
        }
    }
}
