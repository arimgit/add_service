<!-- resources/views/home.blade.php -->
@extends('base')

@section('title', 'List Popup')

@section('content')
<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 40px;
        /* Adjust width to make it thinner */
        height: 20px;
        /* Adjust height to make it thinner */
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        border-radius: 10px;
        /* Adjust to fit the new height */
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 16px;
        /* Adjust the handle height */
        width: 16px;
        /* Adjust the handle width */
        left: 2px;
        /* Adjust the left position */
        bottom: 2px;
        /* Adjust the bottom position */
        background-color: white;
        border-radius: 50%;
        /* Keep the handle round */
        transition: .4s;
    }

    input:checked+.slider {
        background-color: #2196F3;
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked+.slider:before {
        transform: translateX(20px);
        /* Adjust this based on the new width */
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 10px;
        /* Adjust to match the new height */
    }

    .slider.round:before {
        border-radius: 50%;
    }
</style>
<div class="container" style="max-width: 900px; margin: 20px auto; padding: 20px;  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); border-radius: 8px;">
    <table class="table display" id="listTable">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Website</th>
                <th>Title</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($websites as $index => $website)
            <tr class="text-left">
                <td>{{ $index + 1 }}</td>
                <td class="w-25">{{ $website->website_name }}</td>
                <td class="w-25">{{ $website->title }}</td>
                <td>
                    <a href="{{ route('ad_web_manage_popup', ['popupId' => $website->id]) }}" title="Manage Popup"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                    <i class="fa-solid fa-copy js-copy-tag" data-id="{{ $website->id }}" enc="{{$enc::encryptString($website->id)}}" title="Copy Script"></i>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                    <label class="switch" title="On/Off Popup">
                        <input type="checkbox" class="js-toggle-status" data-id="{{ $website->id }}" data-status="{{ $website->status }}" {{ $website->status == 'active' ? 'checked' : '' }}>
                        <span class="slider round"></span>
                    </label>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                    <a href="{{ route('api_view_lead', ['popupId' => $website->id]) }}" title="View Lead ({{ $website->lead_count }})"><i class="fa fa-eye"></i></a>
                    
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
                        /*if (response.status === 'success') {
                            console.log('Status updated successfully.');
                        }*/
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