<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Liste des membres</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
    </style>
</head>
<body>
    <h2>Liste des membres</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th><th>Prénom</th><th>Nom</th><th>Email</th><th>Téléphone</th><th>Date naissance</th>
            </tr>
        </thead>
        <tbody>
        @foreach($membres as $membre)
            <tr>
                <td>{{ $membre->id }}</td>
                <td>{{ $membre->prenom }}</td>
                <td>{{ $membre->nom }}</td>
                <td>{{ $membre->email }}</td>
                <td>{{ $membre->telephone }}</td>
                <td>{{ $membre->date_naissance }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>
