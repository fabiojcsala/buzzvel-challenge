@extends ('home.index')

@section ('result')
    <h1 class="display-6">&nbsp;</h1>
    <table class="table caption-top">
        <thead>
            <tr>
                <th scope="col">Hotel</th>
                <th scope="col">Distância</th>
                <th scope="col">Preço</th>
            </tr>
        </thead>
        <tbody>
            @foreach($array as $data)
                <tr>
                    <td>{{ $data['name'] }}</td>
                    <td>{{ $data['distance'] }} KM</td>
                    <td>{{ $data['price'] }} €</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection