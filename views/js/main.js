document.addEventListener("DOMContentLoaded", function () {
    // URL to the data controller
    const url = document.querySelector('.sm-text-module').dataset.url;

    fetch(url , {
      method: 'GET', 
      headers: {
        'Content-Type': 'application/json'
      }
    })
    .then(response => {
      if (!response.ok) {
        throw new Error('Network error');
      }
      return response.json(); 
    })
    .then(data => {
        //Updates container with text
        document.querySelector('.sm-text-container').innerHTML = data.data;
    })
    .catch(error => {
      console.error('Error:', error);
    });
  });
  