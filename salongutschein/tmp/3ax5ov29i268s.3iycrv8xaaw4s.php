<!-- Modal -->
<div class="modal fade modal-salon-subpage" id="hairDataModal" tabindex="-1" role="dialog" aria-labelledby="stylistModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

        <!-- MODAL-CONTENT  -->
        <div class="modal-content emp-modal">

            <div class="modal-header border-0 p-0">
                <button id="lightboxCloseTermine" type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div style="padding: 15px !important;" class="modal-body text-center text-dark w-100">

                <div class="hairLengthContainer row mt-4">

                    <div class="hairLengthShort col-md-4 col-xs-12">
                        <input class="short radio-btn" id="lenghtShort" required type="radio" value="false" name="hairlength" />
                        <label for="lenghtShort"><span></span>Kurze Haare</label>
                    </div>

                    <div class="hairLengthLong col-md-4 col-xs-12">
                        <input class="long radio-btn" id="lenghtLong" required type="radio" value="true" name="hairlength" />
                        <label for="lenghtLong"><span></span>Lange Haare</label>
                    </div>      

					<div class="hairLengthDontKnow col-md-4 col-xs-12">
                        <input class="long radio-btn" id="hairLengthDontKnow" required  onclick="javascript:void(0);" type="radio" value="false" name="hairlength" />
                        <label for="hairLengthDontKnow"><span></span>Weiß nicht?</label>
                    </div>


                    <div class="hairLengthDontKnow col-md-12 col-xs-12 my-3">
                        <button data-original-text="Termin finden"  data-loading-text="Laden..." id="termin-finden-hair" type="button" class="btn btn-dark btn-submit position-relative" style="display: inline-block;" disabled>Termin finden</button>
                    </div>



                </div>

                <div class="row mt-2">
                    <div class="col-md-12 col-xs-12 moreInfo text-center" style="background-image: url('<?= ($ASSETS) ?>images/haarlaenge.jpg')">&nbsp;</div>
                </div>

            </div>

        </div>
        <!-- end modal content -->

    </div>
</div>