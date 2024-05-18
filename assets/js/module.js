import { enUS } from 'date-fns/locale';

const timestamps = document.querySelector('#timestamps')
const values = document.querySelector('#values')


const ctx = document.getElementById('moduleChart').getContext('2d');
const moduleChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: timestamps.value,
        datasets: [{
            label: 'Module Data',
            data: values.value,
            borderColor: 'rgba(75, 192, 192, 1)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            x: {
                type: 'time',
                time: {
                    unit: 'minute'
                },
                adapters: {
                    date: {
                        locale: enUS
                    }
                }
            },
            y: {
                beginAtZero: true
            }
        }
    }
});