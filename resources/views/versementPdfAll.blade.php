<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Stargas SCMS</title>
    <link rel="icon" href="/images/logo.png">
    <link href="toastr.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body class="">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        @font-face {
            font-family: 'Riot';
            src: url({{ storage_path('/fonts/ProtestRiot-Regular.ttf') }}) format("truetype");
            font-weight: 400;
            font-style: normal;
        }

        body {
            font-family: "Riot";
            padding: 5px;
        }

        table {
            border: 1px solid white;
            border-collapse: collapse;
        }

        .table-1,
        .table-2 {
            border: 2px solid black;

        }

        .table-1 th,
        .table-2 th {
            font-size: 0.8rem;

        }

        .table-1 th,
        .table-1 tr,
        .table-1 td,
        .table-2 th,
        .table-2 tr,
        .table-2 td {
            border: 1px solid black;
            padding: 4px;

        }

        .logo-section {
            position: absolute;
            top: 2px;
        }

        .head-color {
            background-color: burlywood;
            padding: 20px;
        }
    </style>
    <br>
    <br><br>
    <br>
    <br><br><br>
    <br><br>

    <div class="logo-section">
        <img src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('images/logo.png'))) }}"
            width="100px">
        <p>
            <b>StarGas S.A</b><br>
            <b>B.P:</b>17159 Yaoundé-Cameroun <br>
            <b>Tél:</b>222 22 49 36 <br>
            <b>Email:</b> stargas@stargas.cm <br>
        </p>
    </div>
    <center>
        <h3>{{ strtoupper(Auth::user()->role) }} :{{ strtoupper(Auth::user()->region) }} </h3 <h4> FICHE DE VERSEMENTS
        GLOBAL</h4>

    </center>
    <table>
        <tbody>

            <tr>
                <td>
                    <table class="table-1">
                        <thead>
                            <th colspan="5">VERSEMENTS AFB</th>
                            <tr>
                                <th><b>DATES</b></th>
                                <th><b>GPL</b></th>
                                <th><b>Consigne</b></th>
                                <th><b>Total</b></th>
                                <th><b>Commentaire</b></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($afb as $data)
                                <tr class="hover:bg-blue-400 hover:text-white hover:cursor-pointer">
                                    <td>
                                        {{ $data->created_at }}
                                    </td>

                                    <td>
                                        {{ $data->montant_gpl }}
                                    </td>
                                    <td>{{ $data->montant_consigne }}</td>
                                    <td>{{ $data->montant_gpl + $data->montant_consigne }}</td>
                                    <td>{{ $data->commentaire }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
                <td>

                    <table class="table-2">
                        <thead>
                            <th colspan="5">VERSEMENTS CCA</th>
                            <tr>
                                <th><b>DATES</b></th>
                                <th><b>GPL</b></th>
                                <th><b>Consigne</b></th>
                                <th><b>Total</b></th>
                                <th><b>Commentaire</b></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cca as $data)
                                <tr class="hover:bg-blue-400 hover:text-white hover:cursor-pointer">
                                    <td>
                                        {{ $data->created_at }}
                                    </td>

                                    <td>
                                        {{ $data->montant_gpl }}
                                    </td>
                                    <td>{{ $data->montant_consigne }}</td>
                                    <td>{{ $data->montant_gpl + $data->montant_consigne }}</td>
                                    <td>{{ $data->commentaire }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        </tabl </td>
            </tr>
        </tbody>
    </table>
</body>

</html>
