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
                <h3 class="card-title">Test 2</h3>

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
                    <label for="type" class="col-sm-2 col-form-label">Population</label>
                    <select class="form-control col-sm-4" name="population" id="population">
                        <option value="">Please select population range</option>
                        @foreach(range(1000, 9000, 1000) as $number)
                            <option value="{{ $number }}"   >
                                {{ $number }} - {{ $number+999 }}
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
    var districts = @json($districts);
    var selectedDivision = null;
    var selectedPopulation = null;
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
    // var maxTotal = d3.max(zillas.features, function (d) { return d.total });
    // var colorScale = d3.scaleQuantile()
    //   .domain(d3.range(buckets).map(function (d) { return (d / buckets) * maxTotal }))
    //   .range(colors);
    // var y = d3.scale.sqrt()
    //   .domain([0, 10000])
    //   .range([0,300]);
    // var yAxis = d3.svg.axis()
    //     .scale(y)
    //     .tickValues(colorScale.domain())
    //     .orient("right");


    d3.json('getZillas')
        .then(data => {
            console.log('data:', data);
            console.log('districts', districts);
            var center = d3.geoCentroid(districts)
            var scale  = 150;
            var offset = [width/2, height/2];
            var bounds  = path.bounds(districts);
            var hscale  = scale*width  / (bounds[1][0] - bounds[0][0]);
            var vscale  = scale*height / (bounds[1][1] - bounds[0][1]);
            var scale   = (hscale < vscale) ? hscale : vscale;
            var offset  = [width - (bounds[0][0] + bounds[1][0])/2,
                            height - (bounds[0][1] + bounds[1][1])/2];
            projection = d3.geoMercator().center(center)
                                .scale(scale).translate(offset);
            path = path.projection(projection);
            console.log('path:', path);

            //Bind data and create one path per GeoJSON feature
            updatePath();
        });


    // handle on click event
    d3.select('[name="division"]')
        .on('change', function() {
            var sel = document.getElementById('division');
            selectedDivision = sel.options[sel.selectedIndex].value;
            console.log(selectedDivision);
            // d3.select('.card-header')
            //     .append('p')
            //     .text(selectedDivision + ' is the last selected option.');
            updatePath();
        });

    d3.select('[name="population"]')
        .on('change', function() {
            var sel = document.getElementById('population');
            selectedPopulation = sel.options[sel.selectedIndex].value;
            // console.log(selectedPopulation);
            // d3.select('.card-header')
            //     .append('p')
            //     .text(selectedPopulation + ' is the last selected option.');
            updatePath();
        });


    function updatePath() {
        console.log('updatePath: ',selectedDivision, selectedPopulation, districts);
        d3.selectAll('svg').remove();
        svg = d3.select('.card-body').append('svg')
                        .attr('width',width)
                        .attr('height',height);
        
        svg.selectAll("path")
            .data(districts.features)
            .enter()
            .append("path")
            .attr("d", path)
            .style("stroke", "black")
            .style("fill", function(d) {
                console.log(d);
                if(selectedDivision && selectedPopulation/1000) {
                    if(d.properties.division_name === selectedDivision  && (Math.floor(d.properties.population / 1000) === selectedPopulation/1000)) {
                        // console.log(d.properties.ADM2_EN, d.properties.population,  Math.floor(d.properties.population / 1000));
                        return colors[ Math.floor(d.properties.population / 1000) ];
                    } else {
                        return 'white';
                    }
                } else if(selectedDivision) {
                    if(d.properties.division_name === selectedDivision) {
                        // console.log(d.properties.ADM2_EN, d.properties.population,  Math.floor(d.properties.population / 1000));
                        return colors[ Math.floor(d.properties.population / 1000) ];
                    } else {
                        return 'white';
                    }
                } else if(selectedPopulation/1000) {
                  if(Math.floor(d.properties.population / 1000) === selectedPopulation/1000) {
                      // console.log(d.properties.ADM2_EN, d.properties.population,  Math.floor(d.properties.population / 1000), selectedPopulation, colors[selectedPopulation/1000]);
                      return colors[ selectedPopulation / 1000 ];
                      // return colors[ Math.floor(d.properties.population / 1000) ];
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