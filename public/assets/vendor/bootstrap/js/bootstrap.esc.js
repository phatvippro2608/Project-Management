let ip111;
fetch('https://api.ipify.org?format=json')
    .then(response => response.json())
    .then(data => {
        ip111 = data.ip;
        console.log(ip111);
    })
    .catch(error => {
        console.error('Error fetching IP address:', error);
    });

function updateState(state){

}
