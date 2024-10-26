@extends("Layouts.comLayout")
@section("content")

    <center>
        
        @if(!empty($moves2->first()))
        <h1>MOUVEMENT DES {{ $moves2[0]->fromArticle->type }} {{ $moves2[0]->fromArticle->weight }} KG  VIDES</h1>
    @endif
        <table class=" scroll mt-10 w-full border-2 border-gray-400 border-collapse-0">
            <thead class="bg-gray-500 text-white p-2 border-collapse-0">
                <tr>
                    <td>VENTES</td>
                    <td>ACHATS</td>
                    <td>PERTES</td>
                    <td>DATES</td>
                    <td>LIBELLE</td>
                    <td>ENTREE</td>
                    <td>SORTIE</td>
                    <td>STOCK</td>
                    <td>action</td>
                </tr>
            </thead>
            <tbody>
                @if(!empty($moves2->first()))
                @foreach ($moves2 as $move )
                <tr id={{ $move->id }} class="hover:bg-blue-400 hover:text-white hover:cursor-pointer">
                    <td>
                       {{ $move->origin == "ventes" ? $move->qty:0 }}
                    </td>
                    <td>
                        {{ $move->origin == "achat" ? $move->qty:0 }}</td>
                    <td>
                        {{ $move->origin == "pertes" ? $move->qty:0 }}</td>
                    <td>{{ $move->created_at }}</td>
                    <td>{{ $move->label }}</td>
                    <td>{{ $move->entree ? $move->qty :0 }}</td>
                    <td>{{ $move->sortie ? $move->qty :0 }}</td>
                    <td>{{ $move->stock }}</td>
                    <td><span class="text-red-500 delete"> supprimer</span></td>

                </tr>     
                @endforeach
            @else
                <p> aucun resultat</p>
            @endif
            </tbody>
        </table>
        <br>
        <br>
        <br>
        @if(!empty($moves->first()))
            <h1>MOUVEMENT DES {{ $moves[0]->fromArticle->type }} {{ $moves[0]->fromArticle->weight }} KG  PLEINES</h1>
            @endif
            <table class=" scroll mt-10 w-full border-2 border-gray-400 border-collapse-0">
                <thead class="bg-gray-500 text-white p-2 border-collapse-0">
                    <tr>
                        <td>VENTES</td>
                        <td>ACHATS</td>
                        <td>PERTES</td>
                        <td>DATES</td>
                        <td>LIBELLE</td>
                        <td>ENTREE</td>
                        <td>SORTIE</td>
                        <td>STOCK</td>
                        <td>ACTIONS</td>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($moves->first()))
                    @foreach ($moves as $move )
                    <tr id="{{ $move->id }}" class="hover:bg-blue-400 hover:text-white hover:cursor-pointer">
                        <td>
                           {{ $move->origin == "ventes" ? $move->qty:0 }}
                        </td>
                        <td>
                            {{ $move->origin == "achat" ? $move->qty:0 }}</td>
                        <td>
                            {{ $move->origin == "pertes" ? $move->qty:0 }}</td>
                        <td>{{ $move->created_at }}</td>
                        <td>{{ $move->label }}</td>
                        <td>{{ $move->entree ? $move->qty :0 }}</td>
                        <td>{{ $move->sortie ? $move->qty :0 }}</td>
                        <td>{{ $move->stock }}</td>
                        <td><span class="text-red-500 delete"> supprimer</span></td>
                    </tr>     
                    @endforeach

                @else
                    <p> aucun resultat</p>
                @endif
                </tbody>
            </table>
    </center>
     
<script>
    $( function () {
        //evement sur les historiques
        $(".delete").on("click",function(){
            id = $(this).parent().parent().attr("id");
            var token = $("meta[name='csrf-token']").attr("content");
            Swal.fire({
      title: "Etes vous sures ? cette operation est irreversible",
      showDenyButton: true,
      showCancelButton: true,
      confirmButtonText: "Supprimer",
      denyButtonText: `Annuler`
    }).then((result) => {
      /* Read more about isConfirmed, isDenied below */
      if (result.isConfirmed) {
            $.ajax({
                url:"/commercial/DeleteMove/"+id,
                dataType:"json",
                data: {
                    "id": id,
                    "_token": token,
                },
                method:"DELETE",
                success:function(res){
                    toastr.warning(res.message)
                    $("#"+id).load(location.href+ " #"+id)
                },
                error:function(xhr, status, err){
                    console.log(xhr)
                    console.log(err)
                }
            }) } else if (result.isDenied) {
        Swal.fire("Changement non enregistre", "", "info");
      }
    })
        })
    })</script>
@endsection