@extends('Layouts.ComLayout')
@section('content')
    <div>
        List des elements
        <div class="grid grid-cols-3 w-full">
            @foreach (Cart::content() as $row)
                <div class="font-bold w-3/5 p-4 m-4 shadow-lg rounded-md bg-slate-200 ">
                    <h1>{{ $row->name == 'stargas' ? 'bouteille-gaz' : $row->name }}</h1>
                    <p><b>{{ $row->weight > 0 ? $row->weight . ' kg' : '' }}</b></p>
                    <form method="POST" action="{{ route('updateCart', [$row->rowId]) }}">
                        @csrf
                        <input type="hidden" name="rowId" value="{{ $row->rowId }}">
                        <div class="my-5">
                            <label for="">Qte:</label> <br>
                            <input class="bg-gray-300  border p-1 border-black rounded-md" type="number" name="qty"
                                value="{{ $row->qty }}"> <br>
                        </div>
                        <div class="flex justify-between">

                            <a href="{{ route('deleteItem', ['id' => $row->rowId]) }}"
                                class="bg-red-500 text-white p-2 rounded-md">Supprimer</a>

                            <button type="submit" class="bg-gray-200 p-2 rounded-md border border-black">Mettre à
                                jour</button>
                        </div>
                    </form>
                </div>
            @endforeach
        </div>
        <center>
            <div class="w-2/4 border border-slate-300 rounded-md my-4">
                <div class="bg-slate-500 text-white p-3 ">
                    <h1>Validation du panier</h1>
                </div>
                <form method="POST" class="p-4" action="{{ route('validateCart') }}">
                    @csrf
                    <div class="champs">
                        <label for="">Client:</label>
                        <select name="client" id="" class="w-full p-2 border border-black">
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->nom . ' ' . $client->prenom }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('client'))
                            <p class="text-red-500">{{ $errors->first('client') }}</p>
                        @endif

                    </div>
                    <div class="champs">
                        <label for="">mode de paiement:</label>
                        <select name="currency" id="" class="w-full p-2 border border-black">
                            <option value="Cash">Cash</option>
                            <option value="Virement">Virement</option>
                        </select>

                        @if ($errors->has('currency'))
                            <p class="text-red-500">{{ $errors->first('currency') }}</p>
                        @endif
                    </div>
                    <div class="champs">
                        <label for="">Type d'operation:</label>
                        <select name="type" id="" class="w-full p-2 border border-black">
                            <option value="vente">Vente</option>
                            <option value="consigne">Consigne</option>
                        </select>

                        @if ($errors->has('currency'))
                            <p class="text-red-500">{{ $errors->first('currency') }}</p>
                        @endif
                    </div>
                    <div class="champs">
                        <label for="">somme percue:</label>
                        <input type="number" name="amount" required>
                        @if ($errors->has('amount'))
                            <p class="text-red-500">{{ $errors->first('amount') }}</p>
                        @endif
                    </div>
                    <div class="flex justify-between mt-4">
                        <button type="reset" class="p-2 bg-black  text-white rounded-md">annuler</button>
                        <button type="submit" class="p-2 primary  text-white rounded-md">Valider</button>
                    </div>
                </form>
            </div>
        </center>
    </div>
@endsection
