@extends("Layouts.appLayout")
@section("content")
<div class="mt-8 p-2">
    <div class=" mb-2 flex justify-end p-3 ">
        <a href="{{route('addUser')}}" class="p-4 bg-green-400 text-white font-bold">Ajouter un Utilisateur</a>
    </div>
    <table class="w-full table-auto bg-slate-200 border-separate p-2">
        <thead class="text-center font-bold py-12">
            <tr class="">
                <td>id</td>
                <td>Nom d'utilisateur</td>
                <td>Email</td>
                <td>role</td>
                <td>region</td>
                <td>actions</td>
            </tr>
            <tbody>
                @foreach ($users as $user )
                <tr class="mb-5 text-center">
                    <td>{{$user->id}}<s/td>
                    <td>{{$user->name}}</td>
                    <td>{{$user->email}}</td>
                    <td>{{$user->role}}</td>
                    <td>{{$user->region}}</td>
                    <td>
                        <a href="{{route('editUser',$user->id)}}" class="px-4 p-1 rounded-md bg-blue-500 text-white"><i class="fa-solid fa-edit"></i></a>
                        <a href="" class="px-4 p-1 rounded-md bg-red-500 text-white"><i class="fa-solid fa-trash"></i></a>
                    </td>
                </tr>
                    
                @endforeach
                <br>
            </tbody>
        </thead>
    </table>
</div>
@endsection