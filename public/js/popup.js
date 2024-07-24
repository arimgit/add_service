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
        const popupHtml = `
            <div class="ad-container" style="max-width: 400px; margin: 20px auto; background-color: #ffffff; box-shadow: 0 0 10px rgba(0,0,0,0.1); border-radius: 8px; overflow: hidden;">
                <div class="ad-header" id="ad-header" style="background-color: ${data.header_background_color}; color: #ffffff; text-align: center; padding: 10px 0;">
                    <img src="${data.logo_url}" alt="Default Logo" style="height: 50px; vertical-align: middle;" id="headerLogo">
                </div>
                <div class="ad-body" id="body-text" style="padding: 20px; text-align: center;">
                    <p>${data.paragraph_content}</p>
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
                        <input type="submit" value="Submit" name="save" style="width: 40%; padding: 10px; background-color: #007bff; border: none; color: #fff; font-size: 16px; border-radius: 4px; cursor: pointer;">
                    </form>
                </div>   
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', popupHtml);
    }

    /*function getQueryParam(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }*/

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
