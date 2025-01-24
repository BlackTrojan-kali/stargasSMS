@extends('Layouts.DirectionLayout')
@section('content')
    <center>
        <h1 class="text-2xl font-bold"> {{ $type }} Global</h1>
        <div>
            <table class=" scroll text-center mt-10 w-full border-2 border-gray-400 border-collapse-0">
                <thead class="bg-gray-500 text-white p-2 border-collapse-0">
                    <tr>
                        <th>Date</th>
                        <th>Qte 6 KG</th>
                        <th>Qte 12.5 KG</th>
                        <th>Qte 50 kg</th>
                        <th>CA</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ventes as $vente)
                        <tr>
                            <td>{{ $vente->mois }}/{{ $vente->annee }} </td>
                            <td>{{number_format( $vente->somme_qty_6, 2, ',', ' ') }}</td>
                            <td>{{ number_format($vente->somme_qty_12 , 2, ',', ' ')}}</td>
                            <td>{{ number_format($vente->somme_qty_50 , 2, ',', ' ')}}</td>
                            <td>{{number_format( $vente->total_gpl, 2, ',', ' ') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </center>
@endsection
