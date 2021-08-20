<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Kanban Board</title>

        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    </head>
    <body>

        <div id="app" class="container">
            <h1>Kanban Board</h1>
            <k-test initial-data="{{$columns }}"></k-test>
            <!--kanban-test initial-data="{{$columns }}"></kanban-test-->
        </div>

        <script src="{{ asset('js/app.js') }}"></script>

    </body>
</html>
