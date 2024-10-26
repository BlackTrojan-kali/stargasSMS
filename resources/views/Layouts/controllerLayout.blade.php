<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Stargas SCMS</title>
    <link rel="icon" href="/images/logo.png">
    <link href="toastr.css" rel="stylesheet"/>
    @vite('resources/css/app.css')
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body class="mx-20">

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
     
    @if(session('success'))
    <script type="module"    
    >
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
            <img src="/images/logo.png" class="w-32 h-auto" alt="">
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
        <nav class="mt-2 p-2 first-letter: w-full flex  gap-5 text-white bg-teal-700 rounded-md">
            <div>  <a ><i class="fa-solid fa-home"></i> ACCUEIL</a></div>
           
            <div class="font-bold cursor-pointer dropdown relative" >GENERER UN DOCUMENT<i class="fa-solid fa-angle-down"></i>
                <ul class="drop-items">
                
               <li  class="elem"  id="activate-pdf-form">Etats des mouvements</li>
                <li  class="elem" id="activate-receives-pdf-form">historique des reception</li>
                <li  class="elem" id="activate-sales-state-pdf-form">Etats des Ventes</li>
                <li  class="elem" id="activate-versement-pdf-form">Historique des Versements</li>

                </ul>
             </div>
          </nav>
        @endif
       </header>
    


       
<!--FORMULAIRE DE GENERATION DE PDF RECEPTIONS-->
<div id="recieves-pdf-form" class="modals">
    <center>
    
        <div class="modal-active">
            <div class="modal-head">
                <h1>Generer un PDF RECEPTIONS</h1>
                <span class="close-modal">X   </span>
            </div>
            <span class="success text-green-500"></span>
            <span class="errors text-red-500"></span>
            <form  method="POST" action="{{ route("receives_boss_pdf") }}" >
               @csrf
                <div class="modal-champs">
                    <label for="">Du:</label>
                    <input type="date" name="depart"  required>
                    @if ($errors->has('depart'))
                        <span class="text-red-500">{{$errors->first("depart")}}</span>
                    @endif
                </div>
                <div class="modal-champs">
                    <label for="">Au:</label>
                    <input type="date" name="fin"  required>
                    @if ($errors->has('fin'))
                        <span class="text-red-500">{{$errors->first("fin")}}</span>
                    @endif
                </div>
                <div class="modal-champs">
                    <label for="">Region:</label>
                    <select  name="region"  required>
                        <option value="centre">centre</option>
                        <option value="est">est</option>
                        <option value="nord">nord</option>
                        <option value="sud">sud</option>
                        <option value="ouest">ouest</option>
                        <option value="littoral">littoral</option>
                    </select>
                    @if ($errors->has('region'))
                        <span class="text-red-500">{{$errors->first("region")}}</span>
                    @endif
                </div>
                <div class="modal-champs">
                    <label for="">Region:</label>
                    <select  name="service"  required>
                        <option value="magasin">Magasin</option>
                        <option value="production">Production</option>
                        <option value="commercial">Commercial</option>
                    </select>
                    @if ($errors->has('service'))
                        <span class="text-red-500">{{$errors->first("service")}}</span>
                    @endif
                </div>
                <div class="modal-champs">
                    <label for="">Citerne</label>
                    <select name="citerne" id="">
                       @foreach ( $mobile as $fix )
                           
                           <option value="{{ $fix->id }}">{{ $fix->name}}</option>

                    @endforeach            
                    </select>
                    @if ($errors->has('citerne'))
                        <span class="text-red-500">{{$errors->first("citerne")}}</span>
                    @endif
                </div>
               
              
                
                <div class="modal-validation">
                <button type="reset">annuler</button>
                <button type="submit"  id="submitForm" >creer</button>
            </div>
            </form>
        </div>
    </center>
</div>
<!--FORMULAIRE DE GENERATION DE PDF-->
<div id="pdf-form" class="modals">
    <center>
    
        <div class="modal-active">
            <div class="modal-head">
                <h1>Generer un PDF</h1>
                <span class="close-modal">X   </span>
            </div>
            <span class="success text-green-500"></span>
            <span class="errors text-red-500"></span>
            <form  method="POST" action="{{ route("pdf") }}" >
               @csrf
                <div class="modal-champs">
                    <label for="">Du:</label>
                    <input type="date" name="depart"  required>
                    @if ($errors->has('depart'))
                        <span class="text-red-500">{{$errors->first("depart")}}</span>
                    @endif
                </div>
                <div class="modal-champs">
                    <label for="">Au:</label>
                    <input type="date" name="fin"  required>
                    @if ($errors->has('fin'))
                        <span class="text-red-500">{{$errors->first("fin")}}</span>
                    @endif
                </div>
                <div class="modal-champs">
                    <label for="">etat :</label><br>
                    
                    <input type="radio" value="1" name="state"> pleine
                    <input type="radio" value="0" name="state">vide
                    <input type="radio" value="777" name="state">accessoire
                </div>
                @if ($errors->has('state'))
                    <span class="text-red-500">{{$errors->first("state")}}</span>
                @endif
                <div class="modal-champs">
                    <label for="">Type de Movement</label>
                    <select name="move" id="">
                       
                           <option value="1">entree</option>
                           <option value="0">sortie</option>
                           <option value="777">Global</option>
            
                    </select>
                    @if ($errors->has('move'))
                        <span class="text-red-500">{{$errors->first("move")}}</span>
                    @endif
                </div>
                
                <div class="modal-champs">
                    <label for="">Service</label>
                    <select name="service" id="">
                       
                           <option value="production">production</option>
                           <option value="magasin">magasin</option>
                           <option value="commercial">commmercial</option>
            
                    </select>
                    @if ($errors->has('service'))
                        <span class="text-red-500">{{$errors->first("service")}}</span>
                    @endif
                </div>
                <div class="modal-champs">
                    <label for="">Region</label>
                    <select name="region" id="">
                       
                           <option value="centre">Centre</option>
                           <option value="littoral">Littoral</option>
                           <option value="sud">Sud</option>
                           <option value="est">Est</option>
                           <option value="Adamaoua">Adamaoua</option>
            
                    </select>
                    @if ($errors->has('region'))
                        <span class="text-red-500">{{$errors->first("region")}}</span>
                    @endif
                </div>
                <div class="modal-champs">
                    <label for="">Type de bouteille</label>
                    <select name="type" id="">
                       
                           <option value="50">50KG</option>
                           <option value="12.5">12.5KG</option>
                           <option value="6">6KG</option>
                           <option value="777">accessiore</option>
            
                    </select>
                    @if ($errors->has('type'))
                        <span class="text-red-500">{{$errors->first("type")}}</span>
                    @endif
                </div>
                <div class="modal-validation">
                <button type="reset">annuler</button>
                <button type="submit"  id="submitForm" >creer</button>
            </div>
            </form>
        </div>
    </center>
</div>
    
<!--FORMULAIRE GENERATION ETAT DES VERSEMENTS-->
      
<div id="versement-pdf-form" class="modals">
    <center>
    
        <div class="modal-active">
            <div class="modal-head">
                <h1>Generer un PDF historique des versements</h1>
                <b class="close-modal">X   </b>
            </div>
            <b class="success text-green-500"></b>
            <b class="errors text-red-500"></b>
            <form  method="POST" action="{{ route("boss_versementPdf") }}" >
               @csrf
                <div class="modal-champs">
                    <label for="">Du:</label>
                    <input type="date" name="depart" >
                    @if ($errors->has('depart'))
                        <b class="text-red-500">{{$errors->first("depart")}}</b>
                    @endif
                </div>
                <div class="modal-champs">
                    <label for="">Au:</label>
                    <input type="date" name="fin">
                    @if ($errors->has('fin'))
                        <b class="text-red-500">{{$errors->first("fin")}}</b>
                    @endif
                </div>
                <div class="modal-champs">
                    <label for="">BANQUE :</label><br>
                    
                    <select name="bank" id="">
                        <option value="AFB">AFB</option>
                        <option value="CCA">CCA</option>
                        <option value="all">Tous</option>
                    </select>
                </div>
                @if ($errors->has('bank'))
                    <b class="text-red-500">{{$errors->first("bank")}}</b>
                @endif
                <div class="modal-champs">
                    <label for="">Region</label>
                    <select name="region" id="">
                       
                           <option value="centre">Centre</option>
                           <option value="littoral">Littoral</option>
                           <option value="sud">Sud</option>
                           <option value="est">Est</option>
                           <option value="Adamaoua">Adamaoua</option>
            
                    </select>
                    @if ($errors->has('region'))
                        <span class="text-red-500">{{$errors->first("region")}}</span>
                    @endif
                </div>
                <div class="modal-champs">
                    <label for="">service</label>
                    <select name="service" id="">
                           <option value="commercial">Commercial</option>
            
                    </select>
                    @if ($errors->has('region'))
                        <span class="text-red-500">{{$errors->first("region")}}</span>
                    @endif
                </div>
                <div class="modal-validation">
                <button type="reset">annuler</button>
                <button type="submit"  id="submitForm">creer</button>
            </div>
            </form>
        </div>
    </center>
</div>
<!--FORMULAIRE GENERATION ETAT DES VENTES-->
      
<div id="sales-state-pdf-form" class="modals">
    <center>
    
        <div class="modal-active">
            <div class="modal-head">
                <h1>Generer un PDF etats des ventes/consignes</h1>
                <b class="close-modal">X   </b>
            </div>
            <b class="success text-green-500"></b>
            <b class="errors text-red-500"></b>
            <form  method="POST" action="{{route('boss_sale_state_pdf') }}" >
               @csrf
                <div class="modal-champs">
                    <label for="">Du:</label>
                    <input type="date" name="depart" >
                    @if ($errors->has('depart'))
                        <b class="text-red-500">{{$errors->first("depart")}}</b>
                    @endif
                </div>
                <div class="modal-champs">
                    <label for="">Au:</label>
                    <input type="date" name="fin">
                    @if ($errors->has('fin'))
                        <b class="text-red-500">{{$errors->first("fin")}}</b>
                    @endif
                </div>
                <div class="modal-champs">
                    <label for="">Client :</label><br>
                    
                    <input type="text" name="name">
                </div>
                @if ($errors->has('state'))
                    <b class="text-red-500">{{$errors->first("state")}}</b>
                @endif
                
                <div class="modal-champs">
                    <label for="">region:</label><br>
                    
                    <select name="region" id="">
                        <option value="centre">Centre</option>
                        <option value="est">Est</option>
                        <option value="nord">Nord</option>
                        <option value="sud">Sud</option>
                        <option value="ouest">Ouest</option>
                        <option value="littoral">Littoral</option>
                    </select>
                </div>
                <div class="modal-champs">
                    <label for="">Type de Action</label>
                    <select name="sale" id="">
                       
                           <option value="vente">vente</option>
                           <option value="consigne">consigne</option>
                           <option value="accessoire">accessoire</option>
            
                    </select>
                    @if ($errors->has('move'))
                        <b class="text-red-500">{{$errors->first("move")}}</b>
                    @endif
                </div>
                <div class="modal-validation">
                <button type="reset">annuler</button>
                <button type="submit"  id="submitForm">creer</button>
            </div>
            </form>
        </div>
    </center>
</div>
    @yield('content')
    <footer class="mt-10 w-full bg-teal-950 flex justify-between p-4 text-white rounded-md">
        <div>
            <a href=""  >Contacter</a>
            <a href="">Aide</a>
            <a href="">Mention Legal</a>
        </div>
        <p>&copy; 2024</p>
    </footer>
   <script type="module">
    $(function(){
             //ACTIon GENERATE PDF
             $("#activate-pdf-form").on("click",function(e){
                e.preventDefault()
                if($("#pdf-form").hasClass("modals")){
                    $("#pdf-form").addClass("modals-active");
                    $("#pdf-form").removeClass("modals");
                }
            })
            $(".close-modal").on("click",function(e){
                e.preventDefault()
                if($("#pdf-form").hasClass("modals-active")){
                    $("#pdf-form").addClass("modals");
                    $("#pdf-form").removeClass("modals-active");
                }
            }) 
            
              //ACTION generate pdf receives 
            $("#activate-receives-pdf-form").on("click",function(e){
                e.preventDefault()
            if($("#recieves-pdf-form").hasClass("modals")){
             $("#recieves-pdf-form").addClass("modals-active")

             $("#recieves-pdf-form").removeClass("modals")
            }
            })

            $(".close-modal").on("click",function(e){
                e.preventDefault()
                if($("#recieves-pdf-form").hasClass("modals-active")){
                    $("#recieves-pdf-form").addClass("modals")
                    $("#recieves-pdf-form").removeClass("modals-active")
                }
            })
            $("#activate-sales-state-pdf-form").on("click",function(e){
                e.preventDefault()
            if($("#sales-state-pdf-form").hasClass("modals")){
             $("#sales-state-pdf-form").addClass("modals-active")

             $("#sales-state-pdf-form").removeClass("modals")
            }
            })

            $(".close-modal").on("click",function(e){
                e.preventDefault()
                if($("#sales-state-pdf-form").hasClass("modals-active")){
                    $("#sales-state-pdf-form").addClass("modals")
                    $("#sales-state-pdf-form").removeClass("modals-active")
                }
            })  //ACTION versement historique
               $("#activate-versement-pdf-form").on("click",function(e){
                e.preventDefault()
                if($("#versement-pdf-form").hasClass("modals")){
                    $("#versement-pdf-form").addClass("modals-active");
                    $("#versement-pdf-form").removeClass("modals");
                }

            $(".close-modal").on("click",function(e){
                e.preventDefault()
                if($("#versement-pdf-form").hasClass("modals-active")){
                    $("#versement-pdf-form").addClass("modals");
                    $("#versement-pdf-form").removeClass("modals-active");
                }
            })
            })
         

         
    })
   </script>
</body>
</html>