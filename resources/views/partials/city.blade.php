<div class="container detail-ui">
    <div class="row text-center detail-ui-div">
        <div class="col-12 col-md-6 detail-ui-left p-4">
            <div class="row text-start">
                <div class="col-6">
                    <p class="fw-bold">Meno starostu:</p>
                    <p class="fw-bold">Adresa obecného úradu:</p>
                </div>
                <div class="col-6">
                    <p>{{ $city->mayor_name }}</p>
                    <p>{{ $city->city_hall_address }}</p>
                </div>
                <div class="col-6">
                    <p class="fw-bold">Telefón:</p>
                    <p class="fw-bold">Fax:</p>
                    <p class="fw-bold">Email:</p>
                    <p class="fw-bold">Web:</p>
                    <p class="fw-bold">GPS:</p>
                </div>
                <div class="col-6">
                    <p>{{ $city->phone }}</p>
                    <p>{{ $city->fax }}</p>
                    <p>{{ $city->email }}</p>
                    <p>{{ $city->web_address }}</p>
                    <p>{{ ($city->lat && $city->lng) ? $city->lat . ', ' . $city->lng : '' }}</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 d-flex justify-content-center align-items-center text-center p-4">
            <div>
                <img alt="Erb" class="logo-ui" src="{{ asset('imgs/'.$city->image) }}" onerror="this.src='{{ asset('img/spare.png') }}';">
                <h2 class="pt-2 footer-text-blue text-center">{{ $city->name }}</h2>
            </div>
        </div>
    </div>
</div>
