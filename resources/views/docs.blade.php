<!DOCTYPE html>
<html>
<head>
    <title>Swagger UI</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('swagger-ui/dist/swagger-ui.css') }}">

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

@php
    $path = public_path('api-docs');
    $folders = scandir($path);
    $files = array_filter($folders, fn($file) => is_file($path . '/' . $file));

@endphp

<div class="swagger-ui nav">
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
</div>


<div id="swagger-ui"></div>


<script src="{{ asset('swagger-ui/dist/swagger-ui-bundle.js') }}"></script>
<script src="{{ asset('swagger-ui/dist/swagger-ui-standalone-preset.js') }}"></script>

<script>

    let selectorValue = 'openapi.yaml'


    function changed() {
        selectorValue = document.getElementById('doc').value
        loadSwaggerUI()

    }


    function loadSwaggerUI() {
        const ui = SwaggerUIBundle({
            url: `/api-docs/${selectorValue}`,
            dom_id: '#swagger-ui',
            presets: [
                SwaggerUIBundle.presets.apis,
                SwaggerUIStandalonePreset,
            ],
            layout: 'BaseLayout',
        });

        window.ui = ui;
    }

    window.onload = function () {
        loadSwaggerUI();
    }
</script>
</body>
</html>
