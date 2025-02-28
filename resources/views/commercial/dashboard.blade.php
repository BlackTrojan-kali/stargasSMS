@extends('Layouts.comLayout')
@section('content')
    <h1 class="text-center font-bold text-2xl my-4">Accueil</h1>
    <div class=" text-end px-10">
        <a href="{{ route('cartlist') }}">
            <i class="fa-solid fa-shopping-bag text-4xl"></i>
            <span id="count" class="font-bold ">{{ Cart::count() }}</span>

        </a>
    </div>
    <div class="grid grid-cols-2 w-full p-10 gap-20">
        @foreach ($articles as $article)
            @if ($article->type == 'accessoire')
                <div class="Productcard" id="{{ $article->id }}">
                    <h1>{{ $article->title }}</h1>
                    <p><b>type:</b>{{ $article->type }}</p>
                    <button class="addProd">ajouter</button>

                </div>
            @else
                @if ($article->state > 0)
                    <div class="Productcard" id="{{ $article->id }}">
                        <h1>{{ $article->type }}</h1>
                        <p>
                            <b>poids : </b>{{ $article->weight }} KG<br>
                            <b>etat:</b>{{ $article->state ? 'pleine' : 'vide' }}
                        </p>
                        <button class="addProd">ajouter</button>
                    </div>
                @endif
            @endif
        @endforeach
    </div>
    <script type="module">
        $(function() {
            //add prod to cart
            $(".addProd").on("click", function(e) {
                e.preventDefault();
                let idProd = $(this).parent().attr("id");
                $.ajax({
                    url: "/commercial/addToCart/" + idProd,
                    type: "GET",
                    success: function(res) {
                        toastr.warning(res.message)
                        $(" #count").load(location.href + " #count");
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr);
                        console.log(error);
                    }
                })
            })
        })
    </script>

    <!--
                                                                                                                                                                                                                                 <div class="w-full mx-5  grid grid-cols-2 gap-10 info">
                                                                                                                                                                                                                                        @foreach ($stocks as $stock)
    <?php
    $percent = ($stock->qty / 30000) * 100;
    ?>
                                                                                                                                                                                                                                            <div class="relative w-6/12">
                                                                                                                                                                                                                                                <div class="w-11/12 flex justify-between font-bold">
                                                                                                                                                                                                                                                    <p>{{ $stock->article->title }} <span
                                                                                                                                                                                                                                                            class="general">{{ $stock->article->weight > 0 ? $stock->article->weight . 'kg' : '' }} </span>
                                                                                                                                                                                                                                                        @if ($stock->type == 'bouteille-gaz')
    <span class="text-green-500">{{ $stock->article->state ? 'pleine' : 'vide' }}</span>
                                                                                                                                                                                                                                                    </p>
    @endif
                                                                                                                                                                                                                                        <p>{{ $stock->qty }}</p>
                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                    <div class="w-full bg-gray-300 h-8 rounded-e-full relative">

                                                                                                                                                                                                                                        <div class="primary h-8 top-0   rounded-e-full absolute" style="width:{{ $percent }}%"></div>
                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                    </div>
    @endforeach
                                                                                                                                                                                                                                    <br><br><br>
                                                                                                                                                                                                                                </div> -->
@endsection
