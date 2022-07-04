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

    // d3.queue()
    //     .defer(d3.json, "ban.json")
    //     .await(ready);
    const projection = d3.geoMercator().scale(500)
                .translate([width / 2, height / 1.4]);
    const path = d3.geoPath(projection);

    const g = svg.append('g');



    d3.json('https://raw.githubusercontent.com/techslides/D3-Maps/master/data/world/country/Bangladesh.topo.json')
        .then(data => {
            
            
            // const countries = topojson.feature(data, data.objects.countries);
            // g.selectAll('path').data(countries.features).enter().append('path').attr('class', 'country').attr('d', path);
            
            
            const districts = topojson.feature(data, data.objects.map).features;
            console.log('data:', data);
            console.log('districts:', districts);

            svg.selectAll('.districts')
                .data(districts)
                .enter().append("path")
                .attr("class", 'districts')
                // .attr("d", path)
                .attr("id", function(d,i)   {return d.id} )
                .attr("d", function(d,i){  return d3.geoPath() } )
                ;
            
            // g.selectAll('path').data(countries).enter().append('path').attr('class', 'country').attr('d', path);
            // g.selectAll('path').data(countries).enter().append('path')
            //             .attr("fill", "grey")
            //             .attr("d", d3.geoPath()
            //                 .projection(projection)
            //             );
            // svg.append("path")
            //     .datum({type: "FeatureCollection", features: countries})
            //     .attr("d", d3.geoPath());
        });

</script>

@endsection