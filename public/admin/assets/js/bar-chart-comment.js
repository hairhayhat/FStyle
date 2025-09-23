$(document).ready(function () {
    let today = new Date().toISOString().split('T')[0];

    var ratingOptions = {
        series: [{
            name: 'Số lượng đánh giá',
            data: []
        }],
        chart: {
            height: 320,
            type: 'bar'
        },
        plotOptions: {
            bar: {
                columnWidth: '40%',
                horizontal: true
            }
        },
        colors: ['#e22454'],
        dataLabels: { enabled: false },
        legend: { show: false },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + " lượt đánh giá";
                }
            }
        }
    };

    var ratingChart = new ApexCharts(document.querySelector("#comment-by-rating-chart"), ratingOptions);
    ratingChart.render();


    var ratingRateOptions = {
        series: [],
        labels: ["Đã đánh giá", "Chưa đánh giá"],
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
        colors: ['#4aa4d9', '#e22454'],
        plotOptions: {
            pie: {
                startAngle: -90,
                endAngle: 270,
                donut: {
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Tỷ lệ',
                            formatter: function (w) {
                                let total = w.globals.series.reduce((a, b) => a + b, 0);
                                return total.toFixed(0) + '%';
                            }
                        }
                    }
                }
            }
        },
        dataLabels: {
            enabled: true,
            formatter: function (val) {
                return val.toFixed(2) + "%";
            }
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val.toFixed(2) + "%";
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

    var ratingRateChart = new ApexCharts(document.querySelector("#rating-rate-chart"), ratingRateOptions);
    ratingRateChart.render();

    var topRatingOptions = {
        series: [{
            name: 'Điểm trung bình',
            data: []
        }],
        chart: {
            type: 'bar',
            height: 320
        },
        plotOptions: {
            bar: {
                horizontal: true,
                columnWidth: '50%',
            }
        },
        colors: ['#4aa4d9'],
        dataLabels: {
            enabled: true,
            formatter: function (val) {
                return val.toFixed(2);
            }
        },
        xaxis: {
            categories: [],
            title: {
                text: 'Điểm trung bình'
            }
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val.toFixed(2) + " ★";
                }
            }
        }
    };

    var topRatingChart = new ApexCharts(document.querySelector("#top-tier-rating-chart"), topRatingOptions);
    topRatingChart.render();

    $("#fromDateForComment").val(today);
    $("#toDateForComment").val(today);

    function loadComment(fromDate, toDate) {
        $.ajax({
            url: "/admin/bar-chart/comment",
            method: "GET",
            data: { from_date: fromDate, to_date: toDate },
            success: function (res) {
                ratingChart.updateOptions({ xaxis: { categories: res.labels } });
                ratingChart.updateSeries([{ name: "Số đánh giá", data: res.data }]);
            }
        });
    }

    function loadRatingRate(fromDate, toDate) {
        $.ajax({
            url: "/admin/bar-chart/rating-rate",
            method: "GET",
            data: { from_date: fromDate, to_date: toDate },
            success: function (res) {
                ratingRateChart.updateOptions({
                    labels: res.labels
                });
                ratingRateChart.updateSeries(res.data);
            }
        });
    }

    function loadTopRating(fromDate, toDate) {
        $.ajax({
            url: "/admin/bar-chart/top-rating-products",
            method: "GET",
            data: { from_date: fromDate, to_date: toDate },
            success: function (res) {
                topRatingChart.updateOptions({ xaxis: { categories: res.labels } });
                topRatingChart.updateSeries([{ name: "Điểm trung bình", data: res.data }]);
            }
        });
    }

    loadComment(today, today);
    loadRatingRate(today, today);
    loadTopRating(today, today);

    $("#filterFormForComment").on("submit", function (e) {
        e.preventDefault();
        let fromDate = $("#fromDateForComment").val();
        let toDate = $("#toDateForComment").val();

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

        loadComment(fromDate, toDate);
        loadRatingRate(fromDate, toDate);
        loadTopRating(fromDate, toDate);
    });
});
