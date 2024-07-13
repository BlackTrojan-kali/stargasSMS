 @extends("Layouts.ManagerLayout")
 @section("content")
     <h1 class="text-2xl  font-bold ml-12">Historique des Mouvements </h1>
     <div class="w-full   border-2 border-gray-300 rounded-md">
        <div class="w-full  bg-gray-600 text-white p-2">
            Filtre
        </div>
        <div class="w-full p-3">
            <form action="">
                @csrf
            <div class="flex gap-5">
                <div class="champs">
                    <label for="">De</label><br>
                    <input type="date" name="" id="">
                </div>
                <div class="champs">
                    <label for="">Jusqu'a</label><br>
                    <input type="date" name="date" id="date"
                    value="2023-03-21" required>
                </div>
            </div>
            <div class="flex mt-2">
                <div >
                    <label for="">Article</label>
                <select name="" class="w-full h-10" id="">
                    <option value="">Bouteilles pleines</option>
                    <option value="">Bouteilles vides</option>
                    @foreach ($accessories as $accessory )
                        
                    <option value="">{{$accessory->title}}</option>
                    @endforeach
                </select>
                </div>
            </div>
            <button type="submit" class="mt-4 bg-blue-400 text-white p-2">Appliquer</button>
        </form>
        </div>
     </div>
 @endsection