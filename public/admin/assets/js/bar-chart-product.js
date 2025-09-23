$(document).ready(function () {
    var profitOptions = {
        series: [{ name: 'Lợi nhuận', data: [] }],
        chart: { height: 320, type: 'bar' },
        plotOptions: { bar: { columnWidth: '40%', horizontal: true } },
        colors: ['#e22454'],
        dataLabels: { enabled: false },
        legend: { show: false },
        xaxis: {
            labels: {
                formatter: function (val) {
                    return val.toLocaleString('vi-VN') + ' vnđ';
                }
            }
        },
        yaxis: { categories: [] },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val.toLocaleString('vi-VN') + ' vnđ';
                }
            }
        }
    };

    var profitChart = new ApexCharts(document.querySelector("#profit-by-product-chart"), profitOptions);
    profitChart.render();

    var performanceOptions = {
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

    var performanceChart = new ApexCharts(document.querySelector("#sales-performance-chart"), performanceOptions);
    performanceChart.render();

    let todayDate = new Date();
    let fromDateDefault = new Date();
    fromDateDefault.setDate(todayDate.getDate() - 30);

    let todayStr = todayDate.toISOString().split('T')[0];
    let fromDateStr = fromDateDefault.toISOString().split('T')[0];

    $("#fromDateForProduct").val(fromDateStr);
    $("#toDateForProduct").val(todayStr);


    function loadProfit(fromDate, toDate) {
        $.ajax({
            url: "/admin/bar-chart/product",
            method: "GET",
            data: { from_date: fromDate, to_date: toDate },
            success: function (res) {
                console.log(fromDate, toDate, res);
                profitChart.updateOptions({ xaxis: { categories: res.labels } });
                profitChart.updateSeries([{ name: "Lợi nhuận", data: res.data }]);
            }
        });
    }

    function loadPerformance(fromDate, toDate) {
        $.ajax({
            url: "/admin/bar-chart/sales-performance",
            method: "GET",
            data: { from_date: fromDate, to_date: toDate },
            success: function (res) {
                console.log(fromDate, toDate, res);
                performanceChart.updateOptions({
                    labels: res.labels
                });
                performanceChart.updateSeries(res.data);
            }
        });
    }

    loadProfit(fromDateStr, todayStr);
    loadPerformance(fromDateStr, todayStr);

    $("#filterFormForProduct").on("submit", function (e) {
        e.preventDefault();
        let fromDate = $("#fromDateForProduct").val();
        let toDate = $("#toDateForProduct").val();

        if (fromDate && toDate && fromDate > toDate) {
            Swal.fire({
                icon: 'error',
                text: 'Ngày bắt đầu không được lớn hơn ngày kết thúc!',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
            return;
        }

        loadProfit(fromDate, toDate);
        loadPerformance(fromDate, toDate);
    });
});
