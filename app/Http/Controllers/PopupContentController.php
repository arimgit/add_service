<?php

namespace App\Http\Controllers;

use DOMDocument;
use Illuminate\Http\Request;
use App\Models\PopupContent;
use App\Models\PopupFormData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;



class PopupContentController extends Controller
{
    public function buildPopupJs(Request $request)
    {
        $origin = $request->getSchemeAndHttpHost();

        $popupJs = view('popup-content.build_popup_js', [
            'origin' => $origin
        ])->render();

        $destinationPath = public_path('uploads/build/');

        // Ensure the directory exists
        if (!is_dir($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        // Write the file to the disk
        Storage::disk('js')->put("popup.js", $popupJs);

        // Define the file path
        $filePath = $destinationPath . 'popup.js';

        // Write content to the file
        file_put_contents($filePath, $popupJs);

        return response()->json(['message' => 'File written successfully']);
    }

    public function managePopup(Request $request, $popupId = -1)
    {
        // Initialize variables
        $popup = $popupId != -1 ? PopupContent::find($popupId) : null;
        $headerText = '';
        $bodyText = '';
        $logoUrl = '';

        if ($popup) {
            // If popup exists, parse its content
            $doc = new \DOMDocument();
            @$doc->loadHTML($popup->content);
            $headerText = $doc->getElementById('previewHeaderText')->textContent ?? '';
            $bodyText = $doc->getElementById('previewContent')->textContent ?? '';
            $logoImg = $doc->getElementById('previewLogo');
            $logoUrl = $logoImg ? $logoImg->getAttribute('src') : '';
        }

        if ($request->isMethod('post')) {
            // Handle form submission
            $request->validate([
                'website_name' => 'required|string|max:255',
                'title' => 'required|string|max:255',
                'header_text' => 'required|string|max:255',
                'body_text' => 'required|string',
                'popup_id' => 'nullable|exists:table_popup_content,id'
            ]);

            $websiteName = $request->input('website_name');
            $title = $request->input('title');
            $headerText = $request->input('header_text');
            $bodyText = $request->input('body_text');

            if ($request->hasFile('header_logo')) {
                $request->validate([
                    'header_logo' => 'required|file|image|max:2048', // Validation rules
                ]);

                $logFile = $request->file('header_logo');
                $fileName = uniqid(time()) . '_' . $logFile->getClientOriginalName();
                $destinationPath = public_path('uploads/logos/');

                if (!is_dir($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $logFile->move($destinationPath, $fileName);
                $logoUrl = asset('uploads/logos/' . $fileName);
            } else {
                if ($popupId != -1) {
                    $doc = new \DOMDocument();
                    libxml_use_internal_errors(true);
                    $doc->loadHTML($popup->content);
                    libxml_clear_errors();
                    $logoImg = $doc->getElementById('previewLogo');
                    $logoUrl = $logoImg ? $logoImg->getAttribute('src') : '';
                } else {
                    $logoPath = 'default_storage/building-icon.svg';
                    $logoUrl = asset($logoPath);
                }
            }

            $popupHtml = view('popup-content.popup_template', [
                'headerText' => $headerText,
                'logoUrl' => $logoUrl,
                'bodyText' => $bodyText
            ])->render();

            if ($popupId != -1) {
                // Update existing popup
                if ($popup) {
                    $popup->update([
                        'website_name' => $websiteName,
                        'title' => $title,
                        'content' => $popupHtml,
                        'type' => 'popup',
                    ]);
                }
            } else {
                // Create new popup
                $popup = PopupContent::create([
                    'user_id' => Auth::id(),
                    'website_name' => $websiteName,
                    'title' => $title,
                    'content' => $popupHtml,
                    'type' => 'popup',
                ]);
            }
            
            $request->session()->flash('success', 'Your data has been saved successfully!');
        }

        // Encrypt popupId for view
        $encryptedPopupId = Crypt::encryptString($popupId);

        return view('popup-content.manage_popup', [
            'popup' => $popup,
            'headerText' => $headerText,
            'bodyText' => $bodyText,
            'encryptedPopupId' => $encryptedPopupId
        ]);
    }


    public function getPopupData($popId)
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: *");
        header("Access-Control-Allow-Methods: *");
        header("Allow: *");

        Cookie::queue('name', 'value', 60);

        $popup = PopupContent::find(Crypt::decryptString($popId));

        if (!$popup) {
            return response()->json(['error' => 'Popup not found'], 404);
        }
        $status = $popup->status;
        $content = $popup->content;

        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($content);
        libxml_clear_errors();

        // Extract header text
        $headerTextElement = $doc->getElementById('previewHeaderText');
        $headerText = $headerTextElement ? $headerTextElement->nodeValue : '';

        // Extract logo URL
        $logoImg = $doc->getElementById('previewLogo');
        $logoUrl = $logoImg ? $logoImg->getAttribute('src') : '';

        // Extract body content
        $bodyContentDiv = $doc->getElementById('previewContent');
        $bodyContent = $bodyContentDiv ? $bodyContentDiv->nodeValue : '';

        return response()->json([
            'popup_id' => Crypt::decryptString($popId),
            'header_text' => $headerText,
            'logo_url' => $logoUrl,
            'body_content' => $bodyContent,
            'status' => $status
        ]);
    }

    public function managePopupFormData(Request $request)
    {
        $validatedData = $request->validate([
            'popup_id' => 'required|integer|exists:table_popup_content,id',
            'host_name' => 'required|string',
            'form_data' => 'required|array',
        ]);

        // Save the data to the database
        $popupFormData = PopupFormData::create([
            'popup_id' => $validatedData['popup_id'],
            'host_name' => $validatedData['host_name'],
            'form_data' => $validatedData['form_data'], // Laravel will handle encoding this as JSON
        ]);

        // Return a success response
        return response()->json(['success' => true, 'data' => $popupFormData]);
    }

    public function listPopup(Crypt $enc, Request $request)
    {
        $userId = auth()->id();
        $websites = DB::table('table_popup_content')
        ->leftJoin('popup_form_data', 'table_popup_content.id', '=', 'popup_form_data.popup_id')
        ->where('table_popup_content.user_id', $userId)
        ->select('table_popup_content.id', 'table_popup_content.website_name', 'table_popup_content.title', 'table_popup_content.status', DB::raw('COUNT(popup_form_data.id) as lead_count'))
        ->groupBy('table_popup_content.id', 'table_popup_content.website_name', 'table_popup_content.title', 'table_popup_content.status')
        ->get();

        $request->session()->forget('success');

        return view('popup-content.list_popup', [
            'websites' => $websites,
            'enc' => $enc,
        ]);
    }

    public function toggleStatus(Request $request, $id)
    {
        $status = $request->input('status');

        // Update the status in the database
        DB::table('table_popup_content')
            ->where('id', $id)
            ->update(['status' => $status]);

        return response()->json(['status' => 'success']);
    }

    public function viewLead($popupId)
    {
        // Fetch all records from the PopupFormData table
        $viewLead = PopupFormData::where('popup_id', $popupId)->get();
        $popupContent = PopupContent::find($popupId);
        // Pass the data to the view
        return view('popup-content.lead_data', [
            'viewLead' => $viewLead,
            'popupContent' => $popupContent
        ]);
    }
}
