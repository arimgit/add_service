<!-- resources/views/home.blade.php -->
@extends('base')

@section('title', 'Manage Popup')

@section('content')
<div style="background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); width: 100%; max-width: 400px; margin: auto;">
    <h2 style="text-align: center; margin-bottom: 20px;">Create Popup</h2>
    <form id="popupForm" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="popup_id" value="{{ $popup->id ?? '' }}">
        <div style="margin-bottom: 15px;">
            <label for="website_name" style="display: block; margin-bottom: 5px; font-weight: bold;">Website Name:</label>
            <input type="text" id="website_name" name="website_name" value="{{ $popup->website_name ?? old('website_name') }}" required style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
        </div>
        <div style="margin-bottom: 15px;">
            <label for="title" style="display: block; margin-bottom: 5px; font-weight: bold;">Title:</label>
            <input type="text" id="title" name="title" value="{{ $popup->title ?? old('title') }}" required style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
        </div>
        <div style="margin-bottom: 15px;">
            <label for="header_text" style="display: block; margin-bottom: 5px; font-weight: bold;">Header Text:</label>
            <input type="text" id="header_text" name="header_text" value="{{ $headerText ?? old('header_text') }}" required style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
        </div>
        <div style="margin-bottom: 15px;">
            <label for="header_logo" style="display: block; margin-bottom: 5px; font-weight: bold;">Header Logo:</label>
            <input type="file" id="header_logo" name="header_logo" style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
        </div>
        <div style="margin-bottom: 15px;">
            <label for="body_text" style="display: block; margin-bottom: 5px; font-weight: bold;">Body Content:</label>
            <textarea id="body_text" name="body_text" style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;" required>{{ $bodyText ?? old('body_text') }}</textarea>
        </div>
        <button type="submit" formaction="{{ route('ad_web_manage_popup', ['popupId' => $popup->id ?? -1]) }}" formmethod="post" style="width: 100%; padding: 10px; background-color: #28a745; border: none; color: #fff; font-size: 16px; border-radius: 4px; cursor: pointer; margin-top: 10px;">
            {{ $popup ? 'Update Popup' : 'Create Popup' }}
        </button>
    </form>
</div>
</div>
@if($popup)
<div style="background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); width: 100%; max-width: 400px; margin: auto;">
    <h2 style="text-align: left; margin-bottom: 20px;">Preview
        <!-- <button id="copyButton" style="float: right; background-color: #F3F4F6; border: 1px solid #D1D5DB; color: #111827; padding: 8px 12px; font-size: 16px; border-radius: 4px; cursor: pointer; display: flex; align-items: center; gap: 6px;">
            <i class="fas fa-copy" style="font-size: 18px;"></i> Copy
        </button> -->
    </h2>
    {!! $popup->content !!}
</div>
@else
<div style="background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); width: 100%; max-width: 400px; margin: auto;">
    <h2 style="text-align: center; margin-bottom: 20px;">Preview</h2>
    <div id="popup" style="max-width: 600px; margin: 20px auto; background-color: #ffffff; box-shadow: 0 0 10px rgba(0,0,0,0.1); border-radius: 8px; overflow: hidden; position: relative">
        <span style="position: absolute; top: 0px; right: 10px; font-size: 20px; color: #e71e1e; background: #fff; border: none; outline: none;">&times;</span>
        <header id="previewHeader" style="background-color: #ffffff; padding: 10px; text-align: center;">
            <span id="previewHeaderText" style="font-size:larger; font-weight:bold;"></span>
        </header>
        <img id="previewLogo" src="/default_storage/building-icon.svg" alt="default logo" style="border-radius: 50%; max-height: 50px; display: block; margin: 10px auto;">
        <div id="previewBody" style="padding: 20px; text-align: center;">
            <div id="previewContent" style="margin-bottom: 20px;"></div>
            <form action="" method="post" style="margin-top: 20px;">
                <div style="margin-bottom: 15px;">
                    <input type="text" name="name" style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;" placeholder="Name" readonly>
                </div>
                <div style="margin-bottom: 15px;">
                    <input type="text" name="email" style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;" placeholder="Email" readonly>
                </div>
                <div style="margin-bottom: 15px;">
                    <input type="text" name="mobile" style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;" placeholder="Phone" readonly>
                </div>
                <input type="button" value="Submit" name="save" style="width: 40%; padding: 10px; background-color: #007bff; border: none; color: #fff; font-size: 16px; border-radius: 4px; cursor: pointer;">
            </form>
        </div>
    </div>
</div>
@endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const headerTextElement = document.getElementById('header_text');
        const headerLogoElement = document.getElementById('header_logo');
        const bodyTextElement = document.getElementById('body_text');

        if (headerTextElement) {
            headerTextElement.addEventListener('input', updatePreview);
        }
        if (headerLogoElement) {
            headerLogoElement.addEventListener('change', updatePreview);
        }
        if (bodyTextElement) {
            bodyTextElement.addEventListener('input', updatePreview);
        }
        function updatePreview() {
            // Update header text
            const headerText = headerTextElement.value;
            document.getElementById('previewHeaderText').innerText = headerText;

            // Update logo image
            const headerLogo = headerLogoElement.files[0];
            if (headerLogo) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewLogo').src = e.target.result;
                };
                reader.readAsDataURL(headerLogo);
            } else {
                document.getElementById('previewLogo').src = '/default_storage/building-icon.svg'; // Set to default image URL
            }

            // Update body content
            const bodyText = bodyTextElement.value;
            document.getElementById('previewContent').innerText = bodyText;
        }

        // document.getElementById('copyButton').addEventListener('click', function() {
        //     var encryptedPopupId = "{{ $encryptedPopupId }}";
        //     // Create a script tag as a string
        //     const scriptTag = `<script type="text/javascript" src="${window.location.origin}/js/popup.js?attr=${encryptedPopupId}" charset="UTF-8"><\/script>`;
        //     // Create a temporary textarea to hold the script tag
        //     const tempTextArea = document.createElement('textarea');
        //     tempTextArea.value = scriptTag;
        //     document.body.appendChild(tempTextArea);

        //     // Select the content and copy to clipboard
        //     tempTextArea.select();
        //     document.execCommand('copy');

        //     // Remove the temporary textarea
        //     document.body.removeChild(tempTextArea);
        // });
    });
</script>
@endsection