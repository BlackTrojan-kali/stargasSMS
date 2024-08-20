@extends("Layouts.producerLayout")
@section("content")
<center>
    <div>
        <table class=" scroll mt-10 w-full border-2 border-gray-400 border-collapse-0">
            <thead class="text-white font-bold bg-slate-600 p-2">
                <tr>
                    <td>S\L</td>
                    <td>Citerne</td>
                    <td>Qte</td>
                    <td>Reception</td>
                    <td>Matricule Veh</td>
                    <td>Borde. Livraison</td>
                    <td>date</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($receptions as $reception )
                    <tr class="hover:bg-blue-400 hover:text-white cursor-pointer">
                        <td>{{$reception->id}}</td>
                        <td>{{$reception->citerne->name}} ({{$reception->citerne->type}})</td>
                        <td>{{$reception->qty}}</td>
                        <td>{{$reception->receiver}}</td>
                        <td>{{$reception->matricule}}</td>
                        <td>{{$reception->livraison}}</td>
                        <td>{{$reception->created_at}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</center>
<script>
$( function () {
    $('table').DataTable();
    //evement sur les historiques
})</script>
@endsection