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
            $status = $validated['status'] ?? '';
            $perPage = $validated['per_page'] ?? 10;
            $query = Volunteer::query();
            if (!empty($search)) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                          ->orWhere('email', 'like', '%' . $search . '%')
                          ->orWhere('contact_number', 'like', '%' . $search . '%')
                          ->orWhere('location', 'like', '%' . $search . '%')
                          ->orWhere('donated', 'like', '%' . $search . '%');
                });
            }
            if (!empty($status)) {
                $query->where('status', 'like', '%' . $status . '%');
            }
            $volunteers = $query->orderBy('id','desc')->paginate($perPage);
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
            if ($request->hasFile('upload_cv')) {
                $image = $request->file('upload_cv');
                $imageName = time() . '_' . $image->getClientOriginalExtension();
                $destinationPath = public_path('uploads/cv');
                $image->move($destinationPath, $imageName);
                $imagePath = 'uploads/cv/' . $imageName;
            }
            $volunteerData = $validated;
            if ($imagePath) {
                $volunteerData['upload_cv'] = $imagePath;
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
