(function() {
    function fetchPopupData(popid) {
        fetch(`http://127.0.0.1:8000/popup-data/${popid}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error('Error fetching popup data:', data.error);
                } else {
                    insertPopup(data);
                }
            })
            .catch(error => console.error('Error fetching popup data:', error));
    }

    function insertPopup(data) {
        const popupHtml = `<div id='popupPreview' style='max-width: 400px; margin: 20px auto; background-color: #ffffff; box-shadow: 0 0 10px rgba(0,0,0,0.1); border-radius: 8px; overflow: hidden; position: relative'>
                                <button style='position: absolute; top: 0px; right: 10px; font-size: 20px; color: #e71e1e; background: #fff; border: none; outline: none;' class="close-btn">&times;</button>
                                <header id='previewHeader' style='background-color: #ffffff; padding: 10px; text-align: center;'>
                                    <span id='previewHeaderText'>${data.header_text}</span>
                                </header>
                                <img id='previewLogo' src='${data.logo_url}' alt='default logo' style='border-radius: 50%; max-height: 50px; display: block; margin: 10px auto;'>
                                <div id='previewBody' style='padding: 20px; text-align: center;'>
                                    <div id='previewContent' style='margin-bottom: 20px;'>${data.body_content}</div>
                                    <form method='post' id='popupForm' style='margin-top: 20px;'>
                                        <div style='margin-bottom: 15px;'>
                                            <input type='text' name='name' autocomplete='name' style='width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;' placeholder='Name' required>
                                        </div>
                                        <div style='margin-bottom: 15px;'>
                                            <input type='text' name='email' autocomplete='email' style='width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;' placeholder='Email' required>
                                        </div>
                                        <div style='margin-bottom: 15px;'>
                                            <input type='text' name='mobile' autocomplete='mobile' style='width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;' placeholder='Phone' required>
                                        </div>
                                        <input type='submit' value='Submit' name='save' style='width: 40%; padding: 10px; background-color: #007bff; border: none; color: #fff; font-size: 16px; border-radius: 4px; cursor: pointer;'>
                                    </form>
                                </div>
                            </div>`;
        document.body.insertAdjacentHTML('beforeend', popupHtml);

        document.querySelector('#popupForm').addEventListener('submit', function(event) {
            event.preventDefault();
            var formData= new FormData(this);
            formData.append('popup_id',data.popup_id);
            formData.append('host_name',window.location.origin);
            var formObject = {};
            formData.forEach((value, key) => { formObject[key] = value });
            submitForm(formObject);
        });

        const closeButton = document.querySelector('.close-btn');
        // Add a click event listener to the close button
        closeButton.addEventListener('click', function() {
            // Find the popup element and remove it or hide it
            const popup = document.getElementById('popupPreview');
            popup.style.display = 'none'; // or popup.remove();
        });
    }

    function submitForm(formObject) {
        
        fetch(`http://127.0.0.1:8000/api/save-popup-data`, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'Accept': 'application/json',   
            },
            body: JSON.stringify({
                popup_id: formObject.popup_id,
                host_name: formObject.host_name,
                form_data: formObject
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('popupPreview').style.display = 'none';
            } else {
                console.log('An error occurred: ' + data.error);
            }
        })
        .catch(error => console.error('Error:', error));
    }
    

    function getScriptTagAttribute() {
        const scripts = document.querySelectorAll('script[src*="popup.js"]');
        if (scripts.length > 0) {
            const src = scripts[0].src;
            const urlParams = new URL(src).searchParams;
            const encodedAttr = urlParams.get('attr');
            return encodedAttr ? atob(encodedAttr) : null;
        }
        return null;
    }

    window.onload = function() {
        const popid = getScriptTagAttribute();
        if (popid) {
            fetchPopupData(popid);
        } else {
            console.error('No popid found in the script tag');
        }
    };
})();
