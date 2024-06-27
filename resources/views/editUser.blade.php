@extends("Layouts.appLayout")
@section("content")

<center>
 <h1 class="text-xl font-bold">Modifier L'utilisateur {{$user->name}}</h1>
    <form action="{{route('updateUser',$user->id)}}" method="post" class="w-4/12 border-2 mt-2 p-5 border-blue-400 rounded-md">
        @csrf
        <div class="champs">
            <label for="username">Nom:</label>
            <input type="text" name="name" value="{{$user->name}}" required>
            @if ($errors->has("name"))
                <p class="text-red-500"> {{$errors->first('name')}}</p>
            @endif
        </div>
        <div class="w-full text-start">
            <label for="">Region:</label>
            <select name="region" id="" class="w-full text-center p-2 mt-2 mb-2" >

                <option value="{{$user->region}}" selected>{{$user->region}}</option>
                <option value="centre">Centre</option>
                <option value="ouest">Ouest</option>
                <option value="est">Est</option>
                <option value="nord">Nord</option>
                <option value="littoral">Littoral</option>
                <option value="sud">Sud</option>
            </select>
        </div>
        <div class="w-full text-start">
            <label for="">Role:</label>
            <select name="role" id="" class="w-full text-center p-2 mt-2 mb-2" >
                <option value="{{$user->role}}" selected>{{$user->role}}</option>
                <option value="magazinier">Magazinier</option>
                <option value="producteur">Producteur</option>
                <option value="commercial">Commercial</option>
                
            </select>
        </div>
        <div class="w-full flex justify-between mt-4">
            <button type="reset" class="p-2 bg-red-500 text-white">Annuler</button>
            <button type="submit" class="p-2 bg-green-500 text-white">Enregistrer</button>
        </div>
    </form>
</center>

@endsection