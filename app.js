var ctx = document.getElementById("myPieChart");
var myPieChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ["カロリー摂取量"],
        datasets: [{
            backgroundColor: [
                "#f37056"
            ],
            data: [87, 13]
        }]
    },
    options: {
        title: {
            display: true,

        }
    }
});