<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Stargas SMS</title>
    <link rel="icon" href="/images/logo.png">
    <link href="toastr.css" rel="stylesheet"/>
    @vite('resources/css/app.css')
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css">
</head>
<body class="mx-20">

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
     <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
    @if(session('success'))
    <script>
    $(document).ready(function(){
        toastr.success("{{session('success')}}")
    })
</script>
    @elseif ($errors->has('message'))
    <script>
    $(document).ready(function(){
        toastr.error("{{$errors->first('message')}}")
    })
</script>
    @endif
       <header class="p-4">
        <div class="w-full flex justify-between">
            <img src="/images/logo.png" class="w-24 h-20" alt="">
            <div class="text-center mt-2">
                <p>
                <i class="fa-solid fa-user"></i>
                {{Auth::user()->email}}
            </p>
            <h2 class="text-large">Stargas Supply Chain Management System</h2>
            <h2 class="text-large">
                Region: {{Auth::user()->region}} 
            </h2>
            <h2 class="text-large">
                Service: {{Auth::user()->role}} 
            </h2>
            </div>
            <div class=" mt-10 cursor-pointer text-center">
                <form action="{{route("logout")}}" method="post">
                    @csrf
               <button type="submit">
                    <i class="fa    -solid fa-right-from-bracket text-red-800 text-4xl"></i>
                <p> deconnexion</p>
            </button>
            </form>
            </div>
        </div>
      
        <nav class="mt-2 p-2 w-full text-white flex gap-2 bg-blue-400 rounded-md">
            <a href="{{route("dashboard-manager")}}"><i class="fa-solid fa-home"></i> ACCEUIL</a>
            <div class="font-bold cursor-pointer dropdown relative" >MOUVEMENTS <i class="fa-solid fa-angle-down"></i>
            <div class="drop-items">
                <div class="drop-2 elem">
                    Entree
                    <ul class="drop-items-2">
                        <li class="elem"><a href="">GPL Vrac</a></li>
                        <li class="elem" id="activate-form-entry-vide"><a>Bouteilles Vides</a></li>
                        <li class="elem" id="activate-form-entry-pleine"><a>Bouteilles Pleines</a></li>
                        <li class="elem" id="activate-form-entry-accessory"><a>Accessoires</a></li>
                    </ul>
                </div>
                <div class="drop-2 elem">
                    Sortie


                    <ul class="drop-items-2">
                        <li class="elem"><a href="">GPL Vrac</a></li>
                        <li class="elem" id="activate-form-outcome-vide"><a>Bouteilles Vides</a></li>
                        <li class="elem" id="activate-form-outcome-pleine"><a>Bouteilles Pleines</a></li>
                        <li class="elem" id="activate-form-outcome-accessory"><a>Accessoires</a></li>
                    </ul> 
                               </div>
            </div>
            </div>
            
            <a href="{{route("manager-history")}}" >HISTORIQUE</a>
            <a href="" >ETATS</a>
        </nav>
        
       </header>
    
    
    @yield('content')
    <br><br><br><br><br><br><br><br>



<!--ENTREE FORMULAIRES-->


    <div id="entry-pleine" class="modals">
       <center>
       
        <div class="modal-active">
            <div class="modal-head">
                <h1>Entree de Bouteilles Pleines</h1>
                <span class="close-modal">X   </span>
            </div>
            <form action="{{route("saveBottleMove",["action"=>"entry","state"=>1])}}" method="POST">
                @csrf
                <div class="modal-champs">
                    <label for="">Type d'operation:</label>
                    <select name="origin" id="">
                        <option value="achat">achat</option>
                        <option value="client">client</option>
                        <option value="region">region</option>
                        <option value="production">production</option>
                    </select>
                </div>
                <div class="modal-champs">
                    <label for="">Type d'operations:</label> <div>
                    <input type="radio" value="6" name="weight"> 6kg
                    <input type="radio" value="12.5" name="weight">12.5kg
                    <input type="radio" value="50" name="weight">50 kg
                    </div>
                </div>
                <div class="modal-champs">
                    <label for="">Quantite :</label>
                    <input type="number" name="qty">
                </div>
                <div class="modal-champs">
                    <label for="">Libelle</label>
                    <input type="text" name="label">
                </div>
                <div class="modal-validation">
                <button type="reset">annuler</button>
                <button type="submit">creer</button>
            </div>
            </form>
        </div></center>
    </div>


    <div id="entry-vides" class="modals">
        <center>
        
         <div class="modal-active">
             <div class="modal-head">
                 <h1>Entree de Bouteilles Vides</h1>
                 <span class="close-modal">X   </span>
             </div>
             <form action="{{route("saveBottleMove",["action"=>"entry","state"=>0])}}" method="POST">
                @csrf
                 <div class="modal-champs">
                     <label for="">Type d'operation:</label>
                     <select name="origin" id="">
                         <option value="achat">achat</option>
                         <option value="client">client</option>
                         <option value="region">region</option>
                         <option value="production">production</option>
                     </select>
                 </div>
                 <div class="modal-champs">
                     <label for="">Type d'operations:</label> <div>
                     <input type="radio" value="6" name="weight"> 6kg
                     <input type="radio" value="12.5" name="weight">12.5kg
                     <input type="radio" value="50" name="weight">50 kg
                     </div>
                 </div>
                 <div class="modal-champs">
                     <label for="">Quantite :</label>
                     <input type="number" name="qty">
                 </div>
                 <div class="modal-champs">
                     <label for="">Libelle</label>
                     <input type="text" name="label">
                 </div>
                 <div class="modal-validation">
                 <button type="reset">annuler</button>
                 <button type="submit">creer</button>
             </div>
             </form>
         </div></center>
     </div>
    

     <div id="entry-accessory" class="modals">
        <center>
        
         <div class="modal-active">
             <div class="modal-head">
                 <h1>Entree de Accessoire</h1>
                 <span class="close-modal">X   </span>
             </div>
             <form action="{{route("saveAccessoryMove",["action"=>"entry"])}}" method="POST">
                @csrf
                 <div class="modal-champs">
                     <label for="">Type d'accessiore:</label>
                     <select name="title" id="">
                        @foreach ($accessories as $accessory )
                            
                         <option value="{{$accessory->title}}">{{$accessory->title}}</option>
                        @endforeach
                     </select>
                 </div>
                 <div class="modal-champs">
                     <label for="">Quantite :</label>
                     <input type="number" name="qty">
                 </div>
                 <div class="modal-champs">
                     <label for="">Libelle</label>
                     <input type="text" name="label">
                 </div>
                 <div class="modal-validation">
                 <button type="reset">annuler</button>
                 <button type="submit">creer</button>
             </div>
             </form>
         </div></center>
     </div>

<!--SORTIES FORMULAIRES-->

    <div id="outcome-pleine" class="modals">
        <center>
        
         <div class="modal-active">
             <div class="modal-head">
                 <h1>Sortie de Bouteilles Pleines</h1>
                 <span class="close-modal">X   </span>
             </div>
             <form action="{{route("saveBottleMove",["action"=>"outcome","state"=>1])}}" method="POST">
               @csrf
                <div class="modal-champs">
                     <label for="">Type d'operation:</label>
                     <select name="origin" id="">
                         <option value="achat">achat</option>
                         <option value="client">client</option>
                         <option value="region">region</option>
                         <option value="production">production</option>
                     </select>
                 </div>
                 <div class="modal-champs">
                     <label for="">Type d'operations:</label> <div>
                     <input type="radio" value="6" name="weight"> 6kg
                     <input type="radio" value="12.5" name="weight">12.5kg
                     <input type="radio" value="50" name="weight">50 kg
                     </div>
                 </div>
                 <div class="modal-champs">
                     <label for="">Quantite :</label>
                     <input type="number" name="qty">
                 </div>
                 <div class="modal-champs">
                     <label for="">Libelle</label>
                     <input type="text" name="label">
                 </div>
                 <div class="modal-validation">
                 <button type="reset">annuler</button>
                 <button type="submit">creer</button>
             </div>
             </form>
         </div></center>
     </div>
 

     <div id="outcome-vides" class="modals">
        <center>
        
         <div class="modal-active">
             <div class="modal-head">
                 <h1>Sortie de Bouteilles Vides</h1>
                 <span class="close-modal">X</span>
             </div>
             <form action="{{route("saveBottleMove",["action"=>"outcome","state"=>0])}}" method="POST">
             @csrf
                <div class="modal-champs">
                     <label for="">Type d'operation:</label>
                     <select name="origin" id="">
                         <option value="achat">achat</option>
                         <option value="client">client</option>
                         <option value="region">region</option>
                         <option value="production">production</option>
                     </select>
                 </div>
                 <div class="modal-champs">
                     <label for="">Type d'operations:</label> <div>
                     <input type="radio" value="6" name="weight"> 6kg
                     <input type="radio" value="12.5" name="weight">12.5kg
                     <input type="radio" value="50" name="weight">50 kg
                     </div>
                 </div>
                 <div class="modal-champs">
                     <label for="">Quantite :</label>
                     <input type="number" name="qty">
                 </div>
                 <div class="modal-champs">
                     <label for="">Libelle</label>
                     <input type="text" name="label">
                 </div>
                 <div class="modal-validation">
                 <button type="reset">annuler</button>
                 <button type="submit">creer</button>
             </div>
             </form>
         </div></center>
     </div>
    

     <div id="outcome-accessory" class="modals">
        <center>
        
         <div class="modal-active">
             <div class="modal-head">
                 <h1>Sortie de Accessoire</h1>
                 <span class="close-modal">X   </span>
             </div>
             <form action="{{route("saveAccessoryMove",["action"=>"outcome"])}}" method="POST">
                @csrf
                <div class="modal-champs">
                     <label for="">Type d'accessiore:</label>
                     <select name="title" id="">
                        @foreach ($accessories as $accessory )
                            
                         <option value="{{$accessory->title}}">{{$accessory->title}}</option>
                        @endforeach
                     </select>
                 </div>
                 <div class="modal-champs">
                     <label for="">Quantite :</label>
                     <input type="number" name="qty">
                 </div>
                 <div class="modal-champs">
                     <label for="">Libelle</label>
                     <input type="text" name="label">
                 </div>
                 <div class="modal-validation">
                 <button type="reset">annuler</button>
                 <button type="submit">creer</button>
             </div>
             </form>
         </div></center>
     </div>

    
    
    <footer class="mt-10 w-full bg-blue-950 flex justify-between p-4 text-white rounded-md">
        <div>
            <a href=""  >Contacter</a>
            <a href="">Aide</a>
            <a href="">Mention Legal</a>
        </div>
        <p>&copy; 2024</p>
    </footer>


    <script>
        //form deployment
        $(document).ready(function(){

            //ACTION ENTRY ON MODAL BOUTEILLES-VIDES
            $("#activate-form-entry-vide").on("click",function(e){
                e.preventDefault()
            if($("#entry-vides").hasClass("modals")){
             $("#entry-vides").addClass("modals-active")

             $("#entry-vides").removeClass("modals")
            }
            })

            $(".close-modal").on("click",function(e){
                e.preventDefault()
                if($("#entry-vides").hasClass("modals-active")){
                    $("#entry-vides").addClass("modals")
                    $("#entry-vides").removeClass("modals-active")
                }
            })
      
            //ACTION ENTRY ON MODAL BOUTEILLES-PLEINES
            $("#activate-form-entry-pleine").on("click",function(e){
                e.preventDefault()
            if($("#entry-pleine").hasClass("modals")){
             $("#entry-pleine").addClass("modals-active")

             $("#entry-pleine").removeClass("modals")
            }
            })

            $(".close-modal").on("click",function(e){
                e.preventDefault()
                if($("#entry-pleine").hasClass("modals-active")){
                    $("#entry-pleine").addClass("modals")
                    $("#entry-pleine").removeClass("modals-active")
                }
            })

            //ACTION ENTRY ON MODAL BOUTEILLES-PLEINES
            $("#activate-form-entry-accessory").on("click",function(e){
                e.preventDefault()
            if($("#entry-accessory").hasClass("modals")){
             $("#entry-accessory").addClass("modals-active")

             $("#entry-accessory").removeClass("modals")
            }
            })

            $(".close-modal").on("click",function(e){
                e.preventDefault()
                if($("#entry-accessory").hasClass("modals-active")){
                    $("#entry-accessory").addClass("modals")
                    $("#entry-accessory").removeClass("modals-active")
                }
            })


            //ACTION OUTCOME ON MODAL BOUTEILLES-VIDES
            $("#activate-form-outcome-vide").on("click",function(e){
                e.preventDefault()
            if($("#outcome-vides").hasClass("modals")){
             $("#outcome-vides").addClass("modals-active")

             $("#outcome-vides").removeClass("modals")
            }
            })

            $(".close-modal").on("click",function(e){
                e.preventDefault()
                if($("#outcome-vides").hasClass("modals-active")){
                    $("#outcome-vides").addClass("modals")
                    $("#outcome-vides").removeClass("modals-active")
                }
            })
      
            //ACTION OUTCOME ON MODAL BOUTEILLES-PLEINES
            $("#activate-form-outcome-pleine").on("click",function(e){
                e.preventDefault()
            if($("#outcome-pleine").hasClass("modals")){
             $("#outcome-pleine").addClass("modals-active")

             $("#outcome-pleine").removeClass("modals")
            }
            })

            $(".close-modal").on("click",function(e){
                e.preventDefault()
                if($("#outcome-pleine").hasClass("modals-active")){
                    $("#outcome-pleine").addClass("modals")
                    $("#outcome-pleine").removeClass("modals-active")
                }
            })

            //ACTION OUTCOME ON MODAL BOUTEILLES-PLEINES
            $("#activate-form-outcome-accessory").on("click",function(e){
                e.preventDefault()
            if($("#outcome-accessory").hasClass("modals")){
             $("#outcome-accessory").addClass("modals-active")

             $("#outcome-accessory").removeClass("modals")
            }
            })

            $(".close-modal").on("click",function(e){
                e.preventDefault()
                if($("#outcome-accessory").hasClass("modals-active")){
                    $("#outcome-accessory").addClass("modals")
                    $("#outcome-accessory").removeClass("modals-active")
                }
            })
        })
    </script>
</body>
</html>