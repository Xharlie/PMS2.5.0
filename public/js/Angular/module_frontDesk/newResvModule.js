/**
 * Created by Xharlie on 8/17/14.
 */
var appResv = angular.module('newResvModule',['ngRoute','ngAnimate','ui.bootstrap']);


appResv.config(['$tooltipProvider', function($tooltipProvider){
    $tooltipProvider.setTriggers({'wrong': 'right'});
}]);

appResv.directive('roomAvilinechart', function(){

    return {
        restrict: 'E',
        scope: {
            soldArray: '=',
            roomType : '=',
            num: '='
        },
        link: function (scope, element, attrs) {
            // whenever the bound 'exp' expression changes, execute this
            scope.$watch('soldArray', function (newVal, oldVal) {
                if (!newVal) {
                    return;
                }
                data = newVal;
                d3.select("#d3Canvas"+scope.num).select("svg").remove();

                var margin = {top: 30, right: 80, bottom: 30, left: 50},
                    width = 630 - margin.left - margin.right,
                    height = 300 - margin.top - margin.bottom;
                if (data.length > 12){
                    width += (width+60) * (data.length-12)/12;
                }
                var parseDate = d3.time.format("%Y-%m-%d").parse;

                var x = d3.time.scale()
                    .range([0, width]);

                var y = d3.scale.linear()
                    .range([height, 0]);

                var color = d3.scale.category10();

                var xAxis = d3.svg.axis()
                    .scale(x)
                    .orient("bottom")
                    .ticks(d3.time.day,1) // change to day
                    .tickFormat(d3.time.format("%m-%d"));


                var yAxis = d3.svg.axis()
                    .scale(y)
                    .orient("left");

                var line = d3.svg.line()
                    //.interpolate("basis")
                    .x(function(d) { return x(d.DATE); })
                    .y(function(d) { return y(d.Avail); });

                var svg = d3.select("#d3Canvas"+scope.num).append("svg")
                    .attr("width", width + margin.left + margin.right)
                    .attr("height", height + margin.top + margin.bottom)
                    .append("g")
                    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");


                // = [{"Double":9,"Double Supreme":1,"Kingbed":5,"Kingbed Supreme":3,"Single":7,"Single Supreme":2,"DATE":"2014-07-01"},{"Double":9,"Double Supreme":1,"Kingbed":5,"Kingbed Supreme":3,"Single":7,"Single Supreme":2,"DATE":"2014-07-02"},{"Double":9,"Double Supreme":1,"Kingbed":5,"Kingbed Supreme":3,"Single":7,"Single Supreme":2,"DATE":"2014-07-03"},{"Double":9,"Double Supreme":1,"Kingbed":5,"Kingbed Supreme":3,"Single":7,"Single Supreme":2,"DATE":"2014-07-04"},{"Double":1,"Double Supreme":1,"Kingbed":5,"Kingbed Supreme":3,"Single":2,"Single Supreme":2,"DATE":"2014-07-05"},{"Double":9,"Double Supreme":1,"Kingbed":5,"Kingbed Supreme":0,"Single":3,"Single Supreme":2,"DATE":"2014-07-06"},{"Double":9,"Double Supreme":1,"Kingbed":0,"Kingbed Supreme":3,"Single":1,"Single Supreme":2,"DATE":"2014-07-07"},{"Double":4,"Double Supreme":1,"Kingbed":5,"Kingbed Supreme":2,"Single":7,"Single Supreme":2,"DATE":"2014-07-08"},{"Double":6,"Double Supreme":1,"Kingbed":-1,"Kingbed Supreme":3,"Single":7,"Single Supreme":2,"DATE":"2014-07-09"},{"Double":9,"Double Supreme":1,"Kingbed":3,"Kingbed Supreme":3,"Single":7,"Single Supreme":2,"DATE":"2014-07-10"},{"Double":7,"Double Supreme":1,"Kingbed":5,"Kingbed Supreme":3,"Single":7,"Single Supreme":2,"DATE":"2014-07-11"},{"Double":9,"Double Supreme":1,"Kingbed":1,"Kingbed Supreme":1,"Single":7,"Single Supreme":2,"DATE":"2014-07-12"},{"Double":9,"Double Supreme":1,"Kingbed":5,"Kingbed Supreme":2,"Single":3,"Single Supreme":2,"DATE":"2014-07-13"},{"Double":9,"Double Supreme":1,"Kingbed":5,"Kingbed Supreme":3,"Single":7,"Single Supreme":2,"DATE":"2014-07-14"},{"Double":0,"Double Supreme":1,"Kingbed":5,"Kingbed Supreme":3,"Single":7,"Single Supreme":2,"DATE":"2014-07-15"},{"Double":2,"Double Supreme":1,"Kingbed":5,"Kingbed Supreme":3,"Single":6,"Single Supreme":2,"DATE":"2014-07-16"},{"Double":9,"Double Supreme":1,"Kingbed":5,"Kingbed Supreme":0,"Single":7,"Single Supreme":2,"DATE":"2014-07-17"}];
                color.domain(d3.keys(data[0]).filter(function(key) { return key !== "DATE"; }));

                data.forEach(function(d) {
                    d.DATE = parseDate(d.DATE);
                });

                var cities = color.domain().map(function(name) {
                    return {
                        name: name,
                        values: data.map(function(d) {
                            return {DATE: d.DATE, Avail: +d[name]};
                        })
                    };
                });

                x.domain(d3.extent(data, function(d) { return d.DATE; }));

                y.domain([
                    0,//d3.min(cities, function(c) { return d3.min(c.values, function(v) { return v.Avail; }); })
                    d3.max(cities, function(c) { return d3.max(c.values, function(v) { return v.Avail; }); })
                ]);

                svg.append("g")
                    .attr("class", "x axis")
                    .attr("transform", "translate(0," + height + ")")
                    .call(xAxis);

                svg.append("g")
                    .attr("class", "y axis")
                    .call(yAxis)
                    .append("text")
                    .attr("transform", "rotate(-90)")
                    .attr("y", 6)
                    .attr("dy", ".71em")
                    .style("text-anchor", "end")
                    .text("余量");

                var city = svg.selectAll(".city")
                    .data(cities)
                    .enter().append("g")
                    .attr("class", "city");

                city.append("path")
                    .attr("class", "line")
                    .attr("d", function(d) { return line(d.values); })
                    .style("stroke", function(d) { return color(d.name); })
                    .filter(function(d){return (scope.roomType != "")&&(d.name != scope.roomType);})
                    .style("opacity",0.1);

                city.append("text")
                    .datum(function(d) { return {name: d.name, value: d.values[d.values.length - 1]}; })
                    .attr("transform", function(d) { return "translate(" + x(d.value.DATE) + "," + y(d.value.Avail) + ")"; })
                    .attr("x", 5)
                    .attr("dy", ".15em")
                    .text(function(d) { return d.name; });

                var labelg = city.selectAll("g")
                    .data(function(d){return d.values})
                    .enter()
                    .append("g");


                city.filter(function(d){return (d.name != scope.roomType)})
                    .selectAll("g")
                    .style("opacity",0);

                city.filter(function(d){return (d.name == scope.roomType)})
                    .selectAll("g")
                    .style("opacity",1);

                labelg.append("text")
                    .attr("x",function(d){return x(d.DATE)})
                    .attr("y",function(d){return y(d.Avail)})
                    .attr("dx","-0.2em")
                    .attr("dy","-0.5em")
                    .text(function(d){return d.Avail;})
                    .filter(function(d){return (d.Avail<=0);})
                    .style("stroke","#ED2800");


//
//                        d3.selectAll("g.x")
//                            .selectAll("g.tick")
//                            .filter(function(d1){
//                                return !(d3.select('#d3Canvas')
//                                    .selectAll(".city")
//                                    .selectAll("g")
//                                    .filter(function(d){
//                                        return (d3.select(this).style("opacity")==1)&&(d.Avail <= 0);
//                                    })
//                                    .empty());
//                            })
//                            .selectAll("text")
//                            .style("stroke","#F02E07");





            });
            scope.$watch('roomType', function (newVal, oldVal) {
                if(newVal==""){
                    d3.select("#d3Canvas"+scope.num)
                        .selectAll('path')
                        .transition()
                        .duration(500)
                        .style("opacity",1);
                    d3.select("#d3Canvas"+scope.num).selectAll(".city").filter(function(d){return (d.name != scope.roomType)})
                        .selectAll("g")
                        .transition()
                        .duration(800)
                        .style("opacity",0);
                }else{
                    d3.select("#d3Canvas"+scope.num)
                        .selectAll(".city")
                        .selectAll('path')
                        .filter(function(d){return (d.name != newVal);})
                        .transition()
                        .duration(500)
                        .style("opacity",0.1);
                    d3.select("#d3Canvas"+scope.num)
                        .selectAll('path')
                        .filter(function(d){return (d.name == newVal);})
                        .transition()
                        .duration(500)
                        .style("opacity",1);
                    d3.select("#d3Canvas"+scope.num).selectAll(".city").filter(function(d){return (d.name != scope.roomType)})
                        .selectAll("g")
                        .transition()
                        .duration(800)
                        .style("opacity",0);
                    d3.select("#d3Canvas"+scope.num).selectAll(".city").filter(function(d){return (d.name == scope.roomType)})
                        .selectAll("g")
                        .transition()
                        .duration(800)
                        .style("opacity",1);
                }

            });

        }
    }
});
