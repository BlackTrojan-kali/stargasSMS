@extends('Layouts.DirectionLayout')
@section('content')
    <center>
        <h1 class="text-2xl font-bold">Versements Global {{ $type }}</h1>
        <div>
            <table class=" scroll text-center mt-10 w-full border-2 border-gray-400 border-collapse-0">
                <thead class="bg-gray-500 text-white p-2 border-collapse-0 ">
                    <tr>
                        <th>Date</th>
                        <th>Versements</th>
                        <th>Bank</th>
                    </tr>
                </thead>
                <tbody class="text-start">
                    @foreach ($versements as $versement)
                        <tr>
                            <td>{{ $versement->mois }}/{{ $versement->annee }} </td>
                            <td>{{ $versement->total_gpl }}</td>
                            <td>{{ $versement->bank }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </center>
@endsection
