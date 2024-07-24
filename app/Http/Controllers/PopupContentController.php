<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PopupContent;
use Illuminate\Support\Facades\Auth;
use DOMDocument;

class PopupContentController extends Controller
{
    public function create()
    {
        return view('popup-content.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'body_text' => 'required|string',
        ]);

        $headerColor = $request->input('header_color');
        $bodyText = $request->input('body_text');
        $logoPath = 'logos/Gk534fcn5wWzzotPBCu0h8TX1lbMcwhNgMXV6G3Y.jpg';
        if ($request->hasFile('header_logo')) {
            $logoPath = $request->file('header_logo')->store('logos', 'public');
        }

        $logoUrl = $logoPath ? asset('storage/' . $logoPath) : asset('default_logo.png');

        $popupHtml = '<div style="max-width: 600px; margin: 20px auto; background-color: #ffffff; box-shadow: 0 0 10px rgba(0,0,0,0.1); border-radius: 8px; overflow: hidden;">
                        <div style="background-color: ' . $headerColor . '; color: #ffffff; text-align: center; padding: 5px 0;">
                            <img src="' . $logoUrl . '" alt="Default Logo" style="height: 50px; vertical-align: middle;">
                        </div>
                        <div style="padding: 20px; text-align: center;">
                            <p>' . htmlentities($bodyText) . '</p>
                            <form action="" method="post" style="margin-top: 20px;">
                                <div style="margin-bottom: 15px;">
                                    <label for="name" style="display: block; margin-bottom: 5px; font-weight: bold;">Name</label>
                                    <input type="text" name="name" style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
                                </div>
                                <div style="margin-bottom: 15px;">
                                    <label for="email" style="display: block; margin-bottom: 5px; font-weight: bold;">Email</label>
                                    <input type="text" name="email" style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
                                </div>
                                <div style="margin-bottom: 15px;">
                                    <label for="mobile" style="display: block; margin-bottom: 5px; font-weight: bold;">Mobile No</label>
                                    <input type="text" name="mobile" style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
                                </div>
                                <input type="submit" value="Save" name="save" style="width: 40%; padding: 10px; background-color: #007bff; border: none; color: #fff; font-size: 16px; border-radius: 4px; cursor: pointer;">
                            </form>
                        </div>
                     </div>';
        $popup = PopupContent::create([
            'user_id' => Auth::id(),
            'content' => $popupHtml,
            'type' => 'popup',
        ]);
        $popupId = $popup->id;

        return redirect()->route('popup.preview',['id' => $popupId])->with('success', 'Popup content created and saved successfully.');
    }

    public function show($id)
    {
        $popup = PopupContent::find($id);

        if (!$popup) {
            return response()->json(['error' => 'Popup not found'], 404);
        }

        $content = $popup->content;

        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($content);
        libxml_clear_errors();

        $headerDiv = $doc->getElementsByTagName('div')->item(1);
        
        $headerStyle = $headerDiv->getAttribute('style');
        preg_match('/background-color:\s*(#[a-fA-F0-9]{6}|rgba?\(\s*\d+\s*,\s*\d+\s*,\s*\d+(\s*,\s*\d+)?\s*\));/', $headerStyle, $matches);
        $headerBackgroundColor = $matches[1];

        $logoImg = $headerDiv->getElementsByTagName('img')->item(0);
        $logoUrl = $logoImg->getAttribute('src');

        $paragraph = $doc->getElementsByTagName('p')->item(0);
        $paragraphContent = $paragraph->nodeValue;
        $id = base64_encode($id);

        return view('popup-content.view_popup', [
            'header_background_color' => $headerBackgroundColor,
            'logo_url' => $logoUrl,
            'paragraph_content' => $paragraphContent,
            'id' => $id
        ]);
    
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

        $content = $popup->content;

        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($content);
        libxml_clear_errors();

        $headerDiv = $doc->getElementsByTagName('div')->item(1);
        
        $headerStyle = $headerDiv->getAttribute('style');
        preg_match('/background-color:\s*(#[a-fA-F0-9]{6}|rgba?\(\s*\d+\s*,\s*\d+\s*,\s*\d+(\s*,\s*\d+)?\s*\));/', $headerStyle, $matches);
        $headerBackgroundColor = $matches[1];

        $logoImg = $headerDiv->getElementsByTagName('img')->item(0);
        $logoUrl = $logoImg->getAttribute('src');

        $paragraph = $doc->getElementsByTagName('p')->item(0);
        $paragraphContent = $paragraph->nodeValue;

        return response()->json([
            'header_background_color' => $headerBackgroundColor,
            'logo_url' => $logoUrl,
            'paragraph_content' => $paragraphContent
        ]);
    }


   
}
