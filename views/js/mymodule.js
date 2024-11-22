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
        // Hiding loader
        document.querySelector('.slider-loader').style.display = 'none';

        //Adds link to the button with all categories
        document.querySelector('.seemore-btn-slider').setAttribute('href', data.category_url);

        //Updates container with text
        document.querySelector('.glide__track').innerHTML = data.html;
    
        //Initializing slider
        const glide = new Glide('.glide', {
          type: 'carousel',  
          startAt: 0,      
          perView: 4,        
          autoplay: 3000     
        });
        glide.mount();

        // short description visibility function
        showShortDesc();
 
    })
    .catch(error => {
      console.error('Error:', error);
    });

    // Function to handle short description visibility
    function showShortDesc() {
      const allSlides = document.querySelectorAll('.glide__slide');
  
      allSlides.forEach((slide) => {
        slide.addEventListener('mouseover', function () {
           slide.querySelector('.short-desc').classList.remove('hidden');
        });
      
        // Hide short description on mouse leave
        slide.addEventListener('mouseleave', function () {
          slide.querySelector('.short-desc').classList.add('hidden');
        });

      })
    }


  });
  