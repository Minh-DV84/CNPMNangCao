@extends('layouts.layoutmaster')
@section('title', 'Danh sách sinh viên')

@push('styles')
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
@endpush

@section('content')
<table>
        <tr>
                <th>ID</th>
                <th>Tên sinh viên</th>
                <th>Tuổi</th>
        </tr>
        @foreach ($sinhviens as $sinhvien)
            <tr>
                <td>{{ $sinhvien['id'] }}</td>
                <td>{{ $sinhvien['name'] }}</td>
                <td>{{ $sinhvien['age'] }}</td>
            </tr>
        @endforeach
    </table>
@endsection