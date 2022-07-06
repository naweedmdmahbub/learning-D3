@extends('layout.app')

@section('content')

<style>
    .zilla:hover {
      fill: red;
      cursor: pointer;
    }
</style>


<section class="content-header">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Test 4</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body" id="map" style="background: lightgreen;">

            </div>
            <!-- /.card-body -->
        </div>
    </div><!-- /.container-fluid -->
</section>


<script>
    const width = 1000;
    const height = 1000;

    const svg = d3.select('.card-body').append('svg')
                    .attr('width',width)
                    .attr('height',height);
    var projection = d3.geoMercator()
                        .center([78, 22])
                        .translate([width / 2, height / 2]);
    var path = d3.geoPath(projection);
    var g = svg.append('g');


    var div = d3.select("body").append("div")
      .attr("class", "tooltip")
      .style("opacity", 0);




    d3.json('getZillas')
        .then(data => {
            console.log(data);

            var center = d3.geoCentroid(data)
            var scale  = 150;
            var offset = [width/2, height/2];
            var bounds  = path.bounds(data);
            var hscale  = scale*width  / (bounds[1][0] - bounds[0][0]);
            var vscale  = scale*height / (bounds[1][1] - bounds[0][1]);
            var scale   = (hscale < vscale) ? hscale : vscale;
            var offset  = [width - (bounds[0][0] + bounds[1][0])/2,
                            height - (bounds[0][1] + bounds[1][1])/2];
            projection = d3.geoMercator().center(center)
                                .scale(scale).translate(offset);
            path = path.projection(projection);


            //Bind data and create one path per GeoJSON feature
            // svg.selectAll("path")
            //     .data(data.features)
            //     .enter()
            //     .append("path")
            //     .attr("d", path)
            //     .style("fill", 'steelblue')
            //     .attr("class", 'zilla')
            //     .on('click', function (d, i) {
            //         d3.select(this).transition().duration(300).style("opacity", 1);
            //         console.log('this: ',this, d);
            //         div.transition().duration(300)
            //             .style("opacity", 1)
            //         div.text(d.id + " : " + d.total)
            //             .style("left", (d3.pageX) + "px")
            //             .style("top", (d3.pageY - 30) + "px");
            //     })
            //     ;

            // g.append("g").attr("class", "boundary")
            //         .selectAll("boundary")
            //         .data(data.features)
            //         .enter().append("path")
            //         .attr("name", function(d) {return d.properties.name;})
            //         .attr("id", function(d) { return d.id;})
            //         .attr("d", path);
            
            svg.append("text")
                .data(data.features)
                .enter()
                .append("text")
                .attr("class", "country-label")
                    // .attr("transform", function(d) { console.log("d", d); return "translate(" +           path.centroid(d) + ")"; })
                .text(function(d,i) {
                    // jsonObj['state'] =  d.properties.ADM1_EN;
                    // jsonObj['district'] =  d.properties.ADM2_EN;
                    // jsonObj['population'] =  Math.floor(Math.random() * 5000) + 1000;
                    console.log(d);
                    // states.push(jsonObj);
                    // console.log(states[i]['state'], states[i]['district'], states[i]['population']);
                    return d.properties.ADM2_EN;
                })
                .attr("dx", function (d) {
                    return "0.3em";
                })
                .attr("dy", function (d) {
                    return "0.35em";
                })
                .style('fill', 'black');
            

            // console.log('states:', states);
        });


</script>

@endsection