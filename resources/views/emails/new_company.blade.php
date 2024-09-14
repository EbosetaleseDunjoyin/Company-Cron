<!DOCTYPE html>
<html>
<head>
    <title>New Active Company</title>
</head>
<body>
    <h1>New Active Company: {{ $company['title'] }}</h1>
    <p>Incorporated On: {{ $company['date_of_creation'] }}</p>
    <p>Address: {{ $company['address_snippet'] }}</p>

    <h3>Directors:</h3>
    <ul>
        @foreach ($directors as $director)
            <li>{{ $director['name'] }} - {{ $director['occupation'] }} ({{ $director['nationality'] }})</li>
        @endforeach
    </ul>

    <p><a href="https://beta.companieshouse.gov.uk{{ $company['links']['self'] }}">View Company</a></p>
</body>
</html>
