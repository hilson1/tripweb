<section class="hero-bg position-block min-vh-100  min-vw-100 d-flex align-items-center px-md-5 px-3"
  style="margin-top:50px">
  <div class="hero-containers">
    <div class="hero-content w-50">
      <h1 class="ml-4 display-3 font-weight-bold ">Escape Your<br>Comfort Zone.</h1>
      <p class="ml-4 h4 font-weight-normal">Grab your stuff and let's get lost.</p>
    </div>

    <div class="position-absolute bg-white p-4 rounded"
      style="right: 5%; top: 50%; transform: translateY(-50%); width: 350px;">
      <div class="form-group">
        <select class="form-control border py-2 " name="activity">
          <option value="">Activity</option>
          <option value="hiking">Hiking</option>
          <option value="swimming">Swimming</option>
          <option value="sightseeing">Sightseeing</option>
        </select>
      </div>

      <div class="form-group">
        <div class="dropdown">
          <button class="dropdown-button btn btn-block text-left border py-2" id="dropdownButton"
            onclick="toggleDropdown()">
            Select Price Range ▼
          </button>
          <div class="dropdown-content" id="dropdownContent">
            <div class="price-range-container p-3">
              <div class="slider-container">
                <input type="range" id="minPrice" class="slider" min="0" max="1000" value="100">
                <input type="range" id="maxPrice" class="slider" min="0" max="1000" value="500">
              </div>
              <div class="price-inputs">
                <label for="minPriceInput">Min Price:</label>
                <input type="number" id="minPriceInput" value="100">
                <label for="maxPriceInput">Max Price:</label>
                <input type="number" id="maxPriceInput" value="500">
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- <select class="form-control border py-2" id="priceSelect" onclick="togglePriceRange()">
                    <option value="">💰 $30 - $2,300</option>
                </select>

                <div id="priceRangeContainer" class="collapse mt-2">
                    <input type="range" class="custom-range" id="priceRange" 
                           min="30" max="2300" step="10"
                           oninput="updatePriceLabel(this.value)">
                    <div class="d-flex justify-content-between">
                        <span id="minPrice">$30</span>
                        <span id="maxPrice">$2,300</span>
                    </div>
                    <div class="text-center mt-2">
                        <span id="currentPrice">$30 - $2,300</span>
                    </div>
                </div> -->


      <!-- 
                <select class="form-control border py-2" name="price-range">
                    <option value="">$30 - $2,300</option>
                    <option value="budget">$30 - $500</option>
                    <option value="mid">$501 - $1,500</option>
                    <option value="luxury">$1,501 - $2,300</option>
                </select> -->


      <div class="form-group">
        <select class="form-control border py-2" name="destination">
          <option value="">Destination</option>
          <option value="europe">Europe</option>
          <option value="asia">Asia</option>
          <option value="americas">Americas</option>
        </select>
      </div>

      <div class="form-group">
        <select class="form-control border py-2" name="duration">
          <option value="">0 Days - 11 Days</option>
          <option value="short">1-3 Days</option>
          <option value="medium">4-7 Days</option>
          <option value="long">8-11 Days</option>
        </select>
      </div>

      <div class="form-group">
        <select class="form-control border py-2" name="date">
          <option value="jan">January, 2025</option>
          <option value="feb">February, 2025</option>
          <option value="mar">March, 2025</option>
          <option value="apr">April, 2025</option>
          <option value="may">May, 2025</option>
          <option value="jun">June, 2025</option>
          <option value="jul">July, 2025</option>
          <option value="aug">August, 2025</option>
          <option value="sep">September, 2025</option>
          <option value="oct">October, 2025</option>
          <option value="nov">November, 2025</option>
          <option value="dec">December, 2025</option>
        </select>
      </div>

      <button class="search-btn btn btn-block text-white py-3 border-0 font-weight-medium">Search</button>
    </div>
  </div>
</section>

<script>
  function toggleDropdown() {
    const dropdownContent = document.getElementById('dropdownContent');
    const dropdownButton = document.getElementById('dropdownButton');

    if (dropdownContent.style.display === 'block') {
      dropdownContent.style.display = 'none';
      // Update button text with selected price range
      const minPrice = minPriceInput.value;
      const maxPrice = maxPriceInput.value;
      dropdownButton.textContent = `$${minPrice} - $${maxPrice} ▼`;
    } else {
      dropdownContent.style.display = 'block';
    }
  }

  // Slider functionality
  const minPriceSlider = document.getElementById('minPrice');
  const maxPriceSlider = document.getElementById('maxPrice');
  const minPriceInput = document.getElementById('minPriceInput');
  const maxPriceInput = document.getElementById('maxPriceInput');

  minPriceSlider.addEventListener('input', () => {
    minPriceInput.value = minPriceSlider.value;
    if (parseInt(minPriceSlider.value) > parseInt(maxPriceSlider.value)) {
      maxPriceSlider.value = minPriceSlider.value;
      maxPriceInput.value = minPriceSlider.value;
    }
  });

  maxPriceSlider.addEventListener('input', () => {
    maxPriceInput.value = maxPriceSlider.value;
    if (parseInt(maxPriceSlider.value) < parseInt(minPriceSlider.value)) {
      minPriceSlider.value = maxPriceSlider.value;
      minPriceInput.value = maxPriceSlider.value;
    }
  });

  minPriceInput.addEventListener('input', () => {
    minPriceSlider.value = minPriceInput.value;
    if (parseInt(minPriceInput.value) > parseInt(maxPriceInput.value)) {
      maxPriceInput.value = minPriceInput.value;
      maxPriceSlider.value = minPriceInput.value;
    }
  });

  maxPriceInput.addEventListener('input', () => {
    maxPriceSlider.value = maxPriceInput.value;
    if (parseInt(maxPriceInput.value) < parseInt(minPriceInput.value)) {
      minPriceInput.value = maxPriceInput.value;
      minPriceSlider.value = maxPriceInput.value;
    }
  });
</script>