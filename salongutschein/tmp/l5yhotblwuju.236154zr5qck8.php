<div class="row gutscheinauswahl-first cstm-clss">
  <div class="col-md-8 offset-md-2 col-12 pt-3">
    <div class="text-md-left text-cente-r mb-4">
      <h3 class="futuraItBk">Betrag</h3>
    </div>

    <form id="gutschein" action="" method="post" class="w-100">
      <div class="mt-3">
        <!-- <p>Für welchen Betrag möchten Sie einen Gutschein bestellen?</p> -->
        <!-- <p><strong>Betrag</strong></p> -->
        <div class="row align-items-center">
          <div class="col-xl-6 col-sm-5 col-6 tt-form-group">
            <!-- <select name="amount" id="amount" class="form-control" required>
                            <option value="" selected disabled>-- wählen --</option>
                            <option value="1,-">1,-</option>
                            <option value="30,-">30,-</option>
                            <option value="50,-">50,-</option>
                            <option value="75,-">75,-</option>
                            <option value="100,-">100,-</option>
                            <option value="125,-">125,-</option>
                            <option value="150,-">150,-</option>
                            <option value="175,-">175,-</option>
                            <option value="200,-">200,-</option>
                            <option value="225,-">225,-</option>
                            <option value="250,-">250,-</option>
                            <option value="275,-">275,-</option>
                            <option value="300,-">300,-</option>

                        </select> -->
            <div>
              <input
                type="number"
                name="amount"
                id="amount"
                class="form-control"
                required
              />
            </div>
          </div>
          <div class="col-xl-6 col-sm-7 col-6">
            <div class="images-visa">
              <img src="<?= ($ASSETS) ?>images/paypal.png" alt="Visa Logo" />
            </div>
          </div>
          <!-- <div class="col-md-1 col-2 align-self-center">
                        <label for="amount">EUR</label>
                    </div> -->
        </div>
        <span class="hide text-danger">
          Bitte wählen Sie Ihren Wunschbetrag aus.</span
        >
      </div>

      <div class="mt-4">
        <!-- <p>Wie möchten Sie den Gutschein erhalten?</p> -->
        <!-- <p><strong>Versand</strong></p> -->
        <!-- <div class="row">
                    <div class="col-xl-4 col-sm-6 col-8 tt-form-group">
                        <select id="versand" name="shipment" size="1" class="form-control">
                            <option value="per E-Mail" selected>per E-Mail</option>
                            <option value="per Post">per Post</option>
                        </select>
                        <p id="hinweisVersand" style="display:none;" class="text-danger">versandkostenfrei</p>
                    </div>
                </div> -->
      </div>
    </form>

    <!--         <div class="mt-5">
            <div class="images-visa">
                <img src="<?= ($ASSETS) ?>images/visa_logo.gif" alt="Visa Logo" />
                <img src="<?= ($ASSETS) ?>images/mastercard_logo.gif" alt="MasterCard Logo" />
               
                <img src="<?= ($ASSETS) ?>images/paypal_logo.png" alt="Paypal Logo" />
            </div>
        </div> -->
  </div>
  <!-- end offset-md-2 -->
</div>
<!-- end gutscheinauswahl-first -->
<div class="clr clearfix"></div>
