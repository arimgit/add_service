<!-- resources/views/home.blade.php -->
@extends('base')

@section('title', 'List Popup')

@section('content')
<table class="table">
    <thead>
        <tr>
            <th>Website</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($websites as $id => $website)
        <tr>
            <td>{{ $website }}</td>
            <td>
                <a href="{{ route('ad_web_manage_popup', ['popupId' => $id]) }}"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                <i class="fa-solid fa-copy js-copy-tag" enc="{{$enc::encryptString($id)}}"></i>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<script>
    $(document).ready(function() {
        $('.js-copy-tag').on('click', function() {
            // Select the text area content
            var scriptTag = `<script type="text/javascript" src="${window.location.origin}/js/popup.js?attr=${$(this).attr('enc')}" charset="UTF-8"><\/script>`;
            const tempTextArea = document.createElement('textarea');
            tempTextArea.value = scriptTag;
            document.body.appendChild(tempTextArea);

            // Select the content and copy to clipboard
            tempTextArea.select();
            document.execCommand('copy');

            // Remove the temporary textarea
            document.body.removeChild(tempTextArea);

            // Optional: Notify the user that the script tag has been copied
            alert('Script tag copied to clipboard!');
        });
    });
</script>
@endsection