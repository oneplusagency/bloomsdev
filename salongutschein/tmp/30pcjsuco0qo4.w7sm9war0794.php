</div>
<!-- end container see layout/page.html -->
<!-- disable footer menu in admin -->
<?php if (strpos($view, 'admin') !== false): ?>
    <?php else: ?>
        <?php if ($view=='home.html'): ?>
            
                <article id="page-impressum-datenschutz" class="p-sm-0 mb-3 mt-5 <?= ($classfoot) ?>-UA">
                    <section class="layout-impressum-datenschutz p-sm-0">
                        <!-- \app\views\layout\menu_footer.html -->
                        <?php echo $this->render('layout/menu_footer.html',NULL,get_defined_vars(),0); ?>
                    </section>
                </article>
            
            <?php else: ?>
                <section class="layout-impressum-datenschutz  mt-5 text-left p-sm-0 pl-sm-3 <?= ($classfoot) ?>-UA">
                    <?php echo $this->render('layout/menu_footer.html',NULL,get_defined_vars(),0); ?>
                </section>
            
        <?php endif; ?>
    
<?php endif; ?>

<!-- charset="utf-8" -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>

<script src="<?= ($ASSETS) ?>js/popper.min.js"></script>

<script src="<?= ($ASSETS) ?>js/bootstrap.min.js"></script>

<script src="<?= ($ASSETS) ?>js/main.js" async></script>
<script type="text/javascript" charset="utf-8">
    //    $('select.form-control').not(document.getElementById( "servicePackageField" )).select2();
    // $('select#servicePackageField').select2({
    //   allowClear: true
    // });
</script>

<script src="<?= ($ASSETS) ?>js/navbar-script.js"></script>
<script src="<?= ($ASSETS) ?>js/cookie.js"></script>
<?php if ($view=='termine.html'): ?>
    
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />

        <!--  @FIX by oppo * @Date: 05.12.2020 13:02  disable code.jquery.com Jquery UI Tooltip disable Jquery UI Tooltip conflict WITH bootstrap Tooltip -->
        <!-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
        <script src="<?= ($ASSETS) ?>js/jquery-ui.min.js"></script>

        <script src="<?= ($ASSETS) ?>js/datepicker-de.js" async></script>

        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js">
        </script>

        <script type="text/javascript" charset="utf-8">
            // $('.multiple-items').slick();
        </script>
        <script type="text/javascript" charset="utf-8">
            $(function () {
                // $("#datepicker").datepicker("setDate", (new Date()));
            });
        </script>

    
<?php endif; ?>

<?php if (isset($addscripts) && is_array($addscripts)): ?>
    
        <?php foreach (($addscripts?:[]) as $script): ?>
            <script src="<?= ($ASSETS . $script) ?>"></script>
        <?php endforeach; ?>
    
<?php endif; ?>
<!-- gutscheine.html -->
<?php if (!empty($addextremalscripts)): ?>
    
        <?= ($this->raw($addextremalscripts))."
" ?>
    
<?php endif; ?>

<!-- get GetmitarbeiterId -->
<?php if (!empty($GetmitarbeiterId)): ?>
    
        <script type="text/javascript">
            let GetmitarbeiterId = '<?= ($GetmitarbeiterId) ?>';
        </script>
    
    <?php else: ?>
        <script type="text/javascript">
            let GetmitarbeiterId = null;
        </script>
    
<?php endif; ?>

<?php if ($view=='termine.html'): ?>
    
        <script type="text/javascript" charset="utf-8">
            // http://localhost/f3-url-shortener
            // https://appelsiini.net/projects/chained/
            //name="servicePackage" id="servicePackageField"

            // let service_field_id = 'servicePackageField';
            var zdin = false;
            let salon_blum = $("#option_salon");
            let mitarbeiter_blum = $("#salonMitarbeiterField");
            let mitarbeiter_value = null;

            // #salonMitarbeiterField
            let salon_field_id = 'option_salon';
            let mitarbeiter_field_id = 'salonMitarbeiterField';
            // _restoreField(salon_field_id)

            jQuery(document).ready(function ($) {

                var salon_vzagaly_sel = $('option:selected', salon_blum).val();
                if (zdin == false && "" != salon_vzagaly_sel) {
                    localStorage.removeItem(mitarbeiter_field_id);
                    salon_blum.trigger('change');
                    let mitarbeiter_value = 0;
                    zdin = true;
                } else if (zdin == false && _restoreField(salon_field_id)) {
                    zdin = true;
                    // let mitarbeiter_value = _getLocalStorageValue(mitarbeiter_field_id);
                    if (typeof GetmitarbeiterId !== 'undefined' && GetmitarbeiterId > 0) {
                        //localStorage.setItem(key, value);
                        let mitarbeiter_value = GetmitarbeiterId;
                        $.totalStorage(mitarbeiter_field_id, mitarbeiter_value);
                        mitarbeiter_blum.val(mitarbeiter_value);
                        _setLocalStorageValue(mitarbeiter_blum);
                    }
                    salon_blum.trigger('change');
                }
            });

            salon_blum.change(function (e) {
                // localStorage.clear();
                // localStorage.removeItem(mitarbeiter_field_id);
                _setLocalStorageValue(salon_blum);



            });

            mitarbeiter_blum.change(function (e) {
                let salon_vzagaly_sel = $('option:selected', salon_blum).val();
                if (salon_vzagaly_sel) {
                    _setLocalStorageValue(mitarbeiter_blum);
                    // _restoreField(service_field_id);
                    // let mitarbeiter_value = _getLocalStorageValue(mitarbeiter_field_id);
                } else {
                    let mitarbeiter_value = null;
                    localStorage.removeItem(mitarbeiter_field_id);
                    mitarbeiter_blum.val(0);
                }
            });

            if (mitarbeiter_value) {
                // mitarbeiter_blum.val(mitarbeiter_value);
                // mitarbeiter_blum.trigger('change');
            }
            //zdin == false
            if (true) {

                mitarbeiter_value = _getLocalStorageValue(mitarbeiter_field_id);
                console.log('foot mitarbeiter_value : ' + mitarbeiter_value);

                mitarbeiter_value = definitelyNaNop(mitarbeiter_value, 0);
                if (mitarbeiter_value <= 0) mitarbeiter_value = 0;
                // set from GET
                if (typeof GetmitarbeiterId !== 'undefined' && zdin == false && GetmitarbeiterId > 0) {
                    mitarbeiter_value = GetmitarbeiterId;
                }

                $("#option_salon").chainSelect('#salonMitarbeiterField', BloombaseUrl + '/termine/mitarbeiter', {
                    defaultValue: mitarbeiter_value,
                    before: function (
                        target) //before request hide the target combobox and display the loading message
                    {
                        localStorage.removeItem(mitarbeiter_field_id);
                        // $("#loading").css("display", "block");
                        // $(target).css("display", "none");
                        var $$ = $(this);
                        $(target).append('<option value="">Laden...</option>').ajaxStart(function () {
                            // $$.show();
                        });
                        $(target).attr('disabled', 'true');
                    },
                    after: function (
                        target) //after request show the target combobox and hide the loading message
                    {
                        // mitarbeiter_blum.val(mitarbeiter_value);
                        // $("#loading").css("display", "none");
                        // $(target).css("display", "inline");
                        // $(target).change();
                        // _setLocalStorageValue(mitarbeiter_blum);
                        _restoreField(service_field_id);
                        $(target).removeAttr('disabled');
                    }
                });
            }


            /*   Service 29.05.2020  */

            // https://warrenlafrance.com/2018/08/29/select-with-optgroup-via-ajax-and-mvc-controller/
            function getChangeServiseSelect(serviceId, servicePackageField, salonId) {


                var servicePackageField = $('#servicePackageField');

                var url = BloombaseUrl + "/termine/services";
                $.getJSON(url, {
                    format: "JSON",
                    Id: serviceId,
                    salonId: salonId
                }).done(function (data) {

                    // console.log(JSON.stringify(data));
                    // var result = data;
                    servicePackageField.removeAttr('disabled');
                    servicePackageField.empty();
                    // servicePackageField.children().remove("optgroup");
                    var groupName = '';
                    var groupPreviousName = '';
                    servicePackageField.append('<option value="" disabled>Bitte Dienstleistung auswählen</option>');
                    if (data.length > 0) {
                        $.each(data, function (i, subsector) {
                            var $optgroup = $('<optgroup />').attr('label', '' + subsector.title).attr(
                                'id', subsector.id).appendTo(servicePackageField);
                            //BaseServicePackageId
                            $.each(subsector.services, function (j, option) {
                                var $option = $("<option>", {
                                    text: option.Name,
                                    value: option.ServicePackageId,
                                    'data-childid': option.BaseServicePackageId
                                });
                                $option.appendTo($optgroup);
                            });
                        });
                        // $('option:first', servicePackageField).prop('selected',true);
                    }

                    let serviceIdStorag = _getDienstleistungId();
                    serviceIdStorag = definitelyNaNop(serviceIdStorag, 0);


                    /**  @FIX by oppo (webiprog.de) * @Date: 2021-09-07 15:48:17
                     * @Desc: The selected service should be saved, and if you select another employee
                     */
                    let lastSPVal = $.totalStorage.getItem('lastServicePackage');
                    lastSPVal = definitelyNaNop(lastSPVal, 0);
                    if (lastSPVal) {
                        serviceIdStorag = lastSPVal;
                    }
                    // alert(serviceIdStorag)
                    if (serviceIdStorag) {
                        servicePackageField.val(serviceIdStorag);
                    }

                    console.log('foot  serviceIdStorag: ' + serviceIdStorag);
                    console.log('foot  servicePackageField: ' + servicePackageField);

                    // _setLocalStorageValue($('#servicePackageField'), null, false);

                    let serviceself_sel = $('option:selected', servicePackageField).val();
                    // alert(serviceself_sel)
                    //console.log('self_sel: ' + self_sel);

                    if (typeof serviceself_sel == 'undefined') {
                        $('option:first', servicePackageField).prop('selected', true); //select first option
                        $('#termin-finden').hide();
                        $('#termineAjaxformWizard .spf-flex i.fa').prop('title', '');

                    } else if (!serviceself_sel) {
                        $('option:first', servicePackageField).prop('selected', true); //select first option

                        // add hide btn  29.05.2020
                        $('#termin-finden').hide();
                        $('#termineAjaxformWizard i.fa.dll').prop('title', '');
                    } else {
                        // add hide btn  29.05.2020
                        $('#termin-finden').show();

                        if ($('#servicePackageField-error').length) {
                            $('#servicePackageField-error').remove();
                        }
                    }

                }).fail(function (jqxhr, textStatus, error) {
                    $('#servicePackageField').removeAttr('disabled');
                    var err = textStatus + ", " + error;
                    console.log("Request servicePackageField Failed: " + err);
                });
            }


            $('#servicePackageField').on('change', function () {
                // save to Storage
                _setLocalStorageValue($(this), null, false);
            });

            var zdinServ = false;
            if (zdinServ == false) {
                zdinServ = true;

                $('#salonMitarbeiterField').on('change', function () {

                    var salon_blum = $("#option_salon");
                    var salon_vzagaly_sel = $('option:selected', salon_blum).val();
                    console.log('foot salon_vzagaly_sel: ' + salon_vzagaly_sel);
                    // alert(salon_vzagaly_sel)
                    if (salon_vzagaly_sel) {

                        var options = $('option:selected', this); //the selected options
                        var id = options.val();
                        var salonId = salon_vzagaly_sel;

                        var $$ = $(this);
                        var servicePackageField = $('#servicePackageField');

                        servicePackageField.empty();
                        servicePackageField.append('<option value="">Laden...</option>').ajaxStart(function () {
                            // $$.show();
                        });
                        servicePackageField.attr('disabled', 'true');

                        getChangeServiseSelect(id, servicePackageField, salonId)

                    }
                });
            }
        </script>
    
<?php endif; ?>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-190114058-1"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'UA-190114058-1');
</script>
<script type="text/javascript" charset="utf-8">
    jQuery(document).ready(function ($) {
        $('.inputfile').each(function () {
            var $input = $(this),
                $label = $input.next('label'),
                labelVal = $label.html();

            $input.on('change', function (e) {
                var fileName = '';

                if (this.files && this.files.length > 1)
                    fileName = (this.getAttribute('data-multiple-caption') || '').replace('{count}', this.files.length);
                else if (e.target.value)
                    fileName = e.target.value.split('\\').pop();

                if (fileName)
                    $label.find('span').html(fileName);
                else
                    $label.html(labelVal);
            });

            // Firefox bug fix
            $input
                .on('focus', function () {
                    $input.addClass('has-focus');
                })
                .on('blur', function () {
                    $input.removeClass('has-focus');
                });
        });
    });
</script>
</div>

</body>

</html>