@extends('Layouts.DirectionLayout')
@section('content')
    <center>
        <h1 class="text-2xl font-bold"> {{ $type }} {{ strtoupper($here) }}</h1>
        <div>
            <table class=" scroll text-center mt-10 w-full border-2 border-gray-400 border-collapse-0">
                <thead class="bg-gray-500 text-white p-2 border-collapse-0">
                    <tr>
                        <th>Date</th>
                        <th>CA</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ventes as $vente)
                        <tr>
                            <td>{{ $vente->mois }}/{{ $vente->annee }} </td>
                            <td>{{ $vente->total_gpl }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </center>
@endsection
