<?php

namespace App\Http\Controllers;

use App\Http\Requests\FaqRequest;
use App\Models\Faq;
use Exception;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function getFaqs()
    {
        try {
            $faqs = Faq::where('status', 'active')->latest()->paginate(10);
            return $this->sendResponse($faqs, 'FAQ list fetched successfully.');
        } catch (Exception $e) {
            return $this->sendError('Failed to fetch FAQs.'. $e->getMessage(),[],500);
        }
    }

    public function createFaq(FaqRequest $request)
    {
        try {
            $validated = $request->validated();
            $faq = Faq::create($validated);
            return $this->sendResponse($faq, 'FAQ created successfully.');
        } catch (Exception $e) {
            return $this->sendError('Failed to create FAQ.'.$e->getMessage(),[],500);
        }
    }

    public function updateFaq(FaqRequest $request)
    {
        try {
            $validated = $request->validated();
            $faq = Faq::findOrFail($request->faq_id);
            $faq->update($validated);
            return $this->sendResponse($faq, 'FAQ updated successfully.');
        } catch (Exception $e) {
            return $this->sendError('Failed to update FAQ.'.$e->getMessage(),[],500);
        }
    }
    public function Faq(Request $request)
    {
        try {
            $faq = Faq::findOrFail($request->faq_id);
            return $this->sendResponse($faq, 'FAQ retrived successfully.');
        } catch (Exception $e) {
            return $this->sendError('Failed to delete FAQ.'.$e->getMessage(),[],500);
        }
    }

    public function deleteFaq(Request $request)
    {
        try {
            $faq = Faq::findOrFail($request->faq_id);
            $faq->delete();
            return $this->sendResponse([], 'FAQ deleted successfully.');
        } catch (Exception $e) {
            return $this->sendError('Failed to delete FAQ.'.$e->getMessage(),[],500);
        }
    }

}
