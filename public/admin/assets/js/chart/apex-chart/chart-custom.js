var options = {
    series: [{
        name: 'Thông báo',
        data: notifyData
    }],

    chart: {
        type: 'area',
        // stacked: false,
        height: 320,
    },

    colors: ['#e22454'],

    xaxis: {
        categories: monthsNotify,
        title: { text: "Tháng" },
    },


    stroke: {
        width: 3,
        curve: 'smooth'
    },
};

var chart = new ApexCharts(document.querySelector("#employ-salary"), options);
chart.render();

//sales purchase return cart
var options = {
    series: [{
        name: 'Đơn hàng thành công',
        data: deliveryData
    }, {
        name: 'Đơn hàng hủy',
        data: cancelData
    }],
    chart: {
        height: 350,
        type: 'area'
    },
    dataLabels: {
        enabled: false,
    },
    stroke: {
        curve: 'straight',
        width: [3, 3]
    },

    colors: ['#e22454', '#2483e2', '#e2c924'],

    xaxis: {
        type: 'Tháng',
        categories: monthsDelivery,
    },

    legend: {
        show: false,
    },

    tooltip: {
        show: false,
    },
};

var chart = new ApexCharts(document.querySelector("#sales-purchase-return-cart"), options);
chart.render();
