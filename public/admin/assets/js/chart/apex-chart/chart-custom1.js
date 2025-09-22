//bar chart
var options = {
    series: [{
            // name: "High - 2013",
            data: [35, 41, 62, 42, 13, 18, 29, 37, 36, 51, 32, 35]
        },

        {
            // name: "Low - 2013",
            data: [87, 57, 74, 99, 75, 38, 62, 47, 82, 56, 45, 47]
        }
    ],

    chart: {
        toolbar: {
            show: false
        }
    },

    chart: {
        height: 320,
    },

    legend: {
        show: false,
    },

    colors: ['#e22454', '#2483e2'],

    markers: {
        size: 1,
    },

    // grid: {
    //     show: false,
    //     xaxis: {
    //         lines: {
    //             show: false
    //         }
    //     },
    // },

    xaxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
        labels: {
            show: false,
        }
    },

    responsive: [{
            breakpoint: 1400,
            options: {
                chart: {
                    height: 300,
                },
            },
        },

        {
            breakpoint: 992,
            options: {
                chart: {
                    height: 210,
                    width: "100%",
                    offsetX: 0,
                },
            },
        },

        {
            breakpoint: 578,
            options: {
                chart: {
                    height: 200,
                    width: "105%",
                    offsetX: -20,
                    offsetY: 10,
                },
            },
        },

        {
            breakpoint: 430,
            options: {
                chart: {
                    width: "108%",
                },
            },
        },

        {
            breakpoint: 330,
            options: {
                chart: {
                    width: "112%",
                },
            },
        },
    ],
};

var chart = new ApexCharts(document.querySelector("#bar-chart-earning"), options);
chart.render();

// expenses cart


