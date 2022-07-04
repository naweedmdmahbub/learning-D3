@extends('layout.app')

@section('content')


<section class="content-header">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Test 3</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

            </div>
            <!-- /.card-body -->
        </div>
    </div><!-- /.container-fluid -->
</section>


<script>
    const width = 700;
    const height = 700;

    const svg = d3.select('.card-body').append('svg')
                .attr('width',width)
                .attr('height',height);


    // const projection = d3.geoMercator().scale(140)
    //             .translate([width / 2, height / 1.4]);
    const projection = d3.geoMercator()
                        .center([78, 22])
                        .scale(1680)
                        .translate([width / 3, height / 3]);
    const path = d3.geoPath(projection);

    const g = svg.append('g');
    
    

    d3.json('getZillas')
        .then(data => {
            console.log(data);

            //Bind data and create one path per GeoJSON feature
            svg.selectAll("path")
                .data(data.features)
                .enter()
                .append("path")
                .attr("d", path)
                .style("fill", "steelblue")
                ;


        });



</script>

@endsection