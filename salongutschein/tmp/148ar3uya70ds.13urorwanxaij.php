<div class="row gutscheinauswahl-four">
	<div class="col-md-8 offset-md-2 col-12 pt-3">
    <form class="form mt-0 w-100" role="form" autocomplete="off" id="Zusammenfassung" action="" novalidate="" method="POST">


    


            <div class="row gutschein-data">
                <div class="col-xl-6 col-12">
                    <div class="row">
                        <div class="col-12">
							<div class="text-md-left text-left mb-4">
								<h3 class="futuraItBk">Zusammenfassung</h3>
							</div>
                           
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-3">
                            <p class="personal-data d-inline"><strong>Betrag:</strong></p>
                        </div>
                        <div class="col-9">
                            <p class="d-inline" id="amount-form"><span class="amount-sp"></span> EUR</p>
                        </div>
                    </div>

                   <h5 class="mt-4">Rechnungsadresse</h5>
         
					<div class="row mt-2">
                        <div class="col-3">
                            <p><strong>Name</strong></p>
                        </div>
                        <div class="col-9">
                            <!-- John Doe -->
                            <p id="vname-nname-form"></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-3">
                            <p><strong>E-Mail</strong>
                        </div>
                        <div class="col-9">
                            <!-- johndoe@gmail.com -->
                            <p id="email-form"></p>
                        </div>
                    </div>        
					<div class="row">
                        <div class="col-3">
                            <p><strong>Phone</strong>
                        </div>
                        <div class="col-9">
                            <!-- johndoe@gmail.com -->
                            <p id="phone-form"></p>
                        </div>
                    </div>

                   <div class="row">
                        <div class="col-3">
                            <p><strong>Straße</strong></p>
                        </div>
                        <div class="col-9">
                            <p id="adresse-form"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <p><strong>Ort</strong></p>
                        </div>
                        <div class="col-9">
                            <p id="ort-form"></p>
                        </div>
                    </div>       
					<div class="row">
                        <div class="col-3">
                            <p><strong>PLZ</strong></p>
                        </div>
                        <div class="col-9">
                            <p id="plz-form"></p>
                        </div>
                    </div>

                   
                    <div class="row">
						
							<div class="col-3">
								<p><strong>Versand</strong></p>
							</div>
							<div class="col-9">
								<!-- John Doe -->
								  <p id="versand-form"></p>
							</div>
					</div>	

					<div class="row mt-3 email post" id="greetingsdiv" style="word-wrap: break-word;">
						<div class="col-12">
							
							<h5 class="mt-4">Grußtext</h5>
							<p class="mb-0 mt-md-0 mt-4" id="greetings-form"></p>
						</div>
					</div>
					
					<div id="diffAdressForm">
					<div class="row email post">
						
							<div class="col-12">
								<h5 class="mt-4">Versand Adresse</h5>
							</div>
					
					</div>
					<div class="row  email">
                        <div class="col-3">
                            <p><strong>E-Mail</strong>
                        </div>
                        <div class="col-9">
                            <!-- johndoe@gmail.com -->
                            <p id="diffemail-form"></p>
                        </div>
                    </div>
					
						<div class="row post">
							<div class="col-3">
								<p><strong>Name</strong></p>
							</div>
							<div class="col-9">
								<!-- John Doe -->
								<p id="diffvname-nname-form"></p>
							</div>
						</div>
					<div class="row post">
                        <div class="col-3">
                            <p><strong>Phone</strong>
                        </div>
                        <div class="col-9">
                            <!-- johndoe@gmail.com -->
                            <p id="diffphone-form"></p>
                        </div>
                    </div>
					   <div class="row post">
							<div class="col-3">
								<p><strong>Straße</strong></p>
							</div>
							<div class="col-9">
								<p id="diffadresse-form"></p>
							</div>
						</div>
						<div class="row post">
							<div class="col-3">
								<p><strong>Ort</strong></p>
							</div>
							<div class="col-9">
								<p id="diffort-form"></p>
							</div>
						</div>       
						<div class="row post">
							<div class="col-3">
								<p><strong>PLZ</strong></p>
							</div>
							<div class="col-9">
								<p id="diffplz-form"></p>
							</div>
						</div>



					</div>
                </div>

                <div class="col-xl-6 col-12 mt-lg-0 mt-2">
                    <div class="row">
                        <div class="col-12 d-flex justify-content-xl-end justify-content-center">
                            <div class="gutscheine-data-img-box text-center ">
                                <img id="design-img-form" src="<?= ($ASSETS) ?>images/email_layout_1.jpg" class="gutscheine-data-img" alt="Design Image" />
                            </div>
                        </div>
                    </div>
                    <!-- row -->
                </div>
            </div>
            <!-- end gutschein-data -->


        <!--     <div class="row greetings-form-text">
                <div class="col-12">
                    <div class="mt-3">
                        <h5 class="mt-2">Grußtext:</h5>
                        <p class="mb-0 mt-md-0 mt-4" id="greetings-form"></p>
                    </div>
                </div>
            </div> -->
            <!-- end greetings-form-text -->

            <div class="row gutscheinauswahl-form-agb mt-5">
                <div class="col-12">
                    <div class="mt-3 text-center no-bold">
                       <!--  <h5 class="mt-2">AGBs:</h5> -->

                       <p>Mit dem Klick auf "Gutschein kaufen" bestätige ich die <a href="<?= ($BASE) ?>/gutscheinagb.html" target="_blank">AGBs</a> und die  <a href="<?= ($BASE) ?>/datenschutz.html" target="_blank">Datenschutzerklärung</a></p>

                       <!--  <p class="mb-0 mt-md-0 mt-4">Mit Klick auf "Gutschein kaufen" bestätige ich die AGBs. <a href="<?= ($BASE) ?>/agb.html" target="_blank">Klicken Sie hier um die AGBs zu lesen</a></p>
                        <p class="mb-0 mt-md-0 mt-4">Bitte nehmen Sie unsere <a href="<?= ($BASE) ?>/datenschutz.html">Datenschutzerklärung</a> zur Kenntnis.</p> -->
                    </div>
                </div>
            </div>
            <!-- end gutscheinauswahl-form-agb -->

        </div>
        <!-- end offset-md-2 -->
    </form>
	
</div>
<!-- end gutscheinauswahl-four -->
<div class="clr"></div>