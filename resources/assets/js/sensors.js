
/*
 * Common stuff for all sensors
 */

require('chart.js');
require('chartjs-plugin-annotation');

window.chartColors = {
    red: 'rgba(255, 99, 132, 0.2)',
    orange: 'rgba(255, 165, 0, 0.3)',
    yellow: 'rgba(255, 205, 86, 0.2)',
    green: 'rgba(0, 178, 0, 0.3)',
    blue: 'rgba(54, 162, 235, 0.2)',
    purple: 'rgba(153, 102, 255, 0.2)',
    grey: 'rgba(201, 203, 207, 0.2)'
};

window.colorNames = Object.keys(window.chartColors);


/*
 * Ifconfig
 */

window.monitorIfconfigChart = function(element) {
    var ctx = element.getContext('2d');
    var config = {
        type: 'line',
        data: {
            datasets: []
        },
        options: {
            legend: {
                display: true,
            },
            scales: {
                xAxes: [{
                    type: 'time',
                    display: true,
                    scaleLabel: {
                            display: true,
                            labelString: 'Time'
                    }
                }],
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    },
                    scaleLabel: {
                            display: true,
                            labelString: '[Kbits / sec]'
                    }
                }]
            },
            annotation: {
                annotations: []
            }
        }
    };
    window.ifconfigChart = new Chart(ctx, config);
    if (typeof window.monitorURL === 'undefined') {
        window.monitorURL = "https://monitor.web-d.be";
    }
    var api_url = window.monitorURL + "/api/sensor/"
            + window.monitorServerID + "/" + window.monitorServerToken
            + "/ifconfig";
    $.getJSON(api_url, function(data) {

        $.each(data, function(key, dataset){
            // console.log(dataset);
            var new_color_name = window.colorNames[key];
            var new_color = window.chartColors[new_color_name];
            var new_dataset = {
                label: dataset.name,
                backgroundColor: "rgba(255, 255, 255, 0.0)", // transparent
                borderColor: new_color,
                data: dataset.points
            };
            config.data.datasets.push(new_dataset);
        });

        window.ifconfigChart.update();
    });
};

/*
 * CPU load
 */

window.monitorLoadChart = function(element) {

    var ctx = element.getContext('2d');
    var config = {
        type: 'line',
        data: {
            datasets: []
        },
        options: {
            legend: {
                display: false,
            },
            scales: {
                xAxes: [{
                    type: 'time',
                    display: true,
                    scaleLabel: {
                            display: true,
                            labelString: 'Time'
                    }
                }],
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    },
                    scaleLabel: {
                        display: true,
                        labelString: 'Load'
                    }
                }]
            },
            annotation: {
                annotations: []
            }
        }
    };

    window.loadChart = new Chart(ctx, config);
    if (typeof window.monitorURL === 'undefined') {
        window.monitorURL = "https://monitor.web-d.be";
    }
    var load_url = window.monitorURL + "/api/sensor/"
            + window.monitorServerID + "/" + window.monitorServerToken
            + "/load";
    $.getJSON(load_url, function( data ) {
        var new_dataset = {
                label: 'Load',
                data: data.points
            };
        config.data.datasets.push(new_dataset);

        var new_annotation = {
                drawTime: 'afterDraw', // overrides annotation.drawTime if set
                type: 'line',
                mode: 'horizontal',
                scaleID: 'y-axis-0',
                value: data.max,
                borderColor: 'red',
                borderWidth: 2
        };
        config.options.annotation.annotations.push(new_annotation);
        window.loadChart.update();
    });
};

/*
 * Memory
 */

window.monitorMemChart = function(element) {
    var ctx = element.getContext('2d');
    var config = {
        type: 'line',
        data: {
            datasets: []
        },
        options: {
            legend: {
                display: true,
            },
            scales: {
                xAxes: [{
                    type: 'time',
                    display: true,
                    scaleLabel: {
                            display: true,
                            labelString: 'Time'
                    }
                }],
                yAxes: [{
                    stacked: true,
                    ticks: {
                        beginAtZero:true
                    },
                    scaleLabel: {
                            display: true,
                            labelString: 'Memory [MB]'
                    }
                }]
            },
            annotation: {
                annotations: []
            }
        }
    };
    window.memChart = new Chart(ctx, config);
    if (typeof window.monitorURL === 'undefined') {
        window.monitorURL = "https://monitor.web-d.be";
    }
    var meminfo_url = window.monitorURL + "/api/sensor/"
            + window.monitorServerID + "/" + window.monitorServerToken
            + "/memory";
    $.getJSON(meminfo_url, function(data) {
        var new_dataset = {
                label: 'Used',
                backgroundColor: 'rgba(0, 178, 0, 0.3)',
                borderColor: 'rgba(0, 178, 0, 0.3)',
                data: data.used
            };
        config.data.datasets.push(new_dataset);

        new_dataset = {
                label: 'Cached',
                backgroundColor: 'rgba(255, 165, 0, 0.3)',
                borderColor: 'rgba(255, 165, 0, 0.3)',
                data: data.cached
            };
        config.data.datasets.push(new_dataset);

        var new_annotation = {
                drawTime: 'afterDraw', // overrides annotation.drawTime if set
                type: 'line',
                mode: 'horizontal',
                scaleID: 'y-axis-0',
                value: data.total,
                borderColor: 'red',
                borderWidth: 2
        };
        config.options.annotation.annotations.push(new_annotation);

        window.memChart.update();
    });
};

/*
 * Disk evolution graphing
 */
window.loadDiskEvolutionChart = function(element) {
    var ctx = element.getContext('2d');
    var config = {
        type: 'line',
        data: {
            datasets: []
        },
        options: {
            legend: {
                display: true,
            },
            scales: {
                xAxes: [{
                    type: 'time',
                    display: true,
                    scaleLabel: {
                            display: true,
                            labelString: 'Time'
                    }
                }],
                yAxes: [{
                    ticks: {
                        beginAtZero:true,
                        labelString: 'Usage [%]'
                    },
                }]
            },
            annotation: {
                annotations: []
            }
        }
    };
    window.diskEvolutionChart = new Chart(ctx, config);
    if (typeof window.monitorURL === 'undefined') {
        window.monitorURL = "https://monitor.web-d.be";
    }
    var api_url = window.monitorURL + "/api/sensor/"
            + window.monitorServerID + "/" + window.monitorServerToken
            + "/diskevolution";
    $.getJSON(api_url, function(data) {

        $.each(data, function(key, dataset){
            // console.log(dataset);
            var new_color_name = window.colorNames[key];
            var new_color = window.chartColors[new_color_name];
            var new_dataset = {
                label: dataset.name,
                backgroundColor: "rgba(255, 255, 255, 0.0)", // transparent
                borderColor: new_color,
                data: dataset.points
            };
            config.data.datasets.push(new_dataset);
        });

        window.diskEvolutionChart.update();
    });
};


/*
 * Netstat
 */

window.monitorNetstatChart = function(element) {
    var ctx = element.getContext('2d');
    var config = {
        type: 'line',
        data: {
            datasets: []
        },
        options: {
            legend: {
                display: true,
            },
            scales: {
                xAxes: [{
                    type: 'time',
                    display: true,
                    scaleLabel: {
                            display: true,
                            labelString: 'Time'
                    }
                }],
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    },
                    scaleLabel: {
                            display: true,
                            labelString: '%'
                    }
                }]
            },
            annotation: {
                annotations: []
            }
        }
    };
    window.netstatChart = new Chart(ctx, config);
    if (typeof window.monitorURL === 'undefined') {
        window.monitorURL = "https://monitor.web-d.be";
    }
    var api_url = window.monitorURL + "/api/sensor/"
            + window.monitorServerID + "/" + window.monitorServerToken
            + "/netstat";
    $.getJSON(api_url, function(data) {

        $.each(data, function(key, dataset){
            // console.log(dataset);
            var new_color_name = window.colorNames[key];
            var new_color = window.chartColors[new_color_name];
            var new_dataset = {
                label: dataset.name,
                backgroundColor: "rgba(255, 255, 255, 0.0)", // transparent
                borderColor: new_color,
                data: dataset.points
            };
            config.data.datasets.push(new_dataset);
        });

        window.netstatChart.update();
    });
};
