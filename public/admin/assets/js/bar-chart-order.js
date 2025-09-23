$(document).ready(function () {
    let today = new Date().toISOString().split('T')[0];

    var orderChartOptions = {
        series: [{
            name: 'Giá trị đơn hàng trung bình',
            data: []
        }],
        chart: {
            height: 320,
            type: 'bar'
        },
        plotOptions: {
            bar: {
                columnWidth: '50%',
                horizontal: false
            }
        },
        colors: ['#e22454'],
        dataLabels: { enabled: false },
        legend: { show: false },
        tooltip: {
            y: {
                formatter: function (val) {
                    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(val);
                }
            }
        }
    };
    var orderChart = new ApexCharts(document.querySelector("#average-order-value-chart"), orderChartOptions);
    orderChart.render();

    var totalOrderChartOptions = {
        series: [
            { name: 'Đơn thành công', data: [] },
            { name: 'Đơn hủy', data: [] }
        ],
        chart: {
            type: 'bar',
            height: 320,
            stacked: false
        },
        plotOptions: {
            bar: { columnWidth: '50%', horizontal: false }
        },
        colors: ['#e22454', '#3b82f6'],
        dataLabels: { enabled: false },
        legend: { position: 'top' },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + " đơn";
                }
            }
        },
        xaxis: { categories: [] }
    };
    var totalOrderChart = new ApexCharts(document.querySelector("#total-order-chart"), totalOrderChartOptions);
    totalOrderChart.render();

    var paymentDonutOptions = {
        series: [],
        chart: { type: 'donut', height: 320 },
        labels: [],
        colors: ['#e22454', '#3b82f6'],
        legend: { position: 'bottom' },
        tooltip: {
            y: { formatter: val => val + " %" }
        }
    };
    var paymentDonutChart = new ApexCharts(document.querySelector("#payment-donut-chart"), paymentDonutOptions);
    paymentDonutChart.render();

    $("#fromDateForOrder").val(today);
    $("#toDateForOrder").val(today);

    function loadAverageOrderValue(fromDate, toDate) {
        $.ajax({
            url: "/admin/bar-chart/average-order-value",
            method: "GET",
            data: { from_date: fromDate, to_date: toDate },
            success: function (res) {
                orderChart.updateOptions({ xaxis: { categories: res.labels } });
                orderChart.updateSeries([{ name: "Giá trị đơn hàng trung bình", data: res.avg_order_value }]);
            }
        });
    }

    function loadTotalOrders(fromDate, toDate) {
        $.ajax({
            url: "/admin/bar-chart/done-and-cancelled-orders",
            method: "GET",
            data: { from_date: fromDate, to_date: toDate },
            success: function (res) {
                totalOrderChart.updateOptions({ xaxis: { categories: res.labels } });
                totalOrderChart.updateSeries([
                    { name: "Đơn thành công", data: res.done_orders },
                    { name: "Đơn hủy", data: res.cancel_orders }
                ]);
            }
        });
    }

function loadPaymentUsage(fromDate, toDate) {
    $.ajax({
        url: "/admin/bar-chart/payment-method-distribution",
        method: "GET",
        data: { from_date: fromDate, to_date: toDate },
        success: function (res) {

            let labels = res.series.map(s => s.name);
            let totalPerMethod = res.series.map(s => s.data.reduce((a, b) => a + b, 0));
            let grandTotal = totalPerMethod.reduce((a, b) => a + b, 0);

            let series = totalPerMethod.map(count => {
                return ((count / grandTotal) * 100).toFixed(2);
            }).map(Number);

            paymentDonutChart.updateOptions({ labels: labels });
            paymentDonutChart.updateSeries(series);
        }
    });
}


    loadAverageOrderValue(today, today);
    loadTotalOrders(today, today);
    loadPaymentUsage(today, today);

    $("#filterFormForOrder").on("submit", function (e) {
        e.preventDefault();
        let fromDate = $("#fromDateForOrder").val();
        let toDate = $("#toDateForOrder").val();

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

        loadAverageOrderValue(fromDate, toDate);
        loadTotalOrders(fromDate, toDate);
        loadPaymentUsage(fromDate, toDate);
    });
});
