<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create Popup Content</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f0f0f0; margin: 0; padding: 0; display: flex; flex-direction: column; height: 100vh;">
    <nav class="navbar navbar-expand-lg navbar-light bg-light" style="margin-bottom: 20px; flex-shrink: 0;">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('popup-content.create') }}">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('website-options') }}">Website</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout.post') }}">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container d-flex">
        <div style="flex: 1; margin-right: 20px;">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div style="background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); width: 100%; max-width: 400px; margin: auto;">
                <h2 style="text-align: center; margin-bottom: 20px;">Create Popup</h2>
                <form id="popupForm" method="post" enctype="multipart/form-data">
                    @csrf
                    <div style="margin-bottom: 15px;">
                        <label for="website_name" style="display: block; margin-bottom: 5px; font-weight: bold;">Website Name:</label>
                        <input type="text" id="website_name" name="website_name" value="{{ $popup->website_name ?? old('website_name') }}" required style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label for="header_text" style="display: block; margin-bottom: 5px; font-weight: bold;">Header Text:</label>
                        <input type="text" id="header_text" name="header_text" required style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label for="header_logo" style="display: block; margin-bottom: 5px; font-weight: bold;">Header Logo:</label>
                        <input type="file" id="header_logo" name="header_logo" style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label for="body_text" style="display: block; margin-bottom: 5px; font-weight: bold;">Body Content:</label>
                        <textarea id="body_text" name="body_text" style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;" required></textarea>
                    </div>
                    <button type="submit" formaction="{{ route('popup-content.store') }}" formmethod="post" style="width: 100%; padding: 10px; background-color: #28a745; border: none; color: #fff; font-size: 16px; border-radius: 4px; cursor: pointer; margin-top: 10px;">Save changes</button>
                </form>
            </div>
        </div>
        @if($popup)
            <div style="background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); width: 100%; max-width: 400px; margin: auto;">
                <h2 style="text-align: left; margin-bottom: 20px;">Saved Popup
                <button id="copyButton" style="float: right; background-color: #F3F4F6; border: 1px solid #D1D5DB; color: #111827; padding: 8px 12px; font-size: 16px; border-radius: 4px; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                    <i class="fas fa-copy" style="font-size: 18px;"></i> Copy
                </button>
                </h2>
                {!! $popup->content !!}
            </div>
        @else
            <div style="background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); width: 100%; max-width: 400px; margin: auto;">
                <h2 style="text-align: center; margin-bottom: 20px;">Live Preview</h2>
                <div id="popupPreview" style="max-width: 600px; margin: 20px auto; background-color: #ffffff; box-shadow: 0 0 10px rgba(0,0,0,0.1); border-radius: 8px; overflow: hidden; position: relative">
                <span style="position: absolute; top: 0px; right: 10px; font-size: 20px; color: #e71e1e; background: #fff; border: none; outline: none;">&times;</span>
                    <header id="previewHeader" style="background-color: #ffffff; padding: 10px; text-align: center;">
                        <span id="previewHeaderText"></span>
                    </header>
                    <img id="previewLogo" src="/storage/logos/QMjFxw3Qy226KmFs4kCC4nF5eGMWfY5uj4bEqmYS.jpg" alt="default logo" style="border-radius: 50%; max-height: 50px; display: block; margin: 10px auto;">
                    <div id="previewBody" style="padding: 20px; text-align: center;">
                        <div id="previewContent" style="margin-bottom: 20px;"></div>
                        <form action="" method="post" style="margin-top: 20px;">
                            <div style="margin-bottom: 15px;">
                                <input type="text" name="name" style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;" placeholder="Name">
                            </div>
                            <div style="margin-bottom: 15px;">
                                <input type="text" name="email" style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;" placeholder="Email">
                            </div>
                            <div style="margin-bottom: 15px;">
                                <input type="text" name="mobile" style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;" placeholder="Phone">
                            </div>
                            <input type="button" value="Submit" name="save" style="width: 40%; padding: 10px; background-color: #007bff; border: none; color: #fff; font-size: 16px; border-radius: 4px; cursor: pointer;">
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script>
        document.getElementById('header_text').addEventListener('input', updatePreview);
        document.getElementById('header_logo').addEventListener('change', updatePreview);
        document.getElementById('body_text').addEventListener('input', updatePreview);

        function updatePreview() {
            // Update header text
            const headerText = document.getElementById('header_text').value;
            document.getElementById('previewHeaderText').innerText = headerText;

            // Update logo image
            const headerLogo = document.getElementById('header_logo').files[0];
            if (headerLogo) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewLogo').src = e.target.result;
                };
                reader.readAsDataURL(headerLogo);
            } else {
                document.getElementById('previewLogo').src = '/storage/logos/QMjFxw3Qy226KmFs4kCC4nF5eGMWfY5uj4bEqmYS.jpg'; // Set to default image URL
            }

            // Update body content
            const bodyText = document.getElementById('body_text').value;
            document.getElementById('previewContent').innerText = bodyText;
        }

        document.getElementById('copyButton').addEventListener('click', function() {
            @if($popup)
                const popupId = "{{ $popup->id }}";
            @endif
            // Encode the popup ID using Base64 encoding
            const encodedPopupId = btoa(popupId);
            // Create a script tag as a string
            const scriptTag = `<script type="text/javascript" src="http://127.0.0.1:8000/js/popup.js?attr=${encodedPopupId}" charset="UTF-8"><\/script>`;
            // Create a temporary textarea to hold the script tag
            const tempTextArea = document.createElement('textarea');
            tempTextArea.value = scriptTag;
            document.body.appendChild(tempTextArea);
            
            // Select the content and copy to clipboard
            tempTextArea.select();
            document.execCommand('copy');
            
            // Remove the temporary textarea
            document.body.removeChild(tempTextArea);
        });
  
    </script>
    
</body>
</html>
