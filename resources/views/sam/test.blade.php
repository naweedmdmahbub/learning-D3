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

                {{-- {{dd($states);}} --}}
                <div class="form-group row">
                    <label for="type" class="col-sm-2 col-form-label">Division</label>
                    <select class="form-control col-sm-10" name="state" id="state">
                        <option value="">Please select a division</option>
                        @foreach($states as $state)
                            <option value="{{ $state }}"   >
                                {{ $state }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body" id="map" style="background: lightgreen;">

            </div>
            <!-- /.card-body -->
        </div>
    </div><!-- /.container-fluid -->
</section>


<script>
    var states = {};
    var zillas = {};
    var bibhags = {};
    $.ajax({
        method: 'GET',
        url: '/sam/getStates',
        //                dataType: 'json',
        success: function (response) {
            states = JSON.parse(response);
        }
    });
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
            svg.selectAll("path")
                .data(data.features)
                .enter()
                .append("path")
                .attr("d", path)
                .style("fill", 'steelblue')
                .style("stroke", "black" )
                .attr("class", 'zilla')
                .text(function(d) {
                    // console.log(d);
                    return d.properties.ADM2_EN;
                })
                ;

        });


    // handle on click event
    d3.select('[name="state"]')
        .on('change', function() {
            // var newData = eval(d3.select(this).property('value'));
            var sel = document.getElementById('state');
            var selectValue = sel.options[sel.selectedIndex].value;
            console.log(selectValue);
            d3.select('.card-header')
                .append('p')
                .text(selectValue + ' is the last selected option.');
            updatePath(selectValue);
        });


    function updatePath(selectValue) {
        console.log('Sssss: ',selectValue, zillas);
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
            // .style("fill", "orange")
            .style("fill", function(d) {
                console.log(d);
                if(d.properties.ADM1_EN === selectValue) {
                    return 'orange';
                } else {
                    return 'steelblue';
                }
            })
            .attr("class", 'zilla')
            ;        

    }

</script>

@endsection