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
        colors: ['#4aa4d9', '#ef3f3e', '#9e65c2', '#6670bd', '#FF9800', '#33b2df', '#546E7A'],
        plotOptions: {
            pie: {
                startAngle: -90,
                endAngle: 270
            }
        },
        dataLabels: {
            enabled: false
        },
        responsive: [
            {
                breakpoint: 1835,
                options: {
                    chart: { height: 245 },
                    legend: {
                        position: 'bottom',
                        itemMargin: { horizontal: 5, vertical: 1 },
                    },
                },
            },
            {
                breakpoint: 1388,
                options: {
                    chart: { height: 330 },
                    legend: { position: 'bottom' },
                },
            },
            {
                breakpoint: 1275,
                options: {
                    chart: { height: 300 },
                    legend: { position: 'bottom' },
                },
            },
            {
                breakpoint: 1158,
                options: {
                    chart: { height: 280 },
                    legend: {
                        fontSize: '10px',
                        position: 'bottom',
                        offsetY: 10,
                    },
                },
            },
            {
                theme: {
                    mode: 'dark',
                    palette: 'palette1',
                    monochrome: {
                        enabled: true,
                        color: '#255aee',
                        shadeTo: 'dark',
                        shadeIntensity: 0.65
                    },
                },
            },
            {
                breakpoint: 598,
                options: {
                    chart: { height: 280 },
                    legend: {
                        fontSize: '12px',
                        position: 'bottom',
                        offsetX: 5,
                        offsetY: -5,
                        markers: { width: 10, height: 10 },
                        itemMargin: { vertical: 1 },
                    },
                },
            },
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
