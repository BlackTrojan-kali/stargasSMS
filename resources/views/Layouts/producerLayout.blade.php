<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite('resources/css/app.css')
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css">
</head>
<body class="mx-8">
    
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
     <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
   
     @if(session('success'))
     <script type="module">
     $(document).ready(function(){
         toastr.success("{{session('success')}}")
     })
 </script>
     @elseif ($errors->has('message'))
     <script type="module">
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
            <a href="{{ route("dashboard-producer") }}"><i class="fa-solid fa-home"></i> ACCEUIL</a>
            <div class="font-bold cursor-pointer dropdown relative" >MOUVEMENTS <i class="fa-solid fa-angle-down"></i>
            <div class="drop-items">
                <div class="drop-2 elem">
                    Reception
                    <ul class="drop-items-2">
                        <li class="elem" id="activate-form-entry-gpl"><a href="">GPL Vrac Reception</a></li>
                        <li class="elem" id="activate-form-entry-accessory"><a>Depotage</a></li>
                        <li class="elem" id="activate-form-entry-vide"><a>Bouteilles vides Reception</a></li>
                    </ul>
                </div>
               
            </div>
            </div>
            <div class="font-bold cursor-pointer dropdown relative" >PRODUCTION <i class="fa-solid fa-angle-down"></i>
                <div class="drop-items ">
                    <div class="elem" id="activate-production-form">
                        Production
                    
                    </div>
                    <div class="elem " id="activate-transmit-form">
                        Transfert
    
    
                               </div>
                </div>
                </div>
            <a href="{{ route("showCiterne") }}">CITERNES</a>
            <a href="{{route("showRelevePro")}}">RECEPTION</a>
            
            <div class="dropdown cursor-pointer font-bold">    ETATS <i class="fa-solid fa-angle-down"></i>
            <div class="drop-items">
                <div class="drop-2 elem">
                    MOUVEMENTS ENTREES
                    <ul class="drop-items-2">
                        <li class="elem" id="activate-form-entry-gpl"><a href="{{ route("moveEntryPro",["state"=>1,"type"=>1,"weight"=>12.5]) }}">12.5 KG</a></li>
                        <li class="elem" id="activate-form-entry-accessory"><a href="{{ route("moveEntryPro",["state"=>1,"type"=>1,"weight"=>50]) }}">  50 KG</a></li>
                        <li class="elem" id="activate-form-entry-vide"><a href="{{ route("moveEntryPro",["state"=>1,"type"=>1,"weight"=>6]) }}">6 KG</a></li>
                        <li class="elem" id="activate-form-entry-vide"><a href="{{ route("moveEntryPro",["state"=>1,"type"=>1,"weight"=>0]) }}">ACCESSOIRES</a></li>
                    </ul>
                </div>
                
                <div class="drop-2 elem">
                    MOUVEMENTS Sortie
                    <ul class="drop-items-2">
                        <li class="elem" id="activate-form-entry-gpl"><a href="{{ route("moveEntryPro",["state"=>1,"type"=>0,"weight"=>12.5]) }}">12.5 KG (sortie)</a></li>
                        <li class="elem" id="activate-form-entry-accessory"><a href="{{ route("moveEntryPro",["state"=>1,"type"=>0,"weight"=>50]) }}">50 KG (sortie)</a></li>
                        <li class="elem" id="activate-form-entry-vide"><a href="{{ route("moveEntryPro",["state"=>1,"type"=>0,"weight"=>6]) }}">6 KG (sortie)</a></li>
                        <li class="elem" id="activate-form-entry-vide"><a href="{{ route("moveEntryPro",["state"=>1,"type"=>0,"weight"=>0]) }}">ACCESSOIRES (sortie)</a></li>
                    </ul>
                </div>
                
                <div class="drop-2 elem">
                    MOUVEMENTS Global
                    <ul class="drop-items-2">
                        <li class="elem" id="activate-form-entry-gpl"><a href="">12.5 KG (global)</a></li>
                        <li class="elem" id="activate-form-entry-accessory"><a>50 KG (global)</a></li>
                        <li class="elem" id="activate-form-entry-vide"><a>6 KG (global)</a></li>
                        <li class="elem" id="activate-form-entry-vide"><a>ACCESSOIRES (global)</a></li>
                    </ul>
                </div>
            </div>
            </div>
        </nav>
        
       </header>  
       @yield('content')
       <br><br><br><br><br><br><br><br>
       <!--FORMULAIRE DE PRODUCTION-->
       <div id="produce-gpl" class="modals">
        <center>
           
            <div class="modal-active">
                <div class="modal-head">
                    <h1>MISE EN PRODUCTION</h1>
                    <span class="close-modal">X</span>
                </div>
                <span class="success text-green-500 "></span>
                <span class="errors text-red-500 "></span>
                <form id="produce-gpl-form"  >
                    @csrf
                    <div class="modal-champs">
                        <label for="">Type de citerne:</label>
                        <select name="citerne" id="">
                         @foreach ($all  as $vra )
                             <option value="{{$vra->name}}">{{$vra->name}} - ({{$vra->type}})</option>
                         @endforeach
                        </select>
                        @if ($errors->has('citerne'))
                            <span class="text-red-500">{{$errors->first("citerne")}}</span>
                        @endif
                    </div> <div class="modal-champs">
                        <label for="">Type de Bouteilles:</label>
                        <select name="type" id="">
                             <option value="12.5">12.5 KG</option>
                             <option value="50">50 KG</option>
                             <option value="6">6 KG</option>
                        </select>
                        @if ($errors->has('type'))
                            <span class="text-red-500">{{$errors->first("type")}}</span>
                        @endif
                    </div>
                    <div class="modal-champs">
                        <label for="">Quantite</label> 
                        <input type="number" name="qty">

                        @if ($errors->has('qty'))
                            <span class="text-red-500">{{$errors->first("qty")}}</span>
                        @endif
                        
                    </div>
                    <div class="modal-champs">
                        <label for="">Numero Bordereau</label>
                        <input type="text" name="bord">
                        @if ($errors->has('bord'))
                            <span class="text-red-500">{{$errors->first("bord")}}</span>
                        @endif
                    </div>
                    <div class="modal-validation">
                    <button type="reset">annuler</button>
                    <button type="submit" id="submitForm">creer</button>
                </div>
                </form>
            </div>
        </center>
    </div>

    
    <div id="transmit-form" class="modals">
        <center>
           
            <div class="modal-active">
                <div class="modal-head">
                    <h1>TRANSFERT DE BOUTEILLES</h1>
                    <span class="close-modal">X   </span>
                </div>
                <span class="success text-green-500 "></span>
                <span class="errors text-red-500 "></span>
                <form id="transmiting-form"  >
                    @csrf
                    <div class="modal-champs">
                        <label for="">Type de citerne:</label>
                      <div>
                        <input type="radio" name="state" value="0">
                        Vide
                        <input type="radio" name="state" value="1">
                        Pleine
                    </div>
                        @if ($errors->has('citerne'))
                            <span class="text-red-500">{{$errors->first("citerne")}}</span>
                        @endif
                    </div> <div class="modal-champs">
                        <label for="">Type de Bouteilles:</label>
                        <select name="type" id="">
                             <option value="12.5">12.5KG</option>
                             <option value="50">50KG</option>
                             <option value="6">6KG</option>
                        </select>
                        @if ($errors->has('type'))
                            <span class="text-red-500">{{$errors->first("type")}}</span>
                        @endif
                    </div>
                    <div class="modal-champs">
                        <label for="">Quantite</label>
                        <input type="number" name="qty">

                        @if ($errors->has('qty'))
                            <span class="text-red-500">{{$errors->first("qty")}}</span>
                        @endif
                        
                    </div>
                    <div class="modal-champs">
                        <label for="">Destination</label>
                        <input type="text" name="destination">
                        @if ($errors->has('destination'))
                            <span class="text-red-500">{{$errors->first("destination")}}</span>
                        @endif
                    </div>
                    <div class="modal-champs">
                        <label for="">Numero Bordereau</label>
                        <input type="text" name="bord">
                        @if ($errors->has('bord'))
                            <span class="text-red-500">{{$errors->first("bord")}}</span>
                        @endif
                    </div>
                    <div class="modal-validation">
                    <button type="reset">annuler</button>
                    <button type="submit" id="submitForm">creer</button>
                </div>
                </form>
            </div>
        </center>
    </div>
       
       <!--FIN DES FORMULAIRES DE PRODUCTION-->


<!--ENTREE FORMULAIRES-->
    <div id="entry-gpl" class="modals">
        <center>
           
            <div class="modal-active">
                <div class="modal-head">
                    <h1>Entree de GPL Vrac</h1>
                    <span class="close-modal">X   </span>
                </div>
                <span class="success text-green-500 "></span>
                <span class="errors text-red-500 "></span>
                <form id="entry-gpl-form"  >
                    @csrf
                    <div class="modal-champs">
                        <label for="">Type de citerne:</label>
                        <select name="citerne" id="">
                         @foreach ($vrac as $vra )
                             <option value="{{$vra->name}}">{{$vra->name}} - ({{$vra->type}})</option>
                         @endforeach
                        </select>
                        @if ($errors->has('citerne'))
                            <span class="text-red-500">{{$errors->first("citerne")}}</span>
                        @endif
                    </div>
                    <div class="modal-champs">
                        <label for="">Quantite</label> 
                        <input type="number" name="qty">

                        @if ($errors->has('qty'))
                            <span class="text-red-500">{{$errors->first("qty")}}</span>
                        @endif
                        
                    </div>
                    <div class="modal-champs">
                        <label for="">Numero Bordereau Livraison:</label>
                        <input type="text" name="livraison">
                        @if ($errors->has('livraison'))
                            <span class="text-red-500">{{$errors->first("label")}}</span>
                        @endif
                    </div>
                    <div class="modal-champs">
                        <label for="">Matricule du vehicule :</label>
                        <input type="text" name="matricule">
                        @if ($errors->has('matricule'))
                            <span class="text-red-500">{{$errors->first("label")}}</span>
                        @endif
                    </div>
                    <div class="modal-validation">
                    <button type="reset">annuler</button>
                    <button type="submit" id="submitForm">creer</button>
                </div>
                </form>
            </div>
        </center>
    </div>

    
    <div id="entry-vides" class="modals">
        <center>
        
         <div class="modal-active">
             <div class="modal-head">
                 <h1>Entree de Bouteilles Vides</h1>
                 <span class="close-modal">X   </span>
             </div>
             <span class="success text-green-500"></span>
             <span class="errors text-red-500"></span>
             <form id="entry-vides-form" >
                @csrf
                 <div class="modal-champs">
                     <label for="">Type d'operation:</label>
                     <select name="origin" id="">
                         <option value="achat">achat</option>
                         <option value="client">client</option>
                         <option value="region">region</option>
                         <option value="production">production</option>
                     </select>
                     @if ($errors->has('origin'))
                         <span class="text-red-500">{{$errors->first("origin")}}</span>
                     @endif
                 </div>
                 <div class="modal-champs">
                     <label for="">Type de bouteilles:</label> <div>
                     <input type="radio" value="6" name="weight"> 6kg
                     <input type="radio" value="12.5" name="weight">12.5kg
                     <input type="radio" value="50" name="weight">50 kg
                     </div>
                 </div>
                 <div class="modal-champs">
                     <label for="">Quantite :</label>
                     <input type="number" name="qty">
                     @if ($errors->has('qty'))
                         <span class="text-red-500">{{$errors->first("qty")}}</span>
                     @endif
                 </div>
                 <div class="modal-champs">
                     <label for="">Libelle</label>
                     <input type="text" name="label">
                     @if ($errors->has('label'))
                         <span class="text-red-500">{{$errors->first("label")}}</span>
                     @endif
                 </div>
                 <div class="modal-champs">
                     <label for="">Bordereau</label>
                     <input type="text" name="bord">
                     @if ($errors->has('bord'))
                         <span class="text-red-500">{{$errors->first("bord")}}</span>
                     @endif
                 </div>
                 <div class="modal-validation">
                 <button type="reset">annuler</button>
                 <button type="submit" id="submitForm">creer</button>
             </div>
             </form>
         </div></center>
     </div>
    

     <div id="entry-accessory" class="modals">
        <center>
        
         <div class="modal-active">
             <div class="modal-head">
                 <h1>Depotage GPL Vrac</h1>
                 <span class="close-modal">X   </span>
             </div>
             <span class="success text-green-500"></span>
             <span class="errors text-red-500"></span>
             <form id="entry-accessory-form" method="POST" action="{{ route("Depotage") }}" >
                @csrf
                 <div class="modal-champs">
                     <label for="">Citerne Mobile:</label>
                     <select name="mobile" id="">
                        @foreach ($vrac as $vra )
                            <option value="{{ $vra->id }} }}">{{ $vra->name }}-({{ $vra->type }})</option>
                        @endforeach
                     </select>
                     @if ($errors->has('mobile'))
                         <span class="text-red-500">{{$errors->first("mobile")}}</span>
                     @endif
                 </div>
                 <div class="modal-champs">
                     <label for="">Citerne Fixe:</label>
                     <select name="fixe" id="">
                        @foreach ($fixe as $fix )
                            <option value="{{ $fix->id }}">{{ $fix->name }}-({{ $fix->type }})</option>                            
                        @endforeach
                     </select>
                     @if ($errors->has('fixe'))
                         <span class="text-red-500">{{$errors->first("fixe")}}</span>
                     @endif
                 </div>
                 <div class="modal-champs">
                     <label for="">Quantite :</label>
                     <input type="number" name="qty">
                 </div>
                 @if ($errors->has('qty'))
                     <span class="text-red-500">{{$errors->first("qty")}}</span>
                 @endif
                 <div class="modal-champs">
                     <label for="">Matricule du Vehicule</label>
                     <input type="text" name="matricule">
                     @if ($errors->has('matricule'))
                         <span class="text-red-500">{{$errors->first("matricule")}}</span>
                     @endif
                 </div>
                 <div class="modal-validation">
                 <button type="reset">annuler</button>
                 <button type="submit" >creer</button>
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
    <script type="module">
        
    $('table').DataTable();
        //form deployment
        $(function(){
            //ACTION  PRODUCE
            
            $("#activate-production-form").on("click",function(e){
                e.preventDefault()
                if($("#produce-gpl").hasClass("modals")){
                    $("#produce-gpl").addClass("modals-active");
                    $("#produce-gpl").removeClass("modals");
                }

            $(".close-modal").on("click",function(e){
                e.preventDefault()
                if($("#produce-gpl").hasClass("modals-active")){
                    $("#produce-gpl").addClass("modals");
                    $("#produce-gpl").removeClass("modals-active");
                }
            })
            })     
            $("#activate-transmit-form").on("click",function(e){
                e.preventDefault()
                if($("#transmit-form").hasClass("modals")){
                    $("#transmit-form").addClass("modals-active");
                    $("#transmit-form").removeClass("modals");
                }

            $(".close-modal").on("click",function(e){
                e.preventDefault()
                if($("#transmit-form").hasClass("modals-active")){
                    $("#transmit-form").addClass("modals");
                    $("#transmit-form").removeClass("modals-active");
                }
            })
            })

            //ACTION ENTRY ON MODAL GPL
            $("#activate-form-entry-gpl").on("click",function(e){
                e.preventDefault()
                if($("#entry-gpl").hasClass("modals")){
                    $("#entry-gpl").addClass("modals-active");
                    $("#entry-gpl").removeClass("modals");
                }

            $(".close-modal").on("click",function(e){
                e.preventDefault()
                if($("#entry-gpl").hasClass("modals-active")){
                    $("#entry-gpl").addClass("modals");
                    $("#entry-gpl").removeClass("modals-active");
                }
            })
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
      

        //VALIDATION FORMULAIRES PRODUCTION

        $("#produce-gpl-form").submit(function(e){
            e.preventDefault()
            $.ajax({
                url:"{{route("produceGas")}}",
                method:"POST",
                data:$(this).serialize(),
                success:function(response){
                    if(response.error){
                        $(".errors").text(response.error);
                        
                        setTimeout(() => {
                            $(".errors").text("");
                            
                        }, 1500);
                        $(".success").text("");
                    }else{
                        $(".success").text(response.success);
                        
                        setTimeout(() => {
                            $(".success").text("");
                            
                        }, 1500);
                        $(".errors").text("");
                        $("#entry-vides-form")[0].reset();
                        $("table").load(location.href + " table")
                    }
                }

            })
        })
        
        $("#transmiting-form").submit(function(e){
            e.preventDefault()
            $.ajax({
                url:"{{route("transmitGas")}}",
                method:"POST",
                data:$(this).serialize(),
                success:function(response){
                    if(response.error){
                        $(".errors").text(response.error);
                        
                        setTimeout(() => {
                            $(".errors").text("");
                            
                        }, 1500);
                        $(".success").text("");
                    }else{
                        $(".success").text(response.success);
                        
                        setTimeout(() => {
                            $(".success").text("");
                            
                        }, 1500);
                        $(".errors").text("");
                        $("#transmiting-form")[0].reset();
                        $("table").load(location.href + " table")
                    }
                }

            })
        })
            
        //VALIDATION FORMULAIRE ENTREES BOUTEILLES VIDES
        $("#entry-vides-form").submit(function(e){
            e.preventDefault()
            $.ajax({
                url:"{{route("saveBottleMovePro",["action"=>"entry","state"=>0])}}",
                method:"POST",
                data:$(this).serialize(),
                success:function(response){
                    if(response.error){
                        $(".errors").text(response.error);
                        
                        setTimeout(() => {
                            $(".errors").text("");
                            
                        }, 1500);
                        $(".success").text("");
                    }else{
                        $(".success").text(response.success);
                        
                        setTimeout(() => {
                            $(".success").text("");
                            
                        }, 1500);
                        $(".errors").text("");
                        $("#entry-vides-form")[0].reset();
                        $("table").load(location.href + " table")
                    }
                }

            })
        })
          //FORMULAIRE ENTREES GPL
          $("#entry-gpl-form").submit(function(e){
                e.preventDefault();
                $.ajax({
                    url:"{{route("MoveGplPro")}}",
                    method:"POST",
                    data:$(this).serialize(),
                    success:function(response){
                        $(".success").text(response.success);
                        setTimeout(() => {
                            $(".success").text("");
                            
                        }, 1500);
                        $("#entry-gpl-form")[0].reset();
                        $(".text-red-500").load(location.href + " .text-red-500")
                        $("table").load(location.href + " table")
                    }
                })

            })


        //VALIDATION FORMULAIRE Depotage gpl
        $("#entry-accessory-form").submit(function(e){
            e.preventDefault();
            $.ajax({
                url:"{{route("Depotage")}}",
                method:"POST",
                data:$(this).serialize(),
                success:function(response){
                    if(response.error){
                        $(".errors").text(response.error);
                        
                        setTimeout(() => {
                            $(".errors").text("");
                            
                        }, 1500);
                        $(".success").text("");
                    }else{
                        $(".errors").text("");
                        $(".success").text(response.success);
                        setTimeout(() => {
                            $(".success").text("");
                            
                        }, 1500);
                        $("#entry-accessory-form")[0].reset()
                        $("table").load(location.href + " table")
                    }
                }
            })
        })

        })
       
            </script>
   
</body>
</html>