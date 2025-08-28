//bar chart
const chartData = monthsTotal.map((month, index) => {
    return {
        x: month,
        y: netRevenue[index],
        goals: [
            {
                name: "Expected",
                value: 1400,
                strokeWidth: 5,
                strokeColor: "#775DD0",
            },
        ],
    };
});

var options = {
    series: [
        {
            name: "Doanh thu thuần",
            data: chartData,
        },
    ],
    chart: {
        height: 320,
        type: "bar",
    },
    plotOptions: {
        bar: {
            columnWidth: "40%",
        },
    },
    colors: ["#e22454"],
    dataLabels: {
        enabled: false,
    },
    legend: {
        show: false,
    },

    yaxis: {
        labels: {
            formatter: function (val) {
                return val.toLocaleString("vi-VN");
            },
        },
        title: {
            text: "Doanh thu thuần",
        },
    },

    tooltip: {
        y: {
            formatter: function (val) {
                return val.toLocaleString("vi-VN") + " ₫";
            },
        },
        x: {
            formatter: function (val) {
                return "Tháng " + val;
            },
        },
    },
};

var chart = new ApexCharts(
    document.querySelector("#bar-chart-earning"),
    options
);
chart.render();


var options = {
    series: [
        {
            name: "Doanh thu trung bình mỗi đơn",
            type: "line",
            data: aovData,
        },
        {
            name: "Số đơn hàng",
            type: "line",
            data: ordersData,
        },
    ],

    chart: {
        height: 320,
        type: "line",
        toolbar: { show: false },
    },

    colors: ["#e22454", "#2483e2"],

    stroke: {
        width: 3,
        curve: "smooth",
    },

    markers: { size: 4 },

    xaxis: {
        categories: months,
        title: { text: "Tháng" },
    },

    yaxis: [
        {
            title: { text: "Doanh thu trung bình mỗi đơn" },
            labels: {
                formatter: function (val) {
                    return val.toLocaleString("vi-VN");
                },
            },
        },
        {
            opposite: true,
            title: { text: "Số đơn hàng" },
            labels: {
                formatter: function (val) {
                    return val;
                },
            },
        },
    ],

    tooltip: {
        shared: true,
        intersect: false,
        y: [
            {
                formatter: function (val) {
                    return val.toLocaleString("vi-VN") + " ₫";
                },
            },
            {
                formatter: function (val) {
                    return val;
                },
            },
        ],
        x: {
            formatter: function (val) {
                return "Tháng " + val;
            }
        }
    },

    legend: {
        show: true,
        position: "top",
    },
};
var chart = new ApexCharts(document.querySelector("#report-chart"), options);
chart.render();
//so nguoi dung theo thang
var options = {
    series: [
        {
            name: "Người dùng",
            type: "line",
            data: usersData,
        },
    ],

    chart: {
        height: 320,
        type: "line",
        toolbar: { show: false },
    },

    colors: ["#2483e2"], // đỏ AOV, xanh Orders

    stroke: {
        width: 3,
        curve: "smooth",
    },

    markers: { size: 4 },

    xaxis: {
        categories: monthsUser,
        title: { text: "Tháng" },
    },

    // Trục Y kép
    yaxis: [
        {
            title: { text: "Số người dùng" },
        },
    ],

    legend: {
        show: true,
        position: "top",
    },
};
var chart = new ApexCharts(document.querySelector("#bar-chart-user"), options);
chart.render();

var options = {
    series: totalPercen,
    labels: [
        "COD",
        "VNPay",
    ],
    chart: {
        height: 320,
        type: "donut",
    },

    legend: {
        fontSize: "12px",
        position: "bottom",
        offsetX: 1,
        offsetY: -1,

        markers: {
            width: 10,
            height: 10,
        },

        itemMargin: {
            vertical: 2,
        },
    },

    colors: ["#4aa4d9", "#ef3f3e", "#9e65c2", "#6670bd", "#FF9800"],

    plotOptions: {
        pie: {
            startAngle: -90,
            endAngle: 270,
        },
    },

    dataLabels: {
        enabled: false,
    },

    responsive: [
        {
            breakpoint: 1835,
            options: {
                chart: {
                    height: 245,
                },

                legend: {
                    position: "bottom",

                    itemMargin: {
                        horizontal: 5,
                        vertical: 1,
                    },
                },
            },
        },

        {
            breakpoint: 1388,
            options: {
                chart: {
                    height: 330,
                },

                legend: {
                    position: "bottom",
                },
            },
        },

        {
            breakpoint: 1275,
            options: {
                chart: {
                    height: 300,
                },

                legend: {
                    position: "bottom",
                },
            },
        },

        {
            breakpoint: 1158,
            options: {
                chart: {
                    height: 280,
                },

                legend: {
                    fontSize: "10px",
                    position: "bottom",
                    offsetY: 10,
                },
            },
        },

        {
            theme: {
                mode: "dark",
                palette: "palette1",
                monochrome: {
                    enabled: true,
                    color: "#255aee",
                    shadeTo: "dark",
                    shadeIntensity: 0.65,
                },
            },
        },

        {
            breakpoint: 598,
            options: {
                chart: {
                    height: 280,
                },

                legend: {
                    fontSize: "12px",
                    position: "bottom",
                    offsetX: 5,
                    offsetY: -5,

                    markers: {
                        width: 10,
                        height: 10,
                    },

                    itemMargin: {
                        vertical: 1,
                    },
                },
            },
        },
    ],
};

var chart = new ApexCharts(
    document.querySelector("#pie-chart-visitors"),
    options
);
chart.render();
