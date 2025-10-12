<section class="footer-section">
  <div class="footer">
    <div class="footer-containers">
      <!-- Contact Info -->
      <div class="footer-column">
        <h3>Contact Info</h3>
        <p>Open from 10 AM to 8 PM everyday.</p>
        <p><i class="fas fa-phone-alt"></i> 01 2345 65486</p>
        <p><i class="fas fa-envelope"></i> info@thankyounepaltrip.com</p>
        <p><i class="fas fa-map-marker-alt"></i> Thamel, Kathmandu</p>
        <div class="footer-icons">
          <a href="#"><i class="fab fa-facebook-f"></i></a>
          <a href="#"><i class="fab fa-twitter"></i></a>
          <a href="#"><i class="fab fa-instagram"></i></a>
        </div>
      </div>

      <!-- Destinations -->
      <div class="footer-column">
        <h3>Destinations</h3>
        <p><a href="./destinations.php?destination-is=Kathmandu">Kathmandu</a></p>
        <p><a href="./destinations.php?destination-is=Pokhara">Pokhara</a></p>
        <p><a href="./destinations.php?destination-is=Chitwan">Chitwan</a></p>
        <p><a href="./destinations.php?destination-is=Mustang">Mustang</a></p>
        <p><a href="./destinations.php?destination-is=Lumbini">Lumbini</a></p>
      </div>

      <!-- Activities -->
      <div class="footer-column">
        <h3>Activities</h3>
        <p><a href="./activities.php?activity-is=City Tour">City Tour</a></p>
        <p><a href="./activities.php?activity-is=Trekking">Trekking</a></p>
        <p><a href="./activities.php?activity-is=Jungle Safari">Jungle Safari</a></p>
        <p><a href="./activities.php?activity-is=Hiking">Hiking</a></p>
        <p><a href="./activities.php?activity-is=Boating">Boating</a></p>
      </div>

      <!-- Trip Types -->
      <div class="footer-column">
        <h3>Trip Types</h3>
        <p><a href="./trip-types.php?triptype-is=Nature Friendly">Nature Friendly</a></p>
        <p><a href="./trip-types.php?triptype-is=Budget Travel">Budget Travel</a></p>
        <p><a href="./trip-types.php?triptype-is=Cultural">Cultural</a></p>
        <p><a href="./trip-types.php?triptype-is=Child-friendly">Child-friendly</a></p>
      </div>
    </div>

    <!-- Footer Bottom -->
    <div class="footer-bottom">
      <p>© Copyright 2025 Thankyounepaltrip.com | Travel your dream Destinations in Nepal.</p>
      <div class="secured-payment">
        <i class="fas fa-shield-alt"></i>
        <span>Secured Payment:</span>
        <a href="https://login.fonepay.com/#/" target="_blank" rel="noopener">
          <img src="assets/img/fonepay.png" alt="Fonepay" />
        </a>
        <a href="https://esewa.com.np/login#/home" target="_blank" rel="noopener">
          <img src="assets/img/esewa.png" alt="eSewa" />
        </a>
        <a href="https://www.unionpayintl.com" target="_blank" rel="noopener">
          <img src="assets/img/unionpay.png" alt="UnionPay" />
        </a>
        <a href="https://www.paypal.com/np/welcome/signup/#/login_info" target="_blank" rel="noopener">
          <img src="assets/img/paypal.png" alt="PayPal" />
        </a>
      </div>
    </div>
  </div>
</section>

<style>
.footer-section {
  width: 100%;
  padding: 50px 5%;
  background-color: #2a7d7d;
  box-sizing: border-box;
  color: white;
}

.footer {
  max-width: 1200px;
  margin: 0 auto;
}

.footer-containers {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  gap: 40px;
}

.footer-column {
  flex: 1 1 200px;
}

.footer-column h3 {
  font-size: 1.5rem;
  font-weight: 200;
}

.footer-containers a {
  text-decoration: none;
  color: white;
  font-weight: 500;
}

.footer-containers a:hover {
  color: rgb(255, 208, 208);
}

.footer-icons {
  display: flex;
  gap: 1rem;
  font-size: 20px;
  margin-top: 10px;
}

.footer-icons a {
  color: white;
}

.footer-icons a:hover {
  color: rgb(255, 208, 208);
}

/* Footer Bottom */
.footer-bottom {
  margin-top: 30px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: nowrap;
  gap: 20px;
  text-align: left;
  flex-direction: row;
}

.footer-bottom p {
  margin: 0;
  font-size: 0.95rem;
  flex: 1;
}

/* Secured Payment */
.secured-payment {
  display: flex;
  align-items: center;
  gap: 10px;
  flex-shrink: 0;
  white-space: nowrap;
}

.secured-payment i {
  color: rgb(0, 175, 18);
  font-size: 22px;
}

.secured-payment img {
  height: 25px;
  width: auto;
  transition: transform 0.2s ease;
}

.secured-payment img:hover {
  transform: scale(1.1);
}

/* Responsive Design */
@media (max-width: 768px) {
  .footer-containers {
    gap: 30px;
  }

  .footer-bottom {
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: 15px;
  }

  .secured-payment {
    justify-content: center;
    flex-wrap: wrap;
  }
}
</style>
