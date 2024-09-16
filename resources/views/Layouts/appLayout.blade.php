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
        @if(Auth::user()->role == "super")
        <nav class="mt-2 p-2 first-letter: w-full text-white bg-blue-400 rounded-md">
            <a href="{{route('dashboard')}}"><i class="fa-solid fa-home"></i> ACCEUIL</a>
            <a href="{{route('manageUsers')}}" >GERER LES UTILISATEURS</a>
            <a href="{{route('manageArticles')}}" >GERER LES PRODUITS</a>
            <a href="" >ETATS</a>
        </nav>
        @elseif (Auth::user()->role=="magazinier")
        <nav class="mt-2 p-2 w-full text-white bg-blue-400 rounded-md">
            <a href="{{route("dashboard-manager")}}"><i class="fa-solid fa-home"></i> ACCEUIL</a>
            <a href="{{route("moveActions")}}" >MOUVEMENTS</a>
            <a href="" >HISTORIQUE</a>
            <a href="" >ETATS</a>
        </nav>
        
        @endif
       </header>
    
    
    @yield('content')
    <footer class="mt-10 w-full bg-blue-950 flex justify-between p-4 text-white rounded-md">
        <div>
            <a href=""  >Contacter</a>
            <a href="">Aide</a>
            <a href="">Mention Legal</a>
        </div>
        <p>&copy; 2024</p>
    </footer>
   
</body>
</html>