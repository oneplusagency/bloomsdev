<div class="row pb-5">
   	<div class="col-md-8 offset-md-2 col-12 pt-3">
        <div class="text-md-left text-left mb-2">
		   <div class="text-md-left mb-1">
                <h3>Fertig</h3>
				<p class="mt-3" style="font-size: 1em;">Vielen Dank für Ihren Einkauf!</p>
            </div>
          
        </div>


        <!-- <p>Zusätzlich sind die Daten hier als Download hinterlegt.</p> -->


        <div class="row">
            <div class="col-lg-6 col-12 text-center">
                <!-- giftcouponcode -->
                <?php if (isset($giftcouponcode) && is_array($giftcouponcode)): ?>
                    
                        <!-- target="_blank" -->
                        <div class="text-center mt-0">
                          
							              <a target="_blank" href="<?= ($giftcouponcode['InvoicePdfFileUri']) ?>" class="btn btn-dark btn-fertig pp-btn" style="text-decoration: none; border: 2px solid #ddd!important;"><i class="fa fa-file-pdf-o"></i> <span class="ml-1 black ">Rechnung drucken</span></a>
							
						 </div>
                    
                <?php endif; ?>   
				
				<?php if (isset($giftcouponcode['GiftCouponPdfFileUri'])): ?>
                    
                        <!-- target="_blank" -->
                        <div class="text-center mt-0">
						  <a target="_blank" href="<?= ($giftcouponcode['GiftCouponPdfFileUri']) ?>" class="btn btn-dark btn-fertig pp-btn" style="text-decoration: none; border: 2px solid #ddd!important;"><i class="fa fa-file-pdf-o"></i> <span class="ml-1 black" style="font-weight: 600!important;">Gutschein als PDF Speichern</span></a>
              
                        </div>
                    
                <?php endif; ?>

         <!--        <div class="my-3 hide">
                    <p class="mb-1">bloom´s</p>
                    <p class="mb-1">Im Zollhof 4, Ludwigshafen</p>
                    <p class="mb-1">0621 54544274</p>
                </div> -->
            </div>

        </div>

<!--         <p class="mt-2">Vielen Dank, dass Sie sich für uns entschieden haben.</p>
        <p>Ihr bloom's Team</p> -->

        <!-- <a href="<?= ($BASE) ?>/termine.html" class="btn btn-dark mt-lg-0 mt-3">Fertig</a> -->
    </div>
</div>