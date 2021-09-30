@extends('layouts.frontend.pagina')

@section('title', \App\Utils\ConstUtil::NOME_SISTEMA)

@section('content')



    <table id="diagnosticos" class="display" style="width:100%">
        <thead>
        <tr>
            <th>Id</th>
            <th>Data</th>
            <th>Área</th>
            <th>Sub Área</th>
            <th width="50">Nível Maturidade</th>
            <th width="20">Operação</th>
        </tr>
        </thead>
        <tbody>
        @foreach($diagnosticos as $d)
        <tr>
            <td>{{$d->id}}</td>
            <td>{{$d->data}}</td>
            <td>{{$d->area}}</td>
            <td>{{$d->subarea}}</td>
            <td class="text-right">{{$d->nivel_maturidade}}</td>
            <td class="text-center"><a href="/diagnostico/showDiagnosticoIndividual/{{$d->id}}" title="Exibir Diagnóstico"><i class="fas fa-chart-line"></i></a></td>
        </tr>
        @endforeach
        </tbody>
    </table>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#diagnosticos').DataTable({
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.12/i18n/Portuguese-Brasil.json"
                },
            });
        } );
    </script>
@endsection
