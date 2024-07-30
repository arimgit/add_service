<div id="popupPreview" style="max-width: 400px; margin: 20px auto; background-color: #ffffff; box-shadow: 0 0 10px rgba(0,0,0,0.1); border-radius: 8px; overflow: hidden; position: relative">
    <span style="position: absolute; top: 0px; right: 10px; font-size: 20px; color: #e71e1e; background: #fff; border: none; outline: none;">&times;</span>
    <header id="previewHeader" style="background-color: #ffffff; padding: 10px; text-align: center;">
        <span id="previewHeaderText">{{ $headerText }}</span>
    </header>
    <img id="previewLogo" src="{{ $logoUrl }}" alt="default logo" style="border-radius: 50%; max-height: 50px; display: block; margin: 10px auto;">
    <div id="previewBody" style="padding: 20px; text-align: center;">
        <div id="previewContent" style="margin-bottom: 20px;">{{ $bodyText }}</div>
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
