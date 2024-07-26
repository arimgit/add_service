<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Options</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f0f0f0; margin: 0; padding: 0;">
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
    <div class="container d-flex" style="margin-top: 20px;">
        <div class="list-group" style="flex: 1; margin-right: 20px;">
            @foreach($websites as $id => $website)
                <a href="#" class="list-group-item list-group-item-action" data-id="{{ $id }}">{{ $website }}</a>
            @endforeach
        </div>
        <div class="content" style="flex: 3;">
            <div id="popupContent" style="position: relative; background-color:; padding: 40px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
                <h3>Select a website to view popup content</h3>
                
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.list-group-item').forEach(item => {
            item.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                fetch(`/popup-content/${id}`)
                    .then(response => response.json())
                    .then(data => {
                        const contentDiv = document.getElementById('popupContent');
                        contentDiv.innerHTML = data.content;
                        contentDiv.innerHTML += `<button id="copyButton" style="position: absolute; float: right; background-color: #F3F4F6; border: 1px solid #D1D5DB; color: #111827; padding: 8px 12px; font-size: 16px; border-radius: 4px; cursor: pointer; display: flex; align-items: center; gap: 6px; top: 10px; right: 210px;">
                                                    <i class="fas fa-copy" style="font-size: 18px;"></i> Copy
                                                </button>`;
                        document.getElementById('copyButton').addEventListener('click', function() {
                            const encodedPopupId = btoa(id);
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
                    })
                    .catch(error => console.error('Error fetching popup content:', error));
            });    
        });   
    </script>
</body>
</html>
