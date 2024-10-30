@extends('Layouts.controllerLayout')
@section('content')
    <div>

        <h1 class="p-2 mt-5 font-bold text-2xl">Historique des {{ strtoupper($type) }}S</h1>
        <!-- The whole future lies in uncertainty: live immediately. - Seneca -->
        <table class="history scroll mt-10 w-full border-2 border-collapse border-gray-400 ">
            <thead class="p-3 bg-gray-500 text-white">
                <td>
                    nom Client
                </td>
                <td>
                    adresse
                </td>
                <td>
                    prix total
                </td>
                <td>
                    date
                </td>
                <td>actions</td>
            </thead>
            <tbody>
                @foreach ($ventes as $vente)
                    <tr id="{{ $vente->id }}" class="hover:text-white hover:bg-blue-400 hover:cursor-pointer">
                        <td>{{ $vente->customer }}</td>
                        <td>{{ $vente->address }}</td>
                        <td>{{ $vente->prix_6 * $vente->qty_6 + $vente->prix_12 * $vente->qty_12 + $vente->prix_50 * $vente->qty_50 }}
                        </td>
                        <td>{{ $vente->created_at }}</td>
                        <td><a href="{{ route('printInvoice', ['id' => $vente->id]) }}"><i class="text-teal-900">print</i></a> <i
                                class="text-red-500 delete">delete</i></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <script type="module">
        $(function() {
            $(".delete").on("click", function() {
                var id = $(this).parent().parent().attr("id");
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
                            url: "/commercial/delventes/" + id,
                            dataType: "json",
                            data: {
                                "id": id,
                                "_token": token,
                            },
                            method: "DELETE",
                            success: function(res) {
                                toastr.warning(res.message)
                                $("#" + id).load(location.href + " #" + id)
                            },
                            error: function(xhr, status, err) {
                                console.log(xhr)
                                console.log(err)
                            }
                        })
                    } else if (result.isDenied) {
                        Swal.fire("Changement non enregistre", "", "info");
                    }
                })
            })
        })
    </script>
@endsection
