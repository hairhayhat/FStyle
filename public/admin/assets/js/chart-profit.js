$(document).ready(function () {
    var options = {
        series: [{ name: 'Lợi nhuận', data: [] }],
        chart: { height: 320, type: 'bar' },
        plotOptions: { bar: { columnWidth: '40%' } },
        colors: ['#e22454'],
        dataLabels: { enabled: false },
        legend: { show: false },
        xaxis: { categories: [] },
        yaxis: {
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

    var chart = new ApexCharts(document.querySelector("#report-chart"), options);
    chart.render();

    let today = new Date().toISOString().split('T')[0];
    $("#fromDate").val(today);
    $("#toDate").val(today);

    function loadProfit(fromDate, toDate) {
        $.ajax({
            url: "/admin/dashboard/profit",
            method: "GET",
            data: { from_date: fromDate, to_date: toDate },
            success: function (res) {
                chart.updateOptions({
                    xaxis: { categories: res.labels }
                });

                chart.updateSeries([{
                    name: "Lợi nhuận",
                    data: res.profits
                }]);
            }
        });
    }

    loadProfit(today, today);

    $("#filterForm").on("submit", function (e) {
        e.preventDefault();
        let fromDate = $("#fromDate").val();
        let toDate = $("#toDate").val();

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
    });
});
