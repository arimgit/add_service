<!-- resources/views/home.blade.php -->
@extends('base')

@section('title', 'List Popup')

@section('content')
<table class="table">
    <thead>
        <tr>
            <th>Website</th>
            <th>Title</th>
            <th>Status</th>
            <th>Action</th>
            <th>View Lead</th>
        </tr>
    </thead>
    <tbody>
        @foreach($websites as $website)
        <tr>
            <td>{{ $website->website_name }}</td>
            <td>{{ $website->title }}</td>
            <td>
                <button type="button" class="js-toggle-status btn btn-sm {{ $website->status == 'active' ? 'btn-success' : 'btn-danger' }}" data-id="{{ $website->id }}" data-status="{{ $website->status }}">
                    {{ $website->status == 'active' ? 'On' : 'Off' }}
                </button>
            </td>
            <td>
                <a href="{{ route('ad_web_manage_popup', ['popupId' => $website->id]) }}" title="Manage Popup"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                <i class="fa-solid fa-copy js-copy-tag" data-id="{{ $website->id }}" enc="{{$enc::encryptString($website->id)}}" title="Copy Script"></i>
            </td>
            <td>
                <a href="{{ route('api_view_lead', ['popupId' => $website->id]) }}" title="View Lead"><i class="fa fa-eye"></i></a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<a href="mailto:abc@g.c">Send Email</a>
<script>
    $(document).ready(function() {
        // Toggle status button click event
        $('.js-toggle-status').on('click', function() {
            var $button = $(this);
            var currentStatus = $button.data('status');
            var newStatus = currentStatus === 'inactive' ? 'active' : 'inactive';

            // Update button text and data-status attribute
            $button.text(newStatus === 'active' ? 'On' : 'Off');
            $button.toggleClass('btn-success btn-danger');
            $button.data('status', newStatus);

            // Send AJAX request to update status in the database
            $.ajax({
                url: '/toggle/status/' + $button.data('id'),
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: newStatus
                },
                success: function(response) {
                    if (response.status === 'success') {
                        console.log('Status updated successfully.');
                    }
                }
            });
        });

        // Copy script tag event
        $('.js-copy-tag').on('click', function() {
            var $copyIcon = $(this);
            var scriptTag = `<script type="text\/javascript" src="${window.location.origin}/uploads/build/popup.js?attr=${$copyIcon.attr('enc')}" charset="UTF-8"><\/script>`;
            const tempTextArea = document.createElement('textarea');
            tempTextArea.value = scriptTag;
            document.body.appendChild(tempTextArea);
            tempTextArea.select();
            document.execCommand('copy');
            document.body.removeChild(tempTextArea);
            Swal.fire({
                icon: "success",
                title: "Script copied to clipboard",
                showConfirmButton: false,
                timer: 1500
            });

        });
    });
</script>
@endsection