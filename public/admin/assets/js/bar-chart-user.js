$(document).ready(function () {
    let today = new Date().toISOString().split('T')[0];

    var loyalOptions = {
        series: [{ name: 'Số đơn hàng', data: [] }],
        chart: { height: 320, type: 'bar' },
        plotOptions: { bar: { columnWidth: '40%', horizontal: true } },
        colors: ['#4aa4d9'],
        dataLabels: { enabled: false },
        legend: { show: false },
        xaxis: { categories: [] },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + " đơn hàng";
                }
            }
        }
    };

    var loyalChart = new ApexCharts(document.querySelector("#loyal-customers-chart"), loyalOptions);
    loyalChart.render();

    var valuableOptions = {
        series: [{ name: 'Tổng chi tiêu', data: [] }],
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
        tooltip: {
            y: {
                formatter: function (val) {
                    return val.toLocaleString('vi-VN') + ' vnđ';
                }
            }
        }
    };

    var valuableChart = new ApexCharts(document.querySelector("#valuable-customers-chart"), valuableOptions);
    valuableChart.render();

    $("#fromDateForUser").val(today);
    $("#toDateForUser").val(today);

    function loadLoyalCustomers(fromDate, toDate) {
        $.ajax({
            url: "/admin/bar-chart/top-users-by-orders-count",
            method: "GET",
            data: { from_date: fromDate, to_date: toDate },
            success: function (res) {
                loyalChart.updateOptions({ xaxis: { categories: res.labels } });
                loyalChart.updateSeries([{ name: "Số đơn hàng", data: res.data }]);
            }
        });
    }

    function loadValuableCustomers(fromDate, toDate) {
        $.ajax({
            url: "/admin/bar-chart/top-users-by-spending",
            method: "GET",
            data: { from_date: fromDate, to_date: toDate },
            success: function (res) {
                valuableChart.updateOptions({ xaxis: { categories: res.labels } });
                valuableChart.updateSeries([{ name: "Tổng chi tiêu", data: res.data }]);
            }
        });
    }

    loadLoyalCustomers(today, today);
    loadValuableCustomers(today, today);

    $("#filterFormForUser").on("submit", function (e) {
        e.preventDefault();
        let fromDate = $("#fromDateForUser").val();
        let toDate = $("#toDateForUser").val();

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

        loadLoyalCustomers(fromDate, toDate);
        loadValuableCustomers(fromDate, toDate);
    });
});
