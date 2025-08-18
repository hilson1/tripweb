<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Escape Your Comfort Zone</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
  <link rel="stylesheet" href="style.css">
  <style>
    .hero-section {
      background-image: url("assets/img/hero.png");
      height: fit-content;
      background-position: center;
      background-repeat: no-repeat;
      background-size: cover;
      padding: 0px 20px 0px 20px;
    }

    .bg {
      height: 100%;
      margin: 0 auto;
      max-width: 1920px;
      position: relative;
      align-items: center;
    }

    .content {
      position: absolute;
      top: 50%;
      left: 10%;
      width: 50%;
      transform: translateY(-50%);
      color: white;
    }

    .content h1 {
      font-size: 3rem;
      font-weight: bold;
    }

    .content p {
      font-size: 1.5rem;
    }

    .search-box {
      position: absolute;
      top: 50%;
      right: 10%;
      transform: translateY(-50%);
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      width: 350px;
    }

    .search-box .form-select,
    .search-box .btn {
      margin-bottom: 10px;
    }

    .search-box .input-group-text {
      background: none;
      border: none;
    }

    @media (max-width: 768px) {
      .hero-section {
        height: max-content;
      }

      .content,
      .search-box {
        position: static;
        transform: none;
        width: 80%;
        margin-bottom: 20px;
      }

      .content {
        text-align: center;
      }

      .bg {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
      }
    }

    .fas {
      color: green;
    }
  </style>
</head>

<body>
  <div class="hell" style="height:1200px;"></div>
  <section class="hero-section">
    <div class="bg">
      <div class="content">
        <h1>Escape Your Comfort Zone.</h1>
        <p>Grab your stuff and let’s get lost.</p>
      </div>
      <div class="search-box">
        <form>
          <div class="mb-3 input-group">
            <span class="input-group-text"><i class="fas fa-walking"></i></span>
            <select class="form-select" aria-label="Activity">
              <option selected>Activity</option>
              <option value="1">Activity 1</option>
              <option value="2">Activity 2</option>
              <option value="3">Activity 3</option>
            </select>
          </div>
          <div class="mb-3 input-group">
            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
            <select class="form-select" aria-label="Price">
              <option selected>$0 - $0</option>
              <option value="1">$0 - $50</option>
              <option value="2">$50 - $100</option>
              <option value="3">$100 - $200</option>
            </select>
          </div>
          <div class="mb-3 input-group">
            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
            <select class="form-select" aria-label="Destination">
              <option selected>Destination</option>
              <option value="1">Destination 1</option>
              <option value="2">Destination 2</option>
              <option value="3">Destination 3</option>
            </select>
          </div>
          <div class="mb-3 input-group">
            <span class="input-group-text"><i class="fas fa-clock"></i></span>
            <select class="form-select" aria-label="Duration">
              <option selected>0 Days - 11 Days</option>
              <option value="1">1 Day - 3 Days</option>
              <option value="2">4 Days - 7 Days</option>
              <option value="3">8 Days - 11 Days</option>
            </select>
          </div>
          <div class="mb-3 input-group">
            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
            <select class="form-select" aria-label="Date">
              <option selected>Date</option>
              <option value="1">Date 1</option>
              <option value="2">Date 2</option>
              <option value="3">Date 3</option>
            </select>
          </div>
          <button type="submit" class="btn btn-warning w-100">Search</button>
        </form>
      </div>
    </div>
  </section>

</body>

</html>