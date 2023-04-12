import 'chartist/dist/chartist.min'
import 'chartist-plugin-legend/chartist-plugin-legend'

$(document).ready(() => {
    if ($('#adoptionChart').length != 0) {
        let optionsAdoptionChart = {
            fullWidth: true,
            stackBars: true,
            low: 0,
            chartPadding: {
                top: 20,
                right: 25,
                bottom: 0,
                left: 0
            },
            axisY: {
                labelInterpolationFnc: function(value) {
                    return ( value % 1 === 0 ) ? value : '';
                }
            },
            plugins: [
                Chartist.plugins.legend()
            ]
        };

        let adoptionsChart = new Chartist.Line('#adoptionChart', dataAdoption, optionsAdoptionChart);

        md.startAnimationForLineChart(adoptionsChart);
    }

    if ($('#deadTreeChart').length != 0) {
        let optionsDeadTreeChart = {
            fullWidth: true,
            stackBars: true,
            low: 0,
            chartPadding: {
                top: 20,
                right: 25,
                bottom: 0,
                left: 0
            },
            axisY: {
                labelInterpolationFnc: function(value) {
                    return ( value % 1 === 0 ) ? value : '';
                }
            },
            plugins: [
                Chartist.plugins.legend()
            ]
        };

        let deadTreeChart = new Chartist.Line('#deadTreeChart', dataDeadTree, optionsDeadTreeChart);

        md.startAnimationForLineChart(deadTreeChart);
    }

    if ($('#statusTreeChart').length != 0)
    {
        let optionsStatusTreeChart = {
            donut: true,
            donutWidth: 100,
            donutSolid: true,
            startAngle: 270,
            showLabel: true,
        };

        new Chartist.Pie('#statusTreeChart', dataStatusTree, optionsStatusTreeChart);
    }

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        let parent = $(e.target).parents('.tab-greenhouse');

        if (parent.length)
        {
            let target = $(e.target).attr("href"); // activated tab
            let link = target.split('-');

            let data = null;

            if (link[0] === '#linkTable')
            {
                data = eval("data" + link[1] + "All");
                link[2] = 'All';
                $("a[href='#link-" + link[1] + "-All']").tab('show');
                $("a[href='#linkTable-" + link[1] + "']").tab('show');
            }

            else if (link[1] !== undefined) {
                data = eval("data" + link[1] + link[2]);
            }

            if (data)
            {
                let sum = function(a, b) {
                    if (typeof a === "object") {
                        a = a.value;
                    }

                    if (typeof b === "object") {
                        b = b.value;
                    }

                    return a + b
                };

                new Chartist.Pie('#greenhouseSpaceChart' + link[1] + link[2], data, {
                    labelInterpolationFnc: function(value) {
                        let total = data.series.reduce(sum);

                        if (typeof total === "object")
                        {
                            total = total.value;
                        }

                        console.log('total', total);
                        console.log('value', value);
                        return Math.round(value / total * 100) + '%';
                    }
                });
            }
        }
    });
});
