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
                <h3 class="card-title">Test</h3>

                {{-- {{dd($divisions);}} --}}
                <div class="row">
                    <label for="type" class="col-sm-2 col-form-label">Division</label>
                    <select class="form-control col-sm-4" name="division" id="division">
                        <option value="">Please select a division</option>
                        @foreach($divisions as $division)
                            <option value="{{ $division }}"   >
                                {{ $division }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body" id="map" style="border: 1px solid;">

            </div>
            <!-- /.card-body -->
        </div>
    </div><!-- /.container-fluid -->
</section>


<script>
    var zillas = {};
    var bibhags = {};
    var populations = @json($populations);
    // console.log('populations: ', populations);
    $.ajax({
        method: 'GET',
        url: '/sam/getZillas',
        //                dataType: 'json',
        success: function (response) {
            zillas = JSON.parse(response);
            console.log(zillas);
        }
    });
    $.ajax({
        method: 'GET',
        url: '/sam/getBibhags',
        success: function (response) {
            bibhags = response;
            // console.log(response);
        }
    });


    const width = 1000;
    const height = 1000;
    var svg = d3.select('.card-body').append('svg')
                    .attr('width',width)
                    .attr('height',height);
    var projection = d3.geoMercator()
                        .center([78, 22])
                        .translate([width / 2, height / 2]);
    var path = d3.geoPath(projection);
    var g = svg.append('g');

    var buckets = 10,
    //   colors = ["#ffffd9", "#edf8b1", "#c7e9b4", "#7fcdbb", "#41b6c4", "#1d91c0", "#225ea8", "#253494", "#081d58", "#cccc41"];
      colors = ["LightGreen", "Lime", "LightGoldenRodYellow", "DarkSeaGreen", "DarkGrey", "DimGrey", "Cyan", "CornflowerBlue", "Blue", "DarkBlue"]; 


    d3.json('getZillas')
        .then(data => {

            zillas.features.forEach(val => {
                var popu = populations.find(function(e) {
                    return e.district == val.properties.ADM2_EN;
                })
                // console.log('popu: ', popu, val);
                val.properties = { ...val.properties, ...popu }
            })

            // console.log('data:', data);
            console.log('zillas', zillas);
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
            updatePath(null);
            // svg.selectAll("path")
            //     .data(data.features)
            //     .enter()
            //     .append("path")
            //     .attr("d", path)
            //     .style("fill", 'steelblue')
            //     .style("stroke", "black" )
            //     .attr("class", 'zilla')
            //     .text(function(d) {
            //         // console.log(d);
            //         return d.properties.ADM2_EN;
            //     })
            //     ;
        });


    // handle on click event
    d3.select('[name="division"]')
        .on('change', function() {
            // var newData = eval(d3.select(this).property('value'));
            var sel = document.getElementById('division');
            var selectValue = sel.options[sel.selectedIndex].value;
            console.log(selectValue);
            d3.select('.card-header')
                .append('p')
                .text(selectValue + ' is the last selected option.');
            updatePath(selectValue);
        });


    function updatePath(selectValue) {
        console.log('updatePath: ',selectValue, zillas);
        d3.selectAll('svg').remove();
        svg = d3.select('.card-body').append('svg')
                        .attr('width',width)
                        .attr('height',height);
        
        svg.selectAll("path")
            .data(zillas.features)
            .enter()
            .append("path")
            .attr("d", path)
            .style("stroke", "black")
            .style("fill", function(d) {
                if(selectValue) {
                    if(d.properties.ADM1_EN === selectValue) {
                        console.log(d.properties.ADM2_EN, d.properties.population,  Math.floor(d.properties.population / 1000));
                        return colors[ Math.floor(d.properties.population / 1000) ];
                    } else {
                        return 'white';
                    }
                } else {
                    return colors[ Math.floor(d.properties.population / 1000) ];
                }
            })
            .attr("class", 'zilla')
            ;        

    }

</script>

@endsection