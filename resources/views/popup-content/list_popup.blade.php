<!-- resources/views/home.blade.php -->
@extends('base')

@section('title', 'List Popup')

@section('content')
<style>
        .switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
    }

    /* Hide default HTML checkbox */
    .switch input {
    opacity: 0;
    width: 0;
    height: 0;
    }

    /* The slider */
    .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    -webkit-transition: .4s;
    transition: .4s;
    }

    .slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
    }

    input:checked + .slider {
    background-color: #2196F3;
    }

    input:focus + .slider {
    box-shadow: 0 0 1px #2196F3;
    }

    input:checked + .slider:before {
    -webkit-transform: translateX(26px);
    -ms-transform: translateX(26px);
    transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
    border-radius: 34px;
    }

    .slider.round:before {
    border-radius: 50%;
    }
</style>
<div class="container" style="max-width: 900px; margin: 20px auto; padding: 20px;  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); border-radius: 8px;"
</div>
<table class="table display" id="listTable">
    <thead>
        <tr>
            <th>S.No</th>
            <th>Website</th>
            <th>Title</th>
            <th>Status</th>
            <th>View Lead</th>
            <th>Action</th> 
        </tr>
    </thead>
    <tbody>
        @foreach($websites as $index => $website)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $website->website_name }}</td>
            <td>{{ $website->title }}</td>
            <td>
                <label class="switch">
                    <input type="checkbox" class="js-toggle-status" data-id="{{ $website->id }}" data-status="{{ $website->status }}" {{ $website->status == 'active' ? 'checked' : '' }}>
                    <span class="slider round"></span>
                </label>
            </td>
            <td>
                <a href="{{ route('api_view_lead', ['popupId' => $website->id]) }}" title="View Lead"><i class="fa fa-eye"></i></a>
            </td>
            <td>
                <a href="{{ route('ad_web_manage_popup', ['popupId' => $website->id]) }}" title="Manage Popup"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                <i class="fa-solid fa-copy js-copy-tag" data-id="{{ $website->id }}" enc="{{$enc::encryptString($website->id)}}" title="Copy Script"></i>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<script>
    $(document).ready(function() {
        // Toggle status button click event
        $('.js-toggle-status').on('click', function() {
            var $checkbox = $(this);
            var newStatus = $checkbox.prop('checked') ? 'active' : 'inactive';

            // Send AJAX request to update status in the database
            $.ajax({
                url: '/toggle/status/' + $checkbox.data('id'),
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
 
        let table = new DataTable('#listTable', {
            responsive: true
        });
    });
</script>
@endsection