// const axios = require('axios/dist/browser/axios.cjs');

document.getElementById('submit').onclick = async function () {
    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;
    var token = null

    await axios.post('/login', {
        username: username,
        password: password
      })
      .then(function (response) {
        if (response.status === 200) {
            token = response.data;
            response.redirect()
        }
      })
      .catch(function (error) {
        console.log(error);
      });

    await axios.get('/', {'token': token})
      .then(function (response) {
        console.log(response);
      })
      .catch(function (error) {
        console.log(error);
      });
};