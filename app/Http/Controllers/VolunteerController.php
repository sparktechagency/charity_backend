<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateVolunteerRequest;
use App\Http\Requests\GetVolunteerRequest;
use App\Http\Requests\VolunteerStatusRequest;
use App\Mail\VolunteerStatusUpdated;
use App\Models\Transition;
use App\Models\Volunteer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class VolunteerController extends Controller
{
    public function getVolunteer(GetVolunteerRequest $request)
    {
        try {
            $validated = $request->validated();
            $search = $validated['search'] ?? '';
            $manage_volunteer = $validated['manage_volunteer'] ?? '';
            $perPage = $validated['per_page'] ?? 10;
            $query = Volunteer::query();
            if (!empty($search)) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                          ->orWhere('email', 'like', '%' . $search . '%')
                          ->orWhere('contact_number', 'like', '%' . $search . '%')
                          ->orWhere('location', 'like', '%' . $search . '%')
                          ->orWhere('donated', 'like', '%' . $search . '%')
                          ->orWhere('status', 'like', '%' . $search . '%');
                });
            }
            if (!empty($manage_volunteer)) {
                $query->where('manage_volunteer', $manage_volunteer);
            }
            $volunteers = $query->paginate($perPage);
            return $this->sendResponse($volunteers, 'Volunteers retrieved successfully!');
        } catch (Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }
    public function createVolunteer(CreateVolunteerRequest $request)
    {
        try {
            $validated = $request->validated();
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalExtension();
                $destinationPath = public_path('uploads/volunteer');
                $image->move($destinationPath, $imageName);
                $imagePath = 'uploads/volunteers/' . $imageName;
            }
            $volunteerData = $validated;
            if ($imagePath) {
                $volunteerData['image'] = $imagePath;
            }
            $donatedAmount = Transition::where('email', $validated['email'])->sum('amount');
            if ($donatedAmount > 0) {
                $volunteerData['donated'] = $donatedAmount;
            }
            $volunteer = Volunteer::create($volunteerData);
          return $this->sendResponse($volunteer,'Volunteer created successfully!');

        } catch (Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }
    public function volunteerStatus(VolunteerStatusRequest $request){
        try{
            $validated = $request->validated();
            $volunteer = Volunteer::find($validated['volunteer_id']);
            if(!$volunteer){
                $this->sendError('Invalid volunteer ID',['volunteer_id'=>'Volunteer does not exist.']);
            }
            $volunteer->status = $validated['status'];
            $volunteer->save();
            Mail::to($volunteer->email)->queue(new VolunteerStatusUpdated($volunteer, $validated['status']));
            return $this->sendResponse($volunteer,'Successfully volunteer status updated.');
        }catch(Exception $e){
            return $this->sendError("An error occured: ".$e->getMessage(),[],500);
        }
    }

}
