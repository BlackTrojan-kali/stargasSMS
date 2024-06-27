<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
     <title>Stargas SMS -Login</title>
    <link rel="icon" href="/images/logo.png">
    @vite('resources/css/app.css')

	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
</head>
<body>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    
    <center class="h-full relative mx-56 bg-blue-400">
        <div>
            test
        </div>
        <div class="w-full bg-black/80  px-8 py-4 flex justify-between">
            <div class="rounded-md ">
                 <img src="/images/logo.png" class="w-24" alt="">
            </div>
        </div>
        <br><br>
        <div class="box p-4 rounded-md font-bold w-6/12 shaodw-md shadow-white/60 text-blue-400 bg-white">
            <h2>Bienvenue sur Stargas Supply Chain Management System</h2>
        </div>
        <br>
        <div class="bg-white rounded-md p-4 w-6/12">
            <form action="{{route('authenticate')}}" method="post">
                @csrf
                <div class="champs">
                    <label for="">Login:</label>
                    <input type="email" name="email">
                    @if($errors->has('email'))
                        <p class="text-red-500">{{$errors->first('email')}}</p>
                    @endif
                </div>
                <div class="champs">
                    <label for="">Mot de passe:</label>
                    <input type="password" name="password">

                    @if($errors->has('password'))
                        <p class="text-red-500">{{$errors->first('password')}}</p>
                    @endif
                </div>
                <button class="mt-4 bg-blue-500 p-2 w-full rounde-md text-white">S'identifier</button>
            </form>
        </div>
        <br>
        <br>
        <br>
        <div class="w-full bg-orange-400 text-white p-4 text-end">
            <p>&copy; allrigths reserved to stargas </p>
        </div>
    </center>


    @if ($errors->has("warning"))
        <script>
            $(document).ready(function(){
                toastr.warning("{{$errors->first('warning')}}")
            })
       </script>
    @endif
</body>
</html>