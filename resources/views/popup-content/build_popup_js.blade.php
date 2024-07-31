(function () {
    const cookieName = "check_popup";
    function fetchPopupData(popId) {
        fetch(`{{$origin}}/popup/data/${popId}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error('Error fetching popup data:', data.error);
                } else {
                    if (data.status === 'active') { // Check if status is active
                        insertPopup(data);
                    } else {
                        console.log('Popup is not active and will not be displayed.');
                    }
                }
            })
            .catch(error => console.error('Error fetching popup data:', error));
    }

    function insertPopup(data) {

        const popupHtml = `
            <div id="overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 999;">
                <div id='popup' style='max-width: 400px; margin: 20px auto; background-color: #ffffff; box-shadow: 0 0 10px rgba(0,0,0,0.1); border-radius: 8px; overflow: hidden; position: relative; top: 50%; transform: translateY(-50%); z-index: 1000;'>
                    <button style='position: absolute; top: 0px; right: 10px; font-size: 20px; color: #e71e1e; background: #fff; border: none; outline: none; cursor: pointer;' class="close-btn">&times;</button>
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
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', popupHtml);

        document.querySelector('#popupForm').addEventListener('submit', function (event) {
            event.preventDefault();
            var formData = new FormData(this);
            formData.append('popup_id', data.popup_id);
            formData.append('host_name', window.location.origin);
            var formObject = {};
            formData.forEach((value, key) => { formObject[key] = value });
            submitForm(formObject);
        });

        const closeButton = document.querySelector('.close-btn');

        // Add a click event listener to the close button
        closeButton.addEventListener('click', function () {
            // Find the overlay element and hide it or remove it
            const overlay = document.getElementById('overlay');
            overlay.style.display = 'none'; // or overlay.remove();
            preventPopup();
        });

    }

    function submitForm(formObject) {

        fetch(`{{$origin}}/api/manage/popup/formdata`, {
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
                    document.getElementById('overlay').style.display = 'none';
                    preventPopup();
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
            return encodedAttr = urlParams.get('attr');
        }
        return null;
    }

    // Function to generate a unique identifier (UUID)
    function generateUniqueValue() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
            var r = Math.random() * 16 | 0, v = c === 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }

    // Function to set a cookie with an expiration timestamp
    function setCookie(name, value, days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        var expires = "expires=" + date.toUTCString();
        var expirationTimestamp = Math.floor(date.getTime() / 1000); // Store expiration as a Unix timestamp
        var cookieValue = JSON.stringify({
            value: value,
            expires: expirationTimestamp
        });
        document.cookie = name + "=" + encodeURIComponent(cookieValue) + ";" + expires + ";path=/";
    }

    // Function to retrieve a cookie value and parse its JSON content
    function getCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1);
            if (c.indexOf(nameEQ) === 0) {
                var cookieData = decodeURIComponent(c.substring(nameEQ.length, c.length));
                return JSON.parse(cookieData);
            }
        }
        return null;
    }

    // Function to check if a cookie has expired
    function isCookieExpired(cookie) {
        if (!cookie) return true; // Cookie does not exist or is not valid
        var now = Math.floor(new Date().getTime() / 1000); // Current Unix timestamp
        return now > cookie.expires;
    }

    // Function to handle the cookie logic
    function handleCookie() {

        var cookie = getCookie(cookieName);
        if (cookie && !isCookieExpired(cookie)) {
            // Cookie exists and is not expired
            // console.log("Cookie exists with value:", cookie.value);
        } else {
            const popId = getScriptTagAttribute();
            if (popId) {
                fetchPopupData(popId);
            } else {
                console.error('No pop id found in the script tag');
            }
        }
    }

    function preventPopup() {
        var newCookieValue = generateUniqueValue();
        setCookie(cookieName, newCookieValue, 7); // Set cookie to expire in 7 days
        // console.log("New cookie set with value:", newCookieValue);
    }

    // Execute the cookie handling logic when the page loads
    window.onload = function () {
        handleCookie();
    };
})();
