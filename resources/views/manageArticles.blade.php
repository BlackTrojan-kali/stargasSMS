@extends("Layouts.appLayout")
@section('content')
<div class="p-8">
    <div class="w-full flex justify-end mb-4">
        <button class="bg-red-500 text-white font-bold p-4 rounded-md "><a href="{{route("chooseArticleType")}}">Ajouter  un Article</a></button>
    </div>
    <table class="w-full bg-orange-200 border-separate p-4 rounded-md text-center">
        <thead class="font-bold">
            <tr>
                <td>Nom</td>
                <td>Type</td>
                <td>Poids</td>
                <td>Etat</td>
                <td>Action</td>
            </tr>
        </thead>
        <tbody>
           @foreach ( $articles as $article )
               <tr>
                <td>{{$article->title}}</td>
                <td>{{$article->type}}</td>
                <td>{{$article->weight ==0 ? "-":$article->weight." kg"}}</td>
                <td>{{$article->state ? "plein":"vide"}}</td>
                <td>     <a  id ={{$article->id}} class="delete px-4 p-1 rounded-md bg-red-500 text-white cursor-pointer"><i class="fa-solid fa-trash"></i></a>
                </td>
               </tr>
           @endforeach
        </tbody>
    </table>
</div>
<script>
$(document).ready(function(){
        $(".delete").on("click",function(e){
            e.preventDefault()
            articleId = $(this).attr('id');
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
        type:"DELETE",
        url:"deleteArticle/"+articleId,
        dataType:"json",
        data:{
            "id":articleId,
            "_token":"{{csrf_token()}}"
        },
        success:function(res){
            Swal.fire("element supprime avec success", "", "success");
           $('table').load(" table")
        }
    })
  } else if (result.isDenied) {
    Swal.fire("Changement non enregistre", "", "info");
  }
});
          
        })
    })
</script>
@endsection