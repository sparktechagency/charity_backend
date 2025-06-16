<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePasswordRequest;
use App\Http\Requests\ForgotPassword;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\OTPverifyRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Mail\OTPMail;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;


class UserController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {
            $validated = $request->validated();

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/users'), $filename);

                $validated['image'] = 'uploads/users/' . $filename;
            }
            $otp = rand(100000, 999999);
            $validated['password'] = bcrypt($validated['password']);
            $validated['otp']= $otp;
            $validated['otp_expires_at'] = now()->addMinutes(2);
            $validated['role'] = 'USER';
            $user = User::create($validated);
            if ($user) {
                $otp_info = [
                    'otp' => $otp,
                    'full_name' => $validated['full_name'],
                ];

                Mail::to($validated['email'])->queue(new OTPMail($otp_info));
            }
            return $this->sendResponse($user, 'User registered successfully.');
        } catch (Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }


    public function login(LoginRequest $request)
    {
        try {
            $validated = $request->validated();
            $user = User::where('email', $validated['email'])->where('verify_email',1)->first();
            if (!$user) {
                return $this->sendError('Invalid credentials.', ['email' => 'User with this email does not exist.']);
            }
            if (!Hash::check($request->password, $user->password)) {
                return $this->sendError('Invalid credentials.', ['password' => 'Incorrect password.']);
            }
            $token = $user->createToken('charity_token')->plainTextToken;

            return $this->sendResponse([
                'user' => $user,
                'token' => $token
            ], 'You are successfully logged in.');
        } catch (Exception $e) {
            return $this->sendError('An error occurred: ' . $e->getMessage(),[],500);
        }
    }
    public function forgotPassword(ForgotPassword $request)
    {
        try {
            $validated = $request->validated();
            $admin = User::where('email', $validated['email'])->whereIn('role',['ADMIN','USER'])->where('status','active')->first();
            if (!$admin) {
                return $this->sendError("Invalid email.",['email'=>'Email does not exist.']);
            } else {
                $otp = rand(100000, 999999);
                $admin->otp = $otp;
                $admin->verify_email = 0;
                $admin->otp_expires_at = now()->addMinutes(2);
                $admin->save();
                $otp_info=[
                    'otp' =>$otp,
                    'full_name' => $admin->full_name
                ];
                Mail::to($admin->email)->queue(new OTPMail($otp_info));
                return $this->sendResponse($admin,'OTP sent successfully. It will expire in 2 minutes.');
            }
        } catch (Exception $e) {
            return $this->sendError('An error occurred: '. $e->getMessage(),[],500);
        }
    }
    public function otpVerify(OTPverifyRequest $request){
        try{
            $validated = $request->validated();
            $user = User::where('otp', $validated['otp'])
                        ->where('verify_email', 0)
                        ->first();

            if (!$user) {
                return $this->sendError('Invalid OTP.',['otp'=>'OTP does not exist.']);
            }
            if (now()->greaterThan($user->otp_expires_at)) {
                return $this->sendError( 'OTP has expired',['otp'=>'OTP has expired. Please request a new one.']);
            }
            $user->update([
                'verify_email' => 1,
                'otp' => null,
                'otp_expires_at' => null,
                'status' => 'active',
            ]);
            $token = $user->createToken('authToken')->plainTextToken;
            $data =[
                'token'=>$token,
            ];
            return $this->sendResponse($data,"Email verified successfully.");
        }catch(Exception $e){
            return $this->sendError("An error occurred: ".$e->getMessage());
        }
    }
    public function createNewPassword(CreatePasswordRequest $request)
    {
        try {
            $validated = $request->validated();
            $user = User::where('email', $validated['email'])->first();
            if (!$user) {
                return $this->sendError('Invalid email', ['email'=>'Email does not exist']);
            }
            $user->password = Hash::make($validated['new_password']);
            $user->save();
            return $this->sendResponse($user, 'New password created successfully.');
        } catch (Exception $e) {
            return $this->sendError('An error occurred: ' . $e->getMessage(),[],500);
        }
    }
    public function updateProfile(UpdateProfileRequest $request)
    {
        try {
            $user = Auth::user();
            $image = $user->image;
            if ($request->hasFile('image')) {
                // $oldImagePath = public_path($user->image);
                // if ($user->image && file_exists($oldImagePath)) {
                //     unlink($oldImagePath);
                // }
                $imagePath = time() . '.' . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->move(public_path('uploads/profile'), $imagePath);
                $image = 'uploads/profile/' . $imagePath;
            }
            $user->full_name = $request->full_name ?? $user->full_name;
            $user->image = $image;
            $user->save();
            return $this->sendResponse($user, 'Profile updated successfully.');
        } catch (Exception $e) {
            return $this->sendError('An error occurred: ' . $e->getMessage(), [], 500);
        }
    }
    public function profile(Request $request)
    {
        try{
            $user = Auth::user();
            return $this->sendResponse($user, 'User profile retrieved successfully.');
        } catch (Exception $e) {
            return $this->sendError('An error occurred: ' . $e->getMessage(), [], 500);
        }
    }
}
