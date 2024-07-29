let DashboardCtrl = {

    // Dados do grafico
    _dadosChart: [],

    // Inicializacao do controller
    init: () => {
        // Grafico das vendas
        DashboardCtrl.setupChartJs();
    },

    // Grafico das vendas para os ultimos 14 dias
    setupChartJs: () => {
        // Carrega o plugins do chart
        Plugins.loadChartJs(() => {
            // Salva os dados
            DashboardCtrl._dadosChart = window['dados_chart'];
            let label_chart = window['label_chart'] || "Valor vendido + Juros (R$)";
            // Cria o grafico
            let ctx = $("#line-chart-01")[0].getContext("2d");
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: DashboardCtrl._dadosChart['labels'],
                    datasets: [{
                        label: label_chart,
                        borderColor: "#5d5386",
                        pointBackgroundColor: "#5d5386",
                        pointHoverBorderColor: "#5d5386",
                        pointHoverBackgroundColor: "#5d5386",
                        pointBorderColor: "#fff",
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        fill: true,
                        backgroundColor: "rgba(93, 83, 134, 0.6)",
                        borderWidth: 3,
                        data: DashboardCtrl._dadosChart['data_set']
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    legend: {display: false},
                    scales: {yAxes: [{ticks: {beginAtZero: true}}]},
                    tooltips: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleFontSize: 14,
                        titleFontColor: '#fff',
                        bodyFontColor: '#fff',
                        caretSize: 12,
                        xPadding: 12,
                        displayColors: false,
                        yPadding: 12,
                        callbacks: {
                            title: (e) => {
                                let dados_data = DashboardCtrl._dadosChart['dados'][e[0]['index']];
                                return `${dados_data['semana']} - ${dados_data['dia']} de ${dados_data['mes']}`;
                            }
                        }
                    }
                }
            });
        });
    }
};
