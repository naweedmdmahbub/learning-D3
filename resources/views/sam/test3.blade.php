@extends('layout.app')

@section('content')

<style>
    .zilla:hover {
      fill: red;
      cursor: pointer;
    }
    .selected {
      fill: red;
    }

    .boundary {
      fill: #DEB887;
      stroke: black;
      stroke-width: 1px; 
    }

    .hidden {
      display: none;
    }

    div.tooltip {
      color: #222; 
      background: #fff; 
      border-radius: 3px; 
      box-shadow: 0px 0px 2px 0px #a6a6a6; 
      padding: .2em; 
      text-shadow: #f5f5f5 0 1px 0;
      opacity: 0.9; 
      position: absolute;
    }
</style>


<section class="content-header">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Test 3</h3>
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



    var offsetL = document.getElementById('map').offsetLeft+10;
    var offsetT = document.getElementById('map').offsetTop+10;

    var tooltip = d3.select("#map")
        .append("div")
        .attr("class", "tooltip hidden");
    const colorScale = d3.scaleLinear()
                        // .domain([min, max])
                        //I goofed, so this has to be in reverse order
                        .range(["#00806D", "#00BC4C", "#00F200", "#85FB44"].reverse());

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
            svg.selectAll("path")
                .data(data.features)
                .enter()
                .append("path")
                .attr("d", path)
                .style("fill", 'steelblue')
                .attr("class", 'zilla')
                ;

            g.append("g").attr("class", "boundary")
                    .selectAll("boundary")
                    .data(data.features)
                    .enter().append("path")
                    .attr("name", function(d) {return d.properties.name;})
                    .attr("id", function(d) { return d.id;})
                    // .on('click', selected)
                    // .on("mouseover", function (d) {
                    //     d3.select(this)
                    //         .style("fill", tinycolor(colorScale(d.unemploymentRate)).darken(15).toString())
                    //         .style("cursor", "pointer");
                    // })
                    .on('mouseover', function (d) {
                        
                        console.log('this: ',this);
                        tooltip.transition()
                            .duration(200)
                            .style("opacity", .9);
                    //Any time the mouse moves, the tooltip should be at the same position
                        tooltip.style("left", (d3.pageX) + "px")
                            .style("top", (d3.pageY) + "px")
                    //The text inside should be State: rate%
                            .text(() => {
                                console.log('asdsad',d);
                                return;
                            })
                    })
                    // .on("mousemove", showTooltip)
                    // .on("mouseout",  function(d,i) {
                    //     tooltip.classed("hidden", true);
                    // })
                    .attr("d", path);
            

            // svg.selectAll("text")
            //     .data(data.features)
            //     .enter()
            //     .append("text")
            //     .text( (d,i) => {
            //         // console.log('i: ',i, '   d[i]: ', d);
            //         return d.properties.ADM2_EN;
            //     })
            //     .attr("x", (d) => {
            //         console.log('   d.geometry.coordinates: ', d.geometry.coordinates);
            //         return d.geometry.coordinates;
            //     })
            //     .attr("y", (d) => {
            //         return d.properties.coordinates;
            //     })
            //     .attr("name", function(d) {return d.properties.name;})
            //     .attr("id", function(d) { return d.id;})
            //     .on('click', selected)
            //     .on("mousemove", showTooltip)
            //     .on("mouseout",  function(d,i) {
            //         tooltip.classed("hidden", true);
            //     })
            //     ;


            // g.append("g").append("text")
            //             .attr("class", "country-label")
            //             // .attr("transform", function(d) { console.log("d", d); return "translate(" + path.centroid(d) + ")"; })
            //             .text(function(d) { 
            //                 console.log(d);
            //                 return d.properties.ADM2_EN;
            //              })
            //             .attr("dx", function (d) {
            //                 return "0.3em";
            //             })
            //             .attr("dy", function (d) {
            //                 return "0.35em";
            //             })
            //             .style('fill', 'black');


        });



    function showTooltip(d) {
        // console.log(d);
        console.log(d.path[0].__data__.properties.ADM2_EN);
        label = d.path[0].__data__.properties.ADM2_EN;
        var mouse = d3.pointer(svg.node())
            .map( function(d) { return parseInt(d); } );
        tooltip.classed("hidden", false)
            .attr("style", "left:"+(mouse[0]+offsetL)+"px;top:"+(mouse[1]+offsetT)+"px")
            .html(label);
    }


    function selected() {
        console.log('selected');
        d3.select('.selected').classed('selected', false);
        d3.select(this).classed('selected', true);
    }

</script>

@endsection