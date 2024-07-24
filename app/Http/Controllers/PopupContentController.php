<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PopupContent;
use App\Models\PopupFormData;
use Illuminate\Support\Facades\Auth;
use DOMDocument;
use Illuminate\Support\Facades\DB;


class PopupContentController extends Controller
{
    public function create(Request $request)
    {
        $popup = null;
        $popupId = $request->query('popup_id');

        if ($popupId) {
            $popup = PopupContent::find($popupId);
        }

        return view('popup-content.form', compact('popup'));
   }

    public function store(Request $request)
    {
        $request->validate([
            'website_name' => 'required|string|max:255',
            'header_text' => 'required|string|max:255',
            'body_text' => 'required|string',
        ]);
        $websiteName = $request->input('website_name');
        $headerText = $request->input('header_text');
        $bodyText = $request->input('body_text');
        $logoPath = 'logos/QMjFxw3Qy226KmFs4kCC4nF5eGMWfY5uj4bEqmYS.jpg';
        if ($request->hasFile('header_logo')) {
            $logoPath = $request->file('header_logo')->store('logos', 'public');
        }

        $logoUrl = asset('storage/' . $logoPath);

        $popupHtml = "<div id='popupPreview' style='max-width: 400px; margin: 20px auto; background-color: #ffffff; box-shadow: 0 0 10px rgba(0,0,0,0.1); border-radius: 8px; overflow: hidden; position: relative'>
                        <span style='position: absolute; top: 0px; right: 10px; font-size: 20px; color: #e71e1e; background: #fff; border: none; outline: none;'>&times;</span>
                        <header id='previewHeader' style='background-color: #ffffff; padding: 10px; text-align: center;'>
                            <span id='previewHeaderText'>{$headerText}</span>
                        </header>
                        <img id='previewLogo' src='{$logoUrl}' alt='default logo' style='border-radius: 50%; max-height: 50px; display: block; margin: 10px auto;'>
                        <div id='previewBody' style='padding: 20px; text-align: center;'>
                            <div id='previewContent' style='margin-bottom: 20px;'>{$bodyText}</div>
                            <form action='' method='post' style='margin-top: 20px;'>
                                <div style='margin-bottom: 15px;'>
                                    <input type='text' name='name' style='width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;' placeholder='Name'>
                                </div>
                                <div style='margin-bottom: 15px;'>
                                    <input type='text' name='email' style='width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;' placeholder='Email'>
                                </div>
                                <div style='margin-bottom: 15px;'>
                                    <input type='text' name='mobile' style='width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;' placeholder='Phone'>
                                </div>
                                <input type='button' value='Submit' name='save' style='width: 40%; padding: 10px; background-color: #007bff; border: none; color: #fff; font-size: 16px; border-radius: 4px; cursor: pointer;'>
                            </form>
                        </div>
                     </div>";
        $popup = PopupContent::create([
            'user_id' => Auth::id(),
            'website_name' => $websiteName,
            'content' => $popupHtml,
            'type' => 'popup',
        ]);

        return redirect()->route('popup-content.create', ['popup_id' => $popup->id]);
    }

    public function getPopupData($popid)
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: *");
        header("Access-Control-Allow-Methods: *");
        header("Allow: *");
        $popup = PopupContent::find($popid);

        if (!$popup) {
            return response()->json(['error' => 'Popup not found'], 404);
        }
        $user_id = $popup->user_id;
        $websiteName = $popup->website_name;
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
            'popup_id' => $popid,
            'user_id' => $user_id,
            'websiteName' => $websiteName,
            'header_text' => $headerText,
            'logo_url' => $logoUrl,
            'body_content' => $bodyContent
        ]);
    }   

    public function savePopupData(Request $request)
    {
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobile' => 'required|string|max:15',
            'user_id' => 'required|integer',
            'popup_id' => 'required|integer',
            'website_name' => 'required|string|max:255',
        ]);

        // Save the data to the database, assuming you have a PopupData model
        PopupFormData::create($validatedData);

        return response()->json(['success' => true]);
    }

    public function index()
    {
        $userId = auth()->id();
        $websites = DB::table('table_popup_content')
            ->where('user_id', $userId)
            ->pluck('website_name', 'id'); // Assuming 'websites' is a column that stores the website name

        return view('website_view', [
            'websites' => $websites,
        ]);
    }

    public function showPopupContent($id)
    {
        $popup = DB::table('table_popup_content')->find($id);
        return response()->json($popup);
    }

}
