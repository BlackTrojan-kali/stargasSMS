 @extends("Layouts.ManagerLayout")
 @section("content")
 <div class="mx-10">
     <h1 class="text-2xl  font-bold ml-12">Historique des Mouvements </h1>
     <div class="w-full   border-2 border-gray-300 rounded-md">
        <div class="w-full  bg-gray-600 text-white p-2">
            Filtre
        </div>
        <div class="w-full p-3">
            <form action="{{route("manager-filtered-history")}}" method="POST">
                @csrf
            <div class="flex gap-5">
                <div class="champs">
                    <label for="">De</label><br>
                    <input name="fromdate" type="date" id="">
                    @if($errors->has("fromdate"))
                        <p class="text-red-500">{{$errors->first("fromdate")}}</p>
                    @endif
                </div>
                <div class="champs">
                    <label for="">Jusqu'a</label><br>
                    <input name="todate" type="date" id="date"
                     required>
                     @if($errors->has("todate"))
                         <p class="text-red-500">{{$errors->first("todate")}}</p>
                     @endif
                </div>
            </div>
            <div class="flex mt-2">
                <div >
                    <label for="">Article</label>
                <select name="type" class="w-full h-10" id="">
                    <option value="bouteilles-pleines">Bouteilles pleines</option>
                    <option value="bouteilles-vides">Bouteilles vides</option>
                    @foreach ($accessories as $accessory )
                        
                    <option value="{{$accessory->title}}">{{$accessory->title}}</option>
                    @endforeach
                </select>

                @if($errors->has("type"))
                <p class="text-red-500">{{$errors->first("type")}}</p>
            @endif
                </div>
            </div>
            <button type="submit" class="mt-4 bg-blue-400 text-white p-2">Appliquer</button>
        </form>
        </div>
     </div>
     <br><br><br>
     <div>
         <table class=" scroll mt-10 w-full border-2 border-gray-400 border-collapse-0">
            <thead class="p-2 bg-gray-500 text-white">
                <tr>
                    <td>Date</td>
                    <td>identifiant</td>
                    <td>origin</td>
                    <td>article</td>
                    <td>entree</td>
                    <td>sortie</td>
                    <td>qte</td>
                    <td>commantaire</td>
                    
                </tr>
            </thead>
            <tbody>
                @foreach ($allMoves as $move )
                    <tr class="hover:text-white hover:bg-blue-400">
                        <td>{{$move->created_at}}</td>
                        <td>{{$move->id}}</td>
                        <td>{{$move->origin}}</td>
                        <td>{{$move->fromArticle->title}}</td>
                        <td>{{$move->entree}}</td>
                        <td>{{$move->sortie}}</td>
                        <td>{{$move->qty}}</td>
                        <td>{{$move->label}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
     </div>
    </div>
     <script>
        $(document).ready( function () {
    $('table').DataTable();
} );
     </script>
 @endsection