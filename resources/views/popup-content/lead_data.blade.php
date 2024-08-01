@extends('base')

@section('title', 'View Lead')

@section('content')
<div class="container" style="max-width: 900px; margin: 20px auto; padding: 20px; background-color: #fff; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); border-radius: 8px;">
    @if ($popupContent)
        <h1 style="margin-top: 0; color: #333;">{{ $popupContent->website_name }}: {{ $popupContent->title }}</h1>
    @endif

    <!-- Display form data -->
    @if ($viewLead->isNotEmpty())
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="padding: 10px; border: 1px solid #ddd; background-color: #f4f4f4;">S.No</th>
                    <th style="padding: 10px; border: 1px solid #ddd; background-color: #f4f4f4;">Host Name</th>
                    <th style="padding: 10px; border: 1px solid #ddd; background-color: #f4f4f4;">Lead Data</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($viewLead as $index => $data)
                    <tr style="border-bottom: 1px solid #ddd;">
                        <td style="padding: 10px; text-align: center;">{{ $index + 1 }}</td>
                        <td style="padding: 10px;">{{ $data->host_name }}</td>
                        <td style="padding: 10px;">
                            <div>
                                @if (is_array($dataArray = $data->form_data))
                                    @foreach ($dataArray as $key => $value)
                                    @if ($key === 'email')
                                        <strong>{{ $key }}:</strong> <a href="mailto:{{ $value }}">{{ $value }}</a>
                                    @elseif ($key === 'mobile')
                                        <strong>{{ $key }}:</strong> <a href="tel:{{ $value }}">{{ $value }}</a>
                                    @else
                                        <strong>{{ $key }}:</strong> {{ $value }}
                                    @endif
                                    <br>
                                    @endforeach
                                @else
                                <p>No data available.</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No Lead data available.</p>
    @endif
</div>
@endsection
