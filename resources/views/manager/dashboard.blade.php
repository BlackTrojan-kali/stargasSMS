@extends("Layouts.ManagerLayout")
@section("content")
<h1 class="text-center font-bold text-2xl my-4">Accueil</h1>
<div class="w-full mx-5  grid grid-cols-2 gap-10">
  @foreach ($stocks as $stock)

    <?php
    $percent = ($stock->qty/1000) *100
    ?>
  <div class="relative w-6/12">
    <div class="w-11/12 flex justify-between font-bold">
      <p>{{$stock->article->title}} <span class="text-green-500">{{$stock->article->state? "pleine":"vide"}}</span></p>
      <p>{{$stock->qty}}</p>
    </div>
    <div class="w-full bg-gray-300 h-5 rounded-full">
    
    </div>
    <div class="bg-blue-500 h-5 top-6   rounded-full absolute" style="width:{{$percent}}%"></div>
  </div>

  @endforeach
</div>
@endsection