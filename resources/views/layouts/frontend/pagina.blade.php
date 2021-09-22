<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{--CSRF Token--}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title><?php echo e($__env->yieldContent('title')) ? e($__env->yieldContent('title')) : App\Utils\ConstUtil::NOME_SISTEMA?></title>

    <link rel="shortcut icon" type="image/x-icon" href="{{asset('img/favicon.ico')}}"/>

    @include('layouts.frontend.adicionacss')
    @include('layouts.frontend.adicionajs')

</head>

<body>

<div class="wrapper">
    <div class="cols-md-12">
        @include('layouts.frontend.cabecalho')
    </div>

    <div>
        @include('layouts.frontend.menu')
    </div>

    <br>

    <div class="content" style="margin-top: 0px;margin-left: 0;">

         @yield('content')

    </div>

    <!--
    <div class="footer">
        @include('layouts.frontend.footer')
    </div>
    -->
</div>
</body>
</html>

