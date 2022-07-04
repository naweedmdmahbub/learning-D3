@extends('layout.app')

@section('content')


<section class="content-header">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Education</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

            </div>
            <!-- /.card-body -->
        </div>
    </div><!-- /.container-fluid -->
</section>


<script>
    const width = 900;
    const height = 900;

    const svg = d3.select('.card-body').append('svg')
                .attr('width',width)
                .attr('height',height);


    const projection = d3.geoMercator().scale(140)
                .translate([width / 2, height / 1.4]);
    const path = d3.geoPath(projection);

    const g = svg.append('g');

    d3.json('https://cdn.jsdelivr.net/npm/world-atlas@2/countries-110m.json')
        .then(data => {

            const countries = topojson.feature(data, data.objects.countries);
            console.log(countries);
            g.selectAll('path').data(countries.features).enter().append('path').attr('class', 'country').attr('d', path);

        });


</script>

@endsection