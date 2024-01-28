<!DOCTYPE html>
<html>
<head>
    <title>Swagger UI</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('swagger-ui/dist/swagger-ui.css') }}">
</head>
<body>
<div id="swagger-ui"></div>

<script src="{{ asset('swagger-ui/dist/swagger-ui-bundle.js') }}"></script>
<script src="{{ asset('swagger-ui/dist/swagger-ui-standalone-preset.js') }}"></script>

<script>

    function loadSwaggerUI() {
        const ui = SwaggerUIBundle({
            url: "{{ asset('api-docs/openapi.yaml') }}",
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
