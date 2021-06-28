<table class="table table-hover table-sm">
    <thead>
        <tr>
            <th colspan="14" style="text-align: center; size: 20px;">RELATORIO LANÇAMENTO - {{$relatorios[0]->proj}} </th>
        </tr>
    <thead>
        <tr>
            <th scope="col">Nome </th>
            <th scope="col">Ano</th>
            <th scope="col">Janeiro</th>
            <th scope="col">Fevereiro</th>
            <th scope="col">Março</th>
            <th scope="col">Abril</th>
            <th scope="col">Maio</th>
            <th scope="col">Junho</th>
            <th scope="col">Julho</th>
            <th scope="col">Agosto</th>
            <th scope="col">Setembro</th>
            <th scope="col">Outubro</th>
            <th scope="col">Novembro</th>
            <th scope="col">Dezembro</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($relatorios as $relatorio)
            <tr>
                <td>{{ $relatorio->nome }}</td>
                <td>{{ $relatorio->ano }}</td>
                <th>{{ $relatorio->janeiro }}</th>
                <th> {{ $relatorio->fevereiro }}</th>
                <th>{{ $relatorio->marco }}</th>
                <th>{{ $relatorio->abril }}</th>
                <th> {{ $relatorio->maio }}</th>
                <th>{{ $relatorio->junho }}</th>
                <th> {{ $relatorio->julho }}</th>
                <th>{{ $relatorio->agosto }}</th>
                <th>{{ $relatorio->setembro }}</th>
                <th>{{ $relatorio->outubro }}</th>
                <th>{{ $relatorio->novembro }}</th>
                <th>{{ $relatorio->dezembro }}</th>
            </tr>
        @endforeach
    </tbody>
</table>
