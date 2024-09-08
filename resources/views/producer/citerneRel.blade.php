@extends("Layouts.producerLayout")
@section("content")
    <center>
        <div class="w-4/12 border-2 border-black p-5 text-start">
            <h1 class="text-center font-bold text-2xl">Relever</h1>
            <form action="{{ route("postRel",$id) }}" method="POST">
                @csrf
                <br>
                <div class="">
                    <label for="" class="text-2xl">Nom Citerne:</label><br>
                    <span class="font-bold text-blue-400  text-xl">{{$fixer->name }}</span>
                </div>
                <div class="">
                    <label for="" class="text-xl">qte rel:</label><br>
                    <input type="number" placeholder="quantite relever " name="qty"  class="p-2 w-full border  border-black mt-2 h-10">
                @if ($errors->has("qty"))
                    <span>{{ $errors->first("qty") }}</span>
                @endif
                </div>

                <br>
                <div class="text-end">
                
                    <button class="bg-gray-400 p-2 text-white">appliquer</button>
                </div>
            </form>
        </div>
    </center>
@endsection