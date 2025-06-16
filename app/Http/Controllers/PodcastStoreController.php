<?php

namespace App\Http\Controllers;

use App\Http\Requests\PodcastRequest;
use App\Models\PodcastStore;
use Exception;
use Illuminate\Http\Request;

class PodcastStoreController extends Controller
{
    public function getPodcast(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 10);
            $search = $request->get('search');
            $query = PodcastStore::query();
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('podcast_title', 'LIKE', "%$search%")
                    ->orWhere('host_title', 'LIKE', "%$search%")
                    ->orWhere('guest_title', 'LIKE', "%$search%")
                    ->orWhere('description', 'LIKE', "%$search%");
                });
            }
            $podcasts = $query->latest()->paginate($perPage);
            $podcasts->getCollection()->transform(function ($podcast) {
                return $podcast;
            });
            return $this->sendResponse($podcasts, 'Podcasts fetched successfully.');
        } catch (Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }
    public function createPodCast(PodcastRequest $request)
    {
        try {
            $validated = $request->validated();
            if ($request->hasFile('mp3')) {
                $mp3File = $request->file('mp3');
                $mp3Name = time() . '.' . $mp3File->getClientOriginalExtension();
                $mp3File->move(public_path('uploads/podcasts'), $mp3Name);
                $validated['mp3'] = 'uploads/podcasts/' . $mp3Name;
            }
            if ($request->hasFile('host_profile')) {
                $hostFile = $request->file('host_profile');
                $hostName = 'host_' . time() . '.' . $hostFile->getClientOriginalExtension();
                $hostFile->move(public_path('uploads/host_profiles'), $hostName);
                $validated['host_profile'] = 'uploads/host_profiles/' . $hostName;
            }
            if ($request->hasFile('guest_profile')) {
                $guestFile = $request->file('guest_profile');
                $guestName = 'guest_' . time() . '.' . $guestFile->getClientOriginalExtension();
                $guestFile->move(public_path('uploads/guest_profiles'), $guestName);
                $validated['guest_profile'] = 'uploads/guest_profiles/' . $guestName;
            }

            if ($request->hasFile('thumbnail')) {
                $thumbnailFile = $request->file('thumbnail');
                $thumbnailName = 'thumb_' . time() . '.' . $thumbnailFile->getClientOriginalExtension();
                $thumbnailFile->move(public_path('uploads/thumbnails'), $thumbnailName);
                $validated['thumbnail'] = 'uploads/thumbnails/' . $thumbnailName;
            }
            $podcast = PodcastStore::create($validated);
            $podcast->mp3 = url($podcast->mp3);
            $podcast->host_profile = url($podcast->host_profile);
            $podcast->guest_profile = url($podcast->guest_profile);
            $podcast->thumbnail = $podcast->thumbnail ? url($podcast->thumbnail) : null;

            return $this->sendResponse($podcast, 'Podcast created successfully.');
        } catch (Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }
    public function updatePodCast(PodcastRequest $request)
    {
        try {
            $validated = $request->validated();
            $podcast = PodcastStore::find($request->podcast_id);
            if (!$podcast) {
                return $this->sendError('Podcast not found.', [], 404);
            }
            if ($request->hasFile('mp3')) {
                if ($podcast->mp3 && file_exists(public_path($podcast->mp3))) {
                    unlink(public_path($podcast->mp3));
                }
                $mp3File = $request->file('mp3');
                $mp3Name = time() . '.' . $mp3File->getClientOriginalExtension();
                $mp3File->move(public_path('uploads/podcasts'), $mp3Name);
                $validated['mp3'] = 'uploads/podcasts/' . $mp3Name;
            }
            if ($request->hasFile('host_profile')) {
                if ($podcast->host_profile && file_exists(public_path($podcast->host_profile))) {
                    unlink(public_path($podcast->host_profile));
                }
                $hostFile = $request->file('host_profile');
                $hostName = 'host_' . time() . '.' . $hostFile->getClientOriginalExtension();
                $hostFile->move(public_path('uploads/host_profiles'), $hostName);
                $validated['host_profile'] = 'uploads/host_profiles/' . $hostName;
            }
            if ($request->hasFile('guest_profile')) {
                if ($podcast->guest_profile && file_exists(public_path($podcast->guest_profile))) {
                    unlink(public_path($podcast->guest_profile));
                }
                $guestFile = $request->file('guest_profile');
                $guestName = 'guest_' . time() . '.' . $guestFile->getClientOriginalExtension();
                $guestFile->move(public_path('uploads/guest_profiles'), $guestName);
                $validated['guest_profile'] = 'uploads/guest_profiles/' . $guestName;
            }
            if ($request->hasFile('thumbnail')) {
                if ($podcast->thumbnail && file_exists(public_path($podcast->thumbnail))) {
                    unlink(public_path($podcast->thumbnail));
                }
                $thumbnailFile = $request->file('thumbnail');
                $thumbnailName = 'thumb_' . time() . '.' . $thumbnailFile->getClientOriginalExtension();
                $thumbnailFile->move(public_path('uploads/thumbnails'), $thumbnailName);
                $validated['thumbnail'] = 'uploads/thumbnails/' . $thumbnailName;
            }
            $podcast->update($validated);
            $podcast->mp3 = url($podcast->mp3) ?? $podcast->mp3;
            $podcast->host_profile = url($podcast->host_profile) ?? $podcast->host_profile;
            $podcast->guest_profile = url($podcast->guest_profile) ?? $podcast->guest_profile;
            $podcast->thumbnail = url($podcast->thumbnail) ?? $podcast->thumbnail;

            return $this->sendResponse($podcast, 'Podcast updated successfully.');
        } catch (Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }
    public function detailsPodcast(Request $request)
    {
        try {
            $podcast = PodcastStore::find($request->podcast_id);
            if(!$podcast){
                return $this->sendError("Podcast not found.");
            }
            return $this->sendResponse($podcast, 'Podcast deleted successfully.');
        } catch (Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }
    public function deletePodcast(Request $request)
    {
        try {
            $podcast = PodcastStore::find($request->podcast_id);

            if (!$podcast) {
                return $this->sendError("Podcast not found", [], 404);
            }
            if ($podcast->mp3 && file_exists(public_path($podcast->mp3))) {
                unlink(public_path($podcast->mp3));
            }
            if ($podcast->host_profile && file_exists(public_path($podcast->host_profile))) {
                unlink(public_path($podcast->host_profile));
            }
            if ($podcast->guest_profile && file_exists(public_path($podcast->guest_profile))) {
                unlink(public_path($podcast->guest_profile));
            }
            if ($podcast->thumbnail && file_exists(public_path($podcast->thumbnail))) {
                unlink(public_path($podcast->thumbnail));
            }
            $podcast->delete();
            return $this->sendResponse([], 'Podcast deleted successfully.');
        } catch (Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }

}
