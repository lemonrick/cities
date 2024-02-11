<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <script src="{{ asset('js/axios.min.js') }}"></script>
        <script defer src="{{ asset('js/cdn.min.js') }}"></script>

        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
    <body x-data="data" class="d-flex flex-column min-vh-100">

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('data', () => ({
                results: {},
                filterText: '',
                async search() {
                    if (this.filterText === '') {
                        this.results = {}
                    } else {
                        this.results = {}
                        axios.get('/api/search-city', {
                            params: {
                                name: this.filterText
                            }
                        }).then(response => {
                                this.results = response.data.data;
                            })
                            .catch(error => {
                                this.results = {};
                            })
                    }
                }
            }))
        })
    </script>

    @include('partials/nav')

    @include('partials/search')

    @include('partials/footer')

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

    </body>
</html>
