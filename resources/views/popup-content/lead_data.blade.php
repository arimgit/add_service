@extends('base')

@section('title', 'View Lead')

@section('content')
<div class="container" style="max-width: 900px; margin: 20px auto; padding: 20px;  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); border-radius: 8px;">
    @if ($popupContent)
        <h1 style="margin-top: 0; color: #333;">{{ $popupContent->title }}: {{ $popupContent->website_name }}</h1>
    @endif

    <!-- Display form data -->
    @if ($viewLead->isNotEmpty())
        <table class="table display" id="leadtable">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact No.</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($viewLead as $index => $data)
                    @php
                        $formData = $data->form_data;
                        $name = $formData['name'] ?? 'N/A';
                        $email = $formData['email'] ?? 'N/A';
                        $mobile = $formData['mobile'] ?? 'N/A';
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $name }}</td>
                        <td><a href="mailto:{{ $email }}">{{ $email }}</a></td>
                        <td><a href="tel:{{ $mobile }}">{{ $mobile }}</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No Lead data available.</p>
    @endif
</div>
<script>
    let table = new DataTable('#leadtable', {
            responsive: true
        });
</script>
@endsection
