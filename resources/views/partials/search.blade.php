<div class="container hero-ui">
    <div class="row">
        <div class="col-12 text-center">
            <h1 class="hero-ui-title">Vyhľadať v databáze obcí</h1>
            <div class="row justify-content-center">
                <div class="col-12 col-sm-6 col-md-3 text-center pt-4">
                    <div class="input-group input-group-lg">
                        <input x-model="filterText" x-on:input.debounce.300ms="search" type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-lg" placeholder="Zadajte názov">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div x-show="Object.keys(results).length > 0" class="row text-center pt-4 pb-4">
    <template x-for="result in results">
        <div class="col-12 col-sm-6 col-md-3 col-lg-2">
            <a x-text="result.name" :href="'/city/' + result.id"></a>
        </div>
    </template>
</div>

<template x-if="filterText != '' && Object.keys(results).length === 0">
    <div class="row text-center pt-4 pb-4">
        <div class="col-12">Nič sa nenašlo</div>
    </div>
</template>
