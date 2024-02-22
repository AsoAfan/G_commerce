<!DOCTYPE html>
<html lang="en">
<head>
    <title>Swagger UI</title>
    <link rel="stylesheet" type="text/css" href="{{asset('swagger/swagger-ui.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('swagger/swagger-ui-dark.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('swagger/index.css')}}"/>
    <link rel="icon" type="image/png" href="{{asset('swagger/favicon-32x32.png')}}" sizes="32x32"/>
    <link rel="icon" type="image/png" href="{{asset('swagger/favicon-16x16.png')}}" sizes="16x16"/>

    <style>
        body {
            margin: 0;
        }

        .nav {
            /*width: 100%;*/
            background: #1a202c;
            padding: 12px 32px;
            display: flex;
            justify-content: space-around;
            align-items: center;
        }

        .logo img {
            height: 72px;

        }


    </style>
</head>
<body>
{{--
@php
    $path = public_path('api-docs');
    $folders = scandir($path);
    $files = array_filter($folders, fn($file) => is_file($path . '/' . $file));

@endphp--}}

{{--<div class="swagger-ui nav">
    <div class="logo">
        <img src="{{asset('Img/GigantLogo.png')}}" alt="">
    </div>

    <div class="content">

        <label>
            <select id="doc" onchange="changed()">
                @foreach($files as $file)
                    <option
                        {{$file == "openapi.yaml" ? "selected" : ""}} value="{{$file}}">{{$file}}</option>
                @endforeach
            </select>
        </label>
    </div>
</div>--}}


<div id="swagger-ui"></div>

<script src="{{asset('swagger/swagger-ui-standalone-preset.js')}}" charset="UTF-8"></script>
<script src="{{asset('swagger/swagger-ui-bundle.js')}}" charset="UTF-8"></script>
<script src="{{asset('swagger/swagger-initializer.js')}}" charset="UTF-8"></script>


</body>
</html>
