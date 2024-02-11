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
                        console.log(this.filterText)
                        // console.log('{{ request()->getHost() }}')
                        /* axios.get('/api/version')
                                 .then(response => {
                                   console.log(response.data.data)
                                 }) */
                        this.results = {}
                        axios.get('https://podnety.petrzalka.sk/api/queries_all?page=1&perPage=24&filter=' + this.filterText + '&sort=id&order=desc&stav=1,2,3,4&arch=0')
                            .then(response => {
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

    @include('partials/city', ['city' => $city])

    @include('partials/footer')

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

    </body>
</html>
