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
            width: 100%;
            border: 1px solid black;
            border-collapse: collapse;
        }

        th {
            font-size: 0.8rem;
        }

        th,
        tr,
        td {
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
        <h3>{{ $service }} :{{ $region }} </h3>
        <h4> FICHE DE STOCK {{ $first->type }} {{ $first->state ? 'Pleine' : 'vide' }} {{ $first->weight }} KG du
            {{ $fromdate }} au {{ $todate }}</h4>

        <table>
            <thead>
                <th colspan="3">MVT DU STOCK TOTAL</th>
                <th>DATES</th>
                <th>LIBELLES</th>
                <th colspan="3">MVT EN MAGASIN DES BOUTEILLES
                    {{ $first->state ? 'PLEINES' : 'VIDES' }}</th>

            </thead>
            <tr>
                <td>Achats</td>
                <td>Ventes</td>
                <td>Pertes</td>
                <td>

                </td>
                <td> </td>
                <td>ENTREES</td>
                <td>SORTIES</td>
                <td>STOCKS</td>
            </tr>
            <tr class="head-color">
                <td></td>
                <td></td>
                <td></td>
                <td>

                </td>
                <td> </td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tbody>
                @foreach ($data as $data)
                    <tr class="hover:bg-blue-400 hover:text-white hover:cursor-pointer">
                        <td>
                            {{ $data->origin == 'ventes' ? $data->qty : 0 }}
                        </td>
                        <td>
                            {{ $data->origin == 'achat' ? $data->qty : 0 }}</td>
                        <td>
                            {{ $data->origin == 'pertes' ? $data->qty : 0 }}</td>
                        <td>{{ $data->created_at }}</td>
                        <td>{{ $data->label }}</td>
                        <td>{{ $data->entree ? $data->qty : 0 }}</td>
                        <td>{{ $data->sortie ? $data->qty : 0 }}</td>
                        <td>{{ $data->stock }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </center>
</body>

</html>
