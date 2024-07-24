<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advertisement</title>
    <style>
        .copy-button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #28a745;
            border: none;
            color: #fff;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            position: absolute; /* Position the button absolutely within the container */
            bottom: 300px;
            left: 570px;
            z-index: 1000;
        }
    </style>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f0f0f0; margin: 0; padding: 0;">
    <div class="ad-container" style="max-width: 400px; margin: 20px auto; background-color: #ffffff; box-shadow: 0 0 10px rgba(0,0,0,0.1); border-radius: 8px; overflow: hidden;">
        <div class="ad-header" id="ad-header" style="background-color: {{ $header_background_color }}; color: #ffffff; text-align: center; padding: 10px 0;">
            <img src="{{ $logo_url }}" alt="Default Logo" style="height: 50px; vertical-align: middle;" id="headerLogo">
        </div>
        <div class="ad-body" id="body-text" style="padding: 20px; text-align: center;">
            <p>{{ $paragraph_content }}</p>
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
    <button style="margin-top: 20px; padding: 10px 20px; background-color: #6c757d; border: none; color: #fff; font-size: 16px; border-radius: 4px; cursor: pointer; position: fixed; bottom: 20px; left: 20px; z-index: 1000;" onclick="window.location.href='{{ url('/popup-content/create') }}'">Back to Home</button>
    

    <button id="copyButton" class="copy-button">Copy</button>
    <script>
        document.getElementById('copyButton').addEventListener('click', function() {
            // Create a script tag as a string
            const scriptTag = '<script type="text/javascript" src="http://127.0.0.1:8000/js/popup.js?attr={{ $id }}" charset="UTF-8"><\/script>';
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

    </script>
    
</body> 
</html>

