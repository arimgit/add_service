<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Popup Content</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <style>
        .rounded-logo {
            border-radius: 50%;
            max-height: 50px;
            display: block;
            margin: 10px auto;
        }
        .close-btn {
            position: absolute;
            top: 0px;
            right: 10px;
            font-size: 20px;
            color: #e71e1e;
            background: #fff;
            border: none;
            outline: none;
        }
    </style>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f0f0f0; margin: 0; padding: 0; display: flex; justify-content: center; align-items: center; height: 100vh;">
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
                        <input type="text" id="website_name" name="website_name" required style="width: 100%; padding: 0; height: 35px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
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
        <div style="background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); width: 100%; max-width: 400px; margin: auto;">
            <h2 style="text-align: center; margin-bottom: 20px;">Live Preview</h2>
            <div id="popupPreview" style="max-width: 600px; margin: 20px auto; background-color: #ffffff; box-shadow: 0 0 10px rgba(0,0,0,0.1); border-radius: 8px; overflow: hidden; position: relative">
                <span class="close-btn">&times;</span>
                <header id="previewHeader" style="background-color: #ffffff; padding: 10px; text-align: center;"></header>
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
    </div>

    <script>
        document.getElementById('header_text').addEventListener('input', updatePreview);
        document.getElementById('header_logo').addEventListener('change', updatePreview);
        document.getElementById('body_text').addEventListener('input', updatePreview);

        function updatePreview() {
            const headerText = document.getElementById('header_text').value;
            const headerLogo = document.getElementById('header_logo').files[0];

            let headerContent = `<span>${headerText}</span>`;

            if (headerLogo) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    headerContent += `<img src="${e.target.result}" class="rounded-logo">`;
                    document.getElementById('previewHeader').innerHTML = headerContent;
                };
                reader.readAsDataURL(headerLogo);
            } else {
                document.getElementById('previewHeader').innerHTML = headerContent;
            }

            const bodyText = document.getElementById('body_text').value;
            document.getElementById('previewContent').innerText = bodyText;
        }
    </script>
</body>
</html>
