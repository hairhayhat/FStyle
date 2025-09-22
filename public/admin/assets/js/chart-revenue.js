$(document).ready(function () {
    var options = {
        series: [
            { name: 'Doanh thu gộp', data: [] },
            { name: 'Doanh thu thuần', data: [] }
        ],
        chart: { height: 320, type: 'bar' },
        plotOptions: { bar: { columnWidth: '40%' } },
        colors: ['#2483e2', '#e22454'],
        dataLabels: { enabled: false },
        legend: {
            show: true,
            position: 'bottom'
        },
        xaxis: { categories: [] },
        yaxis: {
            labels: {
                formatter: function (val) {
                    return val.toLocaleString('vi-VN') + ' đ';
                }
            }
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val.toLocaleString('vi-VN') + ' đ';
                }
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#saler-summary"), options);
    chart.render();

    let today = new Date().toISOString().split('T')[0];
    $("#fromDateSummary").val(today);
    $("#toDateSummary").val(today);

    function loadRevenue(fromDate, toDate) {
        $.ajax({
            url: "/admin/dashboard/revenue",
            method: "GET",
            data: { from_date: fromDate, to_date: toDate },
            success: function (res) {
                chart.updateOptions({
                    xaxis: { categories: res.labels }
                });

                chart.updateSeries([
                    { name: "Doanh thu gộp", data: res.gross_revenues },
                    { name: "Doanh thu thuần", data: res.net_revenues }
                ]);
            }
        });
    }

    loadRevenue(today, today);

    $("#filterRevenueForm").on("submit", function (e) {
        e.preventDefault();
        let fromDate = $("#fromDateSummary").val();
        let toDate = $("#toDateSummary").val();

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

        loadRevenue(fromDate, toDate);
    });
});
