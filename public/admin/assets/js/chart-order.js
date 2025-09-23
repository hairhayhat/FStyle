$(document).ready(function () {
    var options = {
        series: [],
        labels: [],
        chart: {
            width: "100%",
            height: 320,
            type: 'donut',
        },
        legend: {
            fontSize: '12px',
            position: 'bottom',
            offsetX: 1,
            offsetY: -1,
            markers: {
                width: 10,
                height: 10,
            },
            itemMargin: {
                vertical: 2
            },
        },
        colors: ['#4aa4d9', '#e22454', '#9e65c2', '#6670bd', '#FF9800'],
        plotOptions: {
            pie: {
                startAngle: -90,
                endAngle: 270,
                donut: {
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Tổng đơn',
                            formatter: function (w) {
                                let total = w.globals.series.reduce((a, b) => a + b, 0);
                                return total.toLocaleString('vi-VN');
                            }
                        },
                        value: {
                            formatter: function (val) {
                                return val.toLocaleString('vi-VN') + " đơn";
                            }
                        }
                    }
                }
            }
        },
        dataLabels: {
            enabled: true,
            formatter: function (val, opts) {
                let seriesIndex = opts.seriesIndex;
                let value = opts.w.globals.series[seriesIndex];
                return value.toLocaleString('vi-VN') + " đơn";
            }
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val.toLocaleString('vi-VN') + " đơn";
                }
            }
        },
        responsive: [
            {
                breakpoint: 768,
                options: {
                    chart: { height: 280 },
                    legend: { position: 'bottom' },
                },
            }
        ],
    };

    var orderChart = new ApexCharts(document.querySelector("#pie-chart-orders"), options);
    orderChart.render();

    function loadOrdersChart() {
        $.ajax({
            url: "/admin/dashboard/orders",
            method: "GET",
            success: function (res) {
                orderChart.updateOptions({
                    labels: res.labels
                });
                orderChart.updateSeries(res.data);
            }
        });
    }

    loadOrdersChart();

    window.Echo.channel('orders')
        .listen('UpdateOrderStatus', (e) => {
            loadOrdersChart();
        })
});
