<div class="row" >
    <div class="col-md-12" style="clear:both;color: #fff;text-align: center;background: #000;">
        <?php echo $this->render('layout/blooms-logo.html',NULL,get_defined_vars(),0); ?>
    
<center>      
     <div class="col-md-6 ">
        <div class="col-md-12">
            <?php echo $this->render('layout/feedback-logo.html',NULL,get_defined_vars(),0); ?>
            <p>&nbsp;</p>
          </div>
        <article>

         
            <section class="justify-content-center">
                <?php if (isset($CONTENT)): ?>
                    
                        <?= ($CONTENT)."
" ?>
                    
                <?php endif; ?>
            </section>
        

     </article>
        <div class="p-md-0 mt-md-3 mt-0">
            <div style="clear:both;color: #fff;text-align: center;background: #000;">
                        
                    
                <form id="reklamationAdmin" enctype="multipart/form-data" style="padding: 0px 15px 15px;">
                <input class="form-control rounded-0" required="required" pattern=".{2,}" maxlenght="50" aria-required="true" aria-invalid="false" style="margin: 10px 0px;" placeholder="Name*" name="name" id="name" type="text">
                <input class="form-control rounded-0" required="required" pattern=".{2,}" maxlenght="50" aria-required="true" aria-invalid="false" style="margin: 10px 0px;" placeholder="E-Mail*" name="emailstaff" id="emailstaff" type="text">
                
                    <?php if ($OPTION_SALON): ?>
                        
                            <select id="option_salon" name="option_salon" class="form-control rounded-0" style="color:grey;"> 
                                
                                <?php foreach (($OPTION_SALON?:[]) as $data): ?>
                                    <?= ($data)."
" ?>
                                <?php endforeach; ?>
                            </select>
                        
                        <?php else: ?>
                            <div class="alert">
                                You currently have no items in our preise.
                            </div>
                        
                    <?php endif; ?>
                
                <input class="form-control rounded-0" required="required" pattern=".{2,}" maxlenght="50" aria-required="true" aria-invalid="false" style="margin: 10px 0px;" placeholder="Telefon*" name="telefon" id="telefon" type="text">
                <textarea maxlenght="500" id="bloom_kontakt_message" name="message" class="form-control rounded-0 md-textarea" placeholder="Nachricht*" required="" style="margin: 10px 0px;" rows="4"></textarea>
                
                <div class="row">
                        <div class="col-lg-3 col-md-12 col-sm-12 upload-btn">
                                 <!------new file type------>
                                 <input id="file" name="file" type="file" class="inputfile" data-multiple-caption="{count} files selected" multiple accept=".jpg,.pdf"/>
                                <label for="file"><span class="mwpl-upload-btn">Upload</span></label> 
                            <!----end new file type---->
                                <span class="pdf-jpgtxt"><p>PDF JPG</p></span>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 mdl-div-sec">
                            <div id="reklamation_feedback_status"></div>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 upload-btn">
                                <input type="hidden"  name="email" id="adminEmail" value="">
                                <button id="reklamation_sendbtn" type="submit" style="background: #000;right: 0;border: 1px solid #fff;color: #fff;float: right;" class="form-control rounded-0 col-md-5">Senden</button>
                        </div>
                    </div>
                </form>
                
    </div>

        

    </div>
    </div>
</center>    
</div>
</div>